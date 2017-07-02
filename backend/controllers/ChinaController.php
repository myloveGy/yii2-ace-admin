<?php
namespace backend\controllers;

use common\models\China;
use yii\helpers\ArrayHelper;

/**
 * Class ChinaController
 * @package backend\controllers
 */
class ChinaController extends Controller
{
    /**
     * 定义使用的model
     * @var string
     */
    public $modelClass = '\common\models\China';

    /**
     * where() 查询参数配置
     * @param array $params
     * @return array
     */
    public function where($params)
    {
        return [
            'id' => ['and' => '=', 'func' => 'intval'],
            'name' => function($key, $value) {
                return ['like', 'name', trim($value)];
            },
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
}
