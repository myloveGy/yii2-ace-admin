/**
 * Created by liujinxing on 17-4-4.
 */

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

            // 处理标题
            if (!this.options.grid.caption) {
                this.options.grid.caption = this.options.title;
            }

            // 搜索地址
            if (!this.options.grid.url) {
                this.options.grid.url = this.getUrl("search");
            }

            // 分页选择器
            if (!this.options.grid.pager) {
                this.options.grid.pager = this.options.pageSelector;
            }

            // 添加数据地址
            if (!this.options.createOptions.url) {
                this.options.createOptions.url = this.getUrl("create");
            }

            // 修改数据地址
            if (!this.options.updateOptions.url) {
                this.options.updateOptions.url = this.getUrl("update");
            }

            if (!this.options.grid.editurl) {
                this.options.grid.editurl = this.getUrl("update");
            }

            // 删除数据地址
            if (!this.options.deleteOptions.url) {
                this.options.deleteOptions.url = this.getUrl("deleteAll");
            }

            // 单个删除
            if (this.options.bOperation) {
                if (!this.options.operation.title) {
                    this.options.operation.title = this.getLanguage("operation");
                }

                if (!this.options.operation.formatoptions.delOptions.url) {
                    this.options.operation.formatoptions.delOptions.url = this.getUrl("delete");
                }
            }

            // 处理按钮
            for (var i in this.options.buttons) {
                if (this.options.buttons[i] != null && this.options.buttons[i].show == true) {
                    this.options.buttonOptions[i] = true;
                    if (!this.options.buttons[i].text) {
                        this.options.buttons[i].text = this.getLanguage(i);
                    }
                    this.options.buttonHtml += '<button class="' + this.options.buttons[i]["className"] + '" id="' + this.options.gridSelector.replace("#", "") + "-" + i + '">\
                                <i class="' + this.options.buttons[i]["icon"] + '"></i>\
                            ' + this.options.buttons[i]["text"] + '\
                            </button> ';
                }
            }

            // 处理操作项目
            if (this.options.bOperation && this.options.grid.colModel.length >= 1) {
                this.options.grid.colModel.unshift(this.options.operation);
            }

            // 根据colModel 显示信息
            if (this.options.grid.colModel.length >= 1) {
                this.options.colNames = [];
                var self = this;
                this.options.grid.colModel.forEach(function(value, key) {
                    self.options.colNames[key] = value.title;
                    if (value.gridSearch) {
                        if (!value.gridSearch.type) {
                            value.gridSearch.type = "text";
                        }

                        value.gridSearch.title = value.title;
                        value.gridSearch.name = value.index;
                        var defaultObject = value.gridSearch.type == "select" ?  {"All": self.getLanguage("all")} : null;
                        try {
                            self.options.searchHtml += meGrid[value.gridSearch.type + "Create"](value.gridSearch, value.value, defaultObject);
                        } catch (e) {
                            console.info(e);
                            self.options.searchHtml += meGrid.textCreate(value.gridSearch);
                        }

                    }
                });

                // 处理标题
                if (!this.options.grid.colNames) {
                    this.options.grid.colNames = this.options.colNames;
                }

                // 添加搜索表单
                if (this.options.search.render) {
                    this.options.searchHtml += '<button class="' + this.options.search.button.class + '">\
                    <i class="' + this.options.search.button.icon + '"></i>\
                    ' + this.getLanguage("search") + '\
                    </button>';
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

            // 添加搜索表单
            try {
                $(this.options.searchSelector)[this.options.search.type](this.options.searchHtml);
            } catch (e) {
                console.info(e);
                $(this.options.searchSelector).append(this.options.searchHtml);
            }

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
                        layer.msg(self.getLanguage("selectRow"), {icon: 5});
                    }
                });
            }

            // 删除数据
            if (self.options.buttons.del) {
                $(self.options.gridSelector + "-del").click(function() {
                    var gr = self.grid.jqGrid('getGridParam', 'selarrrow');
                    if (gr != null && gr.length >= 1) {
                        self.grid.jqGrid('delGridRow', gr, self.options.deleteOptions);
                    }
                    else
                        layer.msg(self.getLanguage("selectRow"), {icon: 5});
                });
            }

            // 刷新表格
            if (self.options.buttons.refresh) {
                $(self.options.gridSelector + "-refresh").click(function(evt) {
                    evt.preventDefault();
                    self.refresh(true);
                });
            }

            // 表单搜索
            $(self.options.searchSelector).submit(function(evt){
                evt.preventDefault();
                self.refresh();
            });

            // 数据导出
            if (self.options.buttons.export) {
                $(document).on('click', self.options.gridSelector + "-export", function(evt){
                    evt.preventDefault();
                    self.export();
                });
            }

            $(document).on('ajaxloadstart', function(e) {
                self.grid.jqGrid('GridUnload');
                $('.ui-jqdialog').remove();
            });

            if (typeof func == "function") {
                func();
            }
        },

        // 数据导出
        export: function() {
            this.action = "export";
            var self = this,
                html = '<form action="' + this.getUrl("export") + '" target="_blank" method="POST" class="me-export" style="display:none">';
            html += '<input type="hidden" name="title" value="' + self.options.title + '"/>';
            html += '<input type="hidden" name="_csrf" value="' + $('meta[name=csrf-token]').attr('content') + '"/>';

            // 添加字段信息
            this.options.grid.colModel.forEach(function(k){
                if (k.index != null && (k.bExport == undefined)) {
                    html += '<input type="hidden" name="fields[' + k.index + ']" value="' + k.title + '"/>';
                }
            });

            // 添加查询条件
            var value = $(self.options.searchSelector).serializeArray();
            for (var i in value) {
                if (meTables.empty(value[i]["value"]) || value[i]["value"] == "All") continue;
                html += '<input type="hidden" name="' + value[i]['name'] + '" value="' + value[i]["value"] + '"/>';
            }

            // 表单提交
            var $form = $(html);
            $('body').append($form);
            var deferred = new $.Deferred,
                temporary_iframe_id = 'temporary-iframe-'+(new Date()).getTime()+'-'+(parseInt(Math.random()*1000)),
                temp_iframe = $('<iframe id="'+temporary_iframe_id+'" name="'+temporary_iframe_id+'" \
								frameborder="0" width="0" height="0" src="about:blank"\
								style="position:absolute; z-index:-1; visibility: hidden;"></iframe>')
                    .insertAfter($form);
            $form.append('<input type="hidden" name="temporary-iframe-id" value="'+temporary_iframe_id+'" />');
            temp_iframe.data('deferrer' , deferred);
            $form.attr({
                method:  'POST',
                enctype: 'multipart/form-data',
                target:  temporary_iframe_id //important
            });

            $form.get(0).submit();
            var ie_timeout = setTimeout(function(){
                ie_timeout = null;
                deferred.reject($(document.getElementById(temporary_iframe_id).contentDocument).text());
                $('.me-export').remove();
            } , 500);

            deferred.fail(function(result) {
                if (result) {
                    try {
                        result = $.parseJSON(result);
                        layer.msg(result.errMsg, {icon: result.errCode == 0 ? 6 : 5});
                    } catch (e) {
                        layer.msg(self.getLanguage("responseError"), {icon: 5});
                    }
                } else {
                    layer.msg(self.getLanguage("exportSuccess"), {icon: 6});
                }
            }).always(function() {clearTimeout(ie_timeout);});
            deferred.promise();
        },

        // 刷新页面
        refresh: function(reload, params) {
            if (!params) params = {page: 1};
            if (reload && $(this.options.searchSelector).get(0)) {
                $(this.options.searchSelector).get(0).reset();
            }
            $(this.options.gridSelector).jqGrid("setGridParam", params).trigger("reloadGrid")
        },

        // 获取连接地址
        getUrl: function (strType) {
            return this.options.urlPrefix + this.options.url[strType] + this.options.urlSuffix;
        },

        // 获取语言参数
        getLanguage: function(str, language) {
            if (!language) language = this.options.language;
            return this.language[language][str];
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
        options: {
            // 表格名称
            title: "",
            // 表格选择器
            gridSelector: "#grid-table",
            // 分页选择器
            pageSelector: "#grid-pager",
            // 搜索表单
            searchSelector: "#grid-search-form",
            // 搜索表单内容
            searchHtml: "",
            // 默认按钮信息
            buttonHtml: "",
            // 按钮添加容器
            buttonSelector: "#grid-buttons",
            // 操作项
            bOperation: true,
            // 语言版本
            language: "zh-cn",
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
                viewrecords: true,
                // 每页条数
                rowNum: 15,
                // 可以选择的分页数
                rowList: [15, 25, 30, 50, 100],
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
                height: 350
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
            "zh-cn": {
                "responseError": "服务器繁忙,请稍后再试...",
                "selectRow": "请选择需要处理的行",
                "operation": "操作",
                "add": "添加",
                "edit": "编辑",
                "del": "删除",
                "refresh": "刷新",
                "export": "导出",
                "pleaseInput": "请输入",
                "search": "搜索",
                "exportSuccess": "数据正在导出，请稍后...",
                "all": "全部"
            }
        }
    });

    meGrid.extend({
        ajaxResponse: function(response) {
            try {
                var jsonObject = $.parseJSON(response.responseText);
                return [jsonObject.errCode == 0, jsonObject.errMsg];
            } catch (e) {
                return [false, meGrid.fn.getLanguage("responseError")]
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
        },

        // 处理参数
        handleParams: function (params, prefix) {
            other = "";
            if (params != undefined && typeof params == "object") {
                prefix = prefix ? prefix : '';
                for (var i in params) {
                    other += " " + i + '="' + prefix + params[i] + '"'
                }

                other += " ";
            }

            return other;
        },

        searchParams: function(params) {
            var defaultParams = {
                "id": "search-" + params.name,
                "name": "params[" + params.name + "]",
                // "placeholder": meGrid.fn.getLanguage("pleaseInput") + params.title,
                "class": "form-control"
            }, defaultLabel = {
                // "class": "sr-only",
                "for": "search-" + name
            }, options = params.options, labelOptions = params.labelOptions;

            // 删除多余信息
            delete params.name;
            delete params.options;
            delete params.labelOptions;

            defaultParams = this.extend(defaultParams, params);
            if (options) {
                defaultParams = this.extend(defaultParams, options);
            }

            if (labelOptions) {
                defaultLabel = this.extend(defaultLabel, labelOptions);
            }

            return {
                input: defaultParams,
                label: defaultLabel
            }
        },

        textCreate: function(params) {
            // 默认赋值
            if (!params.placeholder) {
                params.placeholder = meGrid.fn.getLanguage("pleaseInput") + params.title;
            }

            if (!params.labelOptions) {
                params.labelOptions = {"class": "sr-only"};
            }

            var options = this.searchParams(params);

            return '<div class="form-group">\
                <label' + this.handleParams(options.label) + '>' + params.title + '</label>\
                <input type="text"' + this.handleParams(options.input) + '>\
                </div> ';
        },

        selectCreate: function(params, value, defaultObject) {
            var options = this.searchParams(params), i = null, html = "";

            if (defaultObject) {
                for (i in defaultObject) {
                    html += '<option value="' + i + '" selected="selected">' + defaultObject[i] + '</option>';
                }
            }

            if (value) {
                for (i in value) {
                    html += '<option value="' + i + '">' + value[i] + '</option>';
                }
            }

            return '<div class="form-group">\
                <label' + this.handleParams(options.label) + '>' + params.title + '</label>\
                <select' + this.handleParams(options.input) + '>' + html + '</select>\
                </div> ';
        }
    });

    meGrid.fn.extend({
        options: {
            // 搜索信息
            search: {
                render: true,
                type: "append",
                button: {
                    "class": "btn btn-info btn-sm",
                    "icon": "ace-icon fa fa-search"
                }
            },

            // 表格信息
            grid: {
                // 加载之后的处理
                loadComplete : function() {
                    var table = this;
                    setTimeout(function(){
                        meGrid.updatePagerIcons(table);
                        meGrid.enableTooltips(table);
                    }, 0);
                },
                // 发送数据之前的处理
                loadBeforeSend: function(response, request) {
                    console.info(arguments);
                    var data = $(meGrid.fn.options.searchSelector).serializeArray();
                    for (var i in data) {
                        if (data[i]["value"] !== "" && data[i]["value"] !== "All") {
                            request.data += "&" + encodeURIComponent(data[i]["name"]) + "=" + encodeURIComponent(data[i]["value"]);
                        }
                    }
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

            // 默认详情信息
            operation: {
                name: 'myac',
                index: '',
                width: 80,
                fixed: true,
                sortable: false,
                resize: false,
                formatter: 'actions',
                bExport: false,
                formatoptions: {
                    keys: true,
                    delOptions: {
                        // url: meGrid.fn.getUrl("delete"),
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

            //　默认按钮信息
            buttons: {
                add: {
                    show: true,
                    icon: "ace-icon fa fa-plus-circle",
                    className: "btn btn-primary btn-xs"
                },
                edit: {
                    show: true,
                    icon: "ace-icon fa fa-pencil-square-o",
                    className: "btn btn-info btn-xs"
                },
                del: {
                    show: true,
                    icon: "ace-icon fa fa-trash-o ",
                    className: "btn btn-danger btn-xs"
                },
                refresh: {
                    show: true,
                    icon: "ace-icon fa  fa-refresh",
                    className: "btn btn-success btn-xs"
                },
                export: {
                    show: true,
                    icon: "ace-icon glyphicon glyphicon-export",
                    className: "btn btn-warning btn-xs"
                }
            }
        }
    });


    window.meGrid = mg = meGrid;
})(window, jQuery);
