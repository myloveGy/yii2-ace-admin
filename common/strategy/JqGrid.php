<?php
/**
 * Created by PhpStorm.
 * User: liujinxing
 * Date: 2017/3/28
 * Time: 13:39
 */

namespace common\strategy;

class JqGrid extends  Strategy
{

    public function getRequest()
    {
        // 接收参数参数
        $request = \Yii::$app->request;
        $intPage = (int)$request->post('page', 1); // 第几页
        $intRows = (int)$request->post('rows', 10); // 每页多少条
        $field = $request->post('sidx');      // 排序字段
        $sort = $request->post('sord'); // 排序方式
        $params = $request->post('params'); // 查询参数

        // 处理查询数据条数
        $intPage = $intPage ? $intPage : 1;  // 默认第一页
        $intStart = ($intPage - 1) * $intRows;

        // 处理排序信息
        $sort = $sort == 'asc' ? SORT_ASC : SORT_DESC;

        // 返回查询字段信息
        $this->arrRequest = [
            'sort' => $sort,    // 排序方式
            'field' => $field, // 排序字段
            'offset' => $intStart, // 查询开始位置
            'limit' => $intRows, // 查询数据条数
            'page' => $intPage, // 第几页
            'params' => $params, // 查询参数
        ];

        return $this->arrRequest;
    }

    public function handleResponse($data, $total, $params = null)
    {
        $intTotalPage = $total > 0 ? ceil($total / $this->arrRequest['limit']) : 0;

        // 返回数据
        return [
            'page' => $this->arrRequest['page'], // 第几页
            'total' => $intTotalPage, // 总页数
            'records' => $total, // 总数据条数
            'rows' => $data, // 数据
        ];
    }
}