<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\Auth;
use backend\models\AuthAssignment;
use yii\helpers\Json;
use common\helpers\Helper;
use yii;

/**
 * Class AuthAssignmentController 角色分配 执行操作控制器
 * @package backend\controllers
 */
class AuthAssignmentController extends Controller
{
    /**
     * @var string 定义默认排序使用的字段
     */
    public $sort = 'created_at';

    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'backend\models\AuthAssignment';

    /**
     * 查询处理
     * @param  array $params
     * @return array 返回数组
     */
    public function where($params)
    {
        return [
            'user_id' => function ($value) {
                return ['in', 'user_id', $value];
            },
            'item_name' => function ($value) {
                return ['in', 'item_name', $value];
            }
        ];
    }

    /**
     * 显示视图
     * @return string
     */
    public function actionIndex()
    {
        // 查询出全部角色
        $arrRoles = Admin::getArrayRole();

        // 载入视图
        return $this->render('index', [
            'arrRoles' => $arrRoles,
            'roles' => Json::encode($arrRoles),
        ]);
    }

    /**
     * 处理新增数据
     *
     * @return mixed|string
     */
    public function actionCreate()
    {
        $data = Yii::$app->request->post();
        if (empty($data['user_id']) || empty($data['item_name']) || !is_array($data['item_name'])) {
            return $this->error(201);
        }

        foreach ($data['item_name'] as $name) {
            $model = new AuthAssignment();
            $model->item_name = $name;
            $model->user_id = $data['user_id'];
            if ($model->save()) {
                $this->arrJson['errMsg'] .= $model->item_name . ': 处理成功';
            } else {
                $this->arrJson['errMsg'] .= $model->item_name . ': ';
                $this->arrJson['errMsg'] .= Helper::arrayToString($model->getErrors());
            }
        }

        return $this->success($data, 0);
    }

    /**
     * 删除分配信息
     * @return mixed|string
     */
    public function actionDelete()
    {
        $data = Yii::$app->request->post();
        if (empty($data['item_name']) || empty($data['user_id'])) {
            return $this->error(201);
        }

        // 通过传递过来的唯一主键值查询数据
        /* @var $model \yii\db\ActiveRecord */
        $model = AuthAssignment::findOne(['item_name' => $data['item_name'], 'user_id' => $data['user_id']]);
        if (empty($model)) {
            $this->error(222);
        }

        // 删除数据成功
        if ($model->delete()) {
            return $this->success($model);
        } else {
            return $this->error(1004, Helper::arrayToString($model->getErrors()));
        }
    }
}
