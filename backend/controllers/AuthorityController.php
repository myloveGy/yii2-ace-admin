<?php
/**
 * file: AuthorityController.php
 * desc: 权限信息 执行操作控制器
 * user: liujx
 * date: 2016-07-21 13:29:28
 */

namespace backend\controllers;

use backend\models\Auth;

/**
 * Class AuthorityController 权限管理类
 * @package backend\controllers
 */
class AuthorityController extends RoleController
{
    /**
     * where() 查询参数配置
     * @param array $params
     * @return array
     */
    public function where($params)
    {
        return [
            'name' => 'like',
			'description' => 'like',
            'where' => [['=', 'type', Auth::TYPE_PERMISSION]],
        ];
    }

    /**
     * 导出数据显示问题(时间问题可以通过Excel自动转换)
     * @param \backend\models\Auth $objModel
     */
    public function handleExport(&$objModel)
    {
        $objModel->created_at = date('Y-m-d H:i:s', $objModel->created_at);
        $objModel->updated_at = date('Y-m-d H:i:s', $objModel->updated_at);
    }
}
