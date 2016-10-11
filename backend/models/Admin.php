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
 * @property string $auth_key
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $updated_id
 * @property integer $created_id
 * @property string $password write-only password
 */
class Admin extends \common\models\Admin
{
    public $password;
    public $repassword;
    private $_roleLabel;

    /**
     * getArrayStatus() 获取状态说明信息
     * @return array|string
     */
    public static function getArrayStatus($intStatus = null)
    {
        $array = [
            self::STATUS_ACTIVE   => Yii::t('app', 'STATUS_ACTIVE'),
            self::STATUS_INACTIVE => Yii::t('app', 'STATUS_INACTIVE'),
            self::STATUS_DELETED  => Yii::t('app', 'STATUS_DELETED'),
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
            self::STATUS_ACTIVE   => 'label-success',
            self::STATUS_INACTIVE => 'label-warning',
            self::STATUS_DELETED  => 'label-danger',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    public static function getArrayRole()
    {
        $uid  = Yii::$app->user->id;    // 用户ID
        $auth = Yii::$app->authManager; // 权限对象
        // 管理员
        $roles = $uid == 1 ? $auth->getRoles() : $auth->getRolesByUser($uid);
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
            [['password', 'repassword', 'role'], 'required', 'on' => ['admin-create']],
            [['username', 'email', 'password', 'repassword'], 'trim'],
            [['password', 'repassword'], 'string', 'min' => 6, 'max' => 30],
            // Unique
            [['username', 'email'], 'unique'],
            [['home_url', 'facebook'], 'string', 'min' => 2, 'max' => 50],
            ['home_url', 'url'],
            ['birthday', 'string', 'min' => 2, 'max' => 20],
            // Username
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/'],
            ['username', 'string', 'min' => 3, 'max' => 30],
            // E-mail
            [['email', 'face'], 'string', 'max' => 100],
            ['email', 'email'],
            [['age', 'sex'], 'integer'],
            // Repassword
            ['repassword', 'compare', 'compareAttribute' => 'password'],
            //['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
            // Status
            ['role', 'in', 'range' => array_keys(self::getArrayRole())],
        ];
    }

    // 验证场景
    public function scenarios()
    {
        return [
            'default'      => ['username', 'email', 'password', 'repassword', 'status', 'role'],
            'admin-create' => ['username', 'email', 'password', 'repassword', 'status', 'role'],
            'admin-update' => ['username', 'email', 'password', 'repassword', 'status', 'role', 'face', 'nickname', 'home_url', 'facebook', 'maxim', 'birthday', 'sex', 'age']
        ];
    }

    // 字段信息
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        return array_merge(
            $labels,
            [
                'face'       => '头像信息',
                'last_time'  => '上一次登录时间',
                'last__ip'   => '上一次登录的IP',
                'password'   => '密码',
                'repassword' => '确认密码',
                'home_url'   => '主页地址',
                'birthday'   => '生日',
                'facebook'   => 'FaceBook账号',
                'sex'        => '性别',
                'age'        => '年龄',
                'address'    => '地址',
                'maxim'      => '座右铭',
                'nickname'   => '真实姓名',
            ]
        );
    }

    /**
     * beforeValidate() 验证之前的处理
     * @return bool
     */
    public function beforeValidate()
    {
        // 存在请求数据
        $this->scenario = $this->id == null ? 'admin-create' : 'admin-update';
        return parent::beforeValidate();
    }

    /**
     * beforeSave() 新增之前的处理
     * @param  bool $insert 是否是新增数据
     * @return bool 处理是否成功
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // 新增记录和修改了密码
            if ($this->isNewRecord || (!$this->isNewRecord && $this->password))
            {
                $this->setPassword($this->password);
                $this->generateAuthKey();
                $this->generatePasswordResetToken();
            }

            // 登录修改验证权限
            $intUid = Yii::$app->user->id;
            if ( ! $insert && $intUid) {
                // 不是管理员验证权限
                if ($intUid !== 1) {
                    if ($this->id != $intUid && $this->created_id != $intUid) {
                        $this->addError('username', '你没有权限修改这个管理员信息');
                        return false;
                    }
                }
            }

            return true;
        }

        return false;
    }

    /**
     * afterSave() 修改之后的处理
     * @param bool  $insert             是否是新增数据
     * @param array $changedAttributes  修改的字段
     */
    public function afterSave($insert, $changedAttributes)
    {
        // 修改了角色信息才处理
        if (isset($changedAttributes['role'])) {
            // 不是新增需要删除之前的权限
            if ( ! $insert) {
                Yii::$app->authManager->revokeAll($this->id);
            }

            Yii::$app->authManager->assign(Yii::$app->authManager->getRole($this->role), $this->id);
        }

        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    /**
     * beforeDelete()删除之前的处理
     */
    public function beforeDelete()
    {
        if ($this->id == 1) {
            $this->addError('username', '不能删除超级管理员');
            return false;
        }

        return parent::beforeDelete();
    }

    /**
     * afterDelete() 删除之后的处理
     */
    public function afterDelete()
    {
        // 移出权限信息
        Yii::$app->authManager->revokeAll($this->id);
        parent::afterDelete(); // TODO: Change the autogenerated stub
    }
}
