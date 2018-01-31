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

        $insertKey = [
            'pid',
            'menu_name',
            'icons',
            'url',
            'sort',
            'created_at',
            'created_id',
            'updated_at',
            'updated_id',
        ];

        // 写入后台导航栏目
        $this->insert($this->table, [
            'pid' => 0,
            'menu_name' => '后台管理',
            'icons' => 'menu-icon fa fa-cog',
            'url' => '',
            'sort' => 1,
            'created_at' => $time,
            'created_id' => 1,
            'updated_at' => $time,
            'updated_id' => 1,
        ]);

        $intPid = $this->db->getLastInsertID();

        // 写入后台管理下的导航栏目
        $this->batchInsert($this->table, $insertKey, [
            [$intPid, '管理员信息', '', 'admin/index', 1, $time, 1, $time, 1],
            [$intPid, '导航栏目', '', 'menu/index', 2, $time, 1, $time, 1],
            [$intPid, '模块生成', '', 'module/index', 3, $time, 1, $time, 1],
            [$intPid, '操作日志', '', 'admin-log/index', 4, $time, 1, $time, 1]
        ]);

        $this->insert($this->table, [
            'pid' => $intPid,
            'menu_name' => '后台权限',
            'icons' => '',
            'url' => '',
            'sort' => 5,
            'created_at' => $time,
            'created_id' => 1,
            'updated_at' => $time,
            'updated_id' => 1,
        ]);

        // 写入权限管理
        $intParentId = $this->db->getLastInsertID();

        // 写入后台管理下的导航栏目
        $this->batchInsert($this->table, $insertKey, [
            [$intParentId, '角色管理', 'menu-icon fa fa-graduation-cap', 'role/index', 1, $time, 1, $time, 1],
            [$intParentId, '角色分配', 'menu-icon fa fa-paper-plane', 'auth-assignment/index', 2, $time, 1, $time, 1],
            [$intParentId, '权限管理', 'menu-icon fa fa-fire', 'authority/index', 3, $time, 1, $time, 1],
            [$intParentId, '规则管理', 'menu-icon fa fa-shield', 'auth-rule/index', 4, $time, 1, $time, 1],
        ]);

        $this->batchInsert($this->table, $insertKey, [
            [0, '地址信息', 'menu-icon fa fa-bank', 'china/index', 2, $time, 1, $time, 1],
            [0, '用户信息', 'menu-icon fa fa-user', 'user/index', 3, $time, 1, $time, 1],
            [0, '日程管理', 'menu-icon fa fa-calendar', 'arrange/index', 4, $time, 1, $time, 1],
            [0, '上传文件', 'menu-icon fa fa-upload', 'uploads/index', 5, $time, 1, $time, 1],
            [0, 'API管理', 'menu-icon fa fa-external-link', 'api/index', 6, $time, 1, $time, 1],
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
