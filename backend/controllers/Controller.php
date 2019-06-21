<?php

namespace backend\controllers;

use jinxing\admin\behaviors\Logging;
use jinxing\admin\controllers\Controller as BaseController;
use yii\filters\AccessControl;

/**
 * Class Controller 后台的基础控制器
 * @author  liujx
 * @package backend\controllers
 */
class Controller extends BaseController
{
    /**
     * @var string 使用 yii2-admin 的布局
     */
    public $layout = '@jinxing/admin/views/layouts/main';

    /**
     * @var string 使用自己定义的上传文件处理表单
     */
    public $uploadFromClass = 'backend\models\forms\UploadForm';

    /**
     * 定义使用的行为
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow'       => true,
                        'permissions' => [$this->action->getUniqueId()],
                    ],
                ],
            ],

            'logging' => [
                'class' => Logging::className(),
            ],
        ];
    }
}
