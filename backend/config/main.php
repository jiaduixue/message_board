<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'class' => 'app\components\Request', // 自定义类路径
            'csrfParam' => '_csrf-backend',
            'cookieValidationKey' => 'd4HhKQ7m0kCOXYVQDIM8CFghiFr3HmPj',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'class' => 'yii\web\DbSession',
            'name' => 'advanced-backend',
        ],
        'log' => [
            'class' => 'yii\log\Dispatcher',
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'flushInterval' => 1, // 关键：立即刷新
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info', 'trace'], // 必须包含 debug
                    'exportInterval' => 1, // 关键：立即导出
                    'logVars' => [], // 可选：减少日志体积
                ],
            ],
        ],
        'errorHandler' => [
            'class' => 'app\components\CustomErrorHandler',
            'errorAction' => 'site/error',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=message_board',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8mb4',
        ],
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];
