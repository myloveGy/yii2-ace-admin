<?php
namespace backend\controllers;

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
    public $strUploadPath = './public/assets/avatars/';

    /**
     * 搜索配置
     * @param  array $params 查询参数
     * @return array
     */
    public function where($params)
    {
        $where  = [];
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
            'roles'  => Admin::getArrayRole(),      // 用户角色
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
        $address  = '选择县';
        $user     = Yii::$app->view->params['user'];
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

        // 载入视图文件
        return $this->render('view', [
            'address' => $address,  // 县
            'china'   => $arrChina, // 省市信息
            'logs'    => [],  // 日志信息
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
                $strFace = dirname($strFace).'/thumb_'.basename($strFace);
                if (file_exists('.'.$strFace)) @unlink('.'.$strFace);
            }

            // 处理图片
            $strTmpPath = dirname($strFilePath).'/thumb_'.basename($strFilePath);
            /* @var $image \yii\image\drivers\Kohana_Image */
            $image = Yii::$app->image->load($strFilePath);
            $image->resize(180, 180, Image::CROP)->save($strTmpPath);
            $image->resize(48, 48, Image::CROP)->save();

            // 管理员页面修改头像
            $admin = Admin::findOne(Yii::$app->user->id);
            if ($admin && $strField === 'avatar') {
                // 删除之前的图像信息
                if ($admin->face && file_exists('.'.$admin->face)) {
                    @unlink('.'.$admin->face);
                    @unlink('.'.dirname($admin->face).'/thumb_'.basename($admin->face));
                }

                $admin->face = ltrim($strFilePath, '.');
                $admin->save();
                $strFilePath = $strTmpPath;
            }
        }

        return true;
    }

    /**
     * 获取地址信息
     * @return \yii\web\Response
     */
    public function actionAddress()
    {
        $request = Yii::$app->request;
        $array   = [];
        if ($request->isGet) {
            $strName = $request->get('query');          // 查询参数
            $intPid  = (int)$request->get('iPid', 0);   // 父类ID
            $where   = ['and', ['pid' => $intPid], ['<>', 'id', 0]];
            if ( ! empty($strName)) array_push($where, ['like', 'name', $strName]);
            $arrCountry = China::find()->select(['id', 'name'])->where($where)->asArray()->all();
            if ($arrCountry) {
                foreach ($arrCountry as $value) {
                    $array[] = [
                        'id' => $value['id'],
                        'text' => $value['name']
                    ];
                }
            }
        }

        return $this->asJson($array);
    }

    /**
     * 处理导出数据显示
     * @param array $array
     */
    public function handleExport(&$array)
    {
        $array['created_at'] = date('Y-m-d H:i:s', $array['created_at']);
        $array['updated_at'] = date('Y-m-d H:i:s', $array['updated_at']);
    }
}
