<?php
/**
 * Created by JetBrains PhpStorm.
 * User: funson
 * Date: 14-9-9
 * Time: 下午4:54
 * To change this template use File | Settings | File Templates.
 */
namespace backend\controllers;

use Yii;
use backend\models\Auth;
use backend\models\Menu;
use yii\web\HttpException;

class RoleController extends Controller
{
    public $sort = 'created_at';        // 排序
    public $type = Auth::TYPE_ROLE;     // 类型

    /**
     * where() 设置查询参数
     * @param array $params
     * @return array
     */
    public function where($params)
    {
        $uid   = Yii::$app->user->id;
        $where = [['=', 'type', Auth::TYPE_ROLE]]; // 查询角色信息

        // 不是管理员
        if ($uid != 1) {
            $name = [];
            // 获取用户的所有角色
            $roles = Yii::$app->authManager->getRolesByUser($uid);
            if ($roles){ foreach ($roles as $key => $value) $name[] = $key;}
            if (!empty($name)) $where[] = ['in', 'name', $name];
        }

        return [
            'name'        => 'like',
            'description' => 'like',
            'where'       => $where,    // 查询角色信息
        ];
    }

    /**
     * getModel() 获取model
     * @return Auth
     */
    public function getModel()
    {
        $model = new Auth();
        $model->type = $this->type;
        return $model;
    }

    /**
     * actionCreate() 处理新增数据
     * @return mixed|string
     */
    public function actionCreate()
    {
        $array = Yii::$app->request->post();
        if ($array) {
            $model = $this->getModel();
            if ($model->load(['params' => $array], 'params')) {
                // 添加角色成功
                $permissions = $this->preparePermissions(Yii::$app->request->post());
                // 判断类型 (添加角色还是操作权限)
                $isTrue = $model->type == Auth::TYPE_ROLE ? $model->createRole($permissions) : $model->createPermission();
                $this->arrJson['errCode']  = $model->type == Auth::TYPE_ROLE ? 211 : 212;
                if ($isTrue) $this->handleJson($model);
            }
        }

        // 返回数据
        return $this->returnJson();
    }

    /**
     * actionUpdate() 编辑数据处理
     * @return mixed|string
     */
    public function actionUpdate()
    {
        $array = Yii::$app->request->post();                // 请求参数
        // 判断数据正确提交
        if ($array && isset($array['name'])) {
            $model = Auth::findOne($array['name']);
            $this->arrJson['errCode'] = 213;
            // 执行修改权限
            if ($model->load(['params' => $array], 'params')) {
                // 修改角色
                if ($model->type == Auth::TYPE_ROLE) {
                    $auth = Yii::$app->getAuthManager();
                    $role = $auth->getRole($model->name);
                    $role->description = $model->description;
                    $isTrue = $auth->update($model->name, $role);
                }
                else
                    $isTrue = $model->updatePermission($model->name);

                // 修改成功
                if ($isTrue) $this->handleJson($model);
            }
        }

        return $this->returnJson();
    }

    /**
     * actionDelete() 处理删除数据
     * @return mixed|string
     */
    public function actionDelete()
    {
        $array = Yii::$app->request->post();
        if ($array && isset($array['name'])) {
            $model = Auth::findOne($array['name']);
            if ($model) {
                // 判断操作数据类型
                if ($model->type == Auth::TYPE_ROLE) {
                    // 删除角色
                    $this->arrJson['errCode'] = 209;
                    if ( ! Auth::hasUsersByRole($model->name) && $model->name != Yii::$app->params['adminRoleName']) {
                        $auth = Yii::$app->getAuthManager();
                        $role = $auth->getRole($model->name);

                        // 请求这个角色的所有权限
                        $permissions = $auth->getPermissionsByRole($model->name);
                        foreach($permissions as $permission) {$auth->removeChild($role, $permission);}
                        $this->arrJson['errCode'] = 210;

                        // 删除角色成功
                        if($auth->remove($role)) $this->handleJson($model);
                    }

                } else {
                    // 删除权限
                    $this->arrJson['errCode'] = 214;
                    $auth = Yii::$app->getAuthManager();
                    $item = $auth->getPermission($model->name);
                    if ($item && $auth->remove($item)) $this->handleJson($model);
                }
            }
        }

        return $this->returnJson();
    }

    /**
     * actionEdit() 修改角色权限信息
     * @param  string $name 角色名
     * @return string|\yii\web\Response
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function actionEdit($name)
    {
        // 管理员直接返回
        if($name == Yii::$app->params['adminRoleName']) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'The Administrator has all permissions'));
            return $this->redirect(['view', 'name' => $name]);
        }

        // 判断自己是否有这个权限
        $uid      = Yii::$app->user->id;                     // 用户ID
        $objAuth  = Yii::$app->getAuthManager();             // 权限对象
        $mixRoles = $objAuth->getAssignment($name, $uid);    // 获取用户是否有改权限
        if ( ! $mixRoles && $uid != 1) throw new \yii\web\UnauthorizedHttpException('对不起，您没有修改该角色的权限!');

        // 添加权限
        $request = Yii::$app->request;       // 请求信息
        $model   = $this->findModel($name);  // 查询对象
        $array   = $request->post();         // 请求参数信息
        if ($request->post() && $model->load(['params' => $array], 'params')) {
            // 修改权限
            $permissions = $this->preparePermissions($array);
            if ($model->updateRole($name, $permissions)) {
                Yii::$app->session->setFlash('success', " '$model->name' " . Yii::t('app', 'successfully updated'));
                return $this->redirect(['view', 'name' => $name]);
            }
        }

        // 显示视图
        $permissions = $this->getPermissions();
        $model->loadRolePermissions($name);
        $menus = Menu::findAll(['status' => 1]);
        $trees = [];
        // 获取权限信息
        $arrHaves = array_keys($objAuth->getPermissionsByRole($name));
        if ($menus) {
            // 获取一级目录
            foreach ($menus as $value) {
                // 初始化的判断数据
                $id    = $value->pid == 0 ? $value->id : $value->pid;
                $array = ['name' => $value->menu_name, 'id' => $value->id, 'type' => 'item', 'data' => $value->url];

                // 默认选中
                $array['additionalParameters'] = ['item-selected' => in_array($value->url, $arrHaves)];
                if ( ! isset($trees[$id])) $trees[$id] = ['child' => []];

                // 判断添加数据
                if ($value->pid == 0) {
                    $trees[$id] = array_merge($trees[$id], $array);
                } else {
                    $trees[$id]['child'][] = $array;
                    $trees[$id]['type']  = 'folder';
                }
            }
        }

        // 加载视图返回
        return $this->render('edit', [
            'model'       => $model,        // 模型对象
            'permissions' => $permissions,  // 权限信息
            'trees'       => $trees,        // 导航栏树
        ]);

    }

    /**
     * actionView() 查看角色权限信息
     * @param  string $name 角色名称
     * @return string
     */
    public function actionView($name)
    {
        // 查询角色信息
        $model = $this->findModel($name);
        $model->loadRolePermissions($name);
        $permissions = $this->getPermissions();

        // 查询导航栏信息
        $menus = $parent = [];
        $child = Menu::find()->where(['url' => array_keys($permissions)])->all();
        if ($child) {
            // 处理数据
            foreach ($child as $key => $value) {
                if ($value->pid == 0) {
                    $menus[$value->id]  = ['name' => $value->menu_name, 'child' => []];
                    unset($child[$key]);
                } else {
                    $parent[] = $value->pid;
                }
            }

            // 查询父类数据
            $parents = Menu::find()->where(['id' => $parent, 'pid' => 0])->all();
            if ($parents) foreach ($parents as $value) $menus[$value->id] = ['name' => $value->menu_name, 'child' => []];

            // 最后处理数据
            foreach ($child as $value) { if (isset($menus[$value->pid])){$menus[$value->pid]['child'][] = ['name' => $value->menu_name];}}
        }


        return $this->render('view', [
            'menus'       => $menus,
            'model'       => $model,
            'permissions' => $permissions,
        ]);
    }

    /**
     * findModel() 查询单个model
     * @param array $name
     * @return Auth
     * @throws HttpException
     */
    protected function findModel($name)
    {
        if ($name) {
            $auth  = Yii::$app->getAuthManager();
            $model = new Auth();
            $role  = $auth->getRole($name);
            if ($role) {
                $model->name        = $role->name;
                $model->description = $role->description;
                $model->created_at  = $role->createdAt;
                $model->updated_at  = $role->updatedAt;
                $model->setIsNewRecord(false);
                return $model;
            }
        }

        throw new HttpException(404);
    }

    /**
     * getPermissions() 获取用户对应的权限信息
     * @return array
     */
    protected function getPermissions()
    {
        $uid    = Yii::$app->user->id;
        $models = $uid == 1 ? Auth::find()->where(['type' => Auth::TYPE_PERMISSION])->all() : Yii::$app->getAuthManager()->getPermissionsByUser($uid);
        $permissions = [];
        foreach($models as $model) $permissions[$model->name] = $model->name . ' (' . $model->description . ')';
        return $permissions;
    }

    /**
     * preparePermissions() 加载权限信息
     * @param  array $post 提交参数
     * @return array
     */
    protected function preparePermissions($post) {
        return (isset($post['Auth']['_permissions']) &&
            is_array($post['Auth']['_permissions'])) ? $post['Auth']['_permissions'] : [];
    }
}
