<?php

use yii\db\Migration;

class m170801_074527_create_arrange extends Migration
{
    /**
     * @var string 定义表名
     */
    private $table = '{{%arrange}}';

    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB comment "日程记录信息表"';
        }

        // 创建表
        $this->createTable($this->table, [
            'id' => $this->primaryKey()->notNull()->comment('记录ID'),
            'title' => $this->string(100)->notNull()->defaultValue('')->comment('事件标题'),
            'desc' => $this->string(255)->notNull()->defaultValue('')->comment('事件描述'),
            'status' => $this->boolean()->notNull()->defaultValue(0)->comment('状态[0 - 待处理 1 - 已委派 2 - 完成 3 延期]'),
            'time_status' => $this->boolean()->notNull()->defaultValue(0)->comment('事件状态[0 - 延缓 1 - 正常 2 - 紧急]'),
            'admin_id' => $this->Integer(11)->defaultValue(0)->notNull()->comment('委派管理员'),
            'start_at' => $this->Integer(11)->defaultValue(0)->notNull()->comment('开始时间'),
            'end_at' => $this->Integer(11)->defaultValue(0)->notNull()->comment('结束时间'),
            'created_at' => $this->integer(11)->notNull()->defaultValue(0)->comment('创建时间'),
            'created_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('创建用户'),
            'updated_at' => $this->integer(11)->notNull()->defaultValue(0)->comment('修改时间'),
            'updated_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('修改用户'),
        ], $tableOptions);
    }

    public function safeDown()
    {
        echo "m170801_061245_create_arrange cannot be reverted.\n";
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
        echo "m170801_074527_create_arrange cannot be reverted.\n";

        return false;
    }
    */
}
