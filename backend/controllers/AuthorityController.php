<?php
namespace backend\controllers;

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
