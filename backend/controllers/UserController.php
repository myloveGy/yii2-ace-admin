<?php
namespace backend\controllers;

use backend\models\User;

/**
 * Class UserController 用户信息
 * @package backend\controllers
 */
class UserController extends Controller
{
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'backend\models\User';

    /**
     * 查询处理
     * @param  array $params
     * @return array 返回数组
     */
    public function where($params)
    {
        return [
            'username' => 'like',
			'email' => 'like',
        ];
    }

    /**
     * 首页显示
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'status' => User::getArrayStatus(),
            'statusColor' => User::getStatusColor(),
        ]);
    }

    /**
     * 处理导出数据显示的问题
     * @param array $array
     */
    public function handleExport(&$array)
    {
        $array['created_at'] = date('Y-m-d H:i:s', $array['created_at']);
        $array['updated_at'] = date('Y-m-d H:i:s', $array['updated_at']);
    }
}
