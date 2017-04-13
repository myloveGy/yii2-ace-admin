<?php
use backend\assets\AppAsset;

AppAsset::loadTimeJavascript($this, 'datetime');

// 定义标题和面包屑信息
$this->title = '管理员日程安排';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('@web/public/assets/css/jquery-ui.custom.min.css', ['depends' => 'backend\assets\AppAsset']);
$this->registerCssFile('@web/public/assets/css/bootstrap-editable.css', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/jquery-ui.custom.min.js', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/jquery.ui.touch-punch.min.js', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/markdown/markdown.min.js', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/markdown/bootstrap-markdown.min.js', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/jquery.hotkeys.min.js', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/bootstrap-wysiwyg.min.js', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/x-editable/bootstrap-editable.min.js', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/x-editable/ace-editable.min.js', ['depends' => 'backend\assets\AppAsset']);
?>
<!--前面导航信息-->
<p id="me-table-buttons">
<!--    <button class="btn btn-white btn-success btn-bold me-table-create">-->
<!--        <i class="ace-icon fa fa-plus bigger-120 blue"></i>-->
<!--        添加-->
<!--    </button>-->
<!--    <button class="btn btn-white btn-danger btn-bold me-table-delete">-->
<!--        <i class="ace-icon fa fa-trash-o bigger-120 red"></i>-->
<!--        删除-->
<!--    </button>-->
<!--    <button class="btn btn-white btn-info btn-bold me-hide">-->
<!--        <i class="ace-icon fa  fa-external-link bigger-120 orange"></i>-->
<!--        隐藏-->
<!--    </button>-->
<!--    <button class="btn btn-white btn-pink btn-bold  me-table-reload">-->
<!--        <i class="ace-icon fa fa-refresh bigger-120 pink"></i>-->
<!--        刷新-->
<!--    </button>-->
<!--    <button class="btn btn-white btn-warning btn-bold me-table-export">-->
<!--        <i class="ace-icon glyphicon glyphicon-export bigger-120 orange2"></i>-->
<!--        导出Excel-->
<!--    </button>-->
</p>
<!--表格数据-->
<table class="table table-striped table-bordered table-hover" id="show-table"></table>
<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    var aAdmins = <?=\yii\helpers\Json::encode($this->params['admins'])?>,
        aStatus = <?=\yii\helpers\Json::encode($status)?>,
        aTimeStatus = <?=\yii\helpers\Json::encode($timeStatus)?>,
        aColors = <?=\yii\helpers\Json::encode($statusColors)?>,
        aTimeColors = <?=\yii\helpers\Json::encode($timeColors)?>;
    aAdmins['0'] = '待定';

    var m = mt({
        title: "管理员日程安排",
        editable: true,
//        oEditFormParams: {				// 编辑表单配置
//            bMultiCols: true,          // 是否多列
//            iColsLength: 2,             // 几列
//            aCols: [2, 4],              // label 和 input 栅格化设置
//            sModalClass: "",			// 弹出模块框配置
//            sModalDialogClass: ""		// 弹出模块的class
//        },
//        oViewTable: {                   // 查看详情配置信息
//            bMultiCols: true,
//            iColsLength: 2
//        }
        table: {
            "aoColumns": [
                {
                    "title": "id ",
                    "data": "id",
                    "sName": "id",
                    "edit": {"type": "hidden"},
                    "search": {"type": "text"},
                    "defaultOrder": "desc"
                },
                {
                    "title": "事件标题",
                    "data": "title",
                    "editable": {
                        validate: function (x) {
                            if (x.length > 100 || x.length < 2) return "长度必须为2到50字符";
                        }
                    },
                    "sName": "title",
                    "edit": {"type": "text", "required": true, "rangelength": "[2, 100]"},
                    "search": {"type": "text"},
                    "bSortable": false
                },
                {
                    "title": "事件描述",
                    "data": "desc",
                    "sName": "desc",
                    "edit": {"type": "div", "id": "me-desc", "class": "wysiwyg-editor", "rows": 5, "required": true, "rangelength": "[2, 255]"},
                    "bSortable": false
                },
                {
                    "title": "开始时间",
                    "data": "start_at",
                    "sName": "start_at",
                    "edit": {"type": "dateTime", "class": "time-format", "required": true},
                    "createdCell": mt.dateTimeString
                },
                {
                    "title": "结束时间",
                    "data": "end_at",
                    "sName": "end_at",
                    "edit": {"type": "dateTime", "required": true, "class": "m-time"},
                    "createdCell": mt.dateTimeString
                },
                {
                    "title": "日程状态",
                    "data": "status",
                    "sName": "status",
                    "value": aStatus,
                    "edit": {"type": "radio", "default": 0, "required": true, "number": true},
                    "search": {"type": "select"},
                    "bSortable": false,
                    "editable": {},
                    "createdCell": function (td, data) {
                        $(td).html(mt.valuesString(aStatus, aColors, data));
                    }
                },
                {
                    "title": "时间状态",
                    "data": "time_status",
                    "sName": "time_status",
                    "value": aTimeStatus,
                    "edit": {"type": "radio", "default": 1, "required": true, "number": true},
                    "search": {"type": "select"},
                    "bSortable": false,
                    "createdCell": function (td, data) {
                        $(td).html(mt.valuesString(aTimeStatus, aTimeColors, data));
                    }
                },
                {
                    "title": "处理人",
                    "data": "admin_id",
                    "sName": "admin_id",
                    "value": aAdmins,
                    "edit": {"type": "select", "required": true, "number": true},
                    "search": {"type": "select"},
                    "createdCell": mt.adminString,
                    "bSortable": false
                },
                {"title": "创建时间", "data": "created_at", "sName": "created_at", "createdCell": mt.dateTimeString},
                {
                    "title": "添加用户",
                    "data": "created_id",
                    "sName": "created_id",
                    "bSortable": false,
                    "createdCell": mt.adminString
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

    mt.fn.extend({
        afterShow: function(data) {
            var html = this.action == "create" ? "" : data.desc;
            $('#me-desc').html(html);
            return true;
        },

        beforeSave: function(data) {
            if (this.action != 'delete' && this.action != 'deleteAll') {
                data.push({"name": "desc", "value": $('#me-desc').html()});
            }
            return true;
        }
    });

    $(function(){
        m.init();

        // 时间选项
        $('.datetime-picker').datetimepicker({
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