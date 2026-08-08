<?php

namespace api\controllers;

use Yii;
use yii\db\Query;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;
use yii\web\BadRequestHttpException;
use yii\db\Expression;                 // ← 新增
use yii\web\NotFoundHttpException;     // ← 新增
use common\models\Dynamic; // 引入 User 模型
use common\models\DynamicLike; // 引入 User 模型
use common\models\DynamicComment; // 引入 User 模型
use common\models\DynamicCollect; // 引入 User 模型 
/**
 * 动态控制器
 *
 * GET  /dynamics/my-list   我的动态列表（需要 Token）
 * POST /dynamics/publish   发布动态（需要 Token）
 */
class DynamicsController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // 本控制器所有接口都要带 Bearer Token 才能访问
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

        return $behaviors;
    }

    /**
     * 所有动态列表（广场 / 首页 Feed）
     * GET /dynamics/list?page=1&pageSize=10
     */
    public function actionList()
    {
        $uid = Yii::$app->user->id;   // ← 加这行（如果没有的话）

        // 分页参数
        $page     = max(1, (int)Yii::$app->request->get('page', 1));
        $pageSize = min(50, max(1, (int)Yii::$app->request->get('pageSize', 10)));

        // 关联 customer 表，把发布者信息一起查出来
        $query = (new Query())
            ->select([
                'd.*',                       // 动态的所有字段
                'c.username',                // 发布者用户名
                'c.avatar_url',                  // 发布者头像（⚠️ 表里没这列就删掉）
            ])
            ->from(['d' => 'customer_dynamics'])
            ->where(['status' => Dynamic::STATUS_ACTIVE])
            ->leftJoin(['c' => 'customer'], 'c.id = d.customer_id')
            ->orderBy(['d.id' => SORT_DESC]);   // 最新的在前

        $total = (int)$query->count();

        $list = $query
            ->limit($pageSize)
            ->offset(($page - 1) * $pageSize)
            ->all();

        // 给每条动态附上 点赞数 / 收藏数
        foreach ($list as &$item) {
            $item['likes_count'] = (int)(new Query())
                ->from('dynamic_likes')
                ->where(['dynamic_id' => $item['id']])
                ->count();

            $item['collects_count'] = (int)(new Query())
                ->from('dynamic_collects')
                ->where(['dynamic_id' => $item['id']])
                ->count();
        }
        unset($item);
        $this->attachUserFlags($list, $uid);   // ← 在 return 之前加这行

        return [
            'code'    => 200,
            'message' => 'success',
            'data'    => [
                'total'    => $total,
                'page'     => $page,
                'pageSize' => $pageSize,
                'list'     => $list,
            ],
        ];
    }
    /**
     * 点赞动态
     * POST /dynamics/like
     * Body(JSON): {"dynamic_id": 1}
     */
    public function actionLike()
    {
        $uid = Yii::$app->user->id;

        $dynamicId = (int)Yii::$app->request->post('dynamic_id', 0);
        if ($dynamicId <= 0) {
            throw new BadRequestHttpException('缺少 dynamic_id 参数');
        }

        // 1. 检查动态是否存在
        $dynamic = (new Query())
            ->from('customer_dynamics')
            ->where(['id' => $dynamicId])
            ->one();
        if (!$dynamic) {
            throw new NotFoundHttpException('动态不存在');
        }

        // 2. 防止重复点赞（不想要这段就整段删掉）
        $liked = (new Query())
            ->from('dynamic_likes')
            ->where(['dynamic_id' => $dynamicId, 'customer_id' => $uid])
            ->exists();
        if ($liked) {
            return ['code' => 400, 'message' => '已经点过赞了', 'data' => null];
        }

        // 3. 插点赞记录 + like_count 加 1（事务保证两步同成功同失败）
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            // 3.1 dynamic_likes 插一条记录
            $db->createCommand()->insert('dynamic_likes', [
                'customer_id' => $uid,
                'dynamic_id'  => $dynamicId,
                'status'      => DynamicLike::STATUS_ACTIVE,                    // ⚠️ 表里没 status 列就删掉这行
            ])->execute();

            // 3.2 customer_dynamics 的 like_count + 1
            $db->createCommand()->update(
                'customer_dynamics',
                ['like_count' => new Expression('like_count + 1')],   // 数据库层面 +1，不是 PHP 里算
                ['id' => $dynamicId]
            )->execute();

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return [
            'code'    => 200,
            'message' => '点赞成功',
            'data'    => [
                'dynamic_id' => $dynamicId,
                'like_count' => (int)$dynamic['like_count'] + 1,
            ],
        ];
    }

    /**
     * 评论动态
     * POST /dynamics/comment
     * Body(JSON): {"dynamic_id": 1, "content": "写得真好！", "parent_id": 0}
     */
    public function actionComment()
    {
        $uid = Yii::$app->user->id;

        $dynamicId = (int)Yii::$app->request->post('dynamic_id', 0);
        $content   = trim((string)Yii::$app->request->post('content', ''));
        $parentId  = (int)Yii::$app->request->post('parent_id', 0);   // 可选，回复某条评论时传那条评论的 id

        if ($dynamicId <= 0) {
            throw new BadRequestHttpException('缺少 dynamic_id 参数');
        }
        if ($content === '') {
            throw new BadRequestHttpException('评论内容不能为空');
        }

        // 1. 检查动态是否存在
        $dynamic = (new Query())
            ->from('customer_dynamics')
            ->where(['id' => $dynamicId])
            ->one();
        if (!$dynamic) {
            throw new NotFoundHttpException('动态不存在');
        }

        // 2. 如果是回复评论，检查被回复的评论是否存在且属于该动态
        if ($parentId > 0) {
            $parentExists = (new Query())
                ->from('dynamic_comments')
                ->where(['id' => $parentId, 'dynamic_id' => $dynamicId])
                ->exists();
            if (!$parentExists) {
                throw new NotFoundHttpException('被回复的评论不存在');
            }
        }

        // 3. 插评论记录 + comment_count 加 1（事务）
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $db->createCommand()->insert('dynamic_comments', [
                'dynamic_id'  => $dynamicId,
                'customer_id' => $uid,
                'content'     => $content,
                'parent_id'   => $parentId,
                'status'      => DynamicComment::STATUS_ACTIVE,
            ])->execute();
            $commentId = (int)$db->getLastInsertID();   // 拿到新评论的 id

            $db->createCommand()->update(
                'customer_dynamics',
                ['comment_count' => new Expression('comment_count + 1')],
                ['id' => $dynamicId]
            )->execute();

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return [
            'code'    => 200,
            'message' => '评论成功',
            'data'    => [
                'comment_id'    => $commentId,
                'dynamic_id'    => $dynamicId,
                'content'       => $content,
                'parent_id'     => $parentId,
                'comment_count' => (int)$dynamic['comment_count'] + 1,
            ],
        ];
    }
    /**
     * 收藏动态
     * POST /dynamics/collect
     * Body(JSON): {"dynamic_id": 1}
     */
    public function actionCollect()
    {
        $uid = Yii::$app->user->id;

        $dynamicId = (int)Yii::$app->request->post('dynamic_id', 0);
        if ($dynamicId <= 0) {
            throw new BadRequestHttpException('缺少 dynamic_id 参数');
        }

        // 1. 检查动态是否存在
        $dynamic = (new Query())
            ->from('customer_dynamics')
            ->where(['id' => $dynamicId])
            ->one();
        if (!$dynamic) {
            throw new NotFoundHttpException('动态不存在');
        }

        // 2. 防止重复收藏
        $collected = (new Query())
            ->from('dynamic_collects')
            ->where(['dynamic_id' => $dynamicId, 'customer_id' => $uid])
            ->exists();
        if ($collected) {
            return ['code' => 400, 'message' => '已经收藏过了', 'data' => null];
        }

        // 3. 插收藏记录 + collect_count 加 1（事务）
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            // 3.1 dynamic_collects 插一条记录
            $db->createCommand()->insert('dynamic_collects', [
                'customer_id' => $uid,
                'dynamic_id'  => $dynamicId,
                'status'      => DynamicCollect::STATUS_ACTIVE,
            ])->execute();

            // 3.2 customer_dynamics 的 collect_count + 1
            $db->createCommand()->update(
                'customer_dynamics',
                ['collect_count' => new Expression('collect_count + 1')],
                ['id' => $dynamicId]
            )->execute();

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }

        return [
            'code'    => 200,
            'message' => '收藏成功',
            'data'    => [
                'dynamic_id'    => $dynamicId,
                'collect_count' => (int)$dynamic['collect_count'] + 1,
            ],
        ];
    }
    /**
     * 我收藏的动态列表
     * GET /dynamics/my-collects?page=1&pageSize=10
     */
    public function actionMyCollects()
    {
        $uid = Yii::$app->user->id;   // 当前登录用户

        // 分页参数
        $page     = max(1, (int)Yii::$app->request->get('page', 1));
        $pageSize = min(50, max(1, (int)Yii::$app->request->get('pageSize', 10)));

        // 以 dynamic_collects 为主表，关联动态表 + 发布者表
        // 按收藏时间倒序（最近收藏的在前）
        $query = (new Query())
            ->select([
                'd.*',                // 动态的所有字段
                'c.username',         // 发布者用户名
                'c.avatar_url',           // 发布者头像（⚠️ customer 表没 avatar 列就删掉这行）
                'col.created_at AS collected_at',  // 收藏时间
            ])
            ->from(['col' => 'dynamic_collects'])
            ->innerJoin(['d' => 'customer_dynamics'], 'd.id = col.dynamic_id')
            ->leftJoin(['c' => 'customer'], 'c.id = d.customer_id')
            ->where(['col.customer_id' => $uid])
            ->orderBy(['col.id' => SORT_DESC]);

        $total = (int)$query->count();

        $list = $query
            ->limit($pageSize)
            ->offset(($page - 1) * $pageSize)
            ->all();

        // 给每条动态附上 点赞数 / 收藏数
        foreach ($list as &$item) {
            $item['likes_count'] = (int)(new Query())
                ->from('dynamic_likes')
                ->where(['dynamic_id' => $item['id']])
                ->count();

            $item['collects_count'] = (int)(new Query())
                ->from('dynamic_collects')
                ->where(['dynamic_id' => $item['id']])
                ->count();
        }
        unset($item);
        $this->attachUserFlags($list, $uid);   // ← 加这行

        return [
            'code'    => 200,
            'message' => 'success',
            'data'    => [
                'total'    => $total,
                'page'     => $page,
                'pageSize' => $pageSize,
                'list'     => $list,
            ],
        ];
    }

    /**
     * 我的动态列表
     * GET /dynamics/my-list?page=1&pageSize=10
     */
    public function actionMyList()
    {
        $uid = Yii::$app->user->id;

        // 分页参数
        $page     = max(1, (int)Yii::$app->request->get('page', 1));
        $pageSize = min(50, max(1, (int)Yii::$app->request->get('pageSize', 10)));

        $query = (new Query())
            ->from('customer_dynamics')
            ->where(['customer_id' => $uid])
            ->orderBy(['id' => SORT_DESC]);   // 最新的在前

        $total = (int)$query->count();

        $list = $query
            ->limit($pageSize)
            ->offset(($page - 1) * $pageSize)
            ->all();

        // 给每条动态附上 点赞数 / 收藏数
        foreach ($list as &$item) {
            $item['likes_count'] = (int)(new Query())
                ->from('dynamic_likes')
                ->where(['dynamic_id' => $item['id']])
                ->count();

            $item['collects_count'] = (int)(new Query())
                ->from('dynamic_collects')
                ->where(['dynamic_id' => $item['id']])
                ->count();
        }
        unset($item);
        $this->attachUserFlags($list, $uid);   // ← 加这行

        return [
            'code'    => 200,
            'message' => 'success',
            'data'    => [
                'total'    => $total,
                'page'     => $page,
                'pageSize' => $pageSize,
                'list'     => $list,
            ],
        ];
    }

    /**
     * 发布动态
     * POST /dynamics/publish
     * Body(JSON): {"content": "动态内容", "images": "图片地址,逗号分隔(可选)"}
     */
    public function actionPublish()
    {
        $uid = Yii::$app->user->id;

        $content = trim((string)Yii::$app->request->post('content', ''));
        $location  = trim((string)Yii::$app->request->post('location', ''));

        if ($content === '') {
            throw new BadRequestHttpException('动态内容不能为空');
        }
        if (mb_strlen($content) > 500) {
            throw new BadRequestHttpException('动态内容不能超过 500 字');
        }

        Yii::$app->db->createCommand()->insert('customer_dynamics', [
            'customer_id' => $uid,
            'content'     => $content,
            'location'     => $location,
            'status'     => Dynamic::STATUS_WAIT,
        ])->execute();

        return [
            'code'    => 200,
            'message' => '发布成功',
            'data'    => [
                'id' => (int)Yii::$app->db->getLastInsertID(),
            ],
        ];
    }

    /**
     * 给动态列表附加当前用户的 is_liked / is_collected 标记
     * @param array $list 动态列表（引用传递，直接改）
     * @param int|null $uid 当前登录用户 id
     */
    private function attachUserFlags(&$list, $uid)
    {
        if (empty($list)) {
            return;
        }

        // 未登录：两个标记都返回 0
        if (!$uid) {
            foreach ($list as &$item) {
                $item['is_liked'] = 0;
                $item['is_collected'] = 0;
            }
            unset($item);
            return;
        }

        $ids = array_map('intval', array_column($list, 'id'));

        // 当前用户收藏了哪些（只查列表里的这些动态）
        $collectedIds = array_map('intval', (new Query())
            ->select('dynamic_id')
            ->from('dynamic_collects')
            ->where(['customer_id' => $uid])
            ->andWhere(['in', 'dynamic_id', $ids])
            ->column());

        // 当前用户点赞了哪些
        $likedIds = array_map('intval', (new Query())
            ->select('dynamic_id')
            ->from('dynamic_likes')
            ->where(['customer_id' => $uid])
            ->andWhere(['in', 'dynamic_id', $ids])
            ->column());

        foreach ($list as &$item) {
            $item['is_collected'] = in_array((int)$item['id'], $collectedIds, true) ? 1 : 0;
            $item['is_liked']     = in_array((int)$item['id'], $likedIds, true) ? 1 : 0;
        }
        unset($item);
    }
}