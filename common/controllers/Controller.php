<?php

namespace common\controllers;

use Yii;

class Controller extends \yii\web\Controller
{
    // 定义 AJAX 响应请求的返回数据
    public $arrAjax = [
        'code'   => 201,
        'msg'    => '',
        'status' => 0,
        'data'   => [],
    ];

    /**
     * returnAjax() 响应ajax 返回
     * @param string $array
     * @return mixed|string
     */
    protected function returnAjax($array = null)
    {
        if ($array == null) $array = $this->arrAjax;                    // 默认赋值

        // 没有错误信息使用code 确定错误信息
        if ( ! isset($array['msg']) || empty($array['msg'])) {
            $errCode      = Yii::t('error', 'errorCode');
            $array['msg'] = $errCode[$array['code']];
        }

        // 返回成功状态通过 code 判断
        if ($array['code'] <= 200) $array['status'] = 1;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;   // json 返回
        return $array;
    }

    /**
     * info() 记录日志信息(管理员操作日志)
     * @access protected
     * @param  string   $strFile   文件名(不包括后缀名)
     * @param  mixed    $data      日志信息
     * @param  bool     $isUseUser 是否使用管理员记录
     */
    protected function info($strFile, array $data, $isUseUser = true)
    {
        // 写入文件名
        if ($isUseUser) $strFile .= '_' . Yii::$app->user->id;
        $strFile .= '.log';

        // 写入目录
        $strPath = Yii::$app->basePath.'/runtime/logs/admin/';
        if ( ! file_exists($strPath)) mkdir($strPath, 0777, true);

        // 写入数据
        file_put_contents($strPath.$strFile, serialize($data) . "\n", FILE_APPEND);
    }

    /**
     * getInfo() 获取管理员的日志信息
     * @param string $strFile   日志文件名(不包含后缀名)
     * @param bool   $isUseUser 是否使用管理员ID
     * @param int    $intStart  读取开始位置
     * @param int    $intLength 读取数据条数
     * @return array 返回数组
     */
    protected function getInfo($strFile, $isUseUser = true)
    {
        // 读取文件名
        if ($isUseUser) $strFile .= '_' . Yii::$app->user->id;

        // 读取文件全路径
        $strPath = Yii::$app->basePath.'/runtime/logs/admin/'.$strFile.'.log';

        // 判读是否存在
        $array = [];
        if (file_exists($strPath))
        {
            $resFile = fopen($strPath, 'a+');
            if ($resFile)
            {
                while ( ! feof($resFile))
                {
                    $tmpStr = fgets($resFile);
                    if ($tmpStr) $array[] = unserialize($tmpStr);
                }

                // 只保存用户的200条记录
                $intCount = count($array);
                if ($intCount > 200)
                {
                    unlink($strPath);
                    $array = array_slice($array, $intCount-200);
                    $resFile = fopen($strPath, 'w+');
                    flock($resFile, LOCK_EX);
                    foreach ($array as $value) fwrite($resFile, serialize($value) . "\n");
                }

                fclose($resFile);
                krsort($array);
            }
        }

        return $array;
    }
}