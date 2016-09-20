<?php
/**
 * file: ArrangeController.php
 * desc: 管理员日程安排 执行操作控制器
 * user: liujx
 * date: 2016-09-19 14:39:17
 */

// 引入命名空间
namespace backend\controllers;

use Yii;
use backend\models\Arrange;

class ArrangeController extends Controller
{
    // 查询方法
    public function where($params)
    {
        return [
            'id'       => '=',
			'title'    => 'like',
			'status'   => '=',
            'admin_id' => '='
        ];
    }

    // 首页显示
    public function actionIndex()
    {
        return $this->render('index', [
            'status'       => Arrange::getStatus(),         // 状态
            'timeStatus'   => Arrange::getTimeStatus(),     // 时间状态
            'statusColors' => Arrange::getStatusColors(),   // 状态颜色
            'timeColors'   => Arrange::getTimeColors(),     // 时间状态颜色
        ]);
    }

    // 管理日程
    public function actionCalendar()
    {
        // 查询管理员的日程
        $arrUserArrange = Arrange::findAll(['admin_id' => Yii::$app->user->id]);

        // 查询没有委派的信息
        $arrArrange    = Arrange::find()->where(['status' => Arrange::STATUS_PENDING, 'admin_id' => 0])->orderBy(['time_status' => SORT_DESC])->all();

        // 载入视图
        return $this->render('calendar', [
            'userArrange'  => $arrUserArrange,
            'arrange'      => $arrArrange,
            'statusColors' => Arrange::getStatusColors(),   // 状态颜色
            'timeColors'   => Arrange::getTimeColors(),     // 时间状态颜色
        ]);
    }

    // 返回 Modal
    public function getModel(){return new Arrange();}
}
