<?php

namespace backend\controllers;

use backend\models\AdminLog;
use common\helpers\Helper;
use Yii;
use backend\models\Admin;
use common\models\China;
use yii\image\drivers\Image;

/**
 * Class AdminController 后台管理员操作控制器
 * @package backend\controllers
 */
class AdminController extends Controller
{
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'backend\models\Admin';

    /**
     * @var string 定义上传文件的目录
     */
    public $strUploadPath = './uploads/avatars/';

    /**
     * 搜索配置
     * @param  array $params 查询参数
     * @return array
     */
    public function where($params)
    {
        $where = [];
        $intUid = (int)Yii::$app->user->id;
        if ($intUid !== Admin::SUPER_ADMIN_ID) {
            $where = [['or', ['id' => $intUid], ['created_id' => $intUid]]];
        }

        return [
            'id' => '=',
            'username' => 'like',
            'email' => 'like',
            'where' => $where,
            'status' => '='
        ];
    }

    /**
     * 首页显示
     * @return string
     */
    public function actionIndex()
    {
        // 查询用户数据
        return $this->render('index', [
            'roles' => Admin::getArrayRole(),      // 用户角色
            'status' => Admin::getArrayStatus(),    // 状态
            'statusColor' => Admin::getStatusColor(), // 状态对应颜色
        ]);
    }

    /**
     * 查看个人信息
     * @return string
     */
    public function actionView()
    {
        $address = '选择县';
        $user = Yii::$app->view->params['user'];
        $arrChina = [];
        if ($user->address) {
            $arrAddress = explode(',', $user->address);
            if ($arrAddress) {
                if (isset($arrAddress[2])) $address = $arrAddress[2];
                // 查询省市信息
                $arrChina = China::find()
                    ->where(['name' => array_slice($arrAddress, 0, 2)])
                    ->orderBy(['pid' => SORT_ASC])
                    ->all();
            }
        }

        // 操作日志
        $logs = AdminLog::find()->where([
            'created_id' => $user->id
        ])
            ->orderBy(['id' => SORT_DESC])
            ->limit(100)
            ->asArray()
            ->all();

        // 载入视图文件
        return $this->render('view', [
            'address' => $address,  // 县
            'china' => $arrChina, // 省市信息
            'logs' => $logs,  // 日志信息
        ]);
    }

    /**
     * 上传文件之后的处理
     * @param object $objFile
     * @param string $strFilePath
     * @param string $strField
     * @return bool
     */
    public function afterUpload($objFile, &$strFilePath, $strField)
    {
        // 上传头像信息
        if ($strField === 'avatar' || $strField === 'face') {
            // 删除之前的缩略图
            $strFace = Yii::$app->request->post('face');
            if ($strFace) {
                $strFace = dirname($strFace) . '/thumb_' . basename($strFace);
                if (file_exists('.' . $strFace)) @unlink('.' . $strFace);
            }

            // 处理图片
            $strTmpPath = dirname($strFilePath) . '/thumb_' . basename($strFilePath);

            /* @var $image yii\image\ImageDriver */
            $imageComponent = Yii::$app->get('image');
            if ($imageComponent) {
                /* @var $image yii\image\drivers\Kohana_Image_GD */
                $image = $imageComponent->load($strFilePath);
                $image->resize(180, 180, Image::CROP)->save($strTmpPath);
                $image->resize(48, 48, Image::CROP)->save();

                // 管理员页面修改头像
                $admin = Admin::findOne(Yii::$app->user->id);
                if ($admin && $strField === 'avatar') {
                    // 删除之前的图像信息
                    if ($admin->face && file_exists('.' . $admin->face)) {
                        @unlink('.' . $admin->face);
                        @unlink('.' . dirname($admin->face) . '/thumb_' . basename($admin->face));
                    }

                    $admin->face = ltrim($strFilePath, '.');
                    $admin->save();
                    $strFilePath = $strTmpPath;
                }
            }
        }

        return true;
    }

    /**
     * 获取地址信息
     *
     * @return \yii\web\Response
     */
    public function actionAddress()
    {
        $request = Yii::$app->request;
        $strName = $request->get('query');                     // 查询参数
        $intPid = (int)$request->get('iPid', 0);   // 父类ID
        $arrCountry = China::find()->select(['id', 'name as text'])
            ->where([
                'and',
                ['pid' => $intPid],
                ['>', 'id', 0]
            ])->andFilterWhere(['like', 'name', $strName])->asArray()->all();

        return $this->asJson($arrCountry);
    }

    /**
     * 导出数据显示处理
     *
     * @return array
     */
    public function getExportHandleParams()
    {
        $array['created_at'] = $array['updated_at'] = function ($value) {
            return date('Y-m-d H:i:s', $value);
        };

        return $array;
    }

    /**
     * 重写批量删除处理
     *
     * @return mixed|string
     */
    public function actionDeleteAll()
    {
        $ids = Yii::$app->request->post('id');
        if (empty($ids) || !($arrIds = explode(',', $ids))) {
            return $this->error(201);
        }

        /* @var $model \backend\models\Admin */
        $model = $this->modelClass;
        $this->arrJson['errCode'] = 220;
        $admins = $model::findAll([$this->pk => $arrIds]);
        if (empty($admins)) {
            return $this->error(220);
        }

        $message = '处理成功! <br>';
        foreach ($admins as $admin) {
            if ($admin->delete()) {
                $message .= $admin->username . ' 删除成功; <br>';
            } else {
                $message .= $admin->username . '删除失败：' . Helper::arrayToString($admin->getErrors()) . ' <br>';
            }
        }

        AdminLog::create(AdminLog::TYPE_DELETE, $ids, $this->pk . '=' . $ids);
        return $this->success($arrIds, $message);
    }
}
