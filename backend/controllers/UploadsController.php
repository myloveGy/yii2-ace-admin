<?php

namespace backend\controllers;

/**
 * Class UploadsController 上传文件 执行操作控制器
 * @package backend\controllers
 */
class UploadsController extends Controller
{
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'common\models\Uploads';

    /**
     * 查询处理
     * @return array 返回数组
     */
    public function where()
    {
        return [
            ['title', 'like']
        ];
    }
}
