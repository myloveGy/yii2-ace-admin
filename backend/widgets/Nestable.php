<?php

namespace backend\widgets;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Nestable extends Widget
{
    /**
     * @var array 定义数据来源
     */
    public $items = [];

    /**
     * @var array  定义配置选项
     * - class: 定义ul 的class
     */
    public $options = [
        'class' => 'dd-list'
    ];

    /**
     * @var string 子类数组名称
     */
    public $itemsName = 'items';

    /**
     * @var string 定义名称字段
     */
    public $labelName = 'name';

    /**
     * @return string
     */
    public function run()
    {
        return $this->renderItems($this->items);
    }

    /**
     * @param array $items 数据信息
     * @return string
     */
    private function renderItems($items)
    {
        $arrItems = [];
        foreach ($items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                continue;
            }

            $arrItems[] = $this->renderItem($item);
        }

        return Html::tag('ol', implode("\n", $arrItems), $this->options);
    }

    /**
     * Renders a widget's item.
     * @param string|array $item the item to render.
     * @return string
     */
    private function renderItem($item)
    {
        $html = '<div class="dd-handle">' . ArrayHelper::getValue($item, $this->labelName) . '</div>';
        $items = ArrayHelper::getValue($item, $this->itemsName);
        $options = ArrayHelper::getValue($item, 'options', []);
        Html::addCssClass($options, 'dd-item');
        if (!empty($items)) {
            Html::addCssClass($options, 'item-red');
            $html .= $this->renderItems($items);
        }

        return Html::tag('li', $html, $options);
    }
}