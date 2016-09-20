<?php

namespace backend\models;

use Yii;
use common\models\AdminModel;

/**
 * This is the model class for table "{{%menu}}".
 *
 * @property integer $id
 * @property string  $pid
 * @property string  $menu_name
 * @property string  $icons
 * @property string  $url
 * @property integer $status
 * @property integer $sort
 * @property integer $created_at
 * @property integer $created_id
 * @property integer $updated_at
 * @property integer $updated_id
 */
class Menu extends AdminModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pid', 'status', 'sort'], 'integer'],
            [['menu_name', 'status'], 'required'],
            [['menu_name', 'icons', 'url'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'pid'        => '上级分类',
            'menu_name'  => '栏目名称',
            'icons'      => '图标',
            'url'        => '访问地址',
            'status'     => '状态',
            'sort'       => '排序字段',
            'created_at' => '创建时间',
            'created_id' => '创建用户',
            'updated_at' => '修改时间',
            'updated_id' => '修改用户',
        ];
    }

    // 修改之后
    public function afterSave($insert, $changedAttributes)
    {
        self::setNavigation();
        return true;
    }

    // 设置缓存数据信息
    public static function setNavigation()
    {
        $menus = $navigation =  [];                              // 初始化定义导航栏信息
        $uid   = Yii::$app->user->id;                            // 用户ID
        $sort  = ['sort' => SORT_ASC];                           // 排序条件
        $field = ['id', 'pid', 'menu_name', 'icons', 'url'];     // 查询字段信息
        $index = 'navigation'.$uid;

        // 管理员登录
        if ($uid == 1)
            $menus = self::find()->select($field)->where(['status' => 1])->orderBy($sort)->asArray()->all();
        else
        {
            // 其他用户登录成功获取权限
            $arrAuth = Yii::$app->getAuthManager()->getPermissionsByUser($uid);
            if ($arrAuth)
            {
                $menus = self::find()->select($field)->where(['status' => 1, 'url' => array_keys($arrAuth)])->orderBy($sort)->indexBy('id')->asArray()->all();
                // 有导航栏信息
                if ($menus)
                {
                    $parent = [];
                    // 获取父类信息
                    foreach ($menus as $key => $value){ if ($value['pid'] != 0) $parent[] = $value['pid'];}
                    // 获取主要栏目信息
                    $parent = self::find()->select($field)->where(['status' => 1, 'pid' => 0, 'id' => $parent])->orderBy($sort)->indexBy('id')->asArray()->all();
                    $menus  = $menus + $parent;
                }
            }
        }

        // 处理导航栏信息
        if ($menus)
        {
            foreach ($menus as $value)
            {
                // 判断是否存在
                $id = $value['pid'] == 0 ? $value['id'] : $value['pid'];
                if ( ! isset($navigation[$id])) $navigation[$id] = ['child' => []];

                // 添加数据
                if ($value['pid'] == 0)
                    $navigation[$id] = array_merge($navigation[$id], $value);
                else
                    $navigation[$id]['child'][] = $value;
            }

            // 将导航栏信息添加到缓存
            $cache = Yii::$app->cache;
            if ($cache->get($index)) $cache->delete($index);
            $cache->set($index, $navigation, Yii::$app->params['cacheTime']);
            return true;
        }

        return false;
    }
}
