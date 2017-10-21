<?php

namespace backend\models;

use Yii;
use yii\db\Expression;
use yii\helpers\Json;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%admin_log}}".
 *
 * @property integer $id
 * @property integer $type
 * @property string $controller
 * @property string $action
 * @property string $index
 * @property string $url
 * @property string $params
 * @property integer $created_id
 * @property integer $created_at
 */
class AdminLog extends ActiveRecord
{
    /**
     * 类型
     */
    const TYPE_CREATE = 1; // 创建
    const TYPE_UPDATE = 2; // 修改
    const TYPE_DELETE = 3; // 删除
    const TYPE_OTHER = 4;  // 其他
    const TYPE_UPLOAD = 5;  // 上传

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'created_id'], 'integer'],
            [['params'], 'string'],
            [['controller', 'action'], 'string', 'max' => 64],
            [['url', 'index'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '日志ID',
            'type' => '类型',
            'controller' => '操作控制器',
            'action' => '操作方法',
            'index' => '数据唯一标识',
            'url' => '操作的URL',
            'params' => '请求参数',
            'created_id' => '创建管理员ID',
            'created_at' => '创建时间',
        ];
    }

    /**
     * 获取类型说明
     * @param null $type
     * @return array|mixed|null
     */
    public static function getTypeDescription($type = null)
    {
        $mixReturn = [
            self::TYPE_CREATE => '创建',
            self::TYPE_CREATE => '创建',
            self::TYPE_UPDATE => '修改',
            self::TYPE_DELETE => '删除',
            self::TYPE_OTHER => '其他',
            self::TYPE_UPLOAD => '上传',
        ];

        if ($type !== null) {
            $mixReturn = isset($mixReturn[$type]) ? $mixReturn[$type] : null;
        }

        return $mixReturn;
    }

    /**
     * 创建日志
     * @param integer $type 类型
     * @param array $params 请求参数
     * @param string $index 数据唯一标识
     * @return bool
     */
    public static function create($type, $params = [], $index = '')
    {
        $log = new AdminLog();
        $log->type = $type;
        $log->params = Json::encode($params);
        $log->controller = Yii::$app->controller->id;
        $log->action = Yii::$app->controller->action->id;
        $log->url = Yii::$app->request->url;
        $log->index = $index;
        $log->created_id = Yii::$app->user->id;
        $log->created_at = new Expression('UNIX_TIMESTAMP()');
        return $log->save();
    }
}
