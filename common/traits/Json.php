<?php
/**
 * Created by PhpStorm.
 * User: liujinxing
 * Date: 2017/7/1
 * Time: 22:09
 */

namespace common\traits;

use yii;

/**
 * Trait Json
 * @author liujx
 * @package common\traits
 */
trait Json
{
    /**
     * 定义返回json的数据
     * @var array
     */
    protected $arrJson = [
        'errCode' => 201,
        'errMsg'  => '',
        'data'    => [],
    ];

    /**
     * 响应ajax 返回
     * @param string $array    其他返回参数(默认null)
     * @return mixed|string
     */
    protected function returnJson($array = null)
    {
        // 判断是否覆盖之前的值
        if ($array) $this->arrJson = array_merge($this->arrJson, $array);

        // 没有错误信息使用code 确定错误信息
        if (empty($this->arrJson['errMsg'])) {
            $errCode = Yii::t('error', 'errCode');
            $this->arrJson['errMsg'] = $errCode[$this->arrJson['errCode']];
        }

        // 设置JSON返回
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->arrJson;
    }

    /**
     * handleJson() 处理返回数据
     * @param mixed $data     返回数据
     * @param integer   $errCode  返回状态码
     * @param null  $errMsg   提示信息
     */
    protected function handleJson($data, $errCode = 0, $errMsg = null)
    {
        $this->arrJson['errCode'] = $errCode;
        $this->arrJson['data']    = $data;
        if ($errMsg !== null) {
            $this->arrJson['errMsg'] = $errMsg;
        }
    }
}