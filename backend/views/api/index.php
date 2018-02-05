<?php

use yii\helpers\Json;
use backend\widgets\MeTable;

/* @var $methods array */
/* @var $schemelist array */

// 定义标题和面包屑信息
$this->title = 'API信息';

$url     = '@web/public/assets';
$depends = ['depends' => 'backend\assets\AdminAsset'];
$this->registerCssFile($url . '/css/chosen.css', $depends);
$this->registerJsFile($url . '/js/chosen.jquery.min.js', $depends);
?>
<?= MeTable::widget() ?>
<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    var methods = <?=Json::encode($methods)?>,
        schemelist = <?=Json::encode($schemelist)?>,
        aAdmins = <?=Json::encode($this->params['admins'])?>,
        m = meTables({
            title: "APi信息",
            fileSelector: ["#file"],
            table: {
                "aoColumns": [
                    {
                        "title": "ID",
                        "data": "id",
                        "sName": "id",
                        "edit": {"type": "hidden"},
                        "search": {"type": "text"},
                        "defaultOrder": "desc"
                    },
                    {
                        "title": "Api名称",
                        "data": "summary",
                        "sName": "summary",
                        "edit": {"type": "text", "required": true, "rangelength": "[2, 255]"},
                        "search": {"type": "text"},
                        "bSortable": false
                    },
                    {
                        "title": "传输协议",
                        "data": "schemes",
                        "sName": "schemes",
                        "value": schemelist,
                        "edit": {"type": "select", "required": true},
                        "bSortable": false,
                        "createdCell": function (td, data) {
                            $(td).html(schemelist[data] ? schemelist[data] : data);
                        }
                    },
                    {
                        "title": "Method",
                        "data": "method",
                        "sName": "method",
                        "value": methods,
                        "edit": {"type": "select", "required": true},
                        "bSortable": false,
                        "createdCell": function (td, data) {
                            $(td).html(methods[data] ? methods[data] : data);
                        }
                    },
                    {
                        "title": "版本",
                        "data": "version",
                        "sName": "version",
                        "isHide": true,
                        "edit": {"type": "text", "rangelength": "[2, 20]"},
                        "bSortable": false,
                        "defaultContent": "",
                        "bViews": false
                    },
                    {
                        "title": "URL",
                        "data": "url",
                        "sName": "url",
                        "isHide": true,
                        "edit": {"type": "text", "rangelength": "[2, 120]"},
                        "bSortable": false,
                        "defaultContent": "",
                        "bViews": false
                    },
                    {
                        "title": "tags",
                        "data": "tags",
                        "sName": "tags",
                        "isHide": true,
                        "edit": {"type": "text", "rangelength": "[2, 255]"},
                        "bSortable": false,
                        "defaultContent": "",
                        "bViews": false
                    },
                    {
                        "title": "description",
                        "data": "description",
                        "sName": "description",
                        "isHide": true,
                        "edit": {"type": "text", "rangelength": "[2, 255]"},
                        "bSortable": false,
                        "defaultContent": "",
                        "bViews": false
                    },
                    {
                        "title": "创建时间",
                        "data": "created_at",
                        "sName": "created_at",
                        "createdCell": meTables.dateTimeString
                    },
                    {"title": "修改时间", "data": "updated_at", "sName": "updated_at", "createdCell": mt.dateTimeString},
                    {
                        "title": "修改用户",
                        "data": "updated_id",
                        "sName": "updated_id",
                        "bSortable": false,
                        "createdCell": mt.adminString
                    }
                ]
            }
        });
    var $file = null;
    mt.fn.extend({
        beforeShow: function (data) {
            $file.ace_file_input("reset_input");

            // 修改复值
            if (this.action == "update" && !empty(data.face)) {
                $file.ace_file_input("show_file_list", [data.face]);
            }

            return true;
        }
    });

    $(function () {
        m.init();
        $file = $("#file");
    });
</script>
<?php $this->endBlock(); ?>
