<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        // 缓存配置
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        // 数据库配置
        'db' => [
            'class'       => 'yii\db\Connection',
            'dsn'         => 'mysql:host=localhost;dbname=yii2',
            'username'    => 'root',
            'password'    => 'gongyan',
            'charset'     => 'utf8',
            'tablePrefix' => 'yii2_',
        ],

        // 路由配置
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules' => [
                '<controller:\w+>/<id:\d+>'              => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>'          => '<controller>/<action>',
            ],
        ],

        // 资源管理修改
        'assetManager' => [
            'bundles' => [
                // 去掉自己的bootstrap 资源
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => []
                ],
                // 去掉自己加载的Jquery
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js'         => [],
                ],
            ],
        ],

        // 多语言配置
        'i18n' => [
            'translations' => [
                '*' => [
                    'class'   => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'app'       => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
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
    ],
];
