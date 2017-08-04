<?php
// 定义标题和面包屑信息
$this->title = '用户信息';
?>
<?=\backend\widgets\MeTable::widget()?>
<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    var aStatus = <?=\yii\helpers\Json::encode($status)?>,
        aStatusColor = <?=\yii\helpers\Json::encode($statusColor)?>,
        m = meTables({
            title: "<?=$this->title?>",
            table: {
                "aoColumns":[
                    {"title": "用户ID", "data": "id", "sName": "id", "edit": {"type": "hidden"}, "search": {"type": "text"}, "defaultOrder": "desc"},
                    {"title": "用户账号", "data": "username", "sName": "username", "edit": {"type": "text", "required":true,"rangelength":"[2, 255]"}, "search": {"type": "text"}, "bSortable": false},
                    {"title": "密码", "data": "password", "sName": "password", "isHide": true, "edit": {"type": "password", "rangelength":"[2, 20]"}, "bSortable": false, "defaultContent":"", "bViews":false},
                    {"title": "确认密码", "data": "repassword", "sName": "repassword", "isHide": true, "edit": {"type": "password", "rangelength":"[2, 20]", "equalTo":"input[name=password]:first"}, "bSortable": false, "defaultContent":"", "bViews":false},
                    {"title": "邮箱", "data": "email", "sName": "email", "edit": {"type": "text", "required":true,"rangelength":"[2, 255]", "email": true}, "search": {"type": "text"}, "bSortable": false},
                    {"title": "状态", "data": "status", "sName": "status", "value": aStatus,
                        "edit": {"type": "radio", "default": 10, "required":true,"number":true},
                        "bSortable": false,
                        "search": {"type": "select"},
                        "createdCell":function(td, data) {
                            $(td).html(mt.valuesString(aStatus, aStatusColor, data));
                        }
                    },
                    {"title": "创建时间", "data": "created_at", "sName": "created_at", "createdCell" : meTables.dateTimeString},
                    {"title": "修改时间", "data": "updated_at", "sName": "updated_at", "createdCell" : mt.dateTimeString},
                ]
            }
        });

    $(function(){
        m.init();
    });
</script>
<?php $this->endBlock(); ?>
