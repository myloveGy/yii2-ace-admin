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

class AuthorityController extends RoleController
{
    public $type = Auth::TYPE_PERMISSION;

    /**
     * where() 查询参数配置
     * @param array $params
     * @return array
     */
    public function where($params)
    {
        return [
            'name'        => 'like',
			'description' => 'like',
            'where'       => [['=', 'type', Auth::TYPE_PERMISSION]],
        ];
    }

    /**
     * handleExport() 导出数据显示问题(时间问题可以通过Excel自动装换)
     */
    public function handleExport(&$objModel)
    {
        $objModel->created_at = date('Y-m-d H:i:s', $objModel->created_at);
        $objModel->updated_at = date('Y-m-d H:i:s', $objModel->updated_at);
    }
}
