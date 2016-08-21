<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use common\models\AdminForm;
use backend\models\Menu;
use backend\models\Admin;
use yii\filters\VerbFilter;
use yii\web\UnauthorizedHttpException;

/**
 * Site controller
 */
class SiteController extends \yii\web\Controller
{
    public  $enableCsrfValidation = false;

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
                        'allow'   => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow'   => true,
                        'roles'   => ['@'],
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
            'error' => ['class' => 'yii\web\ErrorAction',],
        ];
    }

    // 首页显示
    public function actionIndex()
    {
        // 查询导航栏信息
        $menus = Yii::$app->cache->get('navigation'.Yii::$app->user->id);
        if ( ! $menus) throw new UnauthorizedHttpException('对不起，您还没获得显示导航栏目权限!');

        // 用户信息和导航栏目
        Yii::$app->view->params['menus'] = $menus;
        Yii::$app->view->params['user']  = Yii::$app->getUser()->identity;

        // 系统信息
        $system = explode(' ', php_uname());
        $system = $system[0] .'&nbsp;' . ('/' == DIRECTORY_SEPARATOR ? $system[2] : $system[1]);

        // MySql版本
        $version = Yii::$app->db->createCommand('SELECT VERSION() AS `version`')->queryOne();

        // 加载视图
        return $this->render('index', [
            'system' => $system,                                        // 系统信息
            'yii'    => 'Yii '. Yii::getVersion(),                      // Yii 版本
            'php'    => 'PHP '. PHP_VERSION,                            // PHP 版本
            'server' => $_SERVER['SERVER_SOFTWARE'],                    // 服务器信息
            'mysql'  => 'MySQL '.($version ? $version['version'] : ''), // Mysql版本
            'upload' => ini_get('upload_max_filesize'),                 // 上传文件大小
        ]);
    }

    // 用户登录
    public function actionLogin()
    {
        $this->layout = 'login.php';
        if (!\Yii::$app->user->isGuest) {return $this->goHome();}
        $model = new AdminForm();
        if ($model->load(Yii::$app->request->post()) && $model->login())
        {
            Menu::setNavigation();  // 生成缓存导航栏文件
            return $this->goBack(); // 到首页去
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    // 用户退出
    public function actionLogout()
    {
        // 用户退出修改登录时间
        $admin = Admin::findOne(Yii::$app->user->id);
        if ($admin)
        {
            $admin->last_time = time();
            $admin->last_ip   = Yii::$app->request->userIP;
            $admin->save();
        }

        Yii::$app->user->logout();
        return $this->goHome();
    }
}
