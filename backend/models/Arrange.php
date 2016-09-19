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
 * @property integer $end_at
 * @property integer $created_at
 * @property integer $created_id
 * @property integer $updated_at
 * @property integer $updated_id
 */
class Arrange extends AdminModel
{
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
            [['start_at', 'status', 'end_at', 'created_at', 'created_id', 'updated_at', 'updated_id'], 'integer'],
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
            'start_at'   => '开始时间',
            'status'     => '事件状态',
            'end_at'     => '结束时间',
            'created_at' => '创建时间',
            'created_id' => '添加用户',
            'updated_at' => '修改时间',
            'updated_id' => '修改用户',
        ];
    }
}
