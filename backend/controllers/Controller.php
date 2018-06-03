<?php

namespace backend\controllers;

use jinxing\admin\behaviors\AccessControl;

/**
 * Class Controller 后台的基础控制器
 * @author  liujx
 * @package backend\controllers
 */
class Controller extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
            ]
        ];
    }
}
