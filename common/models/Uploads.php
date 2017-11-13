<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%uploads}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property integer $created_at
 * @property integer $updated_at
 */
class Uploads extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%uploads}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'url'], 'required'],
            [['url'], 'string'],
            [['title'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'title' => '标题',
            'url' => '文件访问地址',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}
