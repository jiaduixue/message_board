<?php

namespace app\controllers;

use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\auth\HttpBearerAuth;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use common\models\Message; // 引入 User 模型
/**
 * 漂流瓶功能（messages 表）
 *  parent_id = 0  → 扔出的瓶子
 *  parent_id > 0  → 捞瓶记录（parent_id = 原瓶子 id）
 */
class MessageController extends Controller
{
    public $enableCsrfValidation = false;   // 全局已关 CSRF 的话可删

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,   // 和你其它控制器保持一致
        ];
        return $behaviors;
    }

    /**
     * 扔一个漂流瓶
     * POST /message/throw   {"content":"瓶子里的话","title":"可选标题"}
     */
    public function actionThrow()
    {
        $uid = Yii::$app->user->id;

        $content = trim((string)Yii::$app->request->post('content', ''));
        $title   = trim((string)Yii::$app->request->post('title', ''));

        if ($content === '') {
            throw new BadRequestHttpException('漂流瓶内容不能为空');
        }
        if (mb_strlen($content) > 1000) {
            throw new BadRequestHttpException('内容太长啦（最多 1000 字）');
        }
        if (mb_strlen($title) > 100) {
            throw new BadRequestHttpException('标题不能超过 100 字');
        }

        // 带上当前用户的 username / email 落库
        $me = (new Query())
            ->select(['username', 'email'])
            ->from('customer')
            ->where(['id' => $uid])
            ->one();

        Yii::$app->db->createCommand()->insert('messages', [
            'username'    => $me ? $me['username'] : '',
            'title'       => $title,
            'email'       => $me ? $me['email'] : '',
            'content'     => $content,
            'parent_id'   => 0,                              // 0 = 扔出的瓶子
            'ip_address'  => (string)Yii::$app->request->userIP,
            'customer_id' => $uid,
            'status'      => Message::STATUS_PENDING_REVIEW,
        ])->execute();

        return [
            'code'    => 200,
            'message' => '漂流瓶已扔进大海 🌊',
            'data'    => ['id' => (int)Yii::$app->db->getLastInsertID()],
        ];
    }

    /**
     * 捞一个漂流瓶（随机，不捞自己的、不重复捞）
     * POST /message/pick   {"reply":"可选：给瓶主留一句话"}
     */
    public function actionPick()
    {
        $uid   = Yii::$app->user->id;
        $reply = trim((string)Yii::$app->request->post('reply', ''));

        // 我已经捞过的瓶子 id 子查询
        $pickedSub = (new Query())
            ->select('parent_id')
            ->from('messages')
            ->where(['customer_id' => $uid])
            ->andWhere(['>', 'parent_id', 0]);
        // $allowedStatues = [
        //     Message::STATUS_PENDING_REVIEW,
        //     Message::STATUS_NO_READ,
        //     Message::STATUS_READ,
        // ];
        // 随机捞一个：正常状态 + 是原瓶 + 不是我的 + 我没捞过
        $bottle = (new Query())
            ->select([
                'm.id', 'm.title', 'm.content', 'm.username',
                'm.customer_id', 'm.created_at',
                'c.avatar_url AS thrower_avatar',
            ])
            ->from('messages m')
            ->leftJoin('customer c', 'c.id = m.customer_id')
            ->where(['m.status' => Message::STATUS_NO_READ])
            ->where(['m.parent_id' => 0])
            ->andWhere(['<>', 'm.customer_id', $uid])
            ->andWhere(['not in', 'm.id', $pickedSub])
            ->orderBy(new Expression('RAND()'))
            ->limit(1)
            ->one();

        if (!$bottle) {
            return [
                'code'    => 200,
                'message' => '海里暂时没有可以捞的漂流瓶，稍后再来吧~',
                'data'    => null,
            ];
        }

        // 生成一条"捞瓶记录"（parent_id = 原瓶 id）
        $me = (new Query())
            ->select(['username', 'email'])
            ->from('customer')
            ->where(['id' => $uid])
            ->one();

        Yii::$app->db->createCommand()->insert('messages', [
            'username'    => $me ? $me['username'] : '',
            'title'       => '',
            'email'       => $me ? $me['email'] : '',
            'content'     => $reply,                          // 我的回复，可为空
            'parent_id'   => (int)$bottle['id'],
            'ip_address'  => (string)Yii::$app->request->userIP,
            'customer_id' => $uid,
            'status'      => Message::STATUS_PENDING_REVIEW,
        ])->execute();

        $bottle['picked_id']      = (int)Yii::$app->db->getLastInsertID();
        $bottle['my_reply']       = $reply;
        $bottle['thrower_avatar'] = $bottle['thrower_avatar'] ?? '';

        return [
            'code'    => 200,
            'message' => '捞到一个漂流瓶！',
            'data'    => $bottle,
        ];
    }

    /**
     * 我的瓶子（我扔的）  GET /message/my-bottles?page=1&pageSize=10
     */
    public function actionMyBottles()
    {
        $uid = Yii::$app->user->id;

        $page     = max(1, (int)Yii::$app->request->get('page', 1));
        $pageSize = min(50, max(1, (int)Yii::$app->request->get('pageSize', 10)));

        $query = (new Query())
            ->select([
                'm.*',
                // 这个瓶子被人捞了几次
                '(SELECT COUNT(*) FROM messages p WHERE p.parent_id = m.id) AS picked_count',
            ])
            ->from('messages m')
            ->where(['m.customer_id' => $uid])
            ->andWhere(['m.parent_id' => 0])
            ->orderBy(['m.created_at' => SORT_DESC, 'm.id' => SORT_DESC]);

        $total = (int)$query->count();
        $list  = $query->limit($pageSize)->offset(($page - 1) * $pageSize)->all();

        return [
            'code'    => 200,
            'message' => 'success',
            'data'    => ['total' => $total, 'page' => $page, 'pageSize' => $pageSize, 'list' => $list],
        ];
    }

    /**
     * 信箱（我捞的）  GET /message/my-mailbox?page=1&pageSize=10
     */
    public function actionMyMailbox()
    {
        $uid = Yii::$app->user->id;

        $page     = max(1, (int)Yii::$app->request->get('page', 1));
        $pageSize = min(50, max(1, (int)Yii::$app->request->get('pageSize', 10)));

        $query = (new Query())
            ->select([
                'p.id AS picked_id',          // 捞瓶记录 id
                'p.content AS my_reply',      // 我当时留的回复
                'p.created_at AS picked_at',  // 捞到的时间
                'b.id AS bottle_id',          // 原瓶子 id
                'b.title AS bottle_title',
                'b.content AS bottle_content',
                'b.username AS thrower_username',
                'b.created_at AS thrown_at',
                'c.avatar_url AS thrower_avatar',
            ])
            ->from('messages p')
            ->innerJoin('messages b', 'b.id = p.parent_id')   // 关联回原瓶子
            ->leftJoin('customer c', 'c.id = b.customer_id')
            ->where(['p.customer_id' => $uid])
            ->andWhere(['>', 'p.parent_id', 0])
            ->orderBy(['p.created_at' => SORT_DESC, 'p.id' => SORT_DESC]);

        $total = (int)$query->count();
        $list  = $query->limit($pageSize)->offset(($page - 1) * $pageSize)->all();

        return [
            'code'    => 200,
            'message' => 'success',
            'data'    => ['total' => $total, 'page' => $page, 'pageSize' => $pageSize, 'list' => $list],
        ];
    }

    /**
     * 我和某个瓶主的完整对话
     * GET /message/conversation?bottle_id=5
     *   - bottle_id: 原瓶子 id（从 my-mailbox 返回的 bottle_id 拿）
     */
    public function actionConversation()
    {
        $uid      = Yii::$app->user->id;
        $bottleId = (int)Yii::$app->request->get('bottle_id', 0);

        if ($bottleId <= 0) {
            throw new BadRequestHttpException('缺少 bottle_id 参数');
        }

        // 1. 取原瓶子信息 + 校验：必须是我扔的 或 我捞过的，才能看
        $bottle = (new Query())
            ->select(['b.id', 'b.title', 'b.content', 'b.username AS thrower_username',
                    'b.customer_id AS thrower_id', 'b.created_at AS thrown_at',
                    'c.avatar_url AS thrower_avatar'])
            ->from('messages b')
            ->leftJoin('customer c', 'c.id = b.customer_id')
            ->where(['b.id' => $bottleId, 'b.parent_id' => 0])
            ->one();

        if (!$bottle) {
            throw new NotFoundHttpException('漂流瓶不存在');
        }

        $iThrewIt  = ((int)$bottle['thrower_id'] === $uid);                 // 我扔的
        $pickedSub = (new Query())->select('id')->from('messages')
            ->where(['customer_id' => $uid, 'parent_id' => $bottleId]);     // 我捞过它
        $iPickedIt = $pickedSub->exists();

        if (!$iThrewIt && !$iPickedIt) {
            throw new ForbiddenHttpException('无权查看该对话');
        }

        // 2. 这个瓶子所有的捞瓶/回复记录（按时间正序，像聊天一样从上到下）
        $replies = (new Query())
            ->select([
                'p.id', 'p.content', 'p.customer_id', 'p.created_at',
                'c.username', 'c.avatar_url',
            ])
            ->from('messages p')
            ->leftJoin('customer c', 'c.id = p.customer_id')
            ->where(['p.parent_id' => $bottleId])
            ->orderBy(['p.created_at' => SORT_ASC, 'p.id' => SORT_ASC])
            ->all();

        // 给每条标记是不是"我"发的，前端好区分左右气泡
        foreach ($replies as &$r) {
            $r['is_mine'] = ((int)$r['customer_id'] === $uid) ? 1 : 0;
        }
        unset($r);

        return [
            'code'    => 200,
            'message' => 'success',
            'data'    => [
                'bottle'  => $bottle,    // 原瓶内容 + 瓶主信息
                'replies' => $replies,   // 所有往来回复（含 is_mine）
            ],
        ];
    }

    /**
     * 回复某个漂流瓶（往对话里再发一条）
     * POST /message/reply   {"bottle_id":5,"content":"你好呀~"}
     */
    public function actionReply()
    {
        $uid      = Yii::$app->user->id;
        $bottleId = (int)Yii::$app->request->post('bottle_id', 0);
        $content  = trim((string)Yii::$app->request->post('content', ''));

        if ($bottleId <= 0) {
            throw new BadRequestHttpException('缺少 bottle_id 参数');
        }
        if ($content === '') {
            throw new BadRequestHttpException('回复内容不能为空');
        }
        if (mb_strlen($content) > 1000) {
            throw new BadRequestHttpException('内容太长啦（最多 1000 字）');
        }

        // 1. 原瓶必须存在
        $bottle = (new Query())
            ->select(['id', 'customer_id'])
            ->from('messages')
            ->where(['id' => $bottleId, 'parent_id' => 0])
            ->one();
        if (!$bottle) {
            throw new NotFoundHttpException('漂流瓶不存在');
        }

        // 2. 权限：必须是我扔的 或 我捞过的
        $iThrewIt  = ((int)$bottle['customer_id'] === $uid);
        $iPickedIt = (new Query())->from('messages')
            ->where(['customer_id' => $uid, 'parent_id' => $bottleId])->exists();
        if (!$iThrewIt && !$iPickedIt) {
            throw new ForbiddenHttpException('你还没有和这个瓶子产生过交集，无法回复');
        }

        // 3. 插入一条新的回复记录（和"捞瓶"是同一种记录，parent_id = 原瓶 id）
        $me = (new Query())->select(['username', 'email'])->from('customer')->where(['id' => $uid])->one();

        Yii::$app->db->createCommand()->insert('messages', [
            'username'    => $me ? $me['username'] : '',
            'title'       => '',
            'email'       => $me ? $me['email'] : '',
            'content'     => $content,
            'parent_id'   => $bottleId,
            'ip_address'  => (string)Yii::$app->request->userIP,
            'customer_id' => $uid,
            'status'      => Message::STATUS_NO_READ,
        ])->execute();

        return [
            'code'    => 200,
            'message' => '回复成功',
            'data'    => [
                'id'         => (int)Yii::$app->db->getLastInsertID(),
                'bottle_id'  => $bottleId,
                'content'    => $content,
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];
    }
}