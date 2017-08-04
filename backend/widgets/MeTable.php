<?php

namespace backend\widgets;

use backend\assets\AdminAsset;
use yii\helpers\Html;
use yii\base\Widget;

/**
 * Class MeTable meTables 的小部件信息
 * @package backend\widgets
 */
class MeTable extends  Widget
{
    /**
     * @var array 按钮的配置
     */
    public $buttons = [];

    /**
     * @var array 表格的配置
     */
    public $table = [];

    /**
     * @var string 按钮容器目标
     */
    public $buttonsTemplate = '<p {options}></p>';

    /**
     * @var string 表格目标
     */
    public $tableTemplate = '<table {options}></table>';

    /**
     * @var array 定义表格默认的配置信息
     */
    private $defaultOptions = [
        'class' => 'table table-striped table-bordered table-hover',
        'id' => 'show-table'
    ];

    /**
     * @var array 默认按钮的配置
     */
    private $defaultButtons = [
        'id' => 'me-table-buttons',
    ];

    public function init()
    {
        parent::init();

        // 默认表格配置覆盖
        if ($this->table) {
            $this->defaultOptions = array_merge($this->defaultOptions, $this->table);
        }

        // 默认按钮配置覆盖
        if ($this->buttons) {
            $this->defaultButtons = array_merge($this->defaultButtons, $this->buttons);
        }

    }

    public function run()
    {
        $view = $this->getView();
        AdminAsset::meTablesRegister($view);

        return str_replace('{options}', Html::renderTagAttributes($this->defaultButtons), $this->buttonsTemplate).
            str_replace('{options}', Html::renderTagAttributes($this->defaultOptions), $this->tableTemplate);
    }
}