<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;

/**
 * Site controller
 */
class GamesController extends Controller
{
    public function actionIndex()
    {
        $strGameName = Yii::$app->request->get('gameName');
    }
}
