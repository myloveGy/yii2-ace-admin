<?php

namespace backend\controllers;

use backend\models\Menu;
use common\helpers\Helper;
use common\helpers\Tree;
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
        $parents = Menu::find()->select(['id', 'menu_name', 'pid'])->where([
            'status' => Menu::STATUS_ACTIVE,
        ])->indexBy('id')->asArray()->all();
        $tree = new Tree(['array' => $parents, 'parentIdName' => 'pid']);
        $menus = $tree->getTreeArray(0, 2, 1);
        $strOptions = '';
        foreach ($menus as $value) {
            if (!empty($value['children'])) {
                $strOptions .= '<optgroup label="' . $value['menu_name'] . '">';
            }

            $strOptions .= '<option value="' . $value['id'] . '"> ' . $value['menu_name'] . ' </option>';
            if (!empty($value['children'])) {
                foreach ($value['children'] as $val) {
                    $strOptions .= '<option value="' . $val['id'] . '" pid="' . $val['pid'] . '"> &nbsp;&nbsp;&nbsp;├─ ' . $val['menu_name'] . ' </option>';
                }

                $strOptions .= '</optgroup>';
            }
        }

        return $this->render('index', [
            'options' => $strOptions,
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
