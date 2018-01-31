<?php

namespace backend\controllers;

/**
 * Class ApiController api 执行操作控制器
 * @package backend\controllers
 */
class ApiController extends Controller
{

    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'common\models\Api';

    /**
     * 首页显示
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'methods' => [
                'get' => 'get',
                'post' => 'post',
                'put' => 'put',
                'delete' => 'delete',
                'options' => 'options'
            ],
            'schemelist' => [
                'http' => 'http',
                'https' => 'https',
                'ws' => 'ws',
                'wss' => 'wss'
            ],
        ]);
    }

    /**
     * 查看接口文档
     * @return string
     */
    public function actionDoc()
    {
        $this->layout = false;
        return $this->render('doc');
    }

    /**
     * Api Form
     */
    public function actionForm()
    {
        return $this->render('form', [
            'methods' => [
                'get' => 'get',
                'post' => 'post',
                'put' => 'put',
                'delete' => 'delete',
                'options' => 'options'
            ],
            'schemelist' => [
                'http' => 'http',
                'https' => 'https',
                'ws' => 'ws',
                'wss' => 'wss'
            ],
        ]);
    }
}
