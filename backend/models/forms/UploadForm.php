<?php

namespace backend\models\forms;

use \yii\base\Model;
use yii\web\UploadedFile;

/**
 * Class UploadForm 上传文件处理类
 *
 * @package app\models\forms
 */
class UploadForm extends Model
{
    /**
     * @var UploadedFile 定义上传字段
     */
    public $avatar;

    /**
     * 设置应用场景
     *
     * @return array
     */
    public function scenarios()
    {
        return [
            // 场景名称和字段名称一致
            'avatar' => ['avatar'],
        ];
    }

    // 验证规则
    public function rules()
    {
        return [
            // 定义字段的验证规则，注意需要定义场景
            [['avatar'], 'image', 'extensions' => ['png', 'jpg', 'gif', 'jpeg'], 'on' => 'avatar'],
        ];
    }
}