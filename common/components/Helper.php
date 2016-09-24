<?php

namespace common\components;

class Helper
{
    /**
     * create() 根据类名和命名空间创建对象
     * @param $strClassName
     * @param string $namespace
     * @return null
     */
    public static function create($strClassName, $namespace = 'payments')
    {
        $objReturn    = null;
        $strClassName = __NAMESPACE__.'\\'.$namespace.'\\'.ucfirst($strClassName);
        if (class_exists($strClassName)) $objReturn = new $strClassName;
        return $objReturn;
    }
}