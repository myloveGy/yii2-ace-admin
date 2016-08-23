<?php
// 定义命名空间
namespace common\components\payments;

/**
 * Class Payment
 * Desc  支付处理抽象类
 * Date  2016-08-08
 * @package common\components\payment
 */
abstract class Payment
{
    // 获取支付地址的返回信息
    public $arrReturn = [
        'status' => 0,
        'msg'    => 'There is an error in the request parameter',
        'url'    => '',
        'type'   => 'url',
    ];

    /**
     * getPaymentUrl() 获取支付地址信息
     * @access public
     * @param  可变参数
     * @return array 要求返回一个数组
     * [
     *      'status' => 1,                  // 0 表示失败 1 表示成功
     *      'msg'    => 'success',          // 存在错误提示错误信息
     *      'url'    => 'http://api.com',   // 页面信息或者链接地址URL
     *      'type'   => 'html'              // html 表示页面, 需要加载出来， 'url' 表示是一个连接，直接跳转
     * ]
     */
    abstract public function getPaymentUrl();

    /**
     * handlePaymentCallback() 支付完成回调地址()
     * @access public
     * @param  可变参数
     * @return void 根据各个支付处理
     */
    abstract public function handlePaymentCallback();

    // 生成密钥(请求支付)
    abstract public function createSign();

    // 验证密钥(回调验证)
    abstract public function validateSign();

    /**
     * getSign() 获取密钥连接字符串
     * @access public
     * @param  array  $arrParams 需要处理的参数数组
     * @param  string $strAnd    键值对连接符(默认=)
     * @return string 返回字符串
     */
    public function getSign(array $arrParams, $strAnd = '')
    {
        $strSign = '';
        ksort($arrParams);                                                          // 排序数组
        foreach ($arrParams as $key => $value) $strSign .= $key . $strAnd . $value; // 连接字符串
        return $strSign;                                                            // 返回
    }
}