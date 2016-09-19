<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use common\behaviors\UpdateBehavior;

class AdminModel extends Model
{
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            UpdateBehavior::className(),
        ];
    }
}
