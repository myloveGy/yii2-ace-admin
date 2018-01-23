<?php

use yii\helpers\Json;
use \backend\models\Auth;

// 获取权限
$auth = Auth::getDataTableAuth('authority');

// 定义标题
$this->title = '权限信息';
?>
<?= \backend\widgets\MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
    <script type="text/javascript">
        var rules = <?=$rules?>,
            iType = <?=$type?>;
        var m = mt({
            title: "权限信息",
            pk: "name",
            buttons: <?=Json::encode($auth['buttons'])?>,
            operations: {
                buttons: <?=Json::encode($auth['operations'])?>
            },
            table: {
                "aoColumns": [
                    {
                        "title": "类型",
                        "data": "type",
                        "sName": "type",
                        "isHide": true,
                        "edit": {"type": "hidden", "value": iType}
                    },
                    {
                        "title": "名称",
                        "data": "name",
                        "sName": "name",
                        "isHide": true,
                        "edit": {"type": "hidden"},
                        "search": {"type": "text"},
                        "bSortable": false
                    },
                    {
                        "title": "权限名称",
                        "data": "name",
                        "sName": "newName",
                        "edit": {
                            "type": "text",
                            "required": true,
                            "rangelength": "[2, 64]",
                            placeholder: "请输入英文字母、数字、_、/等字符串"
                        },
                        "bSortable": false
                    },
                    {
                        "title": "说明描述",
                        "data": "description",
                        "sName": "description",
                        "edit": {
                            "type": "text",
                            "required": true,
                            "rangelength": "[2, 64]",
                            placeholder: "请输入简单描述信息"
                        },
                        "search": {"type": "text"},
                        "bSortable": false
                    },
                    {
                        "title": "使用规则",
                        "data": "rule_name",
                        "sName": "rule_name",
                        "value": rules,
                        "edit": {"type": "select"},
                        "search": {"type": "text"},
                        "bSortable": false,
                        "createdCell": function (td, data) {
                            $(td).html(rules[data] ? rules[data] : data);
                        }
                    },
                    {
                        "title": "创建时间",
                        "data": "created_at",
                        "sName": "created_at",
                        "createdCell": mt.dateTimeString,
                        "defaultOrder": "desc"
                    },
                    {"title": "修改时间", "data": "updated_at", "sName": "updated_at", "createdCell": mt.dateTimeString}
                ]
            }
        });

        mt.fn.extend({
            beforeShow: function (data) {
                if (this.action === "update") {
                    data.newName = data.name;
                }

                return true;
            },
            afterShow: function () {
                $(this.options.sFormId).find('input[name=type]').val(iType);
                return true;
            }
        });

        $(function () {
            m.init();
        })
    </script>
<?php $this->endBlock(); ?>