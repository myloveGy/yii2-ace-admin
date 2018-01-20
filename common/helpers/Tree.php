<?php

namespace common\helpers;

use Closure;
use yii\base\Component;

/**
 * Class Tree 通用的树型类，可以生成任何树型结构
 * @package common\helpers
 */
class Tree extends Component
{
    /**
     * @var array 生成树型结构所需要的2维数组
     */
    public $array = [];

    /**
     * @var array 生成树型结构所需修饰符号，可以换成图片
     */
    public $icon = ['│', '├', '└'];

    /**
     * @var string 使用的空格字符串
     */
    public $space = "&nbsp;";

    /**
     * @var string 字段中父类字段名称
     */
    public $parentIdName = 'parent_id';

    /**
     * @var string 子类名称
     */
    public $childrenName = 'children';

    /**
     * @var string 数据信息
     */
    private $html = '';

    /**
     * 设置数据信息
     *
     * @param $array
     */
    public function setArray($array)
    {
        $this->array = $array;
        $this->html = '';
    }

    /**
     * 重新设置html信息
     */
    public function reset()
    {
        $this->html = '';
    }

    /**
     * 得到父级数组
     *
     * @param int $id
     * @return array|boolean
     */
    public function getParent($id)
    {
        if (!isset($this->array[$id])) {
            return false;
        }

        $arrReturn = [];
        $pid = $this->array[$id][$this->parentIdName];
        $pid = $this->array[$pid][$this->parentIdName];
        if (is_array($this->array)) {
            foreach ($this->array as $key => $value) {
                if ($value[$this->parentIdName] == $pid) {
                    $arrReturn[$key] = $value;
                }
            }
        }

        return $arrReturn;
    }

    /**
     * 得到子级数组
     *
     * @param int $id 父类ID
     * @return array
     */
    public function getChild($id)
    {
        $array = [];
        if (is_array($this->array)) {
            foreach ($this->array as $key => $value) {
                if ($value[$this->parentIdName] == $id) {
                    $array[$key] = $value;
                }
            }
        }

        return $array;
    }

    /**
     * 得到当前位置数组
     *
     * @param integer $id 当前的id
     * @param array $array
     * @return array|bool
     */
    public function getPosition($id, &$array)
    {
        if (!isset($this->array[$id])) {
            return false;
        }

        $arrReturn = [];
        $array[] = $this->array[$id];
        $pid = $this->array[$id][$this->parentIdName];
        if (isset($this->array[$pid])) {
            $this->getPosition($pid, $array);
        }

        if (is_array($array)) {
            krsort($array);
            foreach ($array as $v) {
                $arrReturn[$v['id']] = $v;
            }
        }

        return $arrReturn;
    }

    /**
     * 得到树型结构
     * @param integer $id 当前的ID
     * @param string|mixed $handle 处理模板
     * @param int $selectedId 默认选中的ID
     * @param string $prefix 添加前缀
     * @param string $strGroup
     * @return string
     */
    public function getTree($id, $handle, $selectedId = 0, $prefix = '', $strGroup = '')
    {
        $number = 1;
        // 一级栏目
        $child = $this->getChild($id);
        if (is_array($child)) {
            $total = count($child);

            foreach ($child as $key => $value) {
                // 处理分隔符信息
                $arrSeparator = $this->handleSeparator($number, $total, $prefix);
                // 追加信息
                $value['extend_space'] = $prefix ? $prefix . $arrSeparator['prefix'] : '';
                $value['extend_selected'] = $value['id'] == $selectedId ? 'selected' : '';
                // 确定使用的处理方式
                $strTemplate = $value[$this->parentIdName] == 0 && $strGroup ? $strGroup : $handle;
                // 建议使用函数数据
                $this->html .= $this->handleHtml($strTemplate, $value);
                $this->getTree(
                    $value['id'],
                    $handle,
                    $selectedId,
                    $prefix . $arrSeparator['suffix'] . $this->space,
                    $strGroup
                );

                $number++;
            }
        }

        return $this->html;
    }

    /**
     * 得到树型结构
     *
     * @param integer $id 当前的ID
     * @param string|mixed $handle 处理模板
     * @param int $selectedId 默认选中的ID
     * @param string $prefix 添加前缀
     * @return string
     */
    public function getTreeMulti($id, $handle, $selectedId = 0, $prefix = '')
    {
        $number = 1;
        $child = $this->getChild($id);
        if (is_array($child)) {
            $total = count($child);
            foreach ($child as $id => $a) {
                // 处理分隔符信息
                $arrSeparator = $this->handleSeparator($number, $total, $prefix);

                // 追加信息
                $a['extend_space'] = $prefix ? $prefix . $arrSeparator['prefix'] : '';
                $a['extend_selected'] = $this->have($selectedId, $id) ? 'selected' : '';
                $this->html .= $this->handleHtml($handle, $a);
                $this->getTreeMulti(
                    $a['id'],
                    $handle,
                    $selectedId,
                    $prefix . $arrSeparator['suffix'] . $this->space
                );

                $number ++;
            }
        }

        return $this->html;
    }

    /**
     * 处理分类信息
     *
     * @param integer $id 要查询的ID
     * @param string|Closure $handle 第一种模板
     * @param string|Closure $handle2 第二种模板
     * @param int|array|string $selectedId 选中的项目
     * @param string $prefix 前缀
     * @return mixed
     */
    public function getTreeCategory($id, $handle, $handle2, $selectedId = 0, $prefix = '')
    {
        $number = 1;
        $child = $this->getChild($id);
        if (is_array($child)) {
            $total = count($child);
            foreach ($child as $id => $a) {
                // 处理分隔符信息
                $arrSeparator = $this->handleSeparator($number, $total, $prefix);
                $a['extend_space'] = $prefix ? $prefix . $arrSeparator['prefix'] : '';
                $a['extend_selected'] = $this->have($selectedId, $id) ? 'selected' : '';
                $mixHandle = empty($a['html_disabled']) ? $handle : $handle2;
                $this->html .= $this->handleHtml($mixHandle, $a);
                $this->getTreeCategory(
                    $id,
                    $handle,
                    $handle2,
                    $selectedId,
                    $prefix . $arrSeparator['suffix'] . $this->space
                );

                $number ++;
            }
        }

        return $this->html;
    }


    /**
     * 生成树型结构数组
     * @param integer $id 表示获得这个ID下的所有子级
     * @param int $maxLevel $maxLevel 最大获取层级,默认不限制
     * @param int $level 当前层级,只在递归调用时使用,真实使用时不传入此参数
     * @return array
     */
    public function getTreeArray($id, $maxLevel = 0, $level = 1)
    {
        $returnArray = [];
        // 一级数组
        $children = $this->getChild($id);
        if (is_array($children)) {
            foreach ($children as $child) {
                $child['_level'] = $level;
                $returnArray[$child['id']] = $child;
                if ($maxLevel === 0 || ($maxLevel !== 0 && $maxLevel > $level)) {
                    $mLevel = $level + 1;
                    $returnArray[$child['id']][$this->childrenName] = $this->getTreeArray($child['id'], $maxLevel, $mLevel);
                }
            }
        }

        return $returnArray;
    }

    /**
     * 处理分隔符信息
     *
     * @param int $number
     * @param int $total
     * @param string $prefix
     * @return array
     */
    private function handleSeparator($number, $total, $prefix)
    {
        $arrReturn = ['prefix' => '', 'suffix' => ''];

        if ($number == $total) {
            $arrReturn['prefix'] = $this->icon[2];
        } else {
            $arrReturn['prefix'] = $this->icon[1];
            $arrReturn['suffix'] = $prefix ? $this->icon[0] : '';
        }

        return $arrReturn;
    }

    /**
     * 处理模板信息
     * @param string|Closure $handle
     * @param array $array 显示的数据
     * @return mixed
     */
    private function handleHtml($handle, $array)
    {
        // 建议使用函数数据
        if ($handle instanceof Closure) {
            return $handle($array);
        } else {
            // 换一种情况使用替换
            $arrKeys = $arrValue = [];
            if ($array) {
                foreach ($array as $key => $val) {
                    $arrKeys[] = '{' . $key . '}';
                    $arrValue[] = $val;
                }
            }

            return str_replace($arrKeys, $arrValue, $handle);
        }
    }

    /**
     * 是否存在数据
     *
     * @param array|string $list
     * @param mixed|integer $item
     * @return bool|int
     */
    private function have($list, $item)
    {
        if (is_array($list)) {
            return in_array($item, $list);
        } else {
            return (strpos(',,' . $list . ',', ',' . $item . ','));
        }
    }
}

