<?php

use jinxing\admin\widgets\MeTable;
// 定义标题和面包屑信息
$this->title = '用户信息';
?>
<?=MeTable::widget()?>
<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    var m = meTables({
        title: "用户信息",
        
        table: {
            "aoColumns": [
                			{"title": "id", "data": "id", "sName": "id", "edit": {"type": "hidden", }, "bSortable": false}, 
			{"title": "username", "data": "username", "sName": "username", "edit": {"type": "text", "required": true,"rangelength": "[2, 255]"}, "bSortable": false}, 
			{"title": "auth_key", "data": "auth_key", "sName": "auth_key", "edit": {"type": "text", "required": true,"rangelength": "[2, 32]"}, "bSortable": false}, 
			{"title": "password_hash", "data": "password_hash", "sName": "password_hash", "edit": {"type": "text", "required": true,"rangelength": "[2, 255]"}, "bSortable": false}, 
			{"title": "password_reset_token", "data": "password_reset_token", "sName": "password_reset_token", "edit": {"type": "text", "rangelength": "[2, 255]"}, "bSortable": false}, 
			{"title": "email", "data": "email", "sName": "email", "edit": {"type": "text", "required": true,"rangelength": "[2, 255]"}, "bSortable": false}, 
			{"title": "status", "data": "status", "sName": "status", "edit": {"type": "text", "required": true,"number": true}, "bSortable": false}, 
			{"title": "created_at", "data": "created_at", "sName": "created_at", "edit": {"type": "text", "required": true,"number": true}, "bSortable": false, "createdCell" : meTables.dateTimeString}, 
			{"title": "updated_at", "data": "updated_at", "sName": "updated_at", "edit": {"type": "text", "required": true,"number": true}, "bSortable": false, "createdCell" : meTables.dateTimeString}, 

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

     $(function(){
         m.init();
     });
</script>
<?php $this->endBlock(); ?>