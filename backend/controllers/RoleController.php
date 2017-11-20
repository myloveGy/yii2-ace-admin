<?php

namespace backend\controllers;

use backend\models\Admin;
use common\helpers\Helper;
use Yii;
use backend\models\Auth;
use backend\models\Menu;
use yii\web\HttpException;
use \yii\web\UnauthorizedHttpException;

/**
 * Class RoleController 角色管理类
 * @package backend\controllers
 */
class RoleController extends Controller
{
    /**
     * @var string 定义使用的主键
     */
    public $pk = 'name';

    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'backend\models\Auth';

    /**
     * @var string 定义排序字段
     */
    public $sort = 'created_at';

    /**
     * 设置查询参数
     * @param array $params
     * @return array
     */
    public function where($params)
    {
        $uid = Yii::$app->user->id;
        $where = [['=', 'type', Auth::TYPE_ROLE]]; // 查询角色信息

        // 不是管理员
        if ($uid != Admin::SUPER_ADMIN_ID) {
            // 获取用户的所有角色
            $roles = Yii::$app->authManager->getRolesByUser($uid);
            if ($roles) {
                $where[] = ['in', 'name', array_keys($roles)];
            }
        }

        return [
            'name' => 'like',
            'description' => 'like',
            'where' => $where,    // 查询角色信息
        ];
    }

    /**
     * 角色信息显示首页
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'type' => Auth::TYPE_ROLE
        ]);
    }

    /**
     * 修改角色权限信息
     * @param  string $name 角色名
     * @return string|\yii\web\Response
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function actionEdit($name)
    {
        // 管理员直接返回
        if ($name === Auth::SUPER_ADMIN_NAME) {
            Yii::$app->session->setFlash(
                'warning',
                Yii::t('app', 'You can not modify the super administrator privileges')
            );
            return $this->redirect(['view', 'name' => $name]);
        }

        // 判断自己是否有这个权限
        $uid = Yii::$app->user->id;                     // 用户ID
        $objAuth = Yii::$app->getAuthManager();             // 权限对象
        $mixRoles = $objAuth->getAssignment($name, $uid);    // 获取用户是否有改权限
        if (!$mixRoles && $uid != Admin::SUPER_ADMIN_ID) {
            throw new UnauthorizedHttpException('对不起，您没有修改该角色的权限!');
        }

        // 添加权限
        $request = Yii::$app->request;       // 请求信息

        /* @var $model \backend\models\Auth */
        $model = $this->findModel($name);  // 查询对象
        $array = $request->post();         // 请求参数信息
        if ($array && $model->load($array, '')) {
            // 修改权限
            $permissions = $this->preparePermissions($array);
            if ($model->updateRole($name, $permissions)) {
                Yii::$app->session->setFlash(
                    'success',
                    " '$model->name' " . Yii::t('app', 'successfully updated')
                );
                return $this->redirect(['view', 'name' => $name]);
            } else {
                Yii::$app->session->setFlash('error', Helper::arrayToString($model->getErrors()));
            }
        }

        // 自己的权限
        $permissions = $this->getPermissions();

        // 权限对应的导航栏目
        $menus = Menu::getMenusByPermissions($permissions);

        $model->loadRolePermissions($name);

        $arrHaves = $model->_permissions;

        $trees = [];
        if ($menus) {
            // 获取一级目录
            foreach ($menus as $value) {
                // 初始化的判断数据
                $id = $value['pid'] == 0 ? $value['id'] : $value['pid'];
                $array = [
                    'text' => $value['menu_name'],
                    'id' => $value['id'],
                    'data' => $value['url'],
                    'state' => [],
                ];

                // 默认选中
                $array['state']['selected'] = in_array($value['url'], $arrHaves);
                if (!isset($trees[$id])) {
                    $trees[$id] = ['children' => []];
                }

                // 判断添加数据
                if ($value['pid'] == 0) {
                    $array['icon'] = 'menu-icon fa fa-list orange';
                    $trees[$id] = array_merge($trees[$id], $array);
                } else {
                    $array['icon'] = false;
                    $trees[$id]['children'][] = $array;
                }
            }
        }

        // 导航信息
        $trees = array_values($trees);

        // 加载视图返回
        return $this->render('edit', [
            'model' => $model,        // 模型对象
            'permissions' => $permissions,  // 权限信息
            'trees' => $trees,        // 导航栏树,
        ]);
    }

    /**
     * 查看角色权限信息
     * @param  string $name 角色名称
     * @return string
     */
    public function actionView($name)
    {
        // 查询角色信息
        /* @var $model \backend\models\Auth */
        $model = $this->findModel($name);

        // 获取角色权限信息
        $permissions = Yii::$app->authManager->getPermissionsByRole($name);

        // 查询导航栏信息
        $menus = [];
        $child = Menu::getMenusByPermissions($permissions);
        if ($child) {
            foreach ($child as $value) {
                $key = $value['pid'] == 0 ? $value['id'] : $value['pid'];
                if (!isset($menus[$key])) {
                    $menus[$key] = ['child' => []];
                }

                if ($value['pid'] == 0) {
                    $menus[$key]['name'] = $value['menu_name'];
                } else {
                    $menus[$key]['child'][] = ['name' => $value['menu_name']];
                }
            }
        }

        return $this->render('view', [
            'menus' => $menus,
            'model' => $model,
            'permissions' => $permissions,
        ]);
    }

    /**
     * 查询单个model
     * @param  string $name
     * @return \backend\models\Auth
     * @throws \yii\web\HttpException
     */
    protected function findModel($name)
    {
        if ($name) {
            $auth = Yii::$app->getAuthManager();
            $model = new Auth();
            $role = $auth->getRole($name);
            if ($role) {
                $model->name = $role->name;
                $model->type = $role->type;
                $model->description = $role->description;
                $model->created_at = $role->createdAt;
                $model->updated_at = $role->updatedAt;
                $model->setIsNewRecord(false);
                return $model;
            }
        }

        throw new HttpException(404);
    }

    /**
     * 获取用户对应的权限信息
     * @return array
     */
    protected function getPermissions()
    {
        $uid = Yii::$app->user->id;
        $models = $uid == 1 ? Auth::find()->where([
            'type' => Auth::TYPE_PERMISSION
        ])->orderBy(['name' => SORT_ASC])->all() : Yii::$app->getAuthManager()->getPermissionsByUser($uid);
        $permissions = [];
        foreach ($models as $model) {
            $permissions[$model->name] = $model->name . ' (' . $model->description . ')';
        }

        return $permissions;
    }

    /**
     * 加载权限信息
     * @param  array $post 提交参数
     * @return array
     */
    protected function preparePermissions($post)
    {
        return (isset($post['Auth']['_permissions']) &&
            is_array($post['Auth']['_permissions'])) ? $post['Auth']['_permissions'] : [];
    }

    /**
     * 处理导出数据显示的问题
     *
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
