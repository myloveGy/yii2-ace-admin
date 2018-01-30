<?php
namespace doc\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Api;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'main-doc';
        return $this->render('index');
    }

    /**
     * 查看接口文档
     * @return mixed|string
     */
    public function actionDoc()
    {
        $data =  Api::find()->where(['version'=>'v1'])->all();
        $json = '';
        foreach ($data as $key => $value) {
            $json .= '"'.$value['url'].'":{';
            $json .= '"'.$value['method'].'":{';
            $json .= '"tags":["'.$value['tags'].'"]';
            $json .= ',"summary":"'.$value['summary'].'"';
            $json .= ',"description":"'.$value['description'].'"';
            $json .= ',"produces":"['.$value['produces'].']"';
            $json .= ',"parameters":'.$value['parameters'];
            $json .= ',"responses":'.$value['responses'];
            $json .= '}';
            $json .= '},';
        }
        $json = rtrim($json,',');
        return '{
            "swagger": "2.0",
            "info": {
                "title": "ApiDoc",
                "version": "v1"
            },
            "host":"api.yiiadmin.lin",
            "paths": {'.
            $json
            .'}
        }';
    }
}
