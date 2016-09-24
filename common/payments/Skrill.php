<?php
/**
 * Created by PhpStorm.
 * User: liujinxing
 * Date: 2016/8/8
 * Time: 11:56
 */

namespace common\payments;


class Skrill extends Payment
{
    // 获取支付地址
    public function getPaymentUrl()
    {
        $arrParams = func_get_args();
        if (count($arrParams) >= 3)
        {
            $arrOther = [];
            // 用户信息
            if ( !empty($arrParams[0])) foreach ($arrParams[0] as $key => $value) $arrOther[$key] = $value;

            // 订单信息
            if ( !empty($arrParams[1])) foreach ($arrParams[1] as $key => $value) $arrOther[$key] = $value;

            // 其他信息
            if ( !empty($arrParams[2])) foreach ($arrParams[2] as $key => $value) $arrOther[$key] = $value;

            $this->arrReturn['status'] = 1;
            $this->arrReturn['url']    = 'http://api.com/?'.http_build_query($arrOther);
        }

        return $this->arrReturn;
    }

    // 支付回调
    public function handlePaymentCallback()
    {
        // TODO: Implement handlePaymentCallback() method.
    }

    // 生成密钥
    public function createSign()
    {
        // TODO: Implement createSign() method.
    }

    // 验证密钥
    public function validateSign()
    {
        // TODO: Implement validateSign() method.
    }
}