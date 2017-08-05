<?php

use yii\db\Migration;

class m170801_081237_insert_rabc extends Migration
{
    private $table = '{{%auth_item}}';

    private $itemTable = '{{%auth_item_child}}';

    private $ruleTable = '{{%auth_rule}}';

    private $assignmentTable = '{{%auth_assignment}}';

    public function safeUp()
    {
        $time = time();

        // 第一步写入权限
        $this->batchInsert($this->table, [
            'name',
            'type',
            'description',
            'created_at',
            'updated_at'
        ], [
            ['admin', 1, '管理员', $time, $time],
            ['admin/address', 2, '管理员地址信息查询', $time, $time],
            ['admin/create', 2, '创建管理员信息', $time, $time],
            ['admin/delete', 2, '删除管理员信息', $time, $time],
            ['admin/delete-all', 2, '批量删除管理员信息', $time, $time],
            ['admin/editable', 2, '管理员信息行内编辑', $time, $time],
            ['admin/export', 2, '管理员西信息导出', $time, $time],
            ['admin/index', 2, '显示管理员信息', $time, $time],
            ['admin/search', 2, '搜索管理员信息', $time, $time,],
            ['admin/update', 2, '修改管理员信息', $time, $time,],
            ['admin/upload', 2, '上传管理员头像信息', $time, $time],
            ['admin/view', 2, '查看管理员详情信息', $time, $time],
            ['administrator', 1, '超级管理员', $time, $time],
            ['arrange/arrange', 2, '我的日程查询', $time, $time],
            ['arrange/calendar', 2, '我的日程信息', $time, $time],
            ['arrange/create', 2, '创建日程管理', $time, $time],
            ['arrange/delete', 2, '删除日程管理', $time, $time],
            ['arrange/delete-all', 2, '批量删除日程信息', $time, $time],
            ['arrange/editable', 2, '日程管理行内编辑', $time, $time],
            ['arrange/export', 2, '日程信息导出', $time, $time],
            ['arrange/index', 2, '显示日程管理', $time, $time],
            ['arrange/search', 2, '搜索日程管理', $time, $time],
            ['arrange/update', 2, '修改日程管理', $time, $time],
            ['auth-assignment/create', 2, '创建角色分配', $time, $time],
            ['auth-assignment/delete', 2, '删除角色分配', $time, $time],
            ['auth-assignment/export', 2, '导出角色分配', $time, $time],
            ['auth-assignment/index', 2, '显示角色分配', $time, $time],
            ['auth-assignment/search', 2, '搜索角色分配', $time, $time],
            ['auth-rule/create', 2, '创建规则管理', $time, $time],
            ['auth-rule/delete', 2, '删除规则管理', $time, $time],
            ['auth-rule/delete-all', 2, '规则管理-多删除', $time, $time],
            ['auth-rule/export', 2, '导出规则管理', $time, $time],
            ['auth-rule/index', 2, '显示规则管理', $time, $time],
            ['auth-rule/search', 2, '搜索规则管理', $time, $time],
            ['auth-rule/update', 2, '修改规则管理', $time, $time],
            ['authority/create', 2, '创建权限信息', $time, $time],
            ['authority/delete', 2, '删除权限信息', $time, $time],
            ['authority/delete-all', 2, '权限信息多删除操作', $time, $time],
            ['authority/export', 2, '权限信息导出', $time, $time],
            ['authority/index', 2, '显示权限信息', $time, $time],
            ['authority/search', 2, '搜索权限信息', $time, $time],
            ['authority/update', 2, '修改权限信息', $time, $time],
            ['china/create', 2, '创建地址信息', $time, $time],
            ['china/delete', 2, '删除地址信息', $time, $time],
            ['china/export', 2, '地址信息导出', $time, $time],
            ['china/index', 2, '显示地址信息', $time, $time],
            ['china/search', 2, '搜索地址信息', $time, $time],
            ['china/update', 2, '修改地址信息', $time, $time],
            ['menu/create', 2, '创建导航栏目', $time, $time],
            ['menu/delete', 2, '删除导航栏目', $time, $time],
            ['menu/delete-all', 2, '批量删除导航栏目信息', $time, $time],
            ['menu/export', 2, '导航栏目信息导出', $time, $time],
            ['menu/index', 2, '显示导航栏目', $time, $time],
            ['menu/search', 2, '搜索导航栏目', $time, $time],
            ['menu/update', 2, '修改导航栏目', $time, $time],
            ['module/create', 2, '创建模块生成', $time, $time],
            ['module/index', 2, '显示模块生成', $time, $time],
            ['module/produce', 2, '模块生成配置文件', $time, $time],
            ['module/update', 2, '修改模块生成', $time, $time],
            ['role/create', 2, '创建角色信息', $time, $time],
            ['role/delete', 2, '删除角色信息', $time, $time],
            ['role/edit', 2, '角色分配权限', $time, $time],
            ['role/export', 2, '角色信息导出', $time, $time],
            ['role/index', 2, '显示角色信息', $time, $time],
            ['role/search', 2, '搜索角色信息', $time, $time],
            ['role/update', 2, '修改角色信息', $time, $time],
            ['role/view', 2, '角色权限查看', $time, $time],
            ['user/index', 2, '用户信息-显示', $time, $time],
            ['user/search', 2, '用户信息-搜索', $time, $time],
            ['user/create', 2, '用户信息-创建', $time, $time],
            ['user/update', 2, '用户信息-修改', $time, $time],
            ['user/delete', 2, '用户信息-删除', $time, $time],
            ['user/delete-all', 2, '用户信息-批量删除', $time, $time],
            ['user/export', 2, '用户信息-导出', $time, $time],
            ['admin-log/index', 2, '操作日志-显示', $time, $time],
            ['admin-log/search', 2, '操作日志-搜索', $time, $time],
            ['admin-log/delete', 2, '操作日志-删除', $time, $time],
            ['admin-log/delete-all', 2, '操作日志-批量删除', $time, $time],
            ['admin-log/export', 2, '操作日志-导出', $time, $time],
        ]);

        // 管理员信息
        $admin = [
            'auth-assignment/delete', 'auth-assignment/export', 'auth-assignment/index',
            'auth-assignment/search', 'auth-rule/create', 'auth-rule/delete',
            'auth-rule/delete-all', 'auth-rule/export', 'auth-rule/index',
            'auth-rule/search', 'auth-rule/update', 'authority/create',
            'authority/delete', 'authority/delete-all', 'authority/export',
            'authority/index', 'authority/search', 'authority/update',
            'menu/create', 'menu/delete', 'menu/delete-all',
            'menu/export', 'menu/index', 'menu/search',
            'auth-assignment/create', 'admin-log/delete', 'admin-log/delete-all'
        ];

        // 第二步写入超级管理员的权限
        $all = $this->db->createCommand('SELECT `name` FROM '.$this->table.' WHERE `type` = 2')->queryAll();
        if ($all) {
            $insert = [];
            foreach ($all as $value) {
                $insert[] = ['administrator', $value['name']];
                if (!in_array($value['name'], $admin)) {
                    $insert[] = ['admin', $value['name']];
                }
            }

            $this->batchInsert($this->itemTable, ['parent', 'child'], $insert);
        }

        // 第三步写入规则信息
        $this->batchInsert($this->ruleTable, ['name', 'data', 'created_at', 'updated_at'], [
            [
                'admin',
                serialize(unserialize('O:23:"backend\rules\AdminRule":3:{s:4:"name";s:5:"admin";s:9:"createdAt";i:1499006069;s:9:"updatedAt";i:1499006069;}')),
                $time,
                $time
            ],
            [
                'auth-assignment',
                serialize(unserialize('O:32:"backend\rules\AuthAssignmentRule":5:{s:4:"name";s:15:"auth-assignment";s:47:" backend\rules\AuthAssignmentRule adminRoleName";s:13:"administrator";s:43:" backend\rules\AuthAssignmentRule intUserId";i:1;s:9:"createdAt";i:1500105238;s:9:"updatedAt";i:1500105238;}')),
                $time,
                $time
            ],
            [
                'admin-delete',
                serialize(unserialize('O:29:"backend\rules\AdminDeleteRule":3:{s:4:"name";s:12:"admin-delete";s:9:"createdAt";i:1501919066;s:9:"updatedAt";i:1501919066;}')),
                $time,
                $time
            ],
        ]);

        // 第三步修改权限对应的规则
        $this->update($this->table, ['rule_name' => 'admin'], ['name' => 'admin/update']);
        $this->update($this->table, ['rule_name' => 'admin-delete'], ['name' => 'admin/delete']);
        $this->update($this->table, ['rule_name' => 'auth-assignment'], ['name' => 'auth-assignment/delete']);

        // 第四步写入分配信息
        $this->batchInsert($this->assignmentTable, [
            'item_name',
            'user_id',
            'created_at'
        ], [
            ['administrator', 1, $time],
            ['admin', 2, $time]
        ]);
    }

    public function safeDown()
    {
        echo "m170801_081237_insert_rabc cannot be reverted.\n";
        $this->delete($this->table, ['name' => [
            'admin', 'admin/address', 'admin/create',
            'admin/delete', 'admin/delete-all', 'admin/editable',
            'admin/export', 'admin/index', 'admin/search',
            'admin/update', 'admin/upload', 'admin/view',
            'administrator', 'arrange/arrange', 'arrange/calendar',
            'arrange/create', 'arrange/delete', 'arrange/delete-all',
            'arrange/editable', 'arrange/export', 'arrange/index',
            'arrange/search', 'arrange/update', 'auth-assignment/create',
            'auth-assignment/delete', 'auth-assignment/export', 'auth-assignment/index',
            'auth-assignment/search', 'auth-rule/create', 'auth-rule/delete',
            'auth-rule/delete-all', 'auth-rule/export', 'auth-rule/index',
            'auth-rule/search', 'auth-rule/update', 'authority/create',
            'authority/delete', 'authority/delete-all', 'authority/export',
            'authority/index', 'authority/search', 'authority/update',
            'china/create', 'china/delete', 'china/export',
            'china/index', 'china/search', 'china/update',
            'menu/create', 'menu/delete', 'menu/delete-all',
            'menu/export', 'menu/index', 'menu/search',
            'menu/update', 'module/create', 'module/index',
            'module/produce', 'module/update', 'role/create',
            'role/delete', 'role/edit', 'role/export',
            'role/index', 'role/search', 'role/update',
            'role/view', 'user/create', 'user/delete',
            'user/delete-all', 'user/export', 'user/index',
            'user/search', 'user/update', 'admin-log/index',
            'admin-log/search', 'admin-log/delete', 'admin-log/delete-all',
            'admin-log/export',
        ]]);
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170801_081237_insert_rabc cannot be reverted.\n";

        return false;
    }
    */
}
