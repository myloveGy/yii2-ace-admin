<?php

namespace backend\widgets;

use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


/**
 * Class Nav
 * @package backend\widgets
 */
class Nav extends Widget
{
    /**
     * @var array list of items in the nav widget. Each array element represents a single
     * menu item which can be either a string or an array with the following structure:
     *
     * - label|$this->labelName: string, required, the nav item label.
     * - url: optional, the item's URL. Defaults to "#".
     * - visible: boolean, optional, whether this menu item is visible. Defaults to true.
     * - linkOptions: array, optional, the HTML attributes of the item's link.
     * - options: array, optional, the HTML attributes of the item container (LI).
     * - items|$this->itemsName: array|string, optional, the configuration array for creating a [[Dropdown]] widget,
     *   or a string representing the dropdown menu. Note that Bootstrap does not support sub-dropdown menus.
     * - encode: boolean, optional, whether the label will be HTML-encoded. If set, supersedes the $encodeLabels option for only this item.
     *
     * If a menu item is a string, it will be rendered directly without HTML encoding.
     */
    public $items = [];

    /**
     * @var array  定义配置选项
     *
     * - id: 定义ul 的ID
     * - class: 定义ul 的class
     */
    public $options = [];

    /**
     * @var string 下拉图标的显示
     */
    public $dropDownCaret = '<b class="arrow fa fa-angle-down"></b>';

    /**
     * @var bool 是否需要内容转义
     */
    public $encodeLabels = true;

    /**
     * @var string 内容标签的名称
     */
    public $labelName = 'label';

    /**
     * @var string 子类数组名称
     */
    public $itemsName = 'items';

    /**
     * @return string
     * @throws InvalidConfigException
     * @throws \Exception
     */
    public function run()
    {
        return $this->renderItems();
    }

    /**
     * @return string
     * @throws InvalidConfigException
     * @throws \Exception
     */
    public function renderItems()
    {
        $items = [];
        foreach ($this->items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                continue;
            }

            $items[] = $this->renderItem($item);
        }

        return Html::tag('ul', implode("\n", $items), $this->options);
    }

    /**
     * Renders a widget's item.
     * @param string|array $item the item to render.
     * @param bool $isRenderSpan
     * @return string
     * @throws InvalidConfigException
     * @throws \Exception
     */
    public function renderItem($item, $isRenderSpan = true)
    {
        if (is_string($item)) {
            return $item;
        }

        if (!isset($item[$this->labelName])) {
            throw new InvalidConfigException("The '{$this->labelName}' option is required.");
        }

        $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
        $label = $encodeLabel ? Html::encode($item[$this->labelName]) : $item[$this->labelName];
        $icons = ArrayHelper::getValue($item, 'icons');
        $a = $icons ? Html::tag('i', '', ['class' => $icons]) : '';
        $a .= $isRenderSpan ? Html::tag('span', $label, ['class' => 'menu-text']) : $label;
        $options = ArrayHelper::getValue($item, 'options', []);
        $items = ArrayHelper::getValue($item, $this->itemsName);
        $url = ArrayHelper::getValue($item, 'url', '#');
        $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);
        $id = ArrayHelper::getValue($item, 'id');
        if ($id) {
            $linkOptions['data-id'] = $id;
        }

        if (empty($items)) {
            $items = '';
        } else {
            Html::addCssClass($linkOptions, ['dropdown-toggle']);
            if ($this->dropDownCaret !== '') {
                $a .= ' ' . $this->dropDownCaret;
            }

            if (is_array($items)) {
                $items = '<b class="arrow"></b>' . $this->renderDropdown($items);
            }
        }

        return Html::tag('li', Html::a($a, $url ? $url : '#', $linkOptions) . $items, $options);
    }

    /**
     * @param $items
     * @return string
     * @throws InvalidConfigException
     * @throws \Exception
     */
    protected function renderDropdown($items)
    {
        $html = '';
        foreach ($items as $item) {
            $html .= $this->renderItem($item, false);
        }

        return html::tag('ul', $html, ['class' => 'submenu']);
    }
}