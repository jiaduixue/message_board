<?php

$config = [
    'components' => [
        'urlManager' => [
            'class' => 'yii\web\UrlManager', // 默认的核心 URL 管理类
//            'enablePrettyUrl' => true,
//            'showScriptName' => false,
//            'enableStrictParsing' => false,
            'rules' => [
                'login' => 'site/login',
                'logout' => 'site/logout',
                'error' => 'site/error',
                'index' => 'site/index',
                'register' => 'site/register',
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter', // 默认的核心格式化类
            // 定义日期格式（支持 ICU 语法）
            'dateFormat' => 'yyyy-MM-dd',
            // 定义日期时间格式
            'datetimeFormat' => 'yyyy-MM-dd HH:mm:ss',
            // 定义时区（默认 UTC）
            'timeZone' => 'Asia/Shanghai',
            // 定义货币和数字格式（可选）
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'CNY',
        ],

    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
