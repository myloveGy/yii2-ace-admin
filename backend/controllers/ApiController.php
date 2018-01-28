<?php

namespace backend\controllers;

use Yii;
use common\helpers\Helper;
use backend\models\AdminLog;

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

        // 查询用户数据
        return $this->render('index', [
            'methods' => [
                'get'=>'get',
                'post'=>'post',
                'put'=>'put',
                'delete'=>'delete',
                'options'=>'options'
            ],
            'schemelist' => [
                'http'=>'http',
                'https'=>'https',
                'ws'=>'ws',
                'wss'=>'wss'
            ],
        ]);
    }


    /**
     * 查询处理
     * @param  array $params
     * @return array 返回数组
     */
    public function where($params)
    {
        return [

        ];
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
//        $this->layout = false;
        return $this->render('form',[
            'methods' => [
            'get'=>'get',
            'post'=>'post',
            'put'=>'put',
            'delete'=>'delete',
            'options'=>'options'
            ],
            'schemelist' => [
            'http'=>'http',
            'https'=>'https',
            'ws'=>'ws',
            'wss'=>'wss'
            ],
        ]);
    }

    /**
     * Api Store
     *
     */
    public  function actionCreate1()
    {

    }
}
