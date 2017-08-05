<?php

namespace backend\rules;

use backend\models\Admin;
use yii;
use yii\rbac\Rule;

/**
 * Class AdminRule 管理员的删除的权限控制
 * 不能删除超级管理员和自己的信息
 * @package backend\rules
 */
class AdminDeleteRule extends Rule
{
    /**
     * @var string 定义名称
     */
    public $name = 'admin-delete';

    /**
     * 执行验证
     * @param int|string $user
     * @param \yii\rbac\Item $item
     * @param array $params
     * @return bool
     */
    public function execute($user, $item, $params)
    {
        $isReturn = true;
        // 先使用传递的值，再使用请求的值
        $id = intval(empty($params['id']) ? Yii::$app->request->post('id') : $params['id']);
        // 不能删除自己和超级管理员
        if ($id === Admin::SUPER_ADMIN_ID || $id == $user) {
            $isReturn = false;
        } else {
            // 不是超级管理员添加验证
            if ($user !== Admin::SUPER_ADMIN_ID) {
                // 查询数据，先验证自己的修改自己或者修改自己添加的
                $admin = Admin::find()->where(['id' => $id, 'created_id' => $user])->one();
                $isReturn = $admin ? true : false;
            }
        }

        return $isReturn;
    }
}