<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use backend\models\Admin;
use common\models\UploadForm;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

/**
 * Class    PraiseController
 * @package backend\controllers
 * Desc     后台公共的控制器
 * User     liujx
 * Date     2016-4-8
 */
class Controller extends \yii\web\Controller
{
    public    $enableCsrfValidation = false, $admins = null;    // 'enableCsrfValidation' => true // 配置文件关闭CSRF
    protected $sort                 = 'id';                     // 默认排序字段

    // 定义响应请求的返回数据
    public $arrError = [
        'code'   => 201,
        'msg'    => '',
        'status' => 0,
        'data'   => [],
    ];

    // 初始化处理
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ]
                ]
            ],
        ];
    }

    // 权限验证
    public function beforeAction($action)
    {
        // 主控制器验证
        if ( ! parent::beforeAction($action)) {return false;}

        // 验证权限
        if( ! \Yii::$app->user->can($action->controller->id . '/' . $action->id) && Yii::$app->getErrorHandler()->exception === null)
        {
            // 没有权限AJAX返回
            if (Yii::$app->request->isAjax)
                exit(json_encode(['status' => 0, 'msg' => '对不起，您现在还没获得该操作的权限!', 'data' => [],]));
            else
                throw new \yii\web\UnauthorizedHttpException('对不起，您现在还没获得该操作的权限!');
        }

        // 查询导航栏信息
        $menus = Yii::$app->cache->get('navigation'.Yii::$app->user->id);
        if ( ! $menus) throw new \yii\web\UnauthorizedHttpException('对不起，您还没获得显示导航栏目权限!');

        // 注入变量信息
        Yii::$app->view->params['menus'] = $menus;
        return true;
    }

    // 初始化处理函数
    public function init()
    {
        parent::init();
        // 查询后台管理员信息
        $admin = Admin::find()->select(['id', 'username'])->all();
        $this->admins = ArrayHelper::map($admin, 'id', 'username');
        // 注入变量信息
        Yii::$app->view->params['admins'] = $this->admins;
        Yii::$app->view->params['user']   = Yii::$app->getUser()->identity;
    }

    // 首页显示
    public function actionIndex() { return $this->render('index'); }

    /**
     * where() 获取查询的配置信息(查询参数)
     * @access protected
     * @param  array $params 查询的请求参数
     * @return array 返回一个数组用来查询
     */
    protected function where($params)
    {
        return [];
    }

    // 处理查询信息
    protected function query()
    {
        $request = Yii::$app->request;
        $params  = $request->post('params');                    // 接收查询参数
        $sort    = $request->post('sSortDir_0', 'asc');         // 排序方式
        $sort    = $sort == 'asc' ? SORT_ASC : SORT_DESC;       // 排序方式

        // 接收参数
        $aWhere  = $this->where($params);                       // 查询配置信息
        $sFile   = isset($params['orderBy']) && ! empty($params['orderBy']) ? $params['orderBy'] : $this->sort; // 排序字段
        $aSearch = [
            'orderBy' => [$sFile => $sort],                     // 默认排序方式
            'where'   => [],                                    // 查询条件
            'offset'  => $request->post('iDisplayStart',  0),   // 查询开始位置
            'limit'   => $request->post('iDisplayLength', 10),  // 查询数据条数
            'echo'    => $request->post('sEcho',          1),   // 查询次数
        ];

        // 自定义了排序
        if ( ! empty($aWhere) && isset($aWhere['orderBy']) && ! empty($aWhere['orderBy']))
        {
            // 判断自定义排序字段还是方式
            $aSearch['orderBy'] = is_array($aWhere['orderBy']) ? $aSearch['orderBy'] : [$aSearch['orderBy'] => $sort];
            unset($aWhere['orderBy']);
        }

        // 处理默认查询条件
        if ( ! empty($aWhere) && isset($aWhere['where']) && ! empty($aWhere['where']))
        {
            $aSearch['where'] = array_merge($aSearch['where'], $aWhere['where']);
            unset($aWhere['where']);
        }

        // 处理其他查询条件
        if ( ! empty($aWhere) && ! empty($params))
        {
            foreach ($params as $key => $value)
            {
                if ( ! isset($aWhere[$key])) continue;
                $tmpKey = $aWhere[$key];
                $aSearch['where'][] = is_array($tmpKey) ? $tmpKey : [$tmpKey, $key, $value];
            }
        }

        // 添加查询条件
        if ( ! empty($aSearch['where'])) array_unshift($aSearch['where'], 'and');
        return $aSearch;
    }

    /**
     * afterSearch() 查询之后的数据处理函数
     * @access protected
     * @param  mixed $array 查询出来的数组对象
     * @return void  对数据进行处理
     */
    protected function afterSearch(&$array){}

    // 查询方法
    public function actionSearch()
    {
        // 定义请求数据
        if (Yii::$app->request->isAjax)
        {
            $arrSearch = $this->query();                          // 处理查询参数
            $objQuery  = $this->getModel()->find()->where($arrSearch['where']);

            // 查询之前的处理
            $objMod    = clone $objQuery;
            $intTotal  = $objMod->count();                        // 查询数据条数
            $arrObject = $objQuery->offset($arrSearch['offset'])->limit($arrSearch['limit'])->orderBy($arrSearch['orderBy'])->all();
            if ($arrObject) $this->afterSearch($arrObject);

            // 返回数据
            $this->arrError = [
                'code'  => 2,
                'other' => $objQuery->offset($arrSearch['offset'])->limit($arrSearch['limit'])->orderBy($arrSearch['orderBy'])->createCommand()->getRawSql(),
                'data'  => [
                    'sEcho'                => $arrSearch['echo'],     // 查询次数
                    'iTotalRecords'        => count($arrObject),      // 本次查询数据条数
                    'iTotalDisplayRecords' => $intTotal,              // 数据总条数
                    'aaData'               => $arrObject,             // 本次查询数据信息
                ]
            ];
        }

        return $this->returnAjax();
    }

    // 编辑修改
    public function actionUpdate()
    {
        $request = Yii::$app->request;
        if ($request->isAjax)
        {
            // 接收参数
            $type = $request->post('actionType'); // 操作类型
            $this->arrError['code'] = 207;
            if ($type)
            {
                $data   = $request->post();
                $model  = $this->getModel();
                $index  = $model->primaryKey();
                $isTrue = false;
                unset($data['actionType']);

                // 删除全部
                if ($type === 'deleteAll' && isset($data['ids']) && ! empty($data['ids']))
                {
                    // 判断是否有删除全部的权限
                    $this->arrError['code'] = 216;
                    if (Yii::$app->user->can(Yii::$app->controller->id.'/deleteAll'))
                    {
                        $isTrue = $model->deleteAll([$index[0] => explode(',', $data['ids'])]);
                    }
                }
                else
                {
                    // 修改和删除时的查询数据
                    if ($type == 'update' || $type == 'delete') $model = $model->findOne($data[$index[0]]);

                    // 删除数据
                    if ($type == 'delete')
                    {
                        $this->arrError['code'] = 206;
                        $isTrue = $model->delete();
                    }
                    else
                    {
                        // 新增数据
                        $this->arrError['code'] = 205;
                        $isTrue = $model->load(['params' => $data], 'params');
                        if ($isTrue)
                        {
                            $isTrue = $model->save();
                            $this->arrError['msg'] = $model->getErrorString();
                        }
                    }
                }

                // 判断是否成功
                if ($isTrue) $this->arrError['code'] = 0;
                $this->arrError['data'] = $model;

                // 记录日志
                $this->info('update', [
                    'action' => Yii::$app->controller->id.'/update',
                    'type'   => $type,
                    'data'   => $data,
                    'code'   => $this->arrError['code'],
                    'time'   => date('Y-m-d H:i:s')
                ]);
            }
        }

        // 返回数据
        return $this->returnAjax();
    }

    // 编辑修改
    public function actionEdit()
    {
        $request = Yii::$app->request;
        if ($request->isAjax)
        {
            // 接收参数
            $type = $request->post('actionType'); // 操作类型
            if ($type)
            {
                $data  = $request->post();
                unset($data['actionType']);
                $model = $this->getDetailModel();
                $index = $model->primaryKey();

                // 修改和删除时的查询数据
                if ($type == 'update' || $type == 'delete') $model = $model->findOne($data[$index[0]]);

                // 删除数据
                if ($type == 'delete')
                {
                    $isTrue = $model->delete();
                }
                else
                {
                    $isTrue = $model->load(['params' => $data], 'params');
                    $this->arrError['code'] = 205;
                    if ($isTrue)
                    {
                        $isTrue = $model->save();
                        $this->arrError['code'] = 206;
                        $this->arrError['msg']  = $model->getErrorString();
                    }
                }

                // 判断是否成功
                if ($isTrue) $this->arrError['code'] = 0;
            }
        }

        return $this->returnAjax();
    }

    // 行内编辑
    public function actionEditable()
    {
        $request = Yii::$app->request;
        if ($request->isAjax)
        {
            // 接收参数
            $mixPk    = $request->post('pk');    // 主键值
            $strAttr  = $request->post('name');  // 字段名
            $mixValue = $request->post('value'); // 字段值
            $this->arrError['code'] = 207;
            if ($mixPk && $strAttr  && $mixValue != '')
            {
                // 查询到数据
                $model = $this->getModel()->findOne($mixPk);
                $this->arrError['code'] = 220;
                if ($model)
                {
                    $model->$strAttr = $mixValue;
                    $this->arrError['code'] = 206;
                    if ($model->save())
                    {
                        $this->arrError['code'] = 0;
                        $this->arrError['data'] = $model;
                    }
                }
            }

            // 记录日志
            $this->info('update', [
                'action' => Yii::$app->controller->id.'/editable',
                'type'   => 'editable',
                'data'   => ['pk' => $mixPk, 'name' => $strAttr, 'value' => $mixValue],
                'code'   => $this->arrError['code'],
                'time'   => date('Y-m-d H:i:s')
            ]);
        }
        return $this->returnAjax();
    }

    // 查看详情信息
    public function actionViews()
    {
        $request = Yii::$app->request;
        if ($request->isAjax)
        {
            // 接收参数
            $id = $request->get('id');
            if ($id)
            {
                $this->arrError['code'] = 0;
                $this->arrError['data'] = $this->getDetailModel()->find()->where(['parent_id' => $id])->all();
            }
        }

        return $this->returnAjax();
    }

    /**
     * getUploadPath() 获取上传文件目录(默认是相对路径 ./public/uploads)
     * @access protected
     * @return string 返回上传文件的目录地址(相对于index.php文件的目录)
     */
    protected function getUploadPath()
    {
        return './public/uploads';
    }

    /**
     * afterUpload() 文件上传成功的处理信息
     * @access protected
     * @param  object $objFile     文件上传类
     * @param  string $strFilePath 文件保存路径
     * @param  string $strField    上传文件表单名
     * @return bool 上传成功返回true
     */
    public function afterUpload($objFile, &$strFilePath, $strField)
    {
        return true;
    }

    // 图片上传
    public function actionUpload()
    {
        // 定义请求数据
        $request = Yii::$app->request;
        if ($request->isPost)
        {
            // 接收参数
            $strField = $request->get('sField');    // 上传文件表单名称
            if ( ! empty($strField))
            {
                // 判断删除之前的文件
                $strFile  = $request->post($strField);   // 旧的地址
                if (! empty($strFile) && file_exists('.'.$strFile)) unlink('.'.$strFile);

                $model = new UploadForm();
                $model->scenario = $strField;
                try {
                    $objFile = $model->$strField = UploadedFile::getInstance($model, $strField);
                    $this->arrError['code'] = 221;
                    if ($objFile)
                    {
                        $isTrue = $model->validate();
                        $this->arrError['msg'] = $model->getFirstError($strField);
                        if ($isTrue)
                        {
                            // 创建目录
                            $dirName = $this->getUploadPath();
                            if ( ! file_exists($dirName)) mkdir($dirName, 0777, true);
                            $this->arrError['code'] = 202;
                            $this->arrError['data'] = $dirName;
                            if (file_exists($dirName))
                            {
                                // 生成文件随机名
                                $strFileName = uniqid() . '.';
                                $strFilePath = $dirName. $strFileName. $objFile->extension;
                                $this->arrError['code'] = 204;
                                if ($objFile->saveAs($strFilePath) && $this->afterUpload($objFile, $strFilePath, $strField))
                                {
                                    $this->arrError['code'] = 1;
                                    $this->arrError['data'] = [
                                        'sFilePath' => trim($strFilePath, '.'),
                                        'sFileName' => $objFile->baseName.'.'.$objFile->extension,
                                    ];
                                }
                            }
                        }
                    }

                } catch (\Exception $e) {
                    $this->arrError['code'] = 203;
                    $this->arrError['msg']  = $e->getMessage();
                }
            }
        }

        return $this->returnAjax();
    }

    /**
     * handleExport() 处理需要导出的数据显示问题
     * @param array $arrObject 查询到的对象数组
     */
    protected function handleExport(&$arrObject){}

    // 导出Excel文件
    public function actionExport()
    {
        $request = Yii::$app->request;
        if ($request->isPost)
        {
            // 接收参数
            $arrFields = $request->post('aFields');         // 字段信息
//            $intSize   = (int)$request->post('iSize');      // 查询数据条数
            $strTitle  = $request->post('sTitle');          // 标题信息

            // 判断数据的有效性
            if ($arrFields && $strTitle)
            {
                // 获取数据
                $arrKeys   = array_keys($arrFields);        // 所有的字段
                $arrSearch = $this->query();                // 处理查询参数
                $objArray  = $this->getModel()->find()->where($arrSearch['where'])->orderBy($arrSearch['orderBy'])->all();
                // var_dump($this->getModel()->find()->where($arrSearch['where'])->orderBy($arrSearch['orderBy'])->createCommand()->getRawSql());exit;

                // 判断数据是否存在
                $this->arrError['code'] = 220;
                if ($objArray)
                {
                    // 处理查询到的数据
                    $this->handleExport($objArray);

                    ob_end_clean();
                    ob_start();
                    $objPHPExcel = new \PHPExcel();
                    $objPHPExcel->getProperties()->setCreator("Liujx Admin")
                        ->setLastModifiedBy("Liujx Admin")
                        ->setTitle("Office 2007 XLSX Test Document")
                        ->setSubject("Office 2007 XLSX Test Document")
                        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                        ->setKeywords("office 2007 openxml php")
                        ->setCategory("Test result file");
                    $objPHPExcel->setActiveSheetIndex(0);

                    // 获取显示列的信息
                    $intLength = count($arrFields);
                    $arrLetter = range('A', 'Z');
                    if ($intLength > 26)
                    {
                        $arrLetters = array_slice($arrLetter, 0, $intLength - 26);
                        if ($arrLetters) foreach ($arrLetters as $value) array_push($arrLetter, 'A'.$value);
                    }

                    $arrLetter = array_slice($arrLetter, 0, $intLength);

                    // 确定第一行信息
                    foreach ($arrLetter as $key => $value)
                    {
                        $objPHPExcel->getActiveSheet()->setCellValue($value.'1', $arrFields[$arrKeys[$key]]);
                    }

                    // 写入数据信息
                    $intNum = 2;
                    foreach ($objArray as $value)
                    {
                        // 写入信息数据
                        foreach ($arrLetter as $intKey => $strValue)
                        {
                            $tmpAttribute = $arrKeys[$intKey];
                            $objPHPExcel->getActiveSheet()->setCellValue($strValue.$intNum, $value->$tmpAttribute);
                        }

                        $intNum ++;
                    }

                    // 设置sheet 标题信息
                    $objPHPExcel->getActiveSheet()->setTitle($strTitle);
                    $objPHPExcel->setActiveSheetIndex(0);

                    // 设置头信息
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment;filename="'.$strTitle.'.xlsx"');
                    header('Cache-Control: max-age=0');
                    header('Cache-Control: max-age=1');
                    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');           // Date in the past
                    header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');  // always modified
                    header('Cache-Control: cache, must-revalidate');            // HTTP/1.1
                    header('Pragma: public');                                   // HTTP/1.0

                    // 直接输出文件
                    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                    $objWriter->save('php://output');
                    Yii::$app->end();
                }
            }
        }

        return $this->returnAjax();
    }

    // ajax返回
    protected function returnAjax($array = '')
    {
        if (empty($array)) $array = $this->arrError;                    // 默认赋值
        if ( ! isset($array['msg']) || empty($array['msg']))
        {
            $errCode      = Yii::t('error', 'errorCode');
            $array['msg'] = $errCode[$array['code']];
        }

        if ($array['code'] <= 200) $array['status'] = 1;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;   // json 返回
        return $array;
    }

    // 获取model对象
    protected function getModel(){ return new Admin();}

    // 获取详情model对象
    protected function getDetailModel(){return new Admin();}

    /**
     * info() 记录日志信息(管理员操作日志)
     * @access protected
     * @param  string   $strFile   文件名(不包括后缀名)
     * @param  mixed    $data      日志信息
     * @param  bool     $isUseUser 是否使用管理员记录
     */
    protected function info($strFile, array $data, $isUseUser = true)
    {
        // 写入文件名
        if ($isUseUser) $strFile .= '_' . Yii::$app->user->id;
        $strFile .= '.log';

        // 写入目录
        $strPath = Yii::$app->basePath.'/runtime/logs/admin/';
        if ( ! file_exists($strPath)) mkdir($strPath, 0777, true);

        // 写入数据
        file_put_contents($strPath.$strFile, serialize($data) . "\n", FILE_APPEND);
    }

    /**
     * getInfo() 获取管理员的日志信息
     * @param string $strFile   日志文件名(不包含后缀名)
     * @param bool   $isUseUser 是否使用管理员ID
     * @param int    $intStart  读取开始位置
     * @param int    $intLength 读取数据条数
     * @return array 返回数组
     */
    protected function getInfo($strFile, $isUseUser = true)
    {
        // 读取文件名
        if ($isUseUser) $strFile .= '_' . Yii::$app->user->id;

        // 读取文件全路径
        $strPath = Yii::$app->basePath.'/runtime/logs/admin/'.$strFile.'.log';

        // 判读是否存在
        $array = [];
        if (file_exists($strPath))
        {
            $resFile = fopen($strPath, 'a+');
            if ($resFile)
            {
                while ( ! feof($resFile))
                {
                    $tmpStr = fgets($resFile);
                    if ($tmpStr) $array[] = unserialize($tmpStr);
                }

                // 只保存用户的200条记录
                $intCount = count($array);
                if ($intCount > 200)
                {
                    unlink($strPath);
                    $array = array_slice($array, $intCount-200);
                    $resFile = fopen($strPath, 'w+');
                    flock($resFile, LOCK_EX);
                    foreach ($array as $value) fwrite($resFile, serialize($value) . "\n");
                }

                fclose($resFile);
                krsort($array);
            }
        }

        return $array;
    }
}
