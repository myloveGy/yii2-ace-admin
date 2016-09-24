<?php

namespace common\models;

/**
 * This is the model class for table "{{%china}}".
 *
 * @property integer $Id
 * @property string $Name
 * @property integer $Pid
 *
 * @property China $p
 * @property China[] $chinas
 */
class China extends \common\models\Model
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%china}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'pid'], 'integer'],
            [['name'], 'string', 'max' => 40],
            [['pid'], 'exist', 'skipOnError' => true, 'targetClass' => China::className(), 'targetAttribute' => ['Pid' => 'Id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'   => 'ID',
            'name' => '名称',
            'pid'  => '父类ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(China::className(), ['id' => 'pid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChinas()
    {
        return $this->hasMany(China::className(), ['pid' => 'id']);
    }

}
