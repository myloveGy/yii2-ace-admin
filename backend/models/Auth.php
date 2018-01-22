<?php

namespace backend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $rule_name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $menus
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthRule $ruleName
 * @property AuthItemChild[] $authItemChildren
 */
class Auth extends ActiveRecord
{
    /**
     * @var integer 角色
     */
    const TYPE_ROLE = 1;

    /**
     * @var integer 权限
     */
    const TYPE_PERMISSION = 2;

    /**
     * @var string 定义超级管理员角色
     */
    const SUPER_ADMIN_NAME = 'administrator';

    /**
     * @var array 权限信息
     */
    public $_permissions = [];

    /**
     * @var string 定义名称
     */
    public $newName;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type', 'newName', 'description'], 'required'],
            [['name', 'newName'], 'match', 'pattern' => '/^([a-zA-Z0-9_-]|([a-zA-z0-9_-]\\/[0-9_-a-zA-z]))+$/'],
            ['name', 'string', 'min' => 3],
            ['type', 'integer'],
            ['type', 'in', 'range' => [self::TYPE_PERMISSION, self::TYPE_ROLE]],
            [['name', 'newName'], 'unique', 'targetAttribute' => 'name'],
            ['name', 'validatePermission'],
            [['rule_name', 'name', 'newName'], 'string', 'max' => 64],
            ['description', 'string', 'min' => 1, 'max' => 400],
        ];
    }

    /**
     * 定义验证场景需要验证的字段
     * @return array
     */
    public function scenarios()
    {
        return [
            'default' => ['name', 'data', 'type', 'rule_name', 'description'],
            'create' => ['newName', 'data', 'type', 'rule_name', 'description'],
            'update' => ['name', 'newName', 'data', 'type', 'rule_name', 'description']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'description' => '说明',
            'rule_name' => '规则名称',
            'data' => '数据',
            'newName' => '名称',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    public function validatePermission()
    {
        if (!$this->hasErrors()) {
            $auth = Yii::$app->getAuthManager();
            if ($this->isNewRecord && $auth->getPermission($this->newName)) {
                $this->addError('name', Yii::t('auth', 'This name already exists.'));
            }
            if ($this->isNewRecord && $auth->getRole($this->newName)) {
                $this->addError('name', Yii::t('auth', 'This name already exists.'));
            }
        }
    }

    /**
     * 修改数据
     * @param bool $runValidation 是否严重
     * @param null $attributeNames 修改字段
     * @return bool
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if ($this->validate()) {
            $this->type = (int)$this->type;
            $auth = Yii::$app->getAuthManager();
            // 判断是否新增
            if ($this->isNewRecord) {
                if ($this->type === self::TYPE_ROLE) {
                    // 角色
                    $item = $auth->createRole($this->newName);
                } else {
                    // 权限
                    $item = $auth->createPermission($this->newName);
                    if ($this->rule_name) {
                        $item->ruleName = $this->rule_name;
                    }
                }

                $item->description = $this->description;
                if ($this->data) {
                    $item->data = $this->data;
                }

                // 添加数据
                $auth->add($item);
                if ($this->type === self::TYPE_PERMISSION) {
                    // 添加权限的话，要给管理员加上
                    $admin = $auth->getRole(Yii::$app->params['adminRoleName']);
                    if ($admin) {
                        $auth->addChild($admin, $item);
                    }
                } else {
                    // 将角色添加给用户
                    $uid = (int)Yii::$app->user->id;
                    if ($uid !== Admin::SUPER_ADMIN_ID) {
                        $auth->assign($item, $uid);
                    }
                }
            } else {
                if ($this->type === self::TYPE_ROLE) {
                    // 角色
                    $item = $auth->getRole($this->name);
                } else {
                    // 权限
                    $item = $auth->getPermission($this->name);
                    if ($this->rule_name) {
                        $item->ruleName = $this->rule_name;
                    }
                }

                $item->name = $this->newName;
                $item->description = $this->description;
                if ($this->data) {
                    $item->data = $this->data;
                }

                return $auth->update($this->name, $item);
            }

            return true;
        }

        return false;
    }

    /**
     * 删除
     * @return bool
     */
    public function delete()
    {
        $auth = Yii::$app->getAuthManager();
        $this->type = (int)$this->type;

        // 权限
        if ($this->type === self::TYPE_PERMISSION) {
            $item = $auth->getPermission($this->name);
            return $item ? $auth->remove($item) : false;
        }

        // 角色
        if (Auth::hasUsersByRole($this->name) || $this->name == Yii::$app->params['adminRoleName']) {
            $this->addError('name', '角色还在使用');
            return false;
        }

        // 清除这个角色的所有权限
        $role = $auth->getRole($this->name);
        $permissions = $auth->getPermissionsByRole($this->name);
        foreach ($permissions as $permission) {
            $auth->removeChild($role, $permission);
        }

        // 删除角色成功
        return $auth->remove($role);
    }

    /**
     * 删除多个数据
     * @param null $condition
     * @param array $params
     * @return bool
     */
    public static function deleteAll($condition = null, $params = [])
    {
        $all = self::findAll($condition);
        if ($all) {
            foreach ($all as $value) {
                $value->delete();
            }

            return true;
        }

        return false;
    }

    /**
     * 修改角色
     * @param string $name
     * @param $permissions
     * @return bool
     * @throws \Exception
     * @throws \yii\base\Exception
     */
    public function updateRole($name, $permissions)
    {
        if ($this->validate()) {
            $auth = Yii::$app->getAuthManager();
            $role = $auth->getRole($name);
            $role->description = $this->description;
            // save role
            if ($auth->update($name, $role)) {
                // remove old permissions
                $oldPermissions = $auth->getPermissionsByRole($name);
                foreach ($oldPermissions as $permission) {
                    $auth->removeChild($role, $permission);
                }

                // add new permissions
                foreach ($permissions as $permission) {
                    $obj = $auth->getPermission($permission);
                    $auth->addChild($role, $obj);
                }
                return true;
            }
        }

        return false;
    }

    public function loadRolePermissions($name)
    {
        $models = Yii::$app->authManager->getPermissionsByRole($name);
        foreach ($models as $model) {
            $this->_permissions[] = $model->name;
        }
    }

    public static function hasUsersByRole($name)
    {
        $tablePrefix = Yii::$app->getDb()->tablePrefix;
        return Auth::find()
            ->where(['name' => $name])
            ->InnerJoin("{$tablePrefix}auth_assignment", ['item_name' => $name])
            ->count();
    }

    public static function hasRolesByPermission($name)
    {
        $tablePrefix = Yii::$app->getDb()->tablePrefix;
        return Auth::find()
            ->where(['name' => $name])
            ->InnerJoin("{$tablePrefix}auth_item_child", ['child' => $name])
            ->count();
    }

    /**
     * 获取dataTable 表格需要的权限
     * @param string $controller 权限对应的控制器名称
     * @param string $join 链接字符串
     * @return array
     */
    public static function getDataTableAuth($controller, $join = '/')
    {
        $controller .= $join;
        $arrReturn = [
            'buttons' => [
                'create' => [
                    'bShow' => Yii::$app->user->can($controller . 'create')
                ],

                'deleteAll' => [
                    'bShow' => Yii::$app->user->can($controller . 'delete-all'),
                ],

                'export' => [
                    'bShow' => Yii::$app->user->can($controller . 'export')
                ]
            ],
            'operations' => [
                'delete' => [
                    'bShow' => Yii::$app->user->can($controller . 'delete')
                ]
            ],
        ];

        // 修改
        if (Yii::$app->user->can($controller . 'update')) {
            $arrReturn['buttons']['updateAll'] = $arrReturn['operations']['update'] = ['bShow' => true];
        } else {
            $arrReturn['buttons']['updateAll'] = $arrReturn['operations']['update'] = ['bShow' => false];
        }

        return $arrReturn;
    }
}
