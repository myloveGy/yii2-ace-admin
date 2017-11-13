<?php
namespace common\models;

use \yii\base\Model;

/**
 * Class    UploadForm
 * @package backend\models
 * @Desc    文件上传类
 * @User    liujx
 * @Date    2016-4-7
 */
class UploadForm extends Model
{
    // 定义字段
    public $avatar;  // 管理员个人页面上传头像
    public $face;    // 管理员信息页面上传头像

    public $url;

    // 设置应用场景
    public function scenarios()
    {
        return [
            'avatar' => ['avatar'],
            'face'   => ['face'],
            'url' => ['url']
        ];
    }

    // 验证规则
    public function rules()
    {
        return [
            [['avatar'], 'image', 'extensions' => ['png', 'jpg', 'gif', 'jpeg'], 'on' => 'avatar'],
            [['face'], 'image', 'extensions' => ['png', 'jpg', 'gif', 'jpeg'], 'on' => 'face'],
            [['url'], 'image', 'extensions' => ['png', 'jpg', 'gif', 'jpeg'], 'on' => 'url'],
        ];
    }
}