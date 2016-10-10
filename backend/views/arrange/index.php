<?php
// 定义标题和面包屑信息
$this->title = '管理员日程安排';
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
    var aAdmins     = <?=json_encode($this->params['admins'])?>,
        aStatus     = <?=json_encode($status)?>,
        aTimeStatus = <?=json_encode($timeStatus)?>,
        aColors     = <?=json_encode($statusColors)?>,
        aTimeColors = <?=json_encode($timeColors)?>;
    aAdmins['0'] = '待定';
    var myTable = new MeTable({sTitle:"管理员日程安排"},{
        "aoColumns":[
			oCheckBox,
			{"title": "id ", "data": "id", "sName": "id", "edit": {"type": "hidden", "options": {}}, "search": {"type": "text"}}, 
			{"title": "事件标题", "data": "title", "editTable":{validate:function(x){
                var l = x.length; if (x > 100 || x < 2) return "长度必须为2到50字符";
            }}, "sName": "title", "edit": {"type": "text", "options": {"required":true, "rangelength":"[2, 100]"}}, "search": {"type": "text"}, "bSortable": false},
			{"title": "事件描述", "data": "desc", "sName": "desc", "edit": {"type": "textarea", "options": {"required":true, "rangelength":"[2, 255]"}}, "bSortable": false},
			{"title": "开始时间", "data": "start_at", "sName": "start_at", "edit": {"type": "datetime", "options": {"required":true}}, "createdCell" : dateTimeString},
			{"title": "结束时间", "data": "end_at", "sName": "end_at", "edit": {"type": "datetime", "options": {"required":true, "class": "m-time"}}, "createdCell" : dateTimeString},
            {"title": "日程状态", "data": "status", "sName": "status", "value": aStatus, "edit": {"type": "radio", "default": 0, "options": {"required":true,"number":true}}, "search": {"type": "select"}, "bSortable": false, "createdCell": function(td, data, rowArr, row, col) {
                $(td).html('<span class="label label-sm ' + aColors[data] + '">' + (aStatus[data] ? aStatus[data] : data) + '</span>');
            }},
            {"title": "时间状态", "data": "time_status", "sName": "time_status", "value": aTimeStatus, "edit": {"type": "radio", "default": 1, "options": {"required":true,"number":true}}, "search": {"type": "select"}, "bSortable": false, "createdCell": function(td, data, rowArr, row, col) {
                $(td).html('<span class="label label-sm ' + aTimeColors[data] + '">' + (aTimeStatus[data] ? aTimeStatus[data] : data) + '</span>');
            }},
            {"title": "处理人", "data": "admin_id", "sName": "admin_id", "value": aAdmins, "edit": {"type": "select", "options": {"required":true, "number":true}}, "search": {"type": "select"}, "createdCell" : adminToString, "bSortable": false},
            {"title": "创建时间", "data": "created_at", "sName": "created_at", "createdCell" : dateTimeString},
			{"title": "添加用户", "data": "created_id", "sName": "created_id", "bSortable": false, "createdCell" : adminToString},
			{"title": "修改时间", "data": "updated_at", "sName": "updated_at", "createdCell" : dateTimeString},
			{"title": "修改用户", "data": "updated_id", "sName": "updated_id", "bSortable": false, "createdCell" : adminToString},
			oOperate
        ]

        // 设置隐藏和排序信息
        // "order":[[0, "desc"]],
        // "columnDefs":[{"targets":[2,3], "visible":false}],
    });

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

        // 时间选项
        $('.me-datetime').datetimepicker({
            format: 'YYYY-MM-DD H:mm:s'
        });
    })
</script>
<?php $this->endBlock(); ?>