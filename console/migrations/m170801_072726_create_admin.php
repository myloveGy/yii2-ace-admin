<?php

use yii\db\Migration;

/**
 * Class m170801_072726_create_admin 数据库迁移 - 后台管理员信息
 */
class m170801_072726_create_admin extends Migration
{
    /**
     * @var string 定义表名
     */
    private $table = '{{%admin}}';

    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB comment "管理员信息表"';
        }

        // 创建表
        $this->createTable($this->table, [
            'id' => $this->primaryKey()->notNull()->comment('管理员ID'),
            'username' => $this->string(64)->notNull()->comment('管理员账号')->unique(),
            'email' => $this->string(64)->notNull()->comment('管理员邮箱')->unique(),
            'face' => $this->string(100)->notNull()->defaultValue('')->comment('管理员头像'),
            'role' => $this->string(64)->notNull()->defaultValue('')->comment('管理员角色'),
            'status' => $this->boolean()->notNull()->defaultValue(10)->comment('状态'),
            'auth_key' => $this->string(32)->notNull()->defaultValue(''),
            'password_hash' => $this->string(255)->notNull(),
            'password_reset_token' => $this->string(255)->unique(),
            'last_time' => $this->Integer(11)->defaultValue(0)->notNull()->comment('上一次登录时间'),
            'last_ip' => $this->char(15)->defaultValue('')->notNull()->comment('上一次登录的IP'),
            'address' => $this->string(100)->defaultValue('')->notNull()->comment('地址信息'),
            'created_at' => $this->integer(11)->notNull()->defaultValue(0)->comment('创建时间'),
            'created_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('创建用户'),
            'updated_at' => $this->integer(11)->notNull()->defaultValue(0)->comment('修改时间'),
            'updated_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('修改用户'),
        ], $tableOptions);

        $time = time();

        // 写入数据
        $this->batchInsert($this->table, [
            'username',
            'email',
            'role',
            'auth_key',
            'password_hash',
            'password_reset_token',
            'last_time',
            'last_ip',
            'address',
            'created_at',
            'created_id',
            'updated_at',
            'updated_id',
        ], [
            [
                'super',
                'super@admin.com',
                'administrator',
                'gKkLFMdB2pvIXOFNpF_Aeemvdf1j0YUM',
                '$2y$13$Nuf1mzDRoCMxrWI.rIjENu20QshJG41smdEeHFHxq0qdmS99YytHy',
                '5vLaPpUS-I-XxJaoGP-GZDk474WdnaK3_1469073015',
                $time,
                '127.0.0.1',
                '湖南省,岳阳市,岳阳县',
                $time,
                1,
                $time,
                1
            ],
            [
                'admin',
                'admin@admin.com',
                'admin',
                'tArp_Kv4z1JlzBUZYCL33N24AZL-_77p',
                '$2y$13$RNrJ7GK1A5iZRxBpho6sbeCJKfNRxzy5axCeRjZLqvA5W6RuVYBRW',
                'CgScbf1E96N3pqH01b0mVi_Z58j8QsRV_1501916190',
                $time,
                '127.0.0.1',
                '湖南省,岳阳市,岳阳县',
                $time,
                1,
                $time,
                1
            ],
        ]);
    }

    public function safeDown()
    {
        echo "m170801_061245_create_admin cannot be reverted.\n";
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
        echo "m170801_072726_create_admin cannot be reverted.\n";

        return false;
    }
    */
}
