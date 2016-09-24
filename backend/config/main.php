<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php')
);

return [
    'id'         => 'app-backend',
    'name'       => 'Yii2 Admin',
    'basePath'   => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap'  => ['log'],
    'modules'    => [],
    'language'   => 'zh-CN',
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
            'enableAutoLogin' => false,
        ],

        // 错误页面
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],

    'params' => $params,
];