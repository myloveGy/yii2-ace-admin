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
