<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php')
);

return [
    'id'                  => 'app-backend',
    'name'                => 'Yii2 Admin',
    'basePath'            => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap'           => ['log'],
    'modules'             => [
        'admin' => [
            'class' => 'jinxing\admin\Module',
            'user'  => 'user'
        ],
    ],
    'language'            => 'zh-CN',
    'components'          => [
        // 权限管理
        'authManager'  => [
            'class' => 'yii\rbac\DbManager',
        ],

        // 资源管理修改
        'assetManager' => [
            'bundles' => [
                // 去掉自己的bootstrap 资源
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => []
                ],
                // 去掉自己加载的Jquery
                'yii\web\JqueryAsset'          => [
                    'sourcePath' => null,
                    'js'         => [],
                ],
            ],
        ],

        // 用户信息
        'user'         => [
            'identityClass'   => 'jinxing\admin\models\Admin',
            'loginUrl'        => '/admin/default/login',
            'enableAutoLogin' => true,
        ],

        // 错误页面
        'errorHandler' => [
            'errorAction' => 'admin/default/error',
        ],

        // 路由配置
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                 '<controller:\w+>/<id:\d+>' => '<controller>/view',
                 '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                 '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
    ],

    'params' => $params,
];