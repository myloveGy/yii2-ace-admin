<?php

namespace backend\controllers;

use jinxing\admin\behaviors\AccessControl;
use jinxing\admin\behaviors\Logging;
use jinxing\admin\controllers\Controller as BaseController;

/**
 * Class Controller 后台的基础控制器
 * @author  liujx
 * @package backend\controllers
 */
class Controller extends BaseController
{
    /**
     * @var string 使用yii2-admin 的布局
     */
    public $layout = '@jinxing/admin/views/layouts/main';

    /**
     * 定义使用的行为
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'access'  => [
                'class' => AccessControl::className(),
            ],

            'logging' => [
                'class' => Logging::className(),
            ],
        ];
    }
}
