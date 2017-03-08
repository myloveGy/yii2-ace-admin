<?php

namespace backend\models;

use common\models\AdminModel;

/**
 * This is the model class for table "{{%arrange}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $desc
 * @property integer $start_at
 * @property integer $status
 * @property integer $time_status
 * @property integer $end_at
 * @property integer $created_at
 * @property integer $created_id
 * @property integer $updated_at
 * @property integer $updated_id
 */
class Arrange extends AdminModel
{
    // 状态信息
    const STATUS_PENDING  = 0;  // 待处理
    const STATUS_DELEGATE = 1;  // 委派处理
    const STATUS_COMPLETE = 2;  // 处理完成
    const STATUS_DEFER    = 3;  // 延期处理

    // 时间状态
    const TIME_STATUS_SLOW   = 0; // 缓慢
    const TIME_STATUS_NORMAL = 1; // 正常
    const TIME_STATUS_URGENT = 2; // 紧急

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%arrange}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'desc', 'status', 'time_status', 'start_at', 'end_at'], 'required'],
            [['start_at', 'status', 'time_status', 'end_at', 'admin_id', 'created_at', 'created_id', 'updated_at', 'updated_id'], 'integer'],
            [['title'], 'string', 'max' => 100],
            [['desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'id ',
            'title'      => '事件标题',
            'desc'       => '事件描述',
            'status'     => '事件状态',
            'time_status'=> '时间状态',
            'admin_id'   => '处理人',
            'start_at'   => '开始时间',
            'end_at'     => '结束时间',
            'created_at' => '创建时间',
            'created_id' => '添加用户',
            'updated_at' => '修改时间',
            'updated_id' => '修改用户',
        ];
    }

    // 验证之前的处理
    public function beforeValidate()
    {
        if (! empty($this->start_at) && strpos($this->start_at, '-')) $this->start_at = strtotime($this->start_at);
        if (! empty($this->end_at) && strpos($this->end_at, '-')) $this->end_at = strtotime($this->end_at);
        return parent::beforeValidate();
    }

    /**
     * getStatus() 获取状态信息
     * @param null $intStatus 状态值
     * @return array|mixed
     */
    public static function getStatus($intStatus = null)
    {
        $arrReturn = [
            self::STATUS_DELEGATE => '委派处理',
            self::STATUS_PENDING  => '待处理',
            self::STATUS_COMPLETE => '处理完成',
            self::STATUS_DEFER    => '延期处理'
        ];

        if ($intStatus != null && isset($arrReturn[$intStatus])) {
            $arrReturn = $arrReturn[$intStatus];
        }

        return $arrReturn;
    }

    /**
     * getTimeStatus() 获取时间状态信息
     * @param null $intStatus 状态值
     * @return array|mixed
     */
    public static function getTimeStatus($intStatus = null)
    {
        $arrReturn = [
            self::TIME_STATUS_SLOW   => '缓慢',
            self::TIME_STATUS_NORMAL => '正常',
            self::TIME_STATUS_URGENT => '紧急',
        ];

        if ($intStatus != null && isset($arrReturn[$intStatus])) {
            $arrReturn = $arrReturn[$intStatus];
        }

        return $arrReturn;
    }

    /**
     * getStatus() 获取状态信息
     * @param null $intStatus 状态值
     * @return array|mixed
     */
    public static function getStatusColors($intStatus = null)
    {
        $arrReturn = [
            self::STATUS_PENDING  => 'label-warning',
            self::STATUS_DELEGATE => 'label-info',
            self::STATUS_COMPLETE => 'label-success',
            self::STATUS_DEFER    => 'label-danger'
        ];

        if ($intStatus != null && isset($arrReturn[$intStatus])) {
            $arrReturn = $arrReturn[$intStatus];
        }

        return $arrReturn;
    }

    /**
     * getTimeStatus() 获取时间状态信息
     * @param null $intStatus 状态值
     * @return array|mixed
     */
    public static function getTimeColors($intStatus = null)
    {
        $arrReturn = [
            self::TIME_STATUS_SLOW   => 'label-purple',
            self::TIME_STATUS_NORMAL => 'label-yellow',
            self::TIME_STATUS_URGENT => 'label-danger',
        ];

        if ($intStatus != null && isset($arrReturn[$intStatus])) {
            $arrReturn = $arrReturn[$intStatus];
        }

        return $arrReturn;
    }
}
