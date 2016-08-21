<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id'       => 'app-backend',
    'name'     => 'Yii2 Admin',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap'  => ['log'],
    'modules'    => [],
    'components' => [
        // 权限管理
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],

        // 图片处理
        'image' => [
            'class'  => 'yii\image\ImageDriver',
            'driver' => 'GD'
        ],

        // 用户信息
        'user' => [
            'identityClass'   => 'common\models\Admin',
            'enableAutoLogin' => true,
        ],

        // 日志
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

        // 错误页面
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],

    'params' => $params,
];
