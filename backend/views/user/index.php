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
                number: false,
                table: {
                    aoColumns: [
                        {
                            title: "id",
                            data: "id", "edit": {"type": "hidden"},
                            sortable: false
                        },
                        {
                            title: "用户名",
                            data: "username",
                            search: {name: "username:like"},
                            edit: {type: "text", required: true, rangeLength: "[2, 255]"},
                            sortable: false
                        },
                        {
                            title: "密码",
                            data: "password",
                            hide: true,
                            edit: {type: "password", rangeLength: "[2, 20]"},
                            sortable: false,
                            defaultContent: "",
                            view: false
                        },
                        {
                            title: "确认密码",
                            data: "repassword",
                            hide: true,
                            edit: {
                                type: "password",
                                rangeLength: "[2, 20]",
                                equalTo: "input[name=password]:first"
                            },
                            sortable: false,
                            defaultContent: "",
                            view: false
                        },
                        {
                            title: "Email",
                            data: "email",
                            search: {name: "email:like"},
                            edit: {type: "text", required: true, email: true, rangeLength: "[2, 255]"},
                            sortable: false
                        },
                        {
                            title: "状态",
                            data: "status",
                            value: arrStatus,
                            edit: {type: "radio", required: true, number: true, default: 10},
                            createdCell: function (td, data) {
                                $(td).html(MeTables.valuesString(arrStatus, arrColors, data));
                            },
                            sortable: false
                        },
                        {
                            title: "创建时间",
                            data: "created_at",
                            defaultOrder: "desc",
                            createdCell: MeTables.dateTimeString
                        },
                        {
                            title: "修改时间",
                            data: "updated_at",
                            createdCell: MeTables.dateTimeString
                        }
                    ]
                }
            });

        $(function () {
            m.init();
        });
    </script>
<?php $this->endBlock(); ?>