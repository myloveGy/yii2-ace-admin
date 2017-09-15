<?php

namespace backend\controllers;

use backend\models\Admin;
use yii;

/**
 * Class AdminLogController 操作日志 执行操作控制器
 * @package backend\controllers
 */
class AdminLogController extends Controller
{
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'backend\models\AdminLog';

    /**
     * 查询处理
     * @param  array $params
     * @return array 返回数组
     */
    public function where($params)
    {
        $where = [
            'type' => '=',
            'controller' => 'like',
            'action' => 'like',
            'url' => 'like',
            'created_id' => '=',
            'where' => [],
        ];

        if (Yii::$app->user->id != Admin::SUPER_ADMIN_ID) {
            $where['where'] = [
                ['!=', 'created_id', Admin::SUPER_ADMIN_ID]
            ];
        }

        return $where;
    }

    /**
     * 导出创建时间显示处理
     * @return array
     */
    public function getExportHandleParams()
    {
        return [
            'created_at' => function ($value) {
                return date('Y-m-d H:i:s', $value);
            }
        ];
    }
}
