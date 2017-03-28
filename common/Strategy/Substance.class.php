<?php
/**
 * Created by PhpStorm.
 * Date: 2017/3/28
 * Time: 13:15
 */

namespace Admin\Strategy;


class Substance
{
    /**
     * 数据显示策略实例
     * @var
     */
    private static $strategy = null;

    // 不能被 new 出来
    private function __construct()
    {

    }

    /**
     * @param $type
     * @param array $config
     * @return null
     * @throws \Exception
     */
    public static function getInstance($type, array $config = [])
    {
        $className = '\\Admin\\Strategy\\'.ucfirst($type);
        if (class_exists($className)) {
            if (!self::$strategy) {
                self::$strategy = new $className($config);
            }

            return self::$strategy;
        }

        throw new \Exception($type . ' class no exists!');
    }
}