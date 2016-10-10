<?php

namespace backend\controllers;

use backend\models\Admin;
use common\models\China;
use Yii;
/**
 * file: AdminController.php
 * desc: 管理员操作控制器
 * user: liujinxing
 * date: 2016-0-21
 */
class AdminController extends Controller
{
    // 搜索配置
    public function where($params)
    {
        $where  = [];
        $intUid = (int)Yii::$app->user->id;
        if ($intUid != 1) {
            $where = [['or', ['id' => $intUid], ['created_id' => $intUid]]];
        }

        return [
            'id'       => '=',
            'username' => 'like',
            'email'    => 'like',
            'where'    => $where,
        ];
    }

    // 首页显示
    public function actionIndex()
    {
        // 查询用户数据
        return $this->render('index', [
            'roles'  => Admin::getArrayRole(),      // 用户角色
            'status' => Admin::getArrayStatus(),    // 状态
        ]);
    }

    // 返回model
    public function getModel() { return new Admin();}

    // 我的信息
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
                $arrChina = \common\models\China::find()->where(['name' => array_slice($arrAddress, 0, 2)])->orderBy(['pid' => SORT_ASC])->all();
            }
        }

        // 获取用户日志信息
        $arrLogs = $this->getInfo('update');

        // 载入视图文件
        return $this->render('view', [
            'address' => $address,  // 县
            'china'   => $arrChina, // 省市信息
            'logs'    => $arrLogs,  // 日志信息
        ]);
    }

    // 上传文件目录
    public function getUploadPath()
    {
        return './public/assets/avatars/';
    }

    // 上传头像之后的处理
    public function afterUpload($objFile, &$strFilePath, $strField)
    {
        // 上传头像信息
        if ($strField === 'avatar' || $strField === 'face')
        {
            // 删除之前的缩略图
            $strFace = Yii::$app->request->post('face');
            if ($strFace)
            {
                $strFace = dirname($strFace).'/thumb_'.basename($strFace);
                if (file_exists('.'.$strFace)) @unlink('.'.$strFace);
            }

            // 处理图片
            $strTmpPath = dirname($strFilePath).'/thumb_'.basename($strFilePath);
            $image = Yii::$app->image->load($strFilePath);
            $image->resize(180, 180, \yii\image\drivers\Image::CROP)->save($strTmpPath);
            $image->resize(48, 48, \yii\image\drivers\Image::CROP)->save();

            // 管理员页面修改头像
            $admin = Admin::findOne(Yii::$app->user->id);
            if ($admin && $strField === 'avatar')
            {
                // 删除之前的图像信息
                if ($admin->face && file_exists('.'.$admin->face))
                {
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

    // 获取地址信息
    public function actionAddress()
    {
        $request = Yii::$app->request;
        $array   = [];
        if ($request->isGet)
        {
            $strName = $request->get('query');          // 查询参数
            $intPid  = (int)$request->get('iPid', 0);   // 父类ID
            $where   = ['and', ['pid' => $intPid], ['<>', 'id', 0]];
            if ( ! empty($strName)) array_push($where, ['like', 'name', $strName]);
            $arrCountry = China::find()->select(['id', 'name'])->where($where)->all();
            if ($arrCountry)
            {
                foreach ($arrCountry as $value) $array[] = ['id' => $value->id, 'text' => $value->name];
            }
        }

        exit(json_encode($array));
    }
}
