<?php
/**
 * file: UserController.php
 * desc: 用户信息 执行操作控制器
 * date: 2016-10-10 18:26:50
 */

// 引入命名空间
namespace backend\controllers;

use backend\models\User;

/**
 * Class UserController
 * @package backend\controllers
 * @description 用户信息
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
     * actionIndex() 首页显示
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
