<?php
$this->title = 'Yii2 admin JqGrid';

$this->registerCssFile('@web/public/assets/css/ui.jqgrid.css', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/jqGrid/jquery.jqGrid.min.js', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/jqGrid/i18n/grid.locale-cn.js', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/date-time/bootstrap-datepicker.min.js', ['depends' => 'backend\assets\AppAsset']);
?>
<!--显示按钮-->
<p id="grid-buttons"></p>
<div class="well well-sm" id="search-div" style="margin-bottom: 0">
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
<!--显示表格-->
<table id="grid-table"></table>
<!--显示分页-->
<div id="grid-pager"></div>
<?php $this->beginBlock('javascript');?>
<script>
    (function(window, $){
        var meGrid = function(options) {
            return new meGrid.fn._construct(options);
        };

        meGrid.fn = meGrid.prototype = {
            constructor: meGrid,

            // 初始化配置信息
            _construct: function(options) {
                // 处理配置项目
                if (options != undefined) {
                    this.extend({options: options});
                }

                // 处理分页选择项目
                if (!this.options.grid.pager) {
                    this.options.grid.pager = this.options.pageSelector;
                }

                // 处理标题
                if (!this.options.grid.caption) {
                    this.options.grid.caption = this.options.title;
                }

                // 处理查询地址
                if (!this.options.grid.url) {
                    this.options.grid.url = this.getUrl("search");
                }

                // 创建地址
                if (!this.options.createOptions.url) {
                    this.options.createOptions.url = this.getUrl("create");
                }

                // 修改地址
                if (!this.options.updateOptions.url) {
                    this.options.createOptions.url = this.getUrl("update");
                }

                // 删除地址
                if (!this.options.deleteOptions.url) {
                    this.options.deleteOptions.url = this.getUrl("deleteAll");
                }

                // 单个删除地址
                if (this.options.bOperation && !this.options.operation.formatoptions.delOptions.url) {
                    this.options.operation.formatoptions.delOptions.url = this.getUrl("delete");
                }

                // 处理按钮
                for (var i in this.options.buttons) {
                    if (this.options.buttons[i] != null) {
                        this.options.buttonOptions[i] = true;
                        this.options.buttonHtml += '<button class="' + this.options.buttons[i]["className"] + '" id="' + this.options.gridSelector.replace("#", "") + "-" + i + '">\
                                <i class="' + this.options.buttons[i]["icon"] + '"></i>\
                            ' + this.options.buttons[i]["text"] + '\
                            </button> ';
                    }
                }

                return this;
            },

            // 初始化处理
            init: function(func) {
                // 渲染表格
                this.grid = $(this.options.gridSelector).jqGrid(this.options.grid);

                // 添加按钮
                $(this.options.buttonSelector).append(this.options.buttonHtml);

                var self = this;
                // 修改大小
                $(window).on('resize.jqGrid', function () {
                    self.grid.jqGrid('setGridWidth', $(".page-content").width());
                });

                // resize on sidebar collapse/expand
                var parent_column = self.grid.closest('[class*="col-"]');
                $(document).on('settings.ace.jqGrid' , function(ev, event_name) {
                    if( event_name === 'sidebar_collapsed' || event_name === 'main_container_fixed' ) {
                        //setTimeout is for webkit only to give time for DOM changes and then redraw!!!
                        setTimeout(function() {
                            self.grid.jqGrid('setGridWidth', parent_column.width());
                        }, 0);
                    }
                });

                // 添加按钮
                self.grid.jqGrid('navGrid', self.options.pageSelector, self.options.buttonOptions, self.options.updateOptions, self.options.createOptions, self.options.deleteOptions);

                // 添加事件
                $(window).triggerHandler('resize.jqGrid');

                // 添加数据
                if (self.options.buttons.add) {
                    $(self.options.gridSelector + "-add").click(function(evt){
                        evt.preventDefault();
                        self.grid.jqGrid('editGridRow', "new", self.options.createOptions);
                    });
                }

                //　编辑数据
                if (self.options.buttons.edit) {
                    $(self.options.gridSelector + "-edit").click(function(evt){
                        evt.preventDefault();
                        var gr = self.grid.jqGrid('getGridParam','selrow');
                        if (gr != null) {
                            self.grid.jqGrid('editGridRow', gr, self.options.updateOptions);
                        } else {
                            layer.msg(self.language.selectRow, {icon: 5});
                        }
                    });
                }

                // 删除数据
                if (self.options.buttons.del) {
                    $(self.options.gridSelector + "-del").click(function() {
                        var gr = self.grid.jqGrid('getGridParam', 'selarrrow');
                        if (gr != null && gr.length >= 1) {
                            console.info(self.options.deleteOptions)
                            self.grid.jqGrid('delGridRow', gr, self.options.deleteOptions);
                        }
                        else
                            layer.msg(self.language.selectRow, {icon: 5});
                    });
                }

                // 表单搜索
                $("#search-form").submit(function(evt){
                    evt.preventDefault();
                    self.refresh();
                });

                $(document).on('ajaxloadstart', function(e) {
                    self.grid.jqGrid('GridUnload');
                    $('.ui-jqdialog').remove();
                });

                if (typeof func == "function") {
                    func();
                }
            },

            // 刷新页面
            refresh: function(params) {
                if (!params) params = {page: 1};
                $(this.options.gridSelector).jqGrid("setGridParam", params).trigger("reloadGrid")
            },

            // 获取连接地址
            getUrl: function (strType) {
                return this.options.urlPrefix + this.options.url[strType] + this.options.urlSuffix;
            }
        };

        meGrid.fn._construct.prototype = meGrid.fn;

        meGrid.extend = meGrid.fn.extend = function () {
            var name, options,
                target = arguments[0] || {},
                i = 1,
                length = arguments.length;
            if (length === i) {
                target = this;
                --i;
            }

            for (; i < length; i++) {
                if ((options = arguments[i]) != null) {
                    for (name in options) {
                        if (options[name] === target[name]) {
                            continue;
                        }

                        if (typeof target[name] == "object") {
                            target[name] = this.extend(target[name], options[name]);
                        } else if (options[name] !== undefined) {
                            target[name] = options[name];
                        }
                    }
                }
            }

            return target;
        };

        meGrid.fn.extend({
            language: {
                "responseError": "服务器繁忙,请稍后再试...",
                "selectRow": "请选择需要处理的行",
                "operation": "操作",
                "create": "添加",
                "update": "编辑",
                "delete": "删除",
                "reload": "刷新",
                "export": "导出"
            }
        });

        meGrid.extend({
            ajaxResponse: function(response) {
                try {
                    var jsonObject = $.parseJSON(response.responseText);
                    console.info(jsonObject.errCode == 0, jsonObject.errMsg);
                    return [jsonObject.errCode == 0, jsonObject.errMsg];
                } catch (e) {
                    console.info(e);
                    return [false, meGrid.fn.language.responseError]
                }
            },

            // 修改表单样式
            style_edit_form: function(form) {
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
            },

            // 删除表单样式
            style_delete_form: function(form) {
                var buttons = form.next().find('.EditButton .fm-button');
                buttons.addClass('btn btn-sm btn-white btn-round').find('[class*="-icon"]').hide();//ui-icon, s-icon
                buttons.eq(0).addClass('btn-danger').prepend('<i class="ace-icon fa fa-trash-o"></i>');
                buttons.eq(1).addClass('btn-default').prepend('<i class="ace-icon fa fa-times"></i>');
            },

            // 删除表单之前回调
            beforeDeleteCallback: function(e) {
                var form = $(e[0]);
                if(form.data('styled')) return false;
                form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
                meGrid.style_delete_form(form);
                form.data('styled', true);
            },

            // 修改之前回调
            beforeEditCallback: function(e) {
                var form = $(e[0]);
                form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
                meGrid.style_edit_form(form);
            },

            // 修改分页显示
            updatePagerIcons: function(table) {
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
            },

            enableTooltips: function(table) {
                $('.navtable .ui-pg-button').tooltip({container:'body'});
                $(table).find('.ui-pg-div').tooltip({container:'body'});
            }

        });

        meGrid.fn.extend({
            options: {

                // 表格名称
                title: "",
                // 表格选择器
                gridSelector: "#grid-table",
                // 分页选择器
                pageSelector: "#grid-pager",

                // 关于地址配置信息
                urlPrefix: "",
                urlSuffix: "",
                url: {
                    search: "search",
                    create: "create",
                    update: "update",
                    delete: "delete",
                    export: "export",
                    upload: "upload",
                    deleteAll: "delete-all"
                },
                grid: {
                    // 显示详情
                    viewrecords : true,
                    // 每页条数
                    rowNum: 10,
                    // 可以选择的分页数
                    rowList: [10,　20,　30, 50, 100],
                    // 全选是否添加
                    altRows: true,
                    // 多行显示
                    multiselect: true,
                    // multikey: "ctrlKey",
                    multiboxonly: true,
                    // 数据加载类型
                    datatype: "json",
                    // 请求方式
                    mtype: "post",
                    // 返回数据的格式
                    jsonReader: {
                        id: "id",
                        root: "data.rows",
                        page: "data.page",
                        total: "data.total",
                        records: "data.records",
                        repeatiems: false
                    },

                    // 高度
                    height: 350,

                    // 加载之后的处理
                    loadComplete : function() {
                        var table = this;
                        setTimeout(function(){
                            meGrid.updatePagerIcons(table);
                            meGrid.enableTooltips(table);
                        }, 0);
                    }
                },

                // 创建配置选项
                createOptions: {
                    closeAfterAdd: true,
                    recreateForm: true,
                    viewPagerButtons: false,
                    beforeShowForm : function(e) {
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar')
                        .wrapInner('<div class="widget-header" />');
                        meGrid.style_edit_form(form);
                    },

                    afterSubmit: meGrid.ajaxResponse
                },

                // 修改配置选项
                updateOptions: {
                    recreateForm: true,
                    closeAfterEdit: true,
                    beforeShowForm : function(e) {
                        var form = $(e[0]);
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
                        meGrid.style_edit_form(form);
                    },

                    afterSubmit: meGrid.ajaxResponse
                },

                // 删除配置选项
                deleteOptions: {
                    recreateForm: true,
                    beforeShowForm : function(e) {
                        var form = $(e[0]);
                        if (form.data('styled')) return false;
                        form.closest('.ui-jqdialog').find('.ui-jqdialog-titlebar').wrapInner('<div class="widget-header" />');
                        meGrid.style_delete_form(form);
                        form.data('styled', true);
                    },

                    afterSubmit: meGrid.ajaxResponse
                },

                // 按钮选项
                buttonOptions: {
                    edit: false,
                    editicon : 'ace-icon fa fa-pencil blue',
                    add: false,
                    addicon : 'ace-icon fa fa-plus-circle purple',
                    del: false,
                    delicon : 'ace-icon fa fa-trash-o red',
                    search: false,
                    refresh: false,
                    refreshicon : 'ace-icon fa fa-refresh green',
                    view: true,
                    viewicon : 'ace-icon fa fa-search-plus grey'
                }
            },

            language: {
                "responseError": "服务器繁忙,请稍后再试...",
                "selectRow": "请选择需要处理的行",
                "operation": "操作",
                "create": "添加",
                "update": "编辑",
                "delete": "删除",
                "reload": "刷新",
                "export": "导出"
            }
        });

        // 操作按钮
        meGrid.fn.extend({
            options: {
                bOperation: true,
                operation: {
                    name: 'myac',
                    index: '',
                    width: 80,
                    title: meGrid.fn.language.operation,
                    fixed: true,
                    sortable: false,
                    resize: false,
                    formatter: 'actions',
                    formatoptions: {
                        keys: true,
                        delOptions: {
                            url: "delete",
                            recreateForm: true,
                            beforeShowForm: meGrid.beforeDeleteCallback,
                            afterSubmit: meGrid.ajaxResponse
                        },

                        onSuccess: function(response) {
                            var arr = meGrid.ajaxResponse(response);
                            layer.msg(arr[1], {icon: arr[0] ? 6 : 5});
                            return arr[0];
                        }
                    },
                    search: false
                },

                buttonHtml: "",
                buttonSelector: "#grid-buttons",
                buttons: {
                    add: {
                        text: meGrid.fn.language.create,
                        icon: "ace-icon fa fa-plus-circle",
                        className: "btn btn-primary btn-xs"
                    },
                    edit: {
                        text: meGrid.fn.language.update,
                        icon: "ace-icon fa fa-pencil-square-o",
                        className: "btn btn-info btn-xs"
                    },
                    del: {
                        text: meGrid.fn.language.delete,
                        icon: "ace-icon fa fa-trash-o ",
                        className: "btn btn-danger btn-xs"
                    },
                    refresh: {
                        text: meGrid.fn.language.reload,
                        icon: "ace-icon fa  fa-refresh",
                        className: "btn btn-success btn-xs"
                    },
                    export: {
                        text: meGrid.fn.language.export,
                        icon: "ace-icon glyphicon glyphicon-export",
                        className: "btn btn-warning btn-xs"
                    }
                }
            }
        });

        window.meGrid = mg = meGrid;
    })(window, jQuery);

    var g = mg({
        title: "中国地址信息",
        grid: {
            // 子表处理
            subGrid : true,
            // 选项
            subGridOptions : {
                plusicon : "ace-icon fa fa-plus center bigger-110 blue",
                minusicon  : "ace-icon fa fa-minus center bigger-110 blue",
                openicon : "ace-icon fa fa-chevron-right center orange"
            },
            // 子表加载数据处理
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

            colModel: [
                {
                    name:'id',
                    index: 'id',
                    title: "ID",
                    width: 60,
                    sorttype: "int",
                    editable: true
                },
                {
                    name:'name',
                    index: 'name',
                    title: "名称",
                    width: 90,
                    editable: true,
                    sorttype: "date",
                    editoptions: {size: "20", "minlength": "2", "maxlength": "255"}

                },
                {
                    name:'pid',
                    index: 'pid',
                    title: "父类ID",
                    width: 50,
                    editable: true,
                    editoptions: {size: "20", maxlength:"30"}
                }
            ],

            // 发送数据之前的处理
            loadBeforeSend: function() {
                arguments[1].data += "&" + $("#search-form").serialize();
            }
        }
    });

    $(function(){
        g.init(function(){
            console.info(g.options);
        });
    });
</script>
<?php $this->endBlock(); ?>
