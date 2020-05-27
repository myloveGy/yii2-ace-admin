<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id'                  => 'app-backend',
    'name'                => 'Yii Ace Admin',
    'basePath'            => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap'           => ['log'],
    'language'            => 'zh-CN',
    'modules'             => [
        'admin' => [
            'class'                 => 'jinxing\admin\Module',
            'user'                  => 'user',
            'loginOtherRenderPaths' => [],
        ],
    ],
    'components'          => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user'    => [
            'identityClass'   => 'jinxing\admin\models\Admin',
            'loginUrl'        => '/admin/default/login',
            'returnUrl'       => '/admin/default',
            'enableAutoLogin' => true,
            'identityCookie'  => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log'     => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],

        'errorHandler' => [
            'errorAction' => 'admin/default/error',
        ],

        // authority management
        'authManager'  => [
            'class' => 'yii\rbac\DbManager',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [

            ],
        ],

        'assetManager' => [
            'bundles' => [
                // 去掉自己的bootstrap 资源
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],
                ],
                // 去掉自己加载的Jquery
                'yii\web\JqueryAsset'          => [
                    'sourcePath' => null,
                    'js'         => [],
                ],
            ],
        ],

        'i18n' => [
            'translations' => [
                'admin' => [
                    'class'          => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en',
                    'basePath'       => '@jinxing/admin/messages',
                ],
            ],
        ],
    ],
    'params'              => $params,
];
