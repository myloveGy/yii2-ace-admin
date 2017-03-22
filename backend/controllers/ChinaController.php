<?php
/**
 * file: ChinaController.php
 * desc: 地址信息 执行操作控制器
 * user: liujx
 * date: 2016-07-25 10:55:43
 */

// 引入命名空间
namespace backend\controllers;

use common\models\China;
use yii\helpers\ArrayHelper;

class ChinaController extends Controller
{
    /**
     * where() 查询参数配置
     * @param array $params
     * @return array
     */
    public function where($params)
    {
        return [
            'name' => 'like',
            'pid'  => '='
        ];
    }

    public function actionChild()
    {
        $request = \Yii::$app->request;
        $array = China::find()->where(['pid' => $request->post('id')])->all();
        $this->arrJson = [
            'errCode' => 0,
            'other'   => China::find()->where(['pid' => \Yii::$app->request->post('id')])->createCommand()->getRawSql(),
            'data'    => [
                'sEcho'                => $request->post('echo'),  // 查询次数
                'iTotalRecords'        => count($array),    // 本次查询数据条数
                'iTotalDisplayRecords' => count($array),           // 数据总条数
                'aaData'               => $array,           // 本次查询数据信息
            ]
        ];
        return $this->returnJson();
    }

    /**
     * actionIndex() 首页显示
     * @return string
     */
    public function actionIndex()
    {
        // 加载视图
        return $this->render('index', [
            'parent' => ArrayHelper::map(China::find()->where(['pid' => 0])->all(), 'id', 'name'),
        ]);
    }

    /**
     * getModel() 获取model
     * @return China
     */
    public function getModel()
    {
        return new China();
    }
}
