<?php

namespace backend\controllers;

use backend\models\Menu;
use common\helpers\Tree;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class MenuController 导航栏目信息控制器
 * @package backend\controllers
 */
class MenuController extends Controller
{
    /**
     * @var string 定义使用的model
     */
    public $modelClass = 'backend\models\Menu';

    /**
     * 查询参数配置
     * @param array $params
     * @return array
     */
    public function where($params)
    {
        return [
            'id' => '=',
            'menu_name' => 'like',
            'url' => '=',
            'action_name' => 'like',
            'status' => '=',
            'pid' => '='
        ];
    }

    /**
     * 首页显示
     * @return string
     */
    public function actionIndex()
    {
        // 查询父级分类信息
        $parents = Menu::find()->select(['id', 'menu_name', 'pid'])->where([
            'status' => Menu::STATUS_ACTIVE,
        ])->indexBy('id')->asArray()->all();

        // 处理显示select
        $strOptions = (new Tree(['array' => $parents, 'parentIdName' => 'pid']))
            ->getTree(0, '<option value="{id}" data-pid="{pid}"> {extend_space}{menu_name} </option>');

        return $this->render('index', [
            'options' => $strOptions,
            'parents' => Json::encode(ArrayHelper::map($parents, 'id', 'menu_name'))
        ]);
    }

    /**
     * 处理导出显示数据
     * @return array
     */
    public function getExportHandleParams()
    {
        $array['created_at'] = $array['updated_at'] = function ($value) {
            return date('Y-m-d H:i:s', $value);
        };

        return $array;
    }
}
