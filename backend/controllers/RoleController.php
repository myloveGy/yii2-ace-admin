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
    // 搜索配置信息
    public function where($params)
    {
        $uid   = Yii::$app->user->id;
        $where = [['=', 'type', Auth::TYPE_ROLE]]; // 查询角色信息

        // 不是管理员
        if ($uid != 1)
        {
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

    public function getModel() {return new Auth();}

    // 权限管理
    public function actionUpdate()
    {
        // 获取请求参数
        $request = Yii::$app->request;
        $action  = $request->post('actionType');    // 类型
        $array   = $request->post();                // 请求参数
        $type    = (int)$request->get('type');      // 操作类型 1 角色 2 权限

        // 判断数据正确提交
        if (! empty($action) && ! empty($array))
        {
            $this->arrError['code'] = 216;
            if ($type == Auth::TYPE_ROLE || Yii::$app->user->can('authority/update'))
            {
                // 判断类型
                switch ($action)
                {
                    // 添加角色
                    case 'insert':
                        $model = new Auth();
                        if ($model->load(['params' => $array], 'params'))
                        {
                            // 添加角色成功
                            $permissions = $this->preparePermissions(Yii::$app->request->post());
                            // 判断类型 (添加角色还是操作权限)
                            $isTrue = $type == Auth::TYPE_ROLE ? $model->createRole($permissions) : $model->createPermission();
                            $this->arrError['code']  = $type == Auth::TYPE_ROLE ? 211 : 212;
                            if ($isTrue) $this->arrError['code'] = 0;
                        }
                        break;
                    // 删除角色和权限
                    case 'delete':
                        // 判断是否有权限进行该操作
                        if ($type == Auth::TYPE_ROLE)
                        {
                            $this->arrError['code'] = 208;
                            if ( Yii::$app->user->can('deleteRole'))
                            {
                                $name = $array['name'];
                                $this->arrError['code'] = 209;
                                if ( ! Auth::hasUsersByRole($name))
                                {
                                    $auth = Yii::$app->getAuthManager();
                                    $role = $auth->getRole($name);
                                    // clear asset permissions
                                    $permissions = $auth->getPermissionsByRole($name);
                                    foreach($permissions as $permission) {$auth->removeChild($role, $permission);}
                                    $this->arrError['code'] = 210;

                                    // 删除角色成功
                                    if($auth->remove($role)) $this->arrError['code'] = 0;
                                }
                            }

                            // 删除权限
                        } else {
                            $this->arrError['code'] = 214;
                            if (Yii::$app->user->can('deleteAuthority'))
                            {
                                $auth = Yii::$app->getAuthManager();
                                $item = $auth->getPermission($array['name']);
                                $this->arrError['code'] = 214;
                                if ($item){if ($auth->remove($item)) $this->arrError['code'] = 0;}
                            }
                        }

                        break;
                    // 修改权限
                    case 'update':
                        $name = $array['name'];
                        if ($name)
                        {
                            $model = Auth::findOne(['name' => $name]);
                            $this->arrError['code'] = 213;
                            // 执行修改权限
                            if ($model->load(['params' => $array], 'params'))
                            {
                                // 修改角色
                                if ($type == Auth::TYPE_ROLE)
                                {
                                    $auth = Yii::$app->getAuthManager();
                                    $role = $auth->getRole($name);
                                    $role->description = $model->description;
                                    $isTrue = $auth->update($name, $role);
                                }
                                else
                                    $isTrue = $model->updatePermission($name);

                                // 修改成功
                                if ($isTrue) $this->arrError['code'] = 0;
                            }
                        }
                        break;
                }
            }

        }

        return $this->returnAjax();
    }

    // 修改用户的权限
    public function actionCreate($name)
    {
        // 管理员直接返回
        if($name == 'admin')
        {
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
        if ($request->post() && $model->load(['params' => $array], 'params'))
        {
            // 修改权限
            $permissions = $this->preparePermissions($array);
            if ($model->updateRole($name, $permissions))
            {
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
        if ($menus)
        {
            // 获取一级目录
            foreach ($menus as $value)
            {
                // 初始化的判断数据
                $id    = $value->pid == 0 ? $value->id : $value->pid;
                $array = ['name' => $value->menu_name, 'id' => $value->id, 'type' => 'item', 'data' => $value->url];

                // 默认选中
                $array['additionalParameters'] = ['item-selected' => in_array($value->url, $arrHaves)];
                if ( ! isset($trees[$id])) $trees[$id] = ['child' => []];

                // 判断添加数据
                if ($value->pid == 0)
                    $trees[$id] = array_merge($trees[$id], $array);
                else
                {
                    $trees[$id]['child'][] = $array;
                    $trees[$id]['type']  = 'folder';
                }
            }
        }

        // 加载视图返回
        return $this->render('update', [
            'model'       => $model,        // 模型对象
            'permissions' => $permissions,  // 权限信息
            'trees'       => $trees,        // 导航栏树
        ]);

    }

    // 视图
    public function actionView($name)
    {
        // 查询角色信息
        $model = $this->findModel($name);
        $model->loadRolePermissions($name);
        $permissions = $this->getPermissions();

        // 查询导航栏信息
        $menus = $parent = [];
        $child = Menu::find()->where(['url' => array_keys($permissions)])->all();
        if ($child)
        {
            // 处理数据
            foreach ($child as $key => $value)
            {
                if ($value->pid == 0)
                {
                    $menus[$value->id]  = ['name' => $value->menu_name, 'child' => []];
                    unset($child[$key]);
                }
                else
                    $parent[] = $value->pid;
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

    protected function findModel($name)
    {
        if ($name)
        {
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

    // 获取用户对应的权限信息
    protected function getPermissions()
    {
        $uid    = Yii::$app->user->id;
        $models = $uid == 1 ? Auth::find()->where(['type' => Auth::TYPE_PERMISSION])->all() : Yii::$app->getAuthManager()->getPermissionsByUser($uid);
        $permissions = [];
        foreach($models as $model) $permissions[$model->name] = $model->name . ' (' . $model->description . ')';
        return $permissions;
    }

    // 加载权限信息
    protected function preparePermissions($post) {
        return (isset($post['Auth']['_permissions']) &&
            is_array($post['Auth']['_permissions'])) ? $post['Auth']['_permissions'] : [];
    }
}
