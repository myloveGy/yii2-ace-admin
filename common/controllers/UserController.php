<?php

namespace common\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;

// 用户必须登录的控制器
class UserController extends Controller
{
    // 初始化处理
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
        ];
    }
}