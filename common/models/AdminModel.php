<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use common\behaviors\UpdateBehavior;

/**
 * Class AdminModel 后台处理有新增和修改字段的model
 * @package common\models
 */
class AdminModel extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            UpdateBehavior::className(),
        ];
    }
}
