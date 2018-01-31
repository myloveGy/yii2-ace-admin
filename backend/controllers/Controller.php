<?php

namespace backend\controllers;

use backend\models\AdminLog;
use Yii;
use common\models\Admin;
use common\models\UploadForm;
use common\strategy\Substance;
use common\helpers\Helper;
use yii\db\Query;
use yii\helpers\FileHelper;
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
    protected $strUploadPath = './uploads/';

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
     * @throws \yii\web\BadRequestHttpException
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
                    Yii::$app->response->content = Json::encode($this->error(216));
                    return false;
                }

                throw new UnauthorizedHttpException('对不起，您现在还没获得该操作的权限!');
            }

            // 处理获取数据(默认不提前注入)
            if (!in_array($action->id, ['create', 'update', 'delete', 'delete-all', 'editable', 'upload', 'export'])) {
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

        return $this->success($strategy->handleResponse($array, $total));
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
        if (empty($data)) {
            return $this->error(201);
        }

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
        if (!$model->load($data, '')) {
            return $this->error(205);
        }

        // 判断修改返回数据
        if ($model->save()) {
            $this->handleJson($model);
            $pk = $this->pk;
            AdminLog::create(AdminLog::TYPE_CREATE, $data, $this->pk . '=' . $model->$pk);
            return $this->success($model);
        } else {
            return $this->error(1001, Helper::arrayToString($model->getErrors()));
        }
    }

    /**
     * 处理修改数据
     * @return mixed|string
     */
    public function actionUpdate()
    {
        // 接收参数判断
        $data = Yii::$app->request->post();
        $model = $this->findOne();
        if (!$model) {
            return $this->returnJson();
        }

        // 判断是否存在指定的验证场景，有则使用，没有默认
        $arrScenarios = $model->scenarios();
        if (isset($arrScenarios['update'])) {
            $model->scenario = 'update';
        }

        // 对model对象各个字段进行赋值
        if (!$model->load($data, '')) {
            return $this->error(205);
        }

        // 修改数据成功
        if ($model->save()) {
            AdminLog::create(AdminLog::TYPE_UPDATE, $data, $this->pk . '=' . $data[$this->pk]);
            return $this->success($model);
        } else {
            return $this->error(1003, Helper::arrayToString($model->getErrors()));
        }
    }

    /**
     * 处理删除数据
     * @return mixed|string
     */
    public function actionDelete()
    {
        // 接收参数判断
        $data = Yii::$app->request->post();
        $model = $this->findOne();
        if (!$model) {
            return $this->returnJson();
        }

        // 删除数据成功
        if ($model->delete()) {
            AdminLog::create(AdminLog::TYPE_DELETE, $data, $this->pk . '=' . $data[$this->pk]);
            return $this->success($model);
        } else {
            return $this->error(1004, Helper::arrayToString($model->getErrors()));
        }
    }

    /**
     * 查询单个数据
     *
     * @return boolean|\yii\db\ActiveRecord
     */
    private function findOne()
    {
        // 接收参数判断
        $data = Yii::$app->request->post();
        if (empty($data[$this->pk]) || !$data) {
            $this->setCode(201);
            return false;
        }

        // 通过传递过来的唯一主键值查询数据
        /* @var $model \yii\db\ActiveRecord */
        $model = $this->modelClass;
        $model = $model::findOne($data[$this->pk]);
        if (!$model) {
            $this->setCode(220);
            return false;
        }

        return $model;
    }

    /**
     * 批量删除操作
     * @return mixed|string
     */
    public function actionDeleteAll()
    {
        $ids = Yii::$app->request->post('id');
        if (empty($ids) || !($arrIds = explode(',', $ids))) {
            return $this->error(201);
        }

        /* @var $model \yii\db\ActiveRecord */
        $model = $this->modelClass;
        $where = [$this->pk => $arrIds];
        if ($model::deleteAll($where)) {
            AdminLog::create(AdminLog::TYPE_DELETE, $where, $this->pk . '=all');
            return $this->success($ids);
        } else {
            return $this->error(1004);
        }
    }

    /**
     * 处理行内编辑
     *
     * @return mixed|string
     */
    public function actionEditable()
    {
        // 接收参数
        $request = Yii::$app->request;
        $mixPk = $request->post('pk');    // 主键值
        $strAttr = $request->post('name');  // 字段名
        $mixValue = $request->post('value'); // 字段值

        // 第一步验证： 主键值、修改字段、修改的值不能为空字符串
        if (empty($mixPk) || empty($strAttr) || $mixValue === '') {
            return $this->error(207);
        }

        // 通过主键查询数据
        /* @var $model \yii\db\ActiveRecord */
        $model = $this->modelClass;
        $model = $model::findOne($mixPk);
        if (empty($model)) {
            return $this->error(220);
        }

        // 修改对应的字段
        $model->$strAttr = $mixValue;
        if ($model->save()) {
            AdminLog::create(AdminLog::TYPE_UPDATE, $request->post(), $this->pk . '=' . $mixPk);
            return $this->success($model);
        } else {
            return $this->error(206, Helper::arrayToString($model->getErrors()));
        }
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
        // 接收参数
        $request = Yii::$app->request;
        $strField = $request->get('sField');    // 上传文件表单名称
        if (empty($strField)) {
            return $this->error(201);
        }

        // 判断删除之前的文件
        $strFile = (string)$request->post($strField);   // 旧的地址
        if (!empty($strFile) && file_exists('.' . $strFile)) unlink('.' . $strFile);

        // 初始化上次表单model对象，并定义好验证场景
        $model = new UploadForm(['scenario' => $strField]);

        try {
            // 上传文件
            $objFile = $model->$strField = UploadedFile::getInstance($model, $strField);
            if (empty($objFile)) {
                throw new \UnexpectedValueException('没有文件上传');
            }

            // 验证
            if (!$model->validate()) {
                throw new \UnexpectedValueException($model->getFirstError($strField));
            }

            // 定义好保存文件目录，目录不存在那么创建
            $dirName = $this->strUploadPath;
            FileHelper::createDirectory($dirName);
            if (!file_exists($dirName)) {
                throw new \UnexpectedValueException('目录创建失败:' . $dirName);
            }

            // 生成文件随机名
            $strFilePath = $dirName . uniqid() . '.' . $objFile->extension;
            // 执行文件上传保存，并且处理自己定义上传之后的处理
            if ($objFile->saveAs($strFilePath) && $this->afterUpload($objFile, $strFilePath, $strField)) {
                $mixReturn = [
                    'sFilePath' => trim($strFilePath, '.'),
                    'sFileName' => $objFile->baseName . '.' . $objFile->extension,
                ];

                $this->handleJson($mixReturn);

                AdminLog::create(AdminLog::TYPE_UPLOAD, $mixReturn, $strField);
            } else {
                $this->setCode(204);
            }

            return $this->returnJson();
        } catch (\Exception $e) {
            return $this->error(203, $e->getMessage());
        }
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
        // 接收参数
        $request = Yii::$app->request;
        $arrFields = $request->post('fields');    // 字段信息
        $strTitle = $request->post('title');     // 标题信息
        $params = $request->post('params');       // 查询条件信息

        // 判断数据的有效性
        if (empty($arrFields) || empty($strTitle)) {
            return $this->error(201);
        }

        $query = $this->getQuery(Helper::handleWhere($params, $this->where($params)));
        $query->orderBy([$this->sort => SORT_DESC]);

        // 数据导出
        return Helper::excel($strTitle, $arrFields, $query, $this->getExportHandleParams());
    }
}
