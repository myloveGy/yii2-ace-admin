<?php
/**
 * Created by PhpStorm.
 * Date: 2017/3/28
 * Time: 13:15
 */

namespace Admin\Strategy;


abstract class Strategy
{
    // 获取请求参数
    abstract function getRequest();

    // 返回数据
    abstract function handleResponse($array, $intTotal, $params = null);
}