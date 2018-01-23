<?php

use yii\helpers\Url;
use yii\helpers\Json;
use \backend\models\Auth;

// 获取权限
$auth = Auth::getDataTableAuth('role');

// 定义标题和面包屑信息
$this->title = '角色信息';

?>
<?= \backend\widgets\MeTable::widget() ?>
<?php $this->beginBlock('javascript'); ?>
<script type="text/javascript">
    var iType = <?=$type?>,
        oButtons = <?=Json::encode($auth['buttons'])?>,
        oOperationsButtons = <?=Json::encode($auth['operations'])?>;

    oButtons.updateAll = {bShow: false};
    oButtons.deleteAll = {bShow: false};
    oOperationsButtons.see = {"cClass": "role-see"};
    oOperationsButtons.other = {
        bShow: <?=Yii::$app->user->can('role/edit') ? 'true' : 'false' ?>,
        "title": "编辑权限",
        "button-title": "编辑权限",
        "className": "btn-warning",
        "cClass": "role-edit",
        "icon": "fa-pencil-square-o",
        "sClass": "yellow"
    };

    var m = mt({
        title: "角色信息",
        bCheckbox: false,
        buttons: oButtons,
        operations: {
            width: "200px",
            buttons: oOperationsButtons
        },
        table: {
            "aoColumns": [
                {
                    "title": "类型",
                    "data": "type",
                    "sName": "type",
                    "isHide": true,
                    "isExport": false,
                    "edit": {"type": "hidden", "value": iType}
                },
                {
                    "title": "名称",
                    "data": "name",
                    "sName": "name",
                    "isHide": true,
                    "edit": {"type": "hidden"},
                    "search": {"type": "text"}
                },
                {
                    "title": "角色名称",
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
                        "rangelength": "[2, 255]",
                        placeholder: "请输入简单描述信息"
                    },
                    "search": {"type": "text"},
                    "bSortable": false
                },
                {
                    "title": "创建时间",
                    "data": "created_at",
                    "sName": "created_at",
                    "defaultOrder": "desc",
                    "createdCell": mt.dateTimeString
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

    var mixLayer = null;

    function layerClose() {
        layer.close(mixLayer);
        mixLayer = null;
    }

    function layerOpen(title, url) {
        if (mixLayer) {
            layer.msg("请先关闭当前的弹出窗口");
        } else {
            mixLayer = layer.open({
                type: 2,
                area: ["90%", "90%"],
                title: title,
                content: url,
                anim: 2,
                maxmin: true,
                cancel: function () {
                    mixLayer = null;
                }
            });
        }
    }

    $(function () {
        m.init();

        // 添加查看事件
        $(document).on('click', '.role-see', function () {
            var data = m.table.data()[$(this).attr('table-data')];
            if (data) {
                layerOpen(
                    "查看" + data["name"] + "(" + data["description"] + ") 详情",
                    "<?=Url::toRoute(['role/view'])?>?name=" + data['name']
                );
            }
        });

        // 添加修改权限事件
        $(document).on('click', '.role-edit', function () {
            var data = m.table.data()[$(this).attr('table-data')];
            if (data) {
                layerOpen(
                    "编辑" + data["name"] + "(" + data["description"] + ") 信息",
                    "<?=Url::toRoute(['role/edit'])?>?name=" + data['name']
                );
            }
        })
    })
</script>
<?php $this->endBlock(); ?>
