<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/params.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        // 用户配置
        'user' => [
            'identityClass'   => 'common\models\User',
            'enableAutoLogin' => true,
        ],

        // 路由重写
        'urlManager' => [
            'rules' => [
                '<gameName:\w+>/<payType:\w+>/payment' => 'pay/payment', // 支付的路由
                'games/<gameName:\w+>/play'            => 'games/index', // 游戏选择
            ],
        ],
    ],
    'params' => $params,
];
