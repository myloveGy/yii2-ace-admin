<?php
namespace backend\controllers;

class GridController extends Controller
{
    /**
     * getQueryRequest() 获取查询参数处理返回
     * @return array 返回查询信息
     */
    public function getQueryRequest()
    {
        // 接收参数参数
        $request = Yii::$app->request;
        $intPage = (int)$request->post('page'); // 第几页
        $intRows = (int)$request->post('rows'); // 每页多少条
        $field = $request->post('sidx');      // 排序字段
        $sord = $request->post('sord'); // 排序方式

        // 处理查询数据条数
        $intPage = $intPage ? $intPage : 1;  // 默认第一页
        $intStart = ($intPage - 1) * $intRows;

        // 处理排序信息
        $field = $field ? $field : $this->sort;
        $srod = $sord == 'asc' ? SORT_ASC : SORT_DESC;

        // 返回查询字段信息
        return [
            'orderBy' => [$field => $srod], // 排序
            'sort' => $srod,    // 排序方式
            'field' => $filed, // 排序字段
            'offset' => $intStart, // 查询开始位置
            'limit' => $intRows, // 查询数据条数
            'page' => $intPage, // 第几页
            'where' => [],
        ];
    }

    /**
     * actionSearch() 处理查询数据
     * @return mixed|string
     */
    public function actionSearch()
    {
        // 查询数据预先处理
        $search = $this->query();
        $query  = $this->getModel()->find()->where($search['where']);

        // 查询数据条数
        $total = $query->count();
        if ($total) {
            // 计算总页数
            $intTotalPage = ceil($total / $search['limit']);

            // 查询数据
            $array = $query->offset($search['offset'])->limit($search['limit'])->orderBy($search['orderBy'])->all();
            if ($array) $this->afterSearch($array);
        } else {
            $array = [];
            $intTotalPage = 0;
        }

        // 返回数据
        $this->arrJson = [
            'page' => $search['page'], // 第几页
            'total' => $intTotalPage, // 总页数
            'records' => $total, // 总数据条数
            'rows' => $array, // 数据
            'other'   => $query->offset($search['offset'])->limit($search['limit'])->orderBy($search['orderBy'])->createCommand()->getRawSql(),
        ];

        return $this->returnJson();
    }
}
