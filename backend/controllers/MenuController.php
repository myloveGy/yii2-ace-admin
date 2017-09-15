<?php

namespace backend\controllers;

use backend\models\Menu;
use common\helpers\Helper;
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
        ];
    }

    /**
     * 首页显示
     * @return string
     */
    public function actionIndex()
    {
        // 查询父级分类信息
        $parents = Menu::find()->select(['id', 'menu_name'])->where([
            'status' => Menu::STATUS_ACTIVE,
            'pid' => 0
        ])->indexBy('id')->asArray()->all();

        return $this->render('index', [
            'parents' => Json::encode(Helper::map($parents, 'id', 'menu_name', ['顶级分类']))
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
