<?php

namespace backend\controllers;

use Yii;
use backend\models\Menu;
use yii\helpers\ArrayHelper;

/**
 * Class    MenuController
 * @package backend\controllers
 * Desc     导航栏目管理
 * User     liujx
 * Date     2016-4-18
 */
class MenuController extends Controller
{
    // 定义查询信息
    public function where($params)
    {
        return [
            'menu_name'   => 'like',
            'url'         => '=',
            'action_name' => 'like',
            'status'      => '=',
        ];
    }

    public function actionIndex()
    {
        // 查询父级分类信息
        $parents = Menu::find()->select(['id', 'menu_name'])->where(['status' => 1, 'pid' => 0])->indexBy('id')->all();
        $parents = ArrayHelper::map($parents, 'id', 'menu_name');
        $parents[0] = '顶级分类';
        return $this->render('index', ['parents' => json_encode($parents)]);
    }

    // 返回model
    public function getModel(){return new Menu();}
}
