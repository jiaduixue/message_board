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
            'identityClass'   => 'common\models\User', // ← 指向你的用户模型，必须实现 IdentityInterface
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

                ['class' => 'yii\rest\UrlRule', 'controller' => 'user'],

            ],
        ],
        
    ],
    'params' => $params,
];
