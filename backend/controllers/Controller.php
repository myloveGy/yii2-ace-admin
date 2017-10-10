<?php

namespace backend\controllers;

use backend\models\AdminLog;
use Yii;
use common\models\Admin;
use common\models\UploadForm;
use common\strategy\Substance;
use common\helpers\Helper;
use yii\db\Query;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use yii\web\UnauthorizedHttpException;

/**
 * Class Controller 后台的基础控制器
 * @author  liujx
 * @package backend\controllers
 */
class Controller extends \common\controllers\UserController
{
    // 引入json 返回处理类
    use \common\traits\Json;

    /**
     * @var string 定义使用的model
     */
    protected $modelClass = '\backend\models\Admin';

    /**
     * @var string pk 定义表使用的主键名称
     */
    protected $pk = 'id';

    /**
     * @var string sort 定义默认排序条件
     */
    protected $sort = 'id';

    /**
     * @var string 定义上传文件的保存的路径
     */
    protected $strUploadPath = './public/uploads/';

    /**
     * @var string 定义使用资源的策略类名
     */
    protected $strategy = 'DataTables';

    /**
     * @var null admins 定义管理员信息
     */
    protected $admins = null;

    /**
     * 请求之前的数据验证
     * @param \yii\base\Action $action
     * @return bool
     * @throws UnauthorizedHttpException
     */
    public function beforeAction($action)
    {
        // 主控制器验证
        if (parent::beforeAction($action)) {
            // 验证权限
            if (!Yii::$app->user->can($action->controller->id . '/' . $action->id)
                && Yii::$app->getErrorHandler()->exception === null
            ) {
                // 没有权限AJAX返回
                if (Yii::$app->request->isAjax) {
                    header('Content-Type: application/json; charset=UTF-8');
                    exit(Json::encode([
                        'errCode' => 216,
                        'errMsg' => '对不起，您现在还没获得该操作的权限!',
                        'data' => []
                    ]));
                }

                throw new UnauthorizedHttpException('对不起，您现在还没获得该操作的权限!');
            }

            // 处理获取数据(默认不提前注入)
            if (!in_array($action->id, ['create', 'update', 'delete', 'delete-all', 'editeable', 'upload', 'export'])) {
                $this->admins = ArrayHelper::map(Admin::findAll(['status' => Admin::STATUS_ACTIVE]), 'id', 'username');
                // 注入变量信息
                Yii::$app->view->params['admins'] = $this->admins;
                Yii::$app->view->params['user'] = Yii::$app->getUser()->identity;
            }

            return true;
        }

        return false;
    }

    /**
     * 首页显示
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 获取查询的配置信息(查询参数)
     * @access protected
     * @param  array $params 查询的请求参数
     * @return array 返回一个数组用来查询
     */
    protected function where($params)
    {
        return [];
    }

    /**
     * 获取查询对象(查询结果一定要为数组)
     *
     * @param mixed|array $where 查询条件
     * @return \yii\db\Query 返回查询对象
     * @see actionSearch()
     * @see actionExport()
     */
    protected function getQuery($where)
    {
        /* @var $model \yii\db\ActiveRecord */
        $model = $this->modelClass;
        return (new Query())->from($model::tableName())->where($where);
    }

    /**
     * 处理查询数据
     * @return mixed|string
     * @see where()
     * @see getQuery()
     * @see afterSearch()
     */
    public function actionSearch()
    {
        // 实例化数据显示类
        /* @var $strategy \common\strategy\Strategy */
        $strategy = Substance::getInstance($this->strategy);

        // 获取查询参数
        $search = $strategy->getRequest(); // 处理查询参数
        $search['field'] = $search['field'] ? $search['field'] : $this->sort;
        $search['orderBy'] = [$search['field'] => $search['sort'] == 'asc' ? SORT_ASC : SORT_DESC];
        $search['where'] = Helper::handleWhere($search['params'], $this->where($search['params']));

        // 查询数据
        $query = $this->getQuery($search['where']);
        if (YII_DEBUG) $this->arrJson['other'] = $query->createCommand()->getRawSql();

        // 查询数据条数
        $total = $query->count();
        if ($total) {
            $array = $query->offset($search['offset'])->limit($search['limit'])->orderBy($search['orderBy'])->all();
            if ($array) $this->afterSearch($array);
        } else {
            $array = [];
        }

        // 处理返回数据
        $this->handleJson($strategy->handleResponse($array, $total));

        // 返回JSON数据
        return $this->returnJson();
    }

    /**
     * 查询之后的数据处理函数
     * @access protected
     * @param  mixed $array 查询出来的数组对象
     * @return void  对数据进行处理
     * @see actionSearch()
     */
    protected function afterSearch(&$array)
    {

    }

    /**
     * 处理新增数据
     * @return mixed|string
     */
    public function actionCreate()
    {
        $data = Yii::$app->request->post();
        if ($data) {
            // 实例化出查询的model
            /* @var $model \yii\db\ActiveRecord */
            $model = new $this->modelClass();

            // 验证是否定义了创建对象的验证场景
            $arrScenarios = $model->scenarios();
            if (isset($arrScenarios['create'])) {
                $model->scenario = 'create';
            }

            // 对model对象各个字段进行赋值
            $this->arrJson['errCode'] = 205;
            if ($model->load($data, '')) {
                // 判断修改返回数据
                if ($model->save()) {
                    $this->handleJson($model);
                    $pk = $this->pk;
                    AdminLog::create(AdminLog::TYPE_CREATE, $data, $this->pk . '=' . $model->$pk);
                } else {
                    $this->arrJson['errMsg'] = Helper::arrayToString($model->getErrors());
                }
            }
        }

        // 返回数据
        return $this->returnJson();
    }

    /**
     * 处理修改数据
     * @return mixed|string
     */
    public function actionUpdate()
    {
        // 接收参数判断
        $data = Yii::$app->request->post();
        if ($data && !empty($data[$this->pk])) {

            // 通过传递过来的唯一主键值查询数据
            /* @var $model \yii\db\ActiveRecord */
            $model = $this->modelClass;
            $model = $model::findOne($data[$this->pk]);

            // 存在数据
            if ($model) {
                // 判断是否存在指定的验证场景，有则使用，没有默认
                $arrScenarios = $model->scenarios();
                if (isset($arrScenarios['update'])) {
                    $model->scenario = 'update';
                }

                // 对model对象各个字段进行赋值
                $this->arrJson['errCode'] = 205;
                if ($model->load($data, '')) {
                    // 修改数据成功
                    if ($model->save()) {
                        $this->handleJson($model);
                        AdminLog::create(AdminLog::TYPE_UPDATE, $data, $this->pk . '=' . $data[$this->pk]);
                    } else {
                        $this->arrJson['errMsg'] = Helper::arrayToString($model->getErrors());
                    }
                }
            }
        }

        // 返回数据
        return $this->returnJson();
    }

    /**
     * 处理删除数据
     * @return mixed|string
     */
    public function actionDelete()
    {
        $data = Yii::$app->request->post();
        if ($data && !empty($data[$this->pk])) {
            // 通过传递过来的唯一主键值查询数据
            /* @var $model \yii\db\ActiveRecord */
            $model = $this->modelClass;
            $model = $model::findOne($data[$this->pk]);
            $this->arrJson['errCode'] = 222;
            if ($model) {
                // 删除数据成功
                if ($model->delete()) {
                    $this->handleJson($model);
                    AdminLog::create(AdminLog::TYPE_DELETE, $data, $this->pk . '=' . $data[$this->pk]);
                } else {
                    $this->arrJson['errMsg'] = Helper::arrayToString($model->getErrors());
                }
            }
        }

        return $this->returnJson();
    }

    /**
     * 批量删除操作
     * @return mixed|string
     */
    public function actionDeleteAll()
    {
        $ids = Yii::$app->request->post('id');
        if ($ids) {
            $arrIds = explode(',', $ids);
            if ($arrIds) {
                /* @var $model \yii\db\ActiveRecord */
                $model = $this->modelClass;
                $this->arrJson['errCode'] = 220;
                $where = [$this->pk => $arrIds];
                if ($model::deleteAll($where)) {
                    $this->handleJson($ids);
                    AdminLog::create(AdminLog::TYPE_DELETE, $where, $this->pk . '=all');
                }
            }

        }

        return $this->returnJson();
    }

    /**
     * 处理行内编辑
     * @return mixed|string
     */
    public function actionEditable()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            // 接收参数
            $mixPk = $request->post('pk');    // 主键值
            $strAttr = $request->post('name');  // 字段名
            $mixValue = $request->post('value'); // 字段值

            // 主键值、修改字段、修改的值不能为空字符串
            $this->arrJson['errCode'] = 207;
            if ($mixPk && $strAttr && $mixValue !== '') {

                // 通过主键查询数据
                /* @var $model \yii\db\ActiveRecord */
                $model = $this->modelClass;
                $model = $model::findOne($mixPk);
                $this->arrJson['errCode'] = 220;
                if ($model) {
                    // 修改对应的字段
                    $model->$strAttr = $mixValue;
                    $this->arrJson['errCode'] = 206;
                    if ($model->save()) {
                        $this->handleJson($model);
                        AdminLog::create(AdminLog::TYPE_UPDATE, $request->post(), $this->pk . '=' . $mixPk);
                    } else {
                        $this->arrJson['errMsg'] = Helper::arrayToString($model->getErrors());
                    }
                }
            }
        }

        // 返回数据
        return $this->returnJson();
    }

    /**
     * 文件上传成功的处理信息
     * @access protected
     * @param  object $object 文件上传类
     * @param  string $strFilePath 文件保存路径
     * @param  string $strField 上传文件表单名
     * @return bool 上传成功返回true
     */
    protected function afterUpload($object, &$strFilePath, $strField)
    {
        return true;
    }

    /**
     * 处理文件上传操作
     * @return mixed|string
     * @see afterUpload()
     */
    public function actionUpload()
    {
        // 定义请求数据
        $request = Yii::$app->request;
        if ($request->isPost) {
            // 接收参数
            $strField = $request->get('sField');    // 上传文件表单名称
            if (!empty($strField)) {
                // 判断删除之前的文件
                $strFile = $request->post($strField);   // 旧的地址
                if (!empty($strFile) && file_exists('.' . $strFile)) unlink('.' . $strFile);

                // 初始化上次表单model对象，并定义好验证场景
                $model = new UploadForm(['scenario' => $strField]);

                try {
                    $objFile = $model->$strField = UploadedFile::getInstance($model, $strField);
                    $this->arrJson['errCode'] = 221;
                    if ($objFile) {
                        $isTrue = $model->validate();
                        $this->arrJson['errMsg'] = $model->getFirstError($strField);
                        if ($isTrue) {
                            // 定义好保存文件目录，目录不存在那么创建
                            $dirName = $this->strUploadPath;
                            if (!file_exists($dirName)) mkdir($dirName, 0777, true);
                            $this->arrJson['errCode'] = 202;
                            $this->arrJson['data'] = $dirName;
                            if (file_exists($dirName)) {
                                // 生成文件随机名
                                $strFilePath = $dirName . uniqid() . '.' . $objFile->extension;
                                $this->arrJson['errCode'] = 204;

                                // 执行文件上传保存，并且处理自己定义上传之后的处理
                                if ($objFile->saveAs($strFilePath)
                                    && $this->afterUpload($objFile, $strFilePath, $strField)
                                ) {
                                    $mixReturn = [
                                        'sFilePath' => trim($strFilePath, '.'),
                                        'sFileName' => $objFile->baseName . '.' . $objFile->extension,
                                    ];

                                    $this->handleJson($mixReturn);

                                    AdminLog::create(AdminLog::TYPE_UPLOAD, $mixReturn, $strField);
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    $this->handleJson([], 203, $e->getMessage());
                }
            }
        }

        return $this->returnJson();
    }

    /**
     * 导出数据的处理
     *
     * @return array
     */
    protected function getExportHandleParams()
    {
        return [];
    }

    /**
     * 文件导出处理
     *
     * @return mixed|string
     * @see where()
     * @see getQuery()
     * @see getExportHandleParams()
     */
    public function actionExport()
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            // 接收参数
            $arrFields = $request->post('fields');    // 字段信息
            $strTitle = $request->post('title');     // 标题信息
            $params = $request->post('params');       // 查询条件信息

            // 判断数据的有效性
            if ($arrFields && $strTitle) {
                $query = $this->getQuery(Helper::handleWhere($params, $this->where($params)));
                $query->orderBy([$this->sort => SORT_DESC]);

                // 数据导出
                Helper::excel($strTitle, $arrFields, $query, $this->getExportHandleParams());
            }
        }

        return $this->returnJson();
    }
}
