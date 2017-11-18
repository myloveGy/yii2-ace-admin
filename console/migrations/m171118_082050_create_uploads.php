<?php

use yii\db\Migration;

class m171118_082050_create_uploads extends Migration
{
    /**
     * @var string 定义使用的表名字
     */
    private $table = '{{%uploads}}';

    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB comment "上传文件信息表"';
        }

        // 创建表
        $this->createTable($this->table, [
            'id' => $this->primaryKey()->notNull()->comment('主键ID'),
            'title' => $this->string(64)->notNull()->comment('文件名称'),
            'url' => $this->text()->notNull()->comment('访问地址[可以是json字符串]'),
            'multiple' => $this->boolean()->notNull()->defaultValue(0)->comment('是否为多图片上传'),
            'created_at' => $this->integer(11)->notNull()->defaultValue(0)->comment('创建时间'),
            'updated_at' => $this->integer(11)->notNull()->defaultValue(0)->comment('修改时间'),
        ], $tableOptions);
    }

    public function safeDown()
    {
        echo "m171118_082050_create_uploads cannot be reverted.\n";
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
        echo "m171118_082050_create_uploads cannot be reverted.\n";

        return false;
    }
    */
}
