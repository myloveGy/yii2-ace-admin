<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%auth_rule}}".
 *
 * @property string $name
 * @property string $data
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property AuthItem[] $authItems
 */
class AuthRule extends \yii\db\ActiveRecord
{
    /**
     * @var string 定义使用的旧名称
     */
    public $newName;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_rule}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['newName'], 'required'],
            [['newName'], 'unique', 'targetAttribute' => 'name'],
            [['name'], 'required', 'on' => ['update']],
            [['data'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['name', 'newName'], 'string', 'max' => 64],
            [['data'], 'classExists'],
        ];
    }

    /**
     * 定义验证场景需要验证的字段
     * @return array
     */
    public function scenarios()
    {
        return [
            'default' => ['name', 'data'],
            'create' => ['newName', 'data'],
            'update' => ['name', 'newName', 'data']
        ];
    }

    /**
     * Validate class exists
     */
    public function classExists()
    {
        if (!class_exists($this->data)) {
            $message = Yii::t('app', "Unknown class '{class}'", ['class' => $this->data]);
            $this->addError('data', $message);
            return;
        }
        if (!is_subclass_of($this->data, \yii\rbac\Rule::className())) {
            $message = Yii::t('app', "'{class}' must extend from 'yii\rbac\Rule' or its child class", [
                'class' => $this->data]);
            $this->addError('data', $message);
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'data' => 'Data',
            'newName' => '名称',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * 修改数
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        // 先走验证
        if ($this->validate()) {
            /* @var $manager \yii\rbac\DbManager */
            $manager = Yii::$app->getAuthManager();
            $class = new $this->data;
            $class->name = $this->newName;
            // 新增数据
            if ($this->isNewRecord) {
                $manager->add($class);
            } else {
                $manager->update($this->name, $class);
            }

            return true;
        }

        return false;
    }

    /**
     * 删除数据
     * @return bool
     */
    public function delete()
    {
        if ($this->data) {
            /* @var $manager \yii\rbac\DbManager */
            $manager = Yii::$app->getAuthManager();
            $class = unserialize($this->data);
            $class->name = $this->name;
            return $manager->remove($class);
        }

        return false;
    }

    /**
     * 多删除操作
     * @param null $condition
     * @param array $params
     * @return bool
     */
    public static function deleteAll($condition = null, $params = [])
    {
        $all = self::findAll($condition);
        if ($all) {
            foreach ($all as $model) {
                $model->delete();
            }

            return true;
        }

        return false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthItems()
    {
        return $this->hasMany(AuthItem::className(), ['rule_name' => 'name']);
    }
}
