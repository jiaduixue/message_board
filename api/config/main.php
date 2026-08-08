<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',
    'components' => [
        'request' => [
            'baseUrl'    => '/api/web',     // 与你 URL 里的前缀一致
            'csrfParam' => '_csrf-frontend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=message_board',
            'username' => 'yoga',
            'password' => 'Yogazlt1@',
            'charset' => 'utf8mb4',
        ],
        'user' => [
            'identityClass'   => 'common\models\Customer', // ← 指向你的用户模型，必须实现 IdentityInterface
            'enableAutoLogin' => false,
            'enableSession'   => false,   // REST 无状态，不用 session
            'loginUrl'        => null,    // 不跳登录页，未登录直接 401
        ],
        'response' => [
            'format' => \yii\web\Response::FORMAT_JSON,   // 不看 Accept 头，统一 JSON
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class'         => 'yii\web\JsonResponseFormatter',
                    'prettyPrint'   => YII_DEBUG,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-api',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        
        'urlManager' => [

            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,   // REST 建议开；但开了之后「没写进 rules 的路由」一律 404，见下方提醒

            'showScriptName' => false,
            'rules' => [
                // 自定义登录路由，映射到 user 控制器的 login 动作
                ['class' => 'yii\rest\UrlRule', 'controller' => 'user', 'pluralize' => false], 
                'POST users/login' => 'user/login',
                'POST users/register' => 'user/register',   // ← 新增这一行
                'POST users/forgot-password' => 'user/forgot-password',   // ← 新增
                'POST users/reset-password'  => 'user/reset-password',    // ← 新增
                'GET  users/profile'         => 'user/profile',   // ← 新增
                'POST users/update-profile' => 'user/update-profile',   // ← 新增

                // 默认的 RESTful 路由
                ['class' => 'yii\rest\UrlRule', 'controller' => 'user'],
                'GET dynamics/my-list'  => 'dynamics/my-list',
                'POST dynamics/publish' => 'dynamics/publish',
                'GET dynamics/list'     => 'dynamics/list',      // ← 新增这条
                'GET dynamics/my-collects' => 'dynamics/my-collects',   // ← 新增
                'POST dynamics/like'      => 'dynamics/like',        // ← 新增
                'POST dynamics/collect'    => 'dynamics/collect',     // ← 新增
                'POST dynamics/comment'    => 'dynamics/comment',     // ← 新增

                'POST message/throw'      => 'message/throw',        // 扔瓶子
                'POST message/pick'       => 'message/pick',         // 捞瓶子
                'GET message/my-bottles'  => 'message/my-bottles',   // 我的瓶子标签
                'GET message/my-mailbox'  => 'message/my-mailbox',   // 信箱标签
                'GET message/conversation' => 'message/conversation',  // 对话详情
                'POST message/reply'       => 'message/reply',         // 回复对方

                'GET member/plans'        => 'member/plans',         // 会员套餐
                'POST member/create-order'=> 'member/create-order',  // 创建订单(微信/支付宝)
                'POST member/pay-success' => 'member/pay-success',   // 支付回调(演示)
                'GET member/info'         => 'member/info',          // 我的会员
                'GET member/payments'     => 'member/payments',      // 支付记录
            ],
        ],
        
    ],
    'params' => $params,
];
