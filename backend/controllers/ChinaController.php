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
    public $strategy = 'JqGrid';

    /**
     * @var string 定义使用的model
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
            'name' => function($value) {
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
        $china = China::find()->where(['pid' => 0])->asArray()->all();

        // 加载视图
        return $this->render('grid', [
            'parent' => ArrayHelper::map($china, 'id', 'name'),
        ]);
    }
}
