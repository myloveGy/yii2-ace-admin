<?php

namespace backend\controllers;

use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
        // return $this->redirect('/admin');
    }
}
