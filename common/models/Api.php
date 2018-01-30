<?php

namespace common\models;

use Yii;
use common\models\AdminModel;


/**
 * This is the model class for table "yii2_api".
 *
 * @property int $id
 * @property string $schemes 传输协议
 * @property string $url
 * @property string $method
 * @property string $tags
 * @property string $summary
 * @property string $description
 * @property string $operationId
 * @property string $consumes
 * @property string $produces
 * @property string $parameters
 * @property string $responses
 * @property string $version
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Api extends AdminModel //\yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%api}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'method', 'tags', 'summary', 'description', 'consumes', 'produces', 'responses', 'version'], 'required'],
            [['parameters'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['schemes'], 'string', 'max' => 50],
            [['url', 'method', 'tags', 'summary', 'description', 'operationId', 'consumes', 'produces', 'responses', 'version'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'schemes' => 'Schemes',
            'url' => 'Url',
            'method' => 'Method',
            'tags' => 'Tags',
            'summary' => 'Summary',
            'description' => 'Description',
            'operationId' => 'Operation ID',
            'consumes' => 'Consumes',
            'produces' => 'Produces',
            'parameters' => 'Parameters',
            'responses' => 'Responses',
            'version' => 'Version',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
