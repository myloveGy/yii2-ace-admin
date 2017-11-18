<?php

use yii\db\Migration;

class m170805_100055_create_admin_log extends Migration
{
    /**
     * @var string 定义表名
     */
    private $table = '{{%admin_log}}';

    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB comment "管理员信息表"';
        }

        // 创建表
        $this->createTable($this->table, [
            'id' => $this->primaryKey()->notNull()->comment('日志ID'),
            'type' => $this->boolean()->notNull()->defaultValue(1)->comment('日志类型'),
            'controller' => $this->string(64)->notNull()->defaultValue('')->comment('控制器'),
            'action' => $this->string(64)->notNull()->defaultValue('')->comment('方法'),
            'url' => $this->string(100)->notNull()->defaultValue('')->comment('请求地址'),
            'index' => $this->string(100)->notNull()->defaultValue('')->comment('数据标识'),
            'params' => $this->text()->notNull()->comment('请求参数'),
            'created_at' => $this->integer(11)->notNull()->defaultValue(0)->comment('创建时间'),
            'created_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('创建用户'),
            'KEY `admin_id` (`created_id`) USING BTREE COMMENT "管理员"'
        ], $tableOptions);
    }

    public function safeDown()
    {
        echo "m170805_100055_create_admin_log cannot be reverted.\n";
        $this->dropTable($this->table);
        return false;
    }
}
