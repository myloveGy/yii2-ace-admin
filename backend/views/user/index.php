<?php

use yii\helpers\Url;
use yii\helpers\Json;

// 定义标题和面包屑信息
$this->title = '用户信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<!--前面导航信息-->
<p>
    <button class="btn btn-white btn-success btn-bold me-table-insert">
        <i class="ace-icon fa fa-plus bigger-120 blue"></i>
        添加
    </button>
    <button class="btn btn-white btn-danger btn-bold me-table-delete">
        <i class="ace-icon fa fa-trash-o bigger-120 red"></i>
        删除
    </button>
    <button class="btn btn-white btn-info btn-bold me-hide">
        <i class="ace-icon fa  fa-external-link bigger-120 orange"></i>
        隐藏
    </button>
    <button class="btn btn-white btn-pink btn-bold  me-table-reload">
        <i class="ace-icon fa fa-refresh bigger-120 pink"></i>
        刷新
    </button>
    <button class="btn btn-white btn-warning btn-bold me-table-export">
        <i class="ace-icon glyphicon glyphicon-export bigger-120 orange2"></i>
        导出Excel
    </button>
</p>
<!--表格数据-->
<table class="table table-striped table-bordered table-hover" id="showTable"></table>
<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    var aStatus = <?=Json::encode($status)?>,
        aStatusColor = <?=Json::encode($statusColor)?>,
        myTable = new MeTable({
            sTitle:"用户信息",
            aFileSelector: ["#file1"],
            sFileUploadUrl: "<?=\yii\helpers\Url::toRoute(['user/upload'])?>",
            oEditFormParams: {
                bMultiCols: true,
                iColsLength: 2,
                aCols: [2, 4]
            }
    },{
        "aoColumns":[
			oCheckBox,
			{"title": "用户ID", "data": "id", "sName": "id", "edit": {"type": "hidden"}},
			{"title": "用户昵称", "data": "username", "sName": "username", "edit": {"type": "text", "options": {"required":true,"rangelength":"[2, 255]"}}, "bSortable": false},
			{"title": "邮箱", "data": "email", "sName": "email", "edit": {"type": "text", "options": {"required":true,"rangelength":"[2, 255]"}}, "bSortable": false},
            {"title": "密码", "data": "password", "sName": "password", "isHide": true, "edit": {"type": "password", "options": {"rangelength":"[2, 20]"}}, "bSortable": false, "defaultContent":"", "bViews":false},
            {"title": "确认密码", "data": "repassword", "sName": "repassword", "isHide": true, "edit": {"type": "password", "options": {"rangelength":"[2, 20]", "equalTo":"input[name=password]:first"}}, "bSortable": false, "defaultContent":"", "bViews":false},
            {"title": "头像", "data": "face", "sName": "face", "isHide": true,
                "edit": {"type": "file", options:{"id":"file1", "input-type": "ace_file"}}
            },
			{"title": "状态", "data": "status", "sName": "status", "value": aStatus, "edit": {"type": "radio", "default": 10, "options": {"required":true, "number":true}}, "bSortable": false, "createdCell": function(td, data) {
			    $(td).html(showSpan(aStatus, aStatusColor, data));
            }},
			{"title": "创建时间", "data": "created_at", "sName": "created_at", "createdCell" : dateTimeString},
			{"title": "修改时间", "data": "updated_at", "sName": "updated_at",  "createdCell" : dateTimeString},
			{"title": "上一次登录时间", "data": "last_time", "sName": "last_time", "createdCell" : dateTimeString},
			{"title": "上一次登录IP", "data": "last_ip", "sName": "last_ip", "bSortable": false}, 
			oOperate
        ]
    });


    // 显示之前的处理
    myTable.afterShow = function(data, isDetail) {
        // 新增
        $("#file1").ace_file_input("reset_input");

        // 修改复值
        if (this.actionType == "update" && ! empty(data.face)) {
            $("#file1").ace_file_input("show_file_list", [data.face]);
        }
        return true;
    };


    /**
     * 显示的前置和后置操作
     * myTable.beforeShow(object data, bool isDetail) return true 前置
     * myTable.afterShow(object data, bool isDetail)  return true 后置
     */

     /**
      * 编辑的前置和后置操作
      * myTable.beforeSave(object data) return true 前置
      * myTable.afterSave(object data)  return true 后置
      */

     $(function(){
         myTable.init();
     });
</script>
<?php $this->endBlock(); ?>