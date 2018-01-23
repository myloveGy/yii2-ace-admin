<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\models\AdminForm;
use common\helpers\Helper;
use backend\models\Menu;
use backend\models\Admin;

/**
 * Class SiteController 后台首页处理
 * @package backend\controllers
 */
class SiteController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'system', 'grid', 'get-data', 'update', 'create', 'test'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => ['class' => 'yii\web\ErrorAction'],
        ];
    }

    /**
     * 管理员登录欢迎页
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = false;
        // 获取用户导航栏信息
        $menus = Menu::getUserMenus(Yii::$app->user->id);
        Yii::$app->view->params['user'] = Yii::$app->getUser()->identity;
        Yii::$app->view->params['menus'] = $menus ? $menus : [];
        return $this->render('index');
    }

    /**
     * 显示首页系统信息
     *
     * @return string
     */
    public function actionSystem()
    {
        // 用户信息
        Yii::$app->view->params['user'] = Yii::$app->getUser()->identity;

        return $this->render('system', [
            'yii' => 'Yii ' . Yii::getVersion(),                      // Yii 版本
            'upload' => ini_get('upload_max_filesize'),      // 上传文件大小
        ]);
    }

    /**
     * 后台管理员登录
     *
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        $this->layout = 'login.php';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new AdminForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            // 生成缓存导航栏文件
            Menu::setNavigation(Yii::$app->user->id);
            return $this->goBack(); // 到首页去
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 后台管理员退出
     *
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        // 用户退出修改登录时间
        $admin = Admin::findOne(Yii::$app->user->id);
        if ($admin) {
            $admin->last_time = time();
            $admin->last_ip = Helper::getIpAddress();
            $admin->save();
        }

        Yii::$app->cache->delete(Menu::CACHE_KEY.Yii::$app->user->id);
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
