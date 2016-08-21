<?php
/**
 * file: AuthorityController.php
 * desc: 权限信息 执行操作控制器
 * user: liujx
 * date: 2016-07-21 13:29:28
 */

// 引入命名空间
namespace backend\controllers;

use backend\models\Auth;

class AuthorityController extends Controller
{
    public $sort = "created_at";
    // 查询方法
    public function where($params)
    {
        return [
            'name'        => 'like',
			'description' => 'like',
            'where'       => [['=', 'type', Auth::TYPE_PERMISSION]],
        ];
    }

    // 返回 Modal
    public function getModel(){return new Auth();}

    // 导出数据显示问题(时间问题可以通过Excel自动装换)
    public function handleExport(&$arrObject)
    {
        foreach ($arrObject as $value)
        {
            $value->created_at = date('Y-m-d H:i:s', $value->created_at);
            $value->updated_at = date('Y-m-d H:i:s', $value->updated_at);
        }
    }
}
