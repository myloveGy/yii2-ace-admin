<?php
/**
 * Created by PhpStorm.
 * User: liujinxing
 * Date: 2017/7/1
 * Time: 22:09
 */

namespace common\traits;

use yii;
use \yii\web\Response;

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
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->arrJson;
    }

    /**
     * handleJson() 处理返回数据
     * @param mixed $data     返回数据
     * @param integer   $errCode  返回状态码
     * @param string  $errMsg   提示信息
     */
    protected function handleJson($data, $errCode = 0, $errMsg = '')
    {
        $this->arrJson['errCode'] = $errCode;
        $this->arrJson['data']    = $data;
        $this->arrJson['errMsg'] = $errMsg;
    }

    /**
     * 处理成功返回
     *
     * @param mixed $data 返回结果信息
     * @param string $message
     * @return mixed|string
     */
    protected function success($data = [], $message = '')
    {
        return $this->returnJson([
            'errCode' => 0,
            'errMsg' => $message,
            'data' => $data
        ]);
    }

    /**
     * 处理错误返回
     *
     * @param integer $code 错误码
     * @param string $message
     * @return mixed|string
     */
    protected function error($code = 201, $message = '')
    {
        return $this->returnJson([
            'errCode' => $code,
            'errMsg' => $message,
        ]);
    }

    /**
     * 设置错误码
     *
     * @param int $errCode
     */
    public function setCode($errCode = 201)
    {
        $this->arrJson['errCode'] = $errCode;
    }

    /**
     * 设置错误信息
     *
     * @param string $message
     */
    public function setMessage($message = '')
    {
        $this->arrJson['errMsg'] = $message;
    }
}