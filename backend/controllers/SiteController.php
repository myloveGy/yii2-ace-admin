<?php

namespace backend\controllers;

use yii\helpers\Url;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function actionIndex()
    {
        return $this->redirect(Url::toRoute('admin/default'));
    }
}
