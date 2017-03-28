<?php
/**
 * Created by PhpStorm.
 * User: liujinxing
 * Date: 2017/3/28
 * Time: 13:38
 */

namespace common\strategy;


class DataTables extends Strategy
{

    public function getRequest()
    {
        $request = \Yii::$app->request;

        // 接收参数
        $params = $request->post('params');  // 查询参数
        $intStart   = (int)$request->post('iDisplayStart',  0);   // 开始位置
        $intLength  = (int)$request->post('iDisplayLength', 10);  // 查询长度

        // 接收处理排序信息
        $sort  = $request->post('sSortDir_0', 'desc'); // 排序类型
        if (isset($params['orderBy']) && !empty($params['orderBy'])) {
            $field = $params['orderBy'];
            unset($params['orderBy']);
        } else {
            $field = null;
        }

        $this->arrRequest = [
            'sort' => $sort,    // 排序方式
            'field' => $field, // 排序字段
            'offset' => $intStart, // 查询开始位置
            'limit' => $intLength, // 查询数据条数
            'params' => $params, // 查询参数
            'sEcho' => (int)$request->post('sEcho')
        ];

        return $this->arrRequest;
    }

    public function handleResponse($data, $total, $params = null)
    {
        return [
            'sEcho' => $this->arrRequest['sEcho'],  // 请求次数
            'iTotalRecords' => count($data),        // 当前页条数
            'iTotalDisplayRecords' => (int)$total,  // 数据总条数
            'aaData' => $data,                      // 数据信息
        ];
    }
}