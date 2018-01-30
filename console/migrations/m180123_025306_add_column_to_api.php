<?php

use yii\db\Migration;

/**
 * Class m180123_025306_add_column_to_api
 */
class m180123_025306_add_column_to_api extends Migration
{

    /**
     * @var string 定义表名
    */
    private $table = '{{%api}}';
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn($this->table, 'schemes', $this->string(50)->notNull()->defaultValue('http')->comment('传输协议') );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180123_025306_add_column_to_api cannot be reverted.\n";
        $this->dropColumn($this->table, 'schemes');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180123_025306_add_column_to_api cannot be reverted.\n";

        return false;
    }
    */
}
