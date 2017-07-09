<?php
namespace backend\controllers;

use backend\models\AuthRule;
use yii;
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
     * 权限页面显示操作
     * @return string
     */
    public function actionIndex()
    {
        // 查询出全部的规则
        $rules = AuthRule::find()->all();
        $arrRules = ['' => '请选择'];
        if ($rules) {
            foreach ($rules as &$value) {
                if ($value->data) {
                    $tmp = unserialize($value->data);
                    if ($tmp) {
                        $value->data = get_class($tmp);
                    }
                }

                $arrRules[$value->name] = $value->name.' - '.$value->data;
            }
        }

        // 载入试图
        return $this->render('index', [
            'type' => Auth::TYPE_PERMISSION, // 权限类型
            'rules' => yii\helpers\Json::encode($arrRules) // 所有规则
        ]);
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

    /**
     * 一次删除多个权限
     * @return mixed|string
     */
    public function actionDeleteAll()
    {
        // 第一步接收参数
        $ids = Yii::$app->request->post('ids');
        if ($ids) {
            // 处理为数组
            $array = explode(',', $ids);
            if ($array) {
                $this->arrJson['errCode'] = 214;
                // 查询数据存在
                $models = Auth::findAll($array);
                if ($models) {
                    // 执行删除权限
                    $auth = Yii::$app->getAuthManager();
                    foreach ($models as $model) {
                        $item = $auth->getPermission($model->name);
                        if ($item) $auth->remove($item);
                    }
                }

                $this->handleJson($model);
            }
        }

        return $this->returnJson();
    }
}
