<?php

namespace backend\controllers;

/**
 * Class SiteController 后台首页处理
 *
 * @package backend\controllers
 */
class SiteController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $this->redirect('/admin');
    }
}
