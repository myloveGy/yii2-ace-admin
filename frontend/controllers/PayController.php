<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\components\Helper;

class PayController extends Controller
{
    // 获取支付页面
    public function actionPayment()
    {
        $request = Yii::$app->request;
        $strGameName = $request->get('gameName'); // 对应游戏
        $strPayType  = $request->get('payType');  // 对应支付方式

        // 验证数据的有效性
        if ($strPayType && $strGameName)
        {
            $objPayment = Helper::create($strPayType);
            var_dump($objPayment->getPaymentUrl(['user_id' => 1], ['order_id' => 123], ['params' => 1, 'auth' => 1]));
        }
    }
}
