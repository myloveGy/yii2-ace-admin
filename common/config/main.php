<?php
return [
    'language'   => 'zh-CN',
    'timeZone'   => 'Asia/Shanghai',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        // 数据库配置
        'db' => [
            'class'       => 'yii\db\Connection',
            'dsn'         => 'mysql:host=localhost;dbname=my_yii2',
            'username'    => 'root',
            'password'    => 'gongyan',
            'charset'     => 'utf8',
            'tablePrefix' => 'yii_',
        ],

        // redis 配置
        'redis' => [
            'class'    => 'yii\redis\Connection',
            'hostname' => 'localhost',
            'port'     => 6379,
            'database' => 0,
        ],

        // 邮件配置
        'mailer' => [
            'class'            => 'yii\swiftmailer\Mailer',
            'viewPath'         => '@common/mail',
            'useFileTransport' => true,
        ],

        // 文件缓存
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        // 路由配置
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<id:\d+>'              => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>'          => '<controller>/<action>',
            ],
        ],

        // 资源管理修改
        'assetManager' => [
            'bundles' => [
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

        // 错误页面
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
];
