<?php
/**
 * Created by PhpStorm.
 * User: liujinxing
 * Date: 2017/3/28
 * Time: 13:38
 */

namespace Admin\Strategy;


class DataTables extends Strategy
{
    public function getRequest()
    {
        // 接收参数
        $params = I('params');  // 查询参数
        $sort  = I('sSortDir_0', 'desc'); // 排序类型

        $intStart   = (int)I('iDisplayStart',  0);   // 开始位置
        $intLength  = (int)I('iDisplayLength', 10);  // 查询长度
        $field = isset($params['orderBy']) && !empty($params['orderBy']) ? $params['orderBy'] : null;

        return [
            'sort' => $sort,    // 排序方式
            'field' => $field, // 排序字段
            'offset' => $intStart, // 查询开始位置
            'limit' => $intLength, // 查询数据条数
            'params' => $params, // 查询参数
        ];

    }

    public function handleResponse($data, $total, $params = null)
    {
        return [
            'data' => [
                'sEcho' => $intNum  = (int)I('sEcho'),  // 请求次数
                'iTotalRecords' => count($data),        // 当前页面条数
                'iTotalDisplayRecords' => (int)$total,  // 数据总条数
                'aaData' => $data,                      // 数据信息
            ]
        ];
    }
}