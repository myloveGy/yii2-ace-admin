<?php

use yii\db\Migration;

/**
 * Class m170801_061245_create_menu 数据库迁移类 -- 后台导航栏目
 */
class m170801_061245_create_menu extends Migration
{
    /**
     * @var string 定义表名
     */
    private $table = '{{%menu}}';

    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB comment "后台导航栏目信息表"';
        }

        // 创建表
        $this->createTable($this->table, [
            'id' => $this->primaryKey()->notNull()->comment('导航栏目ID'),
            'pid' => $this->integer(11)->notNull()->defaultValue(0)->comment('父类ID(只支持两级)'),
            'menu_name' => $this->string(64)->notNull()->comment('导航栏目'),
            'icons' => $this->string(32)->notNull()->defaultValue('icon-desktop')->comment('使用的小图标'),
            'url' => $this->string(64)->notNull()->defaultValue('site/index')->comment('访问地址'),
            'status' => $this->boolean()->notNull()->defaultValue(1)->comment('状态'),
            'sort' => $this->smallInteger(6)->defaultValue(100)->notNull()->comment('排序'),
            'created_at' => $this->integer(11)->notNull()->defaultValue(0)->comment('创建时间'),
            'created_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('创建用户'),
            'updated_at' => $this->integer(11)->notNull()->defaultValue(0)->comment('修改时间'),
            'updated_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('修改用户'),
        ], $tableOptions);

        $time = time();

        // 写入数据
        $this->batchInsert($this->table, [
            'pid',
            'menu_name',
            'icons',
            'url',
            'sort',
            'created_at',
            'created_id',
            'updated_at',
            'updated_id',
        ], [
            [0, '后台管理', 'menu-icon fa fa-cog', '', 2, $time, 1, $time, 1],
            [1, '管理员信息', '', 'admin/index', 1, $time, 1, $time, 1],
            [1, '角色管理', '', 'role/index', 2, $time, 1, $time, 1],
            [1, '角色分配', 'icon-cog', 'auth-assignment/index', 3, $time, 1, $time, 1],
            [1, '权限管理', '', 'authority/index', 4, $time, 1, $time, 1],
            [1, '规则管理', 'menu-icon fa fa-shield', 'auth-rule/index', 5, $time, 1, $time, 1],
            [1, '导航栏目', '', 'menu/index', 6, $time, 1, $time, 1],
            [1, '模块生成', '', 'module/index', 7, $time, 1, $time, 1],
            [1, '操作日志', '', 'admin-log/index', 8, $time, 1, $time, 1],
            [0, '地址信息', 'menu-icon fa fa-bank', 'china/index', 4, $time, 1, $time, 1],
            [0, '用户信息', 'menu-icon fa fa-user', 'user/index', 3, $time, 1, $time, 1],
            [0, '日程管理', 'menu-icon fa fa-calendar', 'arrange/index', 1, $time, 1, $time, 1],
            [0, '上传文件', 'menu-icon fa fa-upload', 'uploads/index', 9, $time, 1, $time, 1],
        ]);
    }

    public function safeDown()
    {
        echo "m170801_061245_create_menu cannot be reverted.\n";
        $this->dropTable($this->table);
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170801_061245_create_menu cannot be reverted.\n";

        return false;
    }
    */
}
