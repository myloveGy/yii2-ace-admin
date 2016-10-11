<?php

namespace common\helpers;

use yii\helpers\ArrayHelper;

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
     * @param $strClassName
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
}