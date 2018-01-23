<?php

use yii\helpers\Json;
use \backend\models\Auth;

// 获取权限
$auth = Auth::getDataTableAuth('auth-rule');

// 定义标题和面包屑信息
$this->title = '规则管理';
?>
<?=\backend\widgets\MeTable::widget()?>
<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    var m = meTables({
        title: "规则管理",
        pk: "name",
        buttons: <?=Json::encode($auth['buttons'])?>,
        operations: {
            buttons: <?=Json::encode($auth['operations'])?>
        },
        table: {
            "aoColumns": [
                {"title": "名称", "data": "name", "defaultOrder": "desc", "sName": "name",
                    "edit": {"type": "hidden"},
                    "search": {"type": "text"}
                },
                {"title": "名称", "data": "name", "sName": "newName",
                    "edit": {"type": "text", "required": true,"rangelength": "[2, 64]"},
                    "isHide": true
                },
                {"title": "对应规则类", "data": "data", "sName": "data",
                    "edit": {"type": "text", "required": true, "rangelength": "[2, 100]"}, "bSortable": false},
                {"title": "创建时间", "data": "created_at", "sName": "created_at", "createdCell" : meTables.dateTimeString},
                {"title": "修改时间", "data": "updated_at", "sName": "updated_at", "createdCell" : meTables.dateTimeString}
            ]       
        }
    });

    meTables.fn.extend({
        beforeShow: function(data) {
            if (this.action === "update") {
                data.newName = data.name;
            }

            return true;
        }
    });

    $(function(){
        m.init();
    });
</script>
<?php $this->endBlock(); ?>