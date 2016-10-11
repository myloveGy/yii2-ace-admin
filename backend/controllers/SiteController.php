<?php
namespace backend\controllers;

use backend\models\Auth;
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
                        'actions' => ['logout', 'index', 'test'],
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

    /**
     * actionTest() 用来生成默认的权限信息
     */
    public function actionTest()
    {
        $controller = [
            'admin'     => '管理员信息',
            'arrange'   => '日程管理',
            'authority' => '权限信息',
            'china'     => '地址信息',
            'menu'      => '导航栏目',
            'module'    => '模块生成',
            'role'      => '角色信息',
        ];

        $action = [
            'index'  => '显示',
            'search' => '搜索',
            'create' => '创建',
            'update' => '修改',
            'delete' => '删除',
        ];

        foreach ($controller as $key => $value) {
            foreach ($action as $k => $v) {
                $model = new Auth();
                $model->type = 2;
                $model->name = $key.'/'.$k;
                $model->description = $v.$value;
                $model->save();
            }
        }

        $admin = [
            'administrator' => '超级管理员',
            'admin'         => '管理员',
            'user'          => '普通用户',
        ];

        foreach ($admin as $key => $value) {
            $model = new Auth();
            $model->type = 1;
            $model->name = $key;
            $model->description = $value;
            if ($model->save() && $key == 'administrator') {
                $auth = Auth::findAll(['type' => 2]);
                if ($auth) {
                    foreach ($auth as $val) {
                        Yii::$app->db->createCommand()->insert('yii2_auth_item_child', [
                            'parent' => $model->name,
                            'child'  => $val->name,
                        ])->execute();
                    }
                }
            }
        }
    }

    /**
     * actionIndex() 管理员登录欢迎页
     * @return string
     * @throws UnauthorizedHttpException
     */
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

    /**
     * actionLogin() 后台管理员登录
     * @return string|\yii\web\Response
     */
    public function actionLogin()
    {
        $this->layout = 'login.php';
        if (!\Yii::$app->user->isGuest) {return $this->goHome();}
        $model = new AdminForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Menu::setNavigation();  // 生成缓存导航栏文件
            return $this->goBack(); // 到首页去
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * actionLogout() 后台管理员退出
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        // 用户退出修改登录时间
        $admin = Admin::findOne(Yii::$app->user->id);
        if ($admin) {
            $admin->last_time = time();
            $admin->last_ip   = Yii::$app->request->userIP;
            $admin->save();
        }

        Yii::$app->user->logout();
        return $this->goHome();
    }
}
