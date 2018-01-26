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
    public $modelClass = 'backend\models\Api';

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
     * 查看接口文档
     * @return mixed|string
     */
    public function actionSwagger()
    {
        $data =  $this->getQuery(['version'=>'v1'])->all();
        $json = '';
        foreach ($data as $key => $value) {
            $json .= '"'.$value['url'].'":{';
            $json .= '"'.$value['method'].'":{';
            $json .= '"tags":["'.$value['tags'].'"]';
            $json .= ',"summary":"'.$value['summary'].'"';
            $json .= ',"description":"'.$value['description'].'"';
            $json .= ',"produces":'.$value['produces'];
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
            "paths": {'.
                $json
            .'}
        }';
    }

    /**
     * Api Form
     */
    public function actionForm()
    {
        $this->layout = false;
        return $this->render('form');
    }
}
