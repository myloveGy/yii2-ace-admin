<?php
/**
 * Created by PhpStorm.
 * User: liujinxing
 * Date: 2016/8/15
 * Time: 16:50
 */

namespace common\behaviors;

use yii;
use yii\base\InvalidCallException;
use yii\db\BaseActiveRecord;

class UpdateBehavior extends \yii\behaviors\AttributeBehavior
{
    // 创建用户
    public $createdAtAttribute = 'created_id';

    // 添加用户
    public $updatedAtAttribute = 'updated_id';

    // 值
    public $value;


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->createdAtAttribute, $this->updatedAtAttribute],
                BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->updatedAtAttribute,
            ];
        }
    }

    // 获取值
    protected function getValue($event)
    {
        if ($this->value === null) return Yii::$app->getUser()->id;
        return parent::getValue($event);
    }

    // 修改
    public function touch($attribute)
    {
        /* @var $owner BaseActiveRecord */
        $owner = $this->owner;
        if ($owner->getIsNewRecord()) {
            throw new InvalidCallException('Updating the created_id is not possible on a new record.');
        }
        $owner->updateAttributes(array_fill_keys((array) $attribute, $this->getValue(null)));
    }
}