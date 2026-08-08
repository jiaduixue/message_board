<?php

namespace api\controllers;

use Yii;
use yii\web\Controller;
use yii\rest\ActiveController;
use yii\filters\ContentNegotiator;     // ← 你之前漏的就是这一行
use yii\web\Response; 
use yii\db\Query;

use yii\web\BadRequestHttpException;
use common\models\Customer; // 引入 User 模型


/**
 * Site controller
 */
class UserController extends ActiveController
{
    public $modelClass = 'common\models\Customer';
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];
   
    /**
     * 登录接口
     * POST /users/login
     */
    public function actionLogin()
    {
        // 1. 获取前端传来的参数 (假设是 JSON 格式: {"username": "xx", "password": "xx"})
         $request = Yii::$app->request;
         $username =  $request->post('username');
         $password =  $request->post('password');

        if (empty( $username) || empty( $password)) {
            throw new BadRequestHttpException('用户名和密码不能为空');
        }

        // 2. 查找用户
         $user = Customer::findByUsername( $username);
  
        // 3. 验证密码
        if ( $user &&  $user->validatePassword( $password)) {
            // 4. 生成新的 Token 并保存到数据库
             $user->generateAuthKey(); 
            if ( $user->save(false)) { // false 表示不进行规则验证，直接保存
                // 5. 返回成功信息和 Token
                return [
                    'code' => 200,
                    'message' => '登录成功',
                    'data' => [
                        'access_token' =>  $user->auth_key, // 或者  $ user->access_token
                        'user_id' =>  $user->id,
                        'username' =>  $user->username
                    ]
                ];
            } else {
                throw new BadRequestHttpException('Token 生成失败');
            }
        } else {
            throw new BadRequestHttpException('用户名或密码错误');
        }
    }
    /**
     * 我的信息接口（需要登录，带 Token 访问）
     * GET /users/profile
     */
    public function actionProfile()
    {
        // 当前登录用户 ID（由 HttpBearerAuth 认证后自动得到）
        $uid = Yii::$app->user->id;

        // ========== 1. 基本信息（基本表 customer） ==========
        $info = (new Query())
            ->from('customer')
            ->where(['id' => $uid])
            ->one();

        // 如果 customer 表里没有对应记录，就退回 user 表的基本信息
        if (!$info) {
            $user = Yii::$app->user->identity;
            $info = [
                'id'       => $user->id,
                'username' => $user->username,
                'phone'    => $user->phone,
                'email'    => $user->email,
            ];
        }

        // ========== 2. 我的动态数量（customer_dynamics） ==========
        $dynamicsCount = (new Query())
            ->from('customer_dynamics')
            ->where(['customer_id' => $uid])
            ->count();

        // ========== 3. 我收藏别人动态的数量（dynamic_collects） ==========
        $collectsCount = (new Query())
            ->from('dynamic_collects')
            ->where(['customer_id' => $uid])
            // 如果你有"取消收藏"功能（status=0 表示取消），打开下面这行只统计有效收藏
            // ->andWhere(['status' => 1])
            ->count();

        // ========== 4. 我的动态获得的总赞数（dynamic_likes 关联 customer_dynamics） ==========
        $likesReceived = (new Query())
            ->from('dynamic_likes l')
            ->innerJoin('customer_dynamics d', 'l.dynamic_id = d.id')
            ->where(['d.customer_id' => $uid])
            // 同理，如果点赞也可以取消，打开下面这行
            // ->andWhere(['l.status' => 1])
            ->count();

        return [
            'code'    => 200,
            'message' => 'success',
            'data'    => [
                'info'  => $info,
                'stats' => [
                    'dynamics_count' => (int)$dynamicsCount,  // 我的动态数量
                    'collects_count' => (int)$collectsCount,  // 我的收藏数量
                    'likes_received' => (int)$likesReceived,  // 我的动态总获赞数
                ],
            ],
        ];
    }

    /**
     * 修改个人信息（只更新传了的字段）
     * POST /user/update-profile
     * Body(JSON): { "nickname":"新昵称", "avatar_url":"...", "bio":"...", "gender":"男" }
     */
    public function actionUpdateProfile()
    {
        $uid = Yii::$app->user->id;
        if (!$uid) {
            throw new BadRequestHttpException('请先登录');
        }

        $post = Yii::$app->request->post();   // 拿到所有提交的字段

        $update = [];   // 只收集"确实传了且合法"的字段

        // —— nickname 昵称 ——
        if (array_key_exists('nickname', $post)) {
            $nickname = trim((string)$post['nickname']);
            if ($nickname === '') {
                throw new BadRequestHttpException('昵称不能为空');
            }
            if (mb_strlen($nickname) > 50) {
                throw new BadRequestHttpException('昵称不能超过 50 个字符');
            }
            $update['nickname'] = $nickname;
        }

        // —— avatar_url 头像 ——
        if (array_key_exists('avatar_url', $post)) {
            $avatarUrl = trim((string)$post['avatar_url']);
            if (mb_strlen($avatarUrl) > 255) {
                throw new BadRequestHttpException('头像地址过长');
            }
            $update['avatar_url'] = $avatarUrl;   // 允许传空字符串=清空头像
        }

        // —— bio 签名 ——
        if (array_key_exists('bio', $post)) {
            $bio = trim((string)$post['bio']);
            if (mb_strlen($bio) > 500) {
                throw new BadRequestHttpException('签名不能超过 500 个字符');
            }
            $update['bio'] = $bio;
        }

        // —— gender 性别（只能是 男/女/保密）——
        if (array_key_exists('gender', $post)) {
            $gender = trim((string)$post['gender']);
            if (!in_array($gender, ['男', '女', '保密'], true)) {
                throw new BadRequestHttpException('性别只能是 男 / 女 / 保密');
            }
            $update['gender'] = $gender;
        }

        // 一个字段都没传
        if (empty($update)) {
            throw new BadRequestHttpException('没有要修改的内容');
        }

        // 执行更新（只改 customer 表当前用户这一行）
        Yii::$app->db->createCommand()
            ->update('customer', $update, ['id' => $uid])
            ->execute();

        // 返回更新后的最新信息
        $row = (new Query())
            ->select(['id', 'username', 'nickname', 'avatar_url', 'bio', 'gender'])
            ->from('customer')
            ->where(['id' => $uid])
            ->one();

        return [
            'code'    => 200,
            'message' => '修改成功',
            'data'    => $row,
        ];
    }
    /**
     * 注册接口
     * POST /users/register
     */
    public function actionRegister()
    {
        $request  = Yii::$app->request;
        $username = trim($request->post('username'));
        $password = $request->post('password');
        $phone    = trim($request->post('phone'));
        $email    = trim($request->post('email'));

        // 1. 必填项检查
        if (empty($username) || empty($password) || empty($phone) || empty($email)) {
            throw new BadRequestHttpException('用户名、密码、手机号、邮箱不能为空');
        }

        // 2. 格式检查
        if (mb_strlen($username) < 2 || mb_strlen($username) > 20) {
            throw new BadRequestHttpException('用户名长度需在 2-20 个字符之间');
        }
        if (strlen($password) < 6) {
            throw new BadRequestHttpException('密码长度不能少于 6 位');
        }
        if (!preg_match('/^1[3-9]\d{9}$/', $phone)) {
            throw new BadRequestHttpException('手机号格式不正确');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new BadRequestHttpException('邮箱格式不正确');
        }

        // 3. 查重（核心需求：用户名不能和数据库重复）
        if (Customer::find()->where(['username' => $username])->exists()) {
            throw new BadRequestHttpException('该用户名已被注册');
        }
        if (Customer::find()->where(['phone' => $phone])->exists()) {
            throw new BadRequestHttpException('该手机号已被注册');
        }
        if (Customer::find()->where(['email' => $email])->exists()) {
            throw new BadRequestHttpException('该邮箱已被注册');
        }

        // 4. 创建用户（密码必须加密存储！）
        $user = new Customer();
        $user->username      = $username;
        $user->phone         = $phone;
        $user->email         = $email;
        $user->password         = $password;
        $user->status         = $user::STATUS_ACTIVE;
        $user->password_hash = Yii::$app->security->generatePasswordHash($password);
        $user->auth_key      = Yii::$app->security->generateRandomString();
        // 如果你的 user 表有 status 字段且没有默认值，打开下面这行：
        // $user->status = 10;

        if ($user->save(false)) {
            return [
                'code'    => 200,
                'message' => '注册成功',
                'data'    => [
                    'user_id'  => $user->id,
                    'username' => $user->username,
                    'phone'    => $user->phone,
                    'email'    => $user->email,
                ],
            ];
        }

        throw new BadRequestHttpException('注册失败，请稍后再试');
    }
    // 记得在 behaviors 中允许 login 动作不需要认证（如果配置了全局认证的话）
    public function behaviors()
    {
         $behaviors = parent::behaviors();
        
        // 配置 CORS (如果需要跨域)
        // ...

        // 配置认证器 (HttpBearerAuth 或 QueryParamAuth)
         $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::className(),
            // 重点：除了 login 动作外，其他动作都需要验证 Token
            'except' => ['login', 'register', 'forgot-password', 'reset-password'], 
        ];

        return  $behaviors;
    }
    
    /**
     * 找回密码 - 第一步：验证身份并签发重置令牌
     * POST /users/forgot-password
     * 参数：username（必填），phone 或 email（二选一，至少传一个且需与该用户名匹配）
     */
    public function actionForgotPassword()
    {
        $request  = Yii::$app->request;
        $username = trim($request->post('username'));
        $phone    = trim($request->post('phone'));
        $email    = trim($request->post('email'));

        // 1. 基本校验
        if (empty($username)) {
            throw new BadRequestHttpException('用户名不能为空');
        }
        if (empty($phone) && empty($email)) {
            throw new BadRequestHttpException('手机号和邮箱至少填写一个');
        }

        // 2. 按用户名查找用户
        $user = Customer::findByUsername($username);
        if (!$user) {
            // 注意：出于安全考虑，不直接告诉用户"用户名不存在"，统一提示
            throw new BadRequestHttpException('用户名与提供的手机号/邮箱不匹配！');
        }

        // 3. 核心校验：手机号或邮箱，任意一个与该用户名匹配即可
        $matched = false;
        if (!empty($phone) && $user->phone === $phone) {
            $matched = true;
        }
        if (!empty($email) && $user->email === $email) {
            $matched = true;
        }

        if (!$matched) {
            throw new BadRequestHttpException('用户名与提供的手机号/邮箱不匹配');
        }

        // 4. 匹配成功，生成重置令牌
        $user->generatePasswordResetToken();
        if (!$user->save(false)) {
            throw new BadRequestHttpException('令牌生成失败，请稍后再试');
        }

        // 实际项目中这里应该把 token 通过短信/邮件发给用户，
        // 这里为了测试方便直接返回（上线时请改为发送验证码/链接，不要直接返回 token）
        return [
            'code'    => 200,
            'message' => '验证通过，请使用该令牌重置密码',
            'data'    => [
                'reset_token' => $user->password_reset_token,
                'expire_in'   => 3600, // 秒
            ],
        ];
    }

    /**
     * 找回密码 - 第二步：凭令牌设置新密码
     * POST /users/reset-password
     * 参数：reset_token（第一步返回的令牌），new_password（新密码）
     */
    public function actionResetPassword()
    {
        $request     = Yii::$app->request;
        $resetToken  = trim($request->post('reset_token'));
        $newPassword = $request->post('new_password');

        if (empty($resetToken) || empty($newPassword)) {
            throw new BadRequestHttpException('重置令牌和新密码不能为空');
        }
        if (strlen($newPassword) < 6) {
            throw new BadRequestHttpException('新密码长度不能少于 6 位');
        }

        // 1. 用令牌找用户（内部已校验是否过期）
        $user = Customer::findByPasswordResetToken($resetToken);
        if (!$user) {
            throw new BadRequestHttpException('重置令牌无效或已过期，请重新申请');
        }

        // 2. 更新密码并清除令牌
        $user->password = $newPassword;
        $user->password_hash = Yii::$app->security->generatePasswordHash($newPassword);
        $user->removePasswordResetToken();

        if ($user->save(false)) {
            return [
                'code'    => 200,
                'message' => '密码重置成功，请使用新密码登录',
            ];
        }

        throw new BadRequestHttpException('密码重置失败，请稍后再试');
    }
   
    public function actionError()
    {
        // 你的错误处理逻辑
        return $this->render('error');
    }
    
}
