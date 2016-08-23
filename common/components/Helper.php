<?php
// 定义命名空间
namespace common\components;

/**
 * Created by PhpStorm.
 * User: liujinxing
 * Date: 2016/8/8
 * Time: 11:58
 */
class Helper
{
    public static function create($strClassName, $namespace = 'payments')
    {
        $objReturn    = null;
        $strClassName = __NAMESPACE__.'\\'.$namespace.'\\'.ucfirst($strClassName);
        if (class_exists($strClassName)) $objReturn = new $strClassName;
        return $objReturn;
    }
}