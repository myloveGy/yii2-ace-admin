<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180122_095211_create_api
 */
class m180122_095211_create_api extends Migration
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
        $this->createTable($this->table, [
            'id' => Schema::TYPE_PK,
            'url' => Schema::TYPE_STRING . ' NOT NULL',
            'schemes' => $this->string(50)->notNull()->defaultValue('http')->comment('传输协议'),
            'method' => Schema::TYPE_STRING . ' NOT NULL',
            'tags' => Schema::TYPE_STRING . ' NOT NULL',
            'summary' => Schema::TYPE_STRING . ' NOT NULL',
            'worker' => Schema::TYPE_STRING,
            'dev_status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('开发状态 0不显示 1开发中 2开发完成'),
            'status' => $this->smallInteger()->notNull()->defaultValue(0)->comment('是否弃用'),
            'description' => Schema::TYPE_STRING . ' NOT NULL',
            'operationId' => Schema::TYPE_STRING,
            'consumes' => Schema::TYPE_STRING . ' NOT NULL',
            'produces' => Schema::TYPE_STRING . ' NOT NULL',
            'parameters' => Schema::TYPE_TEXT,
            'responses' => Schema::TYPE_STRING . ' NOT NULL',
            'version' => Schema::TYPE_STRING . ' NOT NULL',
            'created_at' => $this->integer(11)->notNull()->defaultValue(0)->comment('创建时间'),
            'created_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('创建用户'),
            'updated_at' => $this->integer(11)->notNull()->defaultValue(0)->comment('修改时间'),
            'updated_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('修改用户'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180122_095211_create_api cannot be reverted.\n";
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
        echo "m180122_095211_create_yii2_api_talbe cannot be reverted.\n";

        return false;
    }
    */
}
