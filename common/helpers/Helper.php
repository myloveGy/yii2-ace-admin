<?php

namespace common\helpers;

use yii\helpers\ArrayHelper;

/**
 * Class Helper
 * 辅助处理类，一般用来定义公共方法
 * @author  liujx
 * @package common\helpers
 */
class Helper
{
    /**
     * map() 使用ArrayHelper 处理数组, 并添加其他信息
     * @param  mixed  $array 需要处理的数据
     * @param  string $id    键名
     * @param  string $name  键值
     * @param  array $params 其他数据
     * @return array
     */
    public static function map($array, $id, $name, $params = ['请选择'])
    {
        $array = ArrayHelper::map($array, $id, $name);
        if ($params) {
            foreach ($params as $key => $value) $array[$key] = $value;
        }

        ksort($array);
        return $array;
    }

    /**
     * getIpAddress() 获取IP地址
     * @return string 返回字符串
     */
    public static function getIpAddress()
    {
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $strIpAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $strIpAddress = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                $strIpAddress = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $strIpAddress = getenv('HTTP_X_FORWARDED_FOR');
            } else if (getenv('HTTP_CLIENT_IP')) {
                $strIpAddress = getenv('HTTP_CLIENT_IP');
            } else {
                $strIpAddress = getenv('REMOTE_ADDR') ? getenv('REMOTE_ADDR') : '';
            }
        }

        return $strIpAddress;
    }

    /**
     * create() 根据类名和命名空间创建对象
     * @param string $strClassName
     * @param string $namespace
     * @return null
     */
    public static function create($strClassName, $namespace = 'payments')
    {
        $objReturn = null;
        if ($strClassName) {
            $strClassName = '\\common\\'.$namespace.'\\'.ucfirst(trim($strClassName));
            if (class_exists($strClassName)) $objReturn = new $strClassName;
        }

        return $objReturn;
    }

    /**
     * 处理通过请求参数对应yii2 where 查询条件
     * @param array $params 请求参数数组
     * @param array $where  定义查询处理方式数组
     * @param string $join  默认查询方式是and
     * @return array
     */
    public static function handleWhere($params, $where, $join = 'and')
    {
        $arrReturn = [];
        if ($where) {
            // 处理默认查询条件
            if (isset($where['where']) && !empty($where['where'])) {
                $arrReturn = $where['where'];
                unset($where['where']);
            }

            // 处理其他查询
            if ($where && $params) {
                foreach ($where as $key => $value) {
                    // 判断不能查询请求的数据不能为空
                    if (isset($params[$key]) && $params[$key] !== '') {
                        // 根据定义的查询类型处理查询
                        switch (gettype($value)) {
                            case 'string':  // 字符串
                                $arrReturn[] = [$value, $key, $params[$key]];
                                break;
                            case 'array':   // 数组
                                // 处理函数
                                if (isset($value['func']) && function_exists($value['func'])) {
                                    $params[$key] = $value['func']($params[$key]);
                                }

                                // 对应字段
                                if (empty($value['field'])) $value['field'] = $key;

                                // 查询连接类型
                                if (empty($value['and'])) $value['and'] = '=';

                                $arrReturn[] = [$value['and'], $value['field'], $params[$key]];
                                break;
                            case 'object':  // 匿名函数类型
                                $arrReturn[] = $value($params[$key]);
                                break;
                            default:
                                $arrReturn[] = ['=', $key, $params[$key]];
                        }
                    }
                }
            }

            // 存在查询条件，数组前面添加 连接类型
            if ($arrReturn) array_unshift($arrReturn, $join);
        }

        return $arrReturn;
    }

    /**
     * 将一个多维数组连接为一个字符串
     * @param  array $array 数组
     * @return string
     */
    public static function arrayToString($array)
    {
        $str = '';
        if (!empty($array)) {
            foreach ($array as $value) {
                $str .= is_array($value) ? implode('', $value) : $value;
            }
        }

        return $str;
    }
}