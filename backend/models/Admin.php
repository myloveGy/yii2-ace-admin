<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $face
 * @property string $auth_key
 * @property integer $role
 * @property integer $status
 * @property string $address
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $updated_id
 * @property integer $created_id
 * @property integer $last_time
 * @property string $last_ip
 * @property string $password write-only password
 */
class Admin extends \common\models\Admin
{
    public $password;
    public $repassword;
    private $_roleLabel;

    /**
     * @var integer 超级管理员ID
     */
    const SUPER_ADMIN_ID = 1;

    /**
     * getArrayStatus() 获取状态说明信息
     * @param integer|null $intStatus
     * @return array|string
     */
    public static function getArrayStatus($intStatus = null)
    {
        $array = [
            self::STATUS_ACTIVE => Yii::t('app', 'STATUS_ACTIVE'),
            self::STATUS_INACTIVE => Yii::t('app', 'STATUS_INACTIVE'),
            self::STATUS_DELETED => Yii::t('app', 'STATUS_DELETED'),
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * getStatusColor() 获取状态值对应颜色信息
     * @param null $intStatus
     * @return array|mixed
     */
    public static function getStatusColor($intStatus = null)
    {
        $array = [
            self::STATUS_ACTIVE => 'label-success',
            self::STATUS_INACTIVE => 'label-warning',
            self::STATUS_DELETED => 'label-danger',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 获取角色信息
     * @param bool $isDelete
     * @return array
     */
    public static function getArrayRole($isDelete = true)
    {
        $uid = Yii::$app->user->id;    // 用户ID
        $auth = Yii::$app->authManager; // 权限对象
        // 管理员
        $roles = $uid == self::SUPER_ADMIN_ID ? $auth->getRoles() : $auth->getRolesByUser($uid);
        if ($roles && $isDelete && isset($roles[Auth::SUPER_ADMIN_NAME])) {
            unset($roles[Auth::SUPER_ADMIN_NAME]);
        }

        return ArrayHelper::map($roles, 'name', 'description');
    }

    public function getRoleLabel()
    {
        if ($this->_roleLabel === null) {
            $roles = self::getArrayRole();
            $this->_roleLabel = $roles[$this->role];
        }
        return $this->_roleLabel;
    }

    // 验证规则
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['password', 'repassword', 'role'], 'required', 'on' => ['create']],
            [['username', 'email', 'password', 'repassword'], 'trim'],
            [['password', 'repassword'], 'string', 'min' => 6, 'max' => 30],
            // Unique
            [['username', 'email'], 'unique'],
            // Username
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/'],
            ['username', 'string', 'min' => 3, 'max' => 30],
            // E-mail
            [['email'], 'string', 'max' => 64],
            [['face', 'address'], 'string', 'max' => 100],
            ['email', 'email'],
            [['age', 'sex'], 'integer'],
            // Repassword
            ['repassword', 'compare', 'compareAttribute' => 'password'],
            //['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            // Status
            ['role', 'in', 'range' => array_keys(self::getArrayRole(false))],
        ];
    }

    // 验证场景
    public function scenarios()
    {
        return [
            'default' => ['username', 'email', 'password', 'repassword', 'status', 'role', 'face'],
            'create' => ['username', 'email', 'password', 'repassword', 'status', 'role', 'face'],
            'update' => ['username', 'email', 'password', 'repassword', 'status', 'role', 'face', 'address']
        ];
    }

    // 字段信息
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        return array_merge(
            $labels,
            [
                'face' => '头像信息',
                'last_time' => '上一次登录时间',
                'last__ip' => '上一次登录的IP',
                'password' => '密码',
                'repassword' => '确认密码',
            ]
        );
    }

    /**
     * 新增之前的处理
     * @param  bool $insert 是否是新增数据
     * @return bool 处理是否成功
     */
    public function beforeSave($insert)
    {
        // 新增记录和修改了密码
        if ($this->isNewRecord || (!$this->isNewRecord && $this->password)) {
            $this->setPassword($this->password);
            $this->generateAuthKey();
            $this->generatePasswordResetToken();
        }

        return parent::beforeSave($insert);
    }

    /**
     * 修改之后的处理
     * @param bool $insert 是否是新增数据
     * @param array $changedAttributes 修改的字段
     */
    public function afterSave($insert, $changedAttributes)
    {
        // 只有在新增或者修改了角色信息，那么才要修改角色信息
        if ($insert || !empty($changedAttributes['role'])) {
            $auth = Yii::$app->authManager;
            $isInsert = true;
            // 修改了角色信息，删除之前的角色信息
            if (!empty($changedAttributes['role'])) {
                // 不删除超级管理员的角色
                if ($this->id != Admin::SUPER_ADMIN_ID) {
                    $auth->revoke($auth->getRole($changedAttributes['role']), $this->id);
                }

                // 没有存在这个角色才新增
                if (in_array($this->id, $auth->getUserIdsByRole($this->role))) {
                    $isInsert = false;
                }
            }

            // 添加角色
            if ($isInsert) {
                $auth->assign($auth->getRole($this->role), $this->id);
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * 删除之前的处理-验证不能删除超级管理员和自己
     */
    public function beforeDelete()
    {
        if ($this->id == self::SUPER_ADMIN_ID) {
            $this->addError('username', '不能删除超级管理员');
            return false;
        }

        if ($this->id == Yii::$app->user->id) {
            $this->addError('username', '不能删除自己');
            return false;
        }

        return parent::beforeDelete();
    }

    /**
     * 删除之后的处理删除缓存
     */
    public function afterDelete()
    {
        // 移出权限信息
        Yii::$app->authManager->revokeAll($this->id);
        parent::afterDelete(); // TODO: Change the autogenerated stub
    }
}
