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
    // 查询方法
    public function where($params)
    {
        return [
            'name' => 'like',
            'pid'  => '='
        ];
    }

    // 首页显示
    public function actionIndex()
    {
        // 加载视图
        return $this->render('index', [
            'parent' => ArrayHelper::map(China::find()->where(['pid' => 0])->all(), 'id', 'name'),
        ]);
    }

    // 返回 Modal
    public function getModel(){return new China();}
}
