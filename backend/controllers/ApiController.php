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
     * 搜索配置
     * @param  array $params 查询参数
     * @return array
     */
    public function where($params)
    {
        return [
            'id' => '=',
            'summary' => 'like',
        ];
    }

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

    public function actionCreate()
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
