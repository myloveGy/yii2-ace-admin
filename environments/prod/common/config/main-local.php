<?php
return [
    'components' => [
        // 数据库配置
        'db' => [
            'class'       => 'yii\db\Connection',
            'dsn'         => 'mysql:host=localhost;dbname=yii2',
            'username'    => 'root',
            'password'    => 'gongyan',
            'charset'     => 'utf8',
            'tablePrefix' => 'yii2_',
        ],

        // 邮件发送
        'mailer' => [
            'class'    => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
