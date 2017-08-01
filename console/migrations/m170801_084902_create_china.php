<?php

use yii\db\Migration;

class m170801_084902_create_china extends Migration
{
    /**
     * @var string 定义表名
     */
    private $table = '{{%china}}';

    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB comment "省份信息表"';
        }

        // 创建表
        $this->createTable($this->table, [
            'id' => $this->primaryKey()->notNull()->comment('id'),
            'pid' => $this->integer(11)->notNull()->defaultValue(0)->comment('父类ID'),
            'name' => $this->string(64)->notNull()->comment('名称'),
        ], $tableOptions);
    }

    public function safeDown()
    {
        echo "m170801_061245_create_china cannot be reverted.\n";
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
        echo "m170801_084902_create_china cannot be reverted.\n";

        return false;
    }
    */
}
