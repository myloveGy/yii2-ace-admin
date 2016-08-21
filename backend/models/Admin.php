<?php
namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

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
 * @property string $password write-only password
 */
class Admin extends \common\models\Admin
{
    public $password;
    public $repassword;
    private $_statusLabel;
    private $_roleLabel;

    /**
     * @inheritdoc
     */
    public function getStatusLabel()
    {
        if ($this->_statusLabel === null) {
            $statuses = self::getArrayStatus();
            $this->_statusLabel = $statuses[$this->status];
        }
        return $this->_statusLabel;
    }

    /**
     * @inheritdoc
     */
    public static function getArrayStatus()
    {
        return [
            self::STATUS_ACTIVE   => Yii::t('app', 'STATUS_ACTIVE'),
            self::STATUS_INACTIVE => Yii::t('app', 'STATUS_INACTIVE'),
            self::STATUS_DELETED  => Yii::t('app', 'STATUS_DELETED'),
        ];
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

    // 修改之前
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            // 新增记录和修改了密码
            if ($this->isNewRecord || (!$this->isNewRecord && $this->password))
            {
                $this->setPassword($this->password);
                $this->generateAuthKey();
                $this->generatePasswordResetToken();
            }

            return true;
        }
        return false;
    }

    // 获取错误信息
    public function getErrorString()
    {
        $str    = '';
        $errors = $this->getErrors();
        if ( ! empty($errors))
        {
            foreach ($errors as $value)
            {
                if (is_array($value))
                    foreach ($value as $val) $str .= $val;
                else
                    $str .= $value;
            }
        }

        return $str;
    }
}
