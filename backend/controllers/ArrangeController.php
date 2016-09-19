<?php
/**
 * file: ArrangeController.php
 * desc: 管理员日程安排 执行操作控制器
 * user: liujx
 * date: 2016-09-19 14:39:17
 */

// 引入命名空间
namespace backend\controllers;

use backend\models\Arrange;

class ArrangeController extends Controller
{
    // 查询方法
    public function where($params)
    {
        return [
            			'id' => '=', 
			'title' => '=', 
			'status' => '=', 

        ];
    }

    // 返回 Modal
    public function getModel(){return new Arrange();}
}
