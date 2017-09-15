<?php

namespace backend\controllers;

/**
 * Class AuthRuleController 规则管理 执行操作控制器
 * @package backend\controllers
 */
class AuthRuleController extends Controller
{
    /**
     * @var string 定义使用默认排序字段
     */
    public $sort = 'name';

    /**
     * @var string 定义主键
     */
    public $pk = 'name';

    /**
     * 定义使用的model
     * @var string
     */
    public $modelClass = 'backend\models\AuthRule';

    /**
     * 查询处理
     * @param  array $params
     * @return array 返回数组
     */
    public function where($params)
    {
        return [
            'name' => 'like'
        ];
    }

    /**
     * 搜索之后的数据处理
     * @param mixed $array
     */
    public function afterSearch(&$array)
    {
        foreach ($array as &$value) {
            if ($value['data']) {
                $tmp = unserialize($value['data']);
                if (is_object($tmp)) {
                    $value['data'] = get_class($tmp);
                }
            }
        }
    }

    /**
     * 导出数据显示问题(时间问题可以通过Excel自动转换)
     * @return array $array
     */
    public function getExportHandleParams()
    {
        $array['created_at'] = $array['updated_at'] = function ($value) {
            return date('Y-m-d H:i:s', $value);
        };

        return $array;
    }
}
