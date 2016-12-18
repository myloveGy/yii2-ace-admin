<?php
use backend\assets\AppAsset;

AppAsset::loadTimeJavascript($this, 'datetime');

// 定义标题和面包屑信息
$this->title = '管理员日程安排';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('@web/public/assets/css/jquery-ui.custom.min.css', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/jquery-ui.custom.min.js', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/jquery.ui.touch-punch.min.js', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/markdown/markdown.min.js', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/markdown/bootstrap-markdown.min.js', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/jquery.hotkeys.min.js', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/bootstrap-wysiwyg.min.js', ['depends' => 'backend\assets\AppAsset']);
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
    var aAdmins = <?=json_encode($this->params['admins'])?>,
        aStatus = <?=json_encode($status)?>,
        aTimeStatus = <?=json_encode($timeStatus)?>,
        aColors = <?=json_encode($statusColors)?>,
        aTimeColors = <?=json_encode($timeColors)?>;
    aAdmins['0'] = '待定';

    var myTable = new MeTable({
        sTitle: "管理员日程安排",
        bEditTable: true
    }, {
        "aoColumns": [
            oCheckBox,
            {
                "title": "id ",
                "data": "id",
                "sName": "id",
                "edit": {"type": "hidden", "options": {}},
                "search": {"type": "text"}
            },
            {
                "title": "事件标题",
                "data": "title",
                "editTable": {
                    validate: function (x) {
                        if (x.length > 100 || x.length < 2) return "长度必须为2到50字符";
                    }
                },
                "sName": "title",
                "edit": {"type": "text", "options": {"required": true, "rangelength": "[2, 100]"}},
                "search": {"type": "text"},
                "bSortable": false
            },
            {
                "title": "事件描述",
                "data": "desc",
                "sName": "desc",
                "edit": {"type": "div", "options": {"id": "me-desc", "class": "wysiwyg-editor", "rows": 5, "required": true, "rangelength": "[2, 255]"}},
                "bSortable": false
            },
            {
                "title": "开始时间",
                "data": "start_at",
                "sName": "start_at",
                "edit": {"type": "datetime", "options": {"required": true}},
                "createdCell": dateTimeString
            },
            {
                "title": "结束时间",
                "data": "end_at",
                "sName": "end_at",
                "edit": {"type": "datetime", "options": {"required": true, "class": "m-time"}},
                "createdCell": dateTimeString
            },
            {
                "title": "日程状态",
                "data": "status",
                "sName": "status",
                "value": aStatus,
                "edit": {"type": "radio", "default": 0, "options": {"required": true, "number": true}},
                "search": {"type": "select"},
                "bSortable": false,
                "editTable": {
                    validate: function (x) {
                        if (x.length > 100 || x.length < 2) return "长度必须为2到50字符";
                    }
                },
                "createdCell": function (td, data, rowArr, row, col) {
                    $(td).html(showSpan(aStatus, aColors, data));
                }
            },
            {
                "title": "时间状态",
                "data": "time_status",
                "sName": "time_status",
                "value": aTimeStatus,
                "edit": {"type": "radio", "default": 1, "options": {"required": true, "number": true}},
                "search": {"type": "select"},
                "bSortable": false,
                "createdCell": function (td, data, rowArr, row, col) {
                    $(td).html(showSpan(aTimeStatus, aTimeColors, data));
                }
            },
            {
                "title": "处理人",
                "data": "admin_id",
                "sName": "admin_id",
                "value": aAdmins,
                "edit": {"type": "select", "options": {"required": true, "number": true}},
                "search": {"type": "select"},
                "createdCell": adminToString,
                "bSortable": false
            },
            {"title": "创建时间", "data": "created_at", "sName": "created_at", "createdCell": dateTimeString},
            {
                "title": "添加用户",
                "data": "created_id",
                "sName": "created_id",
                "bSortable": false,
                "createdCell": adminToString
            },
            {"title": "修改时间", "data": "updated_at", "sName": "updated_at", "createdCell": dateTimeString},
            {
                "title": "修改用户",
                "data": "updated_id",
                "sName": "updated_id",
                "bSortable": false,
                "createdCell": adminToString
            },
            oOperate
        ]
    });


    /**
     * 显示的前置和后置操作
     * myTable.beforeShow(object data, bool isDetail) return true 前置
     * myTable.afterShow(object data, bool isDetail)  return true 后置
     */
    myTable.afterShow = function(data, isDetail) {
        if ( ! isDetail) {
            var html = this.actionType == "insert" ? "" : data.desc;
            $('#me-desc').html(html);
        }
        return true;
    };

    /**
     * 编辑的前置和后置操作
     * myTable.beforeSave(object data) return true 前置
     * myTable.afterSave(object data)  return true 后
     */
    myTable.beforeSave = function(data) {
        if (this.actionType != 'delete' && this.actionType != 'deleteAll') {
            data.push({"name": "desc", "value": $('#me-desc').html()}); //
        }
        return true;
    };


    $(function(){
        myTable.init();

        // 时间选项
        $('.me-datetime').datetimepicker({
            format: 'YYYY-MM-DD H:mm:s'
        });

        function showErrorAlert (reason, detail) {
            var msg='';
            if (reason==='unsupported-file-type') { msg = "Unsupported format " +detail; }
            else {
                //console.log("error uploading file", reason, detail);
            }
            $('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>'+
                '<strong>File upload error</strong> '+msg+' </div>').prependTo('#alerts');
        }

        $('#me-desc').ace_wysiwyg({
            toolbar:
                [
                    'font',
                    null,
                    'fontSize',
                    null,
                    {name:'bold', className:'btn-info'},
                    {name:'italic', className:'btn-info'},
                    {name:'strikethrough', className:'btn-info'},
                    {name:'underline', className:'btn-info'},
                    null,
                    {name:'insertunorderedlist', className:'btn-success'},
                    {name:'insertorderedlist', className:'btn-success'},
                    {name:'outdent', className:'btn-purple'},
                    {name:'indent', className:'btn-purple'},
                    null,
                    {name:'justifyleft', className:'btn-primary'},
                    {name:'justifycenter', className:'btn-primary'},
                    {name:'justifyright', className:'btn-primary'},
                    {name:'justifyfull', className:'btn-inverse'},
                    null,
                    {name:'createLink', className:'btn-pink'},
                    {name:'unlink', className:'btn-pink'},
                    null,
                    {name:'insertImage', className:'btn-success'},
                    null,
                    'foreColor',
                    null,
                    {name:'undo', className:'btn-grey'},
                    {name:'redo', className:'btn-grey'}
                ],
            'wysiwyg': {
                fileUploadError: showErrorAlert
            }
        }).prev().addClass('wysiwyg-style2');

    });
</script>
<?php $this->endBlock() ?>