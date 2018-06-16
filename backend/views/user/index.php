<?php

use yii\helpers\Json;
use jinxing\admin\widgets\MeTable;

// 定义标题和面包屑信息
$this->title = '用户信息';
?>
<?= MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
    <script type="text/javascript">
        var arrStatus = <?= Json::encode($status)?>,
            arrColors = <?=Json::encode($statusColor)?>,
            m = meTables({
                title: "用户信息",
                table: {
                    "aoColumns": [
                        {
                            "title": "id",
                            "data": "id", "edit": {"type": "hidden"},
                            "bSortable": false
                        },
                        {
                            "title": "用户名",
                            "data": "username",
                            "edit": {"type": "text", "required": true, "rangelength": "[2, 255]"},
                            "bSortable": false
                        },
                        {
                            "title": "密码",
                            "data": "password",
                            "bHide": true,
                            "edit": {"type": "password", "rangelength": "[2, 20]"},
                            "bSortable": false,
                            "defaultContent": "",
                            "bViews": false
                        },
                        {
                            "title": "确认密码",
                            "data": "repassword",
                            "bHide": true,
                            "edit": {
                                "type": "password",
                                "rangelength": "[2, 20]",
                                "equalTo": "input[name=password]:first"
                            },
                            "bSortable": false,
                            "defaultContent": "",
                            "bViews": false
                        },
                        {
                            "title": "Email",
                            "data": "email",
                            "edit": {"type": "text", "required": true, email: true, "rangelength": "[2, 255]"},
                            "bSortable": false
                        },
                        {
                            "title": "状态",
                            "data": "status",
                            value: arrStatus,
                            "edit": {"type": "radio", "required": true, "number": true, "default": 10},
                            createdCell: function (td, data) {
                                $(td).html(mt.valuesString(arrStatus, arrColors, data));
                            },
                            "bSortable": false
                        },
                        {
                            "title": "创建时间",
                            "data": "created_at",
                            defaultOrder: "desc",
                            "createdCell": meTables.dateTimeString
                        },
                        {
                            "title": "修改时间",
                            "data": "updated_at",
                            "createdCell": meTables.dateTimeString
                        }
                    ]
                }
            });

        /**
         meTables.fn.extend({
        // 显示的前置和后置操作
        beforeShow: function(data, child) {
            return true;
        },
        afterShow: function(data, child) {
            return true;
        },
        
        // 编辑的前置和后置操作
        beforeSave: function(data, child) {
            return true;
        },
        afterSave: function(data, child) {
            return true;
        }
    });
         */

        $(function () {
            m.init();
        });
    </script>
<?php $this->endBlock(); ?>