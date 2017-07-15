<?php
namespace backend\controllers;

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
     * where() 查询处理
     * @param  array $params
     * @return array 返回数组
     */
    public function where($params)
    {
        return [
            'user_id' => '=',
            'item_name' => '='
        ];
    }

    /**
     * 显示视图
     * @return string
     */
    public function actionIndex()
    {
        // 查询出全部角色
        $roles = Auth::find()->where([
            'type' => Auth::TYPE_ROLE
        ])->orderBy(['created_at' => SORT_DESC])->all();
        $arrRoles = [];
        foreach ($roles as $value) {
            /* @var $value \backend\models\Auth */
            $arrRoles[$value->name] = $value->name.' - '.$value->description;
        }

        // 载入视图
        return $this->render('index', [
            'arrRoles' => $arrRoles,
            'roles' => Json::encode($arrRoles),
            'model' => new AuthAssignment()
        ]);
    }

    /**
     * 处理新增数据
     * @return mixed|string
     */
    public function actionCreate()
    {
        $data = Yii::$app->request->post();
        if ($data && !empty($data['user_id']) && !empty($data['item_name'])) {
            if (is_array($data['item_name'])) {
                foreach ($data['item_name'] as $name) {
                    $model = new AuthAssignment();
                    $model->item_name = $name;
                    $model->user_id = $data['user_id'];
                    if ($model->save()) {
                        $this->arrJson['errMsg'] .= $model->item_name.': 处理成功';
                    } else {
                        $this->arrJson['errMsg'] .= $model->item_name.': ';
                        $this->arrJson['errMsg'] .= Helper::arrayToString($model->getErrors());
                    }
                }

                $this->handleJson($data, 0);
            }
        }

        // 返回数据
        return $this->returnJson();
    }

    /**
     * 删除分配信息
     * @return mixed|string
     */
    public function actionDelete()
    {
        $data = \Yii::$app->request->post();
        if ($data && !empty($data['item_name']) && !empty($data['user_id'])) {
            // 通过传递过来的唯一主键值查询数据
            /* @var $model \yii\db\ActiveRecord */
            $model = AuthAssignment::findOne(['item_name' => $data['item_name'], 'user_id' => $data['user_id']]);
            $this->arrJson['errCode'] = 222;
            if ($model) {
                // 删除数据成功
                if ($model->delete()) {
                    $this->handleJson($model);
                } else {
                    $this->arrJson['errMsg'] = Helper::arrayToString($model->getErrors());
                }
            }
        }

        return $this->returnJson();
    }
}
