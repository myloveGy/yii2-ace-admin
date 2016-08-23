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
            [['Id'], 'required'],
            [['Id', 'Pid'], 'integer'],
            [['Name'], 'string', 'max' => 40],
            [['Pid'], 'exist', 'skipOnError' => true, 'targetClass' => China::className(), 'targetAttribute' => ['Pid' => 'Id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Name' => 'Name',
            'Pid' => 'Pid',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getP()
    {
        return $this->hasOne(China::className(), ['Id' => 'Pid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChinas()
    {
        return $this->hasMany(China::className(), ['Pid' => 'Id']);
    }

}
