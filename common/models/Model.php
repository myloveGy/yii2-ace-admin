<?php
namespace common\models;

class Model extends \yii\db\ActiveRecord
{
    // 获取错误信息
    public function getErrorString()
    {
        $str    = '';
        $errors = $this->getErrors();
        if ( ! empty($errors)) {
            foreach ($errors as $value)
            {
                if (is_array($value))
                    foreach ($value as $val) $str .= $val;
                else
                    $str .= $value;
            }
        }

        return $str;
    }
}
