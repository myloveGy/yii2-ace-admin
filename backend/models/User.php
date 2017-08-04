<?php

namespace backend\models;

use Yii;

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
class User extends \common\models\User
{
    /**
     * @var string 定义密码
     */
    public $password;

    /**
     * @var string 定义确认密码
     */
    public $repassword;

    /**
     * 获取状态说明信息
     * @param  int $intStatus 状态
     * @return array|string
     */
    public static function getArrayStatus($intStatus = null)
    {
        $array = [
            self::STATUS_ACTIVE => Yii::t('app', 'STATUS_ACTIVE'),
            self::STATUS_DELETED => Yii::t('app', 'STATUS_DELETED'),
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 获取状态值对应的颜色信息
     * @param  int $intStatus 状态值
     * @return array|string
     */
    public static function getStatusColor($intStatus = null)
    {
        $array = [
            self::STATUS_ACTIVE => 'label-success',
            self::STATUS_DELETED => 'label-danger',
        ];

        if ($intStatus !== null && isset($array[$intStatus])) {
            $array = $array[$intStatus];
        }

        return $array;
    }

    /**
     * 定义验证规则
     * @return array
     */
    public function rules()
    {
        return [
            [['username', 'email', 'status'], 'required'],
            [['password', 'repassword'], 'required', 'on' => ['user-create']],
            [['username', 'email', 'password', 'repassword'], 'trim'],
            [['password', 'repassword'], 'string', 'min' => 6, 'max' => 30],
            // Unique
            [['email', 'username'], 'unique'],
            // Username
            ['username', 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/'],
            ['username', 'string', 'min' => 3, 'max' => 30],
            // E-mail
            [['email'], 'string', 'max' => 100],
            ['email', 'email'],
            // Repassword
            ['repassword', 'compare', 'compareAttribute' => 'password'],
            //['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * 定义验证场景
     * @return array
     */
    public function scenarios()
    {
        return [
            'default' => ['username', 'email', 'password', 'repassword', 'status'],
            'create' => ['username', 'email', 'password', 'repassword', 'status'],
            'update' => ['username', 'email', 'password', 'repassword', 'status']
        ];
    }

    /**
     * 获取字段信息
     * @return array
     */
    public function attributeLabels()
    {
        $labels = parent::attributeLabels();

        return array_merge(
            $labels,
            [
                'password' => '密码',
                'repassword' => '确认密码',
            ]
        );
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
            if ($this->isNewRecord || (!$this->isNewRecord && $this->password)) {
                $this->setPassword($this->password);
                $this->generateAuthKey();
                $this->generatePasswordResetToken();
            }

            return true;
        }

        return false;
    }
}
