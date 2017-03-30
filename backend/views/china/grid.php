<?php
$this->title = 'Yii2 admin JqGrid';

$this->registerCssFile('@web/public/assets/css/ui.jqgrid.css', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/jqGrid/jquery.jqGrid.min.js', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/jqGrid/i18n/grid.locale-cn.js', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/date-time/bootstrap-datepicker.min.js', ['depends' => 'backend\assets\AppAsset']);
?>
<style type="text/css">
    .well form label input {margin-top: 1px;}
</style>
<div class="well well-sm" id="search-div">
    <form class="form-inline" role="form" id="search-form">
        <div class="form-group">
            <label class="sr-only" for="id">ID</label>
            <input type="text" class="form-control" id="search-id" name="params[id]" placeholder="请输入ID">
        </div>
        <div class="form-group">
            <label  for="name">名称</label>
            <input type="text" class="form-control" id="search-name" name="params[name]" placeholder="请输入名称">
        </div>
        <div class="form-group">
            <label class="sr-only" for="pid">父类ID</label>
            <input type="text" class="form-control" id="search-pid" name="params[pid]" placeholder="请输入父类ID">
        </div>
        <button class="btn btn-info btn-sm">
            <i class="ace-icon fa fa-search align-top bigger-125"></i>
            查询
        </button>
    </form>
</div>
<div id="grid-tools"></div>
<table id="grid-table"></table>
<div id="grid-pager"></div>
<script type="text/javascript">
    var $path_base = "..";//in Ace demo this will be used for editurl parameter
</script>
<?php $this->beginBlock('javascript');?>
<script>
//    jQuery.fn.fmatter.rowactions.call(this,'save');
    $(function(){
        var grid_selector = "#grid-table";
        var pager_selector = "#grid-pager";

        // resize to fit page size
        $(window).on('resize.jqGrid', function () {
            $(grid_selector).jqGrid('setGridWidth', $(".page-content").width());
        });

        // resize on sidebar collapse/expand
        var parent_column = $(grid_selector).closest('[class*="col-"]');
        $(document).on('settings.ace.jqGrid' , function(ev, event_name, collapsed) {
            if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
                //setTimeout is for webkit only to give time for DOM changes and then redraw!!!
                setTimeout(function() {
                    $(grid_selector).jqGrid('setGridWidth', parent_column.width());
                }, 0);
            }
        });

        $(grid_selector).jqGrid({
            // direction: "rtl",
            // subgrid options
            subGrid : true,
            // subGridModel: [{ name : ['No','Item Name','Qty'], width : [55,200,80] }],
            // datatype: "xml",
            subGridOptions : {
                plusicon : "ace-icon fa fa-plus center bigger-110 blue",
                minusicon  : "ace-icon fa fa-minus center bigger-110 blue",
                openicon : "ace-icon fa fa-chevron-right center orange"
            },

            // for this example we are using local data
            subGridRowExpanded: function (subgridDivId, rowId) {
                console.info(rowId);
                var subgridTableId = subgridDivId + "_t";
                $("#" + subgridDivId).html("<table id='" + subgridTableId + "'></table>");
                $("#" + subgridTableId).jqGrid({
                    datatype: "json",
                    url: "<?=\yii\helpers\Url::toRoute('search')?>",
                    mtype: "post",
                    colNames: ["id", "名称", "父类ID"],
                    postData: {
                        "params[pid]":rowId
                    },
                    colModel: [
                        { name: 'id', index: "id",  width: 200},
                        { name: 'name', index: "name", width: 200 },
                        { name: 'pid', index: "pid",  width: 200 }
                    ],
                    jsonReader: {
                        id: "id",
                        root: "data.rows",
                        page: "data.page",
                        total: "data.total",
                        records: "data.records",
                        repeatiems: false
                    }
                });
            },

            url: "<?=\yii\helpers\Url::toRoute('search')?>",
            datatype: "json",
            mtype: "post",
            jsonReader: {
                id: "id",
                root: "data.rows",
                page: "data.page",
                total: "data.total",
                records: "data.records",
                repeatiems: false
            },
            height: 350,
            colNames:[' ', 'ID', '名称', '父类ID'],
            colModel:[
                {
                    name: 'myac',
                    index: '',
                    width: 80,
                    fixed: true,
                    sortable: false,
                    resize: false,
                    formatter: 'actions',
                    formatoptions: {
                        keys: true,
                        delOptions: {
                            url: "delete",
                            recreateForm: true,
                            beforeShowForm: beforeDeleteCallback,
                            afterSubmit: ajaxResponse
                        },

                        aftersave: function () {
                            alert(123)
                        }
                    },
                    search: false
                },
                {name:'id', index: 'id', width: 60, sorttype: "int", editable: true},
                {
                    name:'name',
                    index: 'name',
                    width: 90,
                    editable: true,
                    sorttype: "date",
                    editoptions: {size: "20", "minlength": "2", "maxlength": "255"}
                    /* unformat: pickDate */
                },
                {name:'pid', index: 'pid', width: 50, editable: true, editoptions: {size: "20", maxlength:"30"}, searchoptions: {
                    sopt:["eq"]
                }}
                // {name:'stock',index:'stock', width:70, editable: true,edittype:"checkbox",editoptions: {value:"Yes:No"},unformat: aceSwitch},
                // {name:'ship',index:'ship', width:90, editable: true,edittype:"select",editoptions:{value:"FE:FedEx;IN:InTime;TN:TNT;AR:ARAMEX"}},
                // {name:'note',index:'note', width:150, sortable:false,editable: true,edittype:"textarea", editoptions:{rows:"2",cols:"10"}}
            ],

            viewrecords : true,
            rowNum: 10,
            rowList: [10,　20,　30, 50, 100],
            pager : pager_selector,
            altRows: true,
            // toppager: true,
            // 多行显示
            multiselect: true,
            // multikey: "ctrlKey",
            multiboxonly: true,

            loadComplete : function() {
                var table = this;
                setTimeout(function(){
                    updatePagerIcons(table);
                    enableTooltips(table);
                }, 0);
            },

            loadBeforeSend: function() {
                arguments[1].data += "&" + $("#search-form").serialize();
            },
            editurl: "<?=\yii\helpers\Url::toRoute('update')?>",
            caption: "中国省份信息"
        });

        function ajaxResponse(response) {
            try {
                var jsonObject = $.parseJSON(response.responseText);
                return [jsonObject.errCode == 0, jsonObject.errMsg];
            } catch (e) {
                console.info(e);
                return [false, "服务器繁忙,请稍后再试..."]
            }
        }

        // 表单搜索
        $("#search-form").submit(function(evt){
            evt.preventDefault();
            jQuery(grid_selector).jqGrid("setGridParam", {
               page: 1
            }).trigger("reloadGrid");
        });

        $(window).triggerHandler('resize.jqGrid');

        // switch element when editing inline
        function aceSwitch(cellvalue, options, cell) {
            setTimeout(function(){
                $(cell) .find('input[type=checkbox]')
                .addClass('ace ace-switch ace-switch-5')
                .after('<span class="lbl"></span>');
            }, 0);
        }

        // navButtons
        jQuery(grid_selector).jqGrid('navGrid', pager_selector,
        {
            //navbar options
            edit: true,
            editicon : 'ace-icon fa fa-pencil blue',
            add: true,
            addicon : 'ace-icon fa fa-plus-circle purple',
            del: true,
            delicon : 'ace-icon fa fa-trash-o red',
            search: false,
            // searchicon : 'ace-icon fa fa-search orange',
            refresh: true,
            refreshicon : 'ace-icon fa fa-refresh green',
            view: true,
            viewicon : 'ace-icon fa fa-search-plus grey',
        },
        {
            //edit record form
            // closeAfterEdit: true,
            // width: 700,
            url: "<?=\yii\helpers\Url::toRoute('update')?>",
            recreateForm: true,
            closeAfterEdit: true,
            beforeShowForm : function(e) {
                var form = $(e[0]);
                form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
                style_edit_form(form);
            },

            afterSubmit: ajaxResponse
        },
        {
            //new record form
            //width: 700,
            closeAfterAdd: true,
            recreateForm: true,
            viewPagerButtons: false,
            url: "<?=\yii\helpers\Url::toRoute('create')?>",
            beforeShowForm : function(e) {
                var form = $(e[0]);
                form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar')
                .wrapInner('<div class="widget-header" />');
                style_edit_form(form);
            },
            afterSubmit: ajaxResponse
        },
        {
            // delete record form
            url: "delete",
            recreateForm: true,
            beforeShowForm : function(e) {
                var form = $(e[0]);
                if (form.data('styled')) return false;
                form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
                style_delete_form(form);
                form.data('styled', true);
            },
            afterSubmit: ajaxResponse
        }, false,
        {
            recreateForm: true,
            beforeShowForm: function(e){
                var form = $(e[0]);
                form.closest('.ui-jqdialog').find('.ui-jqdialog-title').wrap('<div class="widget-header" />')
            }
        }
        );

        // 修改表单样式
        function style_edit_form(form) {
            form.find('input[name=sdate]').datepicker({format:'yyyy-mm-dd' , autoclose:true})
            .end().find('input[name=stock]')
            .addClass('ace ace-switch ace-switch-5').after('<span class="lbl"></span>');
            var buttons = form.next().find('.EditButton .fm-button');
            buttons.addClass('btn btn-sm').find('[class*="-icon"]').hide();
            buttons.eq(0).addClass('btn-primary').prepend('<i class="ace-icon fa fa-check"></i>');
            buttons.eq(1).prepend('<i class="ace-icon fa fa-times"></i>');
            buttons = form.next().find('.navButton a');
            buttons.find('.ui-icon').hide();
            buttons.eq(0).append('<i class="ace-icon fa fa-chevron-left"></i>');
            buttons.eq(1).append('<i class="ace-icon fa fa-chevron-right"></i>');
        }

        // 删除表单样式
        function style_delete_form(form) {
            var buttons = form.next().find('.EditButton .fm-button');
            buttons.addClass('btn btn-sm btn-white btn-round').find('[class*="-icon"]').hide();//ui-icon, s-icon
            buttons.eq(0).addClass('btn-danger').prepend('<i class="ace-icon fa fa-trash-o"></i>');
            buttons.eq(1).addClass('btn-default').prepend('<i class="ace-icon fa fa-times"></i>');
        }

        // 删除表单之前回调
        function beforeDeleteCallback(e) {
            var form = $(e[0]);
            if(form.data('styled')) return false;
            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
            style_delete_form(form);
            form.data('styled', true);
        }

        // 修改之前回调
        function beforeEditCallback(e) {
            var form = $(e[0]);
            form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />')
            style_edit_form(form);
        }

        // 修改分页显示
        function updatePagerIcons(table) {
            var replacement = {
                'ui-icon-seek-first' : 'ace-icon fa fa-angle-double-left bigger-140',
                'ui-icon-seek-prev' : 'ace-icon fa fa-angle-left bigger-140',
                'ui-icon-seek-next' : 'ace-icon fa fa-angle-right bigger-140',
                'ui-icon-seek-end' : 'ace-icon fa fa-angle-double-right bigger-140'
            };
            $('.ui-pg-table:not(.navtable) > tbody > tr > .ui-pg-button > .ui-icon').each(function(){
                var icon = $(this);
                var $class = $.trim(icon.attr('class').replace('ui-icon', ''));
                if ($class in replacement) icon.attr('class', 'ui-icon '+ replacement[$class]);
            })
        }

        function enableTooltips(table) {
            $('.navtable .ui-pg-button').tooltip({container:'body'});
            $(table).find('.ui-pg-div').tooltip({container:'body'});
        }

        $(document).on('ajaxloadstart', function(e) {
            $(grid_selector).jqGrid('GridUnload');
            $('.ui-jqdialog').remove();
        });
    });
</script>
<?php $this->endBlock(); ?>
