<?php
/**
 * file: UserController.php
 * desc: 用户信息 执行操作控制器
 * date: 2016-10-10 18:26:50
 */

// 引入命名空间
namespace backend\controllers;

use common\models\User;

class UserController extends Controller
{
    // 查询方法
    public function where($params)
    {
        return [
            'created_at' => '=',
			'updated_at' => '=',
        ];
    }

    // 返回 Modal
    public function getModel(){return new User();}
}
