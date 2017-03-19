/**
 * Created by liujinxing on 2017/3/14.
 */

(function(window, $) {
    var other, html, i, mixLoading = null,
        meTables = function (options) {
            return new meTables.fn._construct(options);
        };

    meTables.fn = meTables.prototype = {
        constructor: meTables,

        // 初始化配置信息
        _construct: function(options) {
            // 初始化数据
            this.table = null;
            this.action = "construct";
            this.data = {};

            // 先处理表格的回调函数
            var self = this;
            this.options.table.fnServerData = function(sSource, aoData, fnCallback) {
                var attributes = aoData[2].value.split(","),
                    mSort 	   = (attributes.length + 1) * 5 + 2;

                // 添加查询条件
                var data = $(self.options.sSearchForm).serializeArray();
                for (i in data) {
                    if (!meTables.empty(data[i]["value"]) && data[i]["value"] != "All") {
                        aoData.push({"name": "params[" + data[i]['name'] + "]", "value": data[i]["value"]});
                    }
                }

                // 添加排序字段信息
                self.push(aoData, {"orderBy": attributes[parseInt(aoData[mSort].value)]}, "params");

                // 添加其他字段信息
                self.push(aoData, self.options.params, "params");

                // ajax请求
                meTables.ajax({
                    url: sSource,
                    data: aoData,
                    type: self.options.sMethod,
                    dataType: 'json'
                }).done(function(data){
                    if (data.errCode != 0) {
                        return layer.msg(self.getLanguage("sAppearError") + data.errMsg, {
                            time:2000,
                            icon:5
                        });
                    }

                    $.fn.dataTable.defaults['bFilter'] = true;
                    fnCallback(data.data);
                });
            };

            // 属性覆盖继承
            if (options !== undefined) this.extend({options: options});
            this.options.table.oLanguage = this.getLanguage("dataTables", "*");
            this.options.table.sAjaxSource = this.getUrl("search");

            // 详情信息
            if (this.options.bDetail) {
                this.options.details.table.oLanguage = this.options.table.oLanguage;
                if (this.options.details.table.fnServerData == undefined) {
                    this.options.details.table.fnServerData = function(sSource, aoData, fnCallback) {
                        self.push(aoData, self.options.details.params);
                        // ajax请求
                        meTables.ajax({
                            url: sSource,
                            data: aoData,
                            type: 'post',
                            dataType: 'json'
                        }).done(function (data) {
                            if (data.errCode != 0) {
                                return layer.msg(self.getLanguage("sAppearError") + data.errMsg, {
                                    time: 2000,
                                    icon: 5
                                });
                            }
                            $.fn.dataTable.defaults['bFilter'] = true;
                            fnCallback(data.data);
                            if (self.options.details.tableObject) self.options.details.tableObject.child(function () {
                                return $(self.options.details.sTable).parent().html();
                            }).show();
                        });
                    }
                }
            }

            return this;
        },

        // 执行操作
        init: function () {
            this.action = "init";
            this.table = $(this.options.sTable).DataTable(this.options.table);	// 初始化主要表格
            return this;
        },

        // 搜索
        search: function(params){
            this.action = "search";
            if (!params) params = true;
            this.table.draw(params);
        },

        // 数据新增
        create: function(){
            this.action = "create";

        },

        // 数据修改
        update: function () {
            this.action = "update";
            return this;
        },

        // 数据删除
        delete: function() {
            this.action = "delete";
        },

        // 删除全部数据
        deleteAll: function() {
            this.action = "deleteAll";
        },

        // 数据导出
        export: function(){
            this.action = "export";
            var self = this,
                html = '<form action="' + this.getUrl("export") + '" target="_blank" method="POST" class="me-export" style="display:none">';
            html += '<input type="hidden" name="sTitle" value="' + self.options.title + '"/>';
            html += '<input type="hidden" name="_csrf" value="' + $('meta[name=csrf-token]').attr('content') + '"/>';

            // 添加字段信息
            this.options.table.aoColumns.forEach(function(k, v){
                if (k.data != null && (k.isExport == undefined)) html += '<input type="hidden" name="aFields[' + k.data + ']" value="' + k.title + '"/>';
            });

            // 添加查询条件
            var value = $(self.options.sSearchForm).serializeArray();
            for (var i in value) {
                if (empty(value[i]["value"]) || value[i]["value"] == "All") continue;
                html += '<input type="hidden" name="params[' + value[i]['name'] + ']" value="' + value[i]["value"] + '"/>';
            }

            // 表单提交
            var $form    = $(html);
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

            deferred
                .fail(function(result) {
                    if (result) {
                        try {
                            result = $.parseJSON(result);
                            layer.msg(result.errMsg, {icon: result.errCode == 0 ? 6 : 5});
                        } catch (e) {
                            self.ajaxFail();
                        }
                    } else {
                        layer.msg(self.language.meTables.sExport, {icon: 6});
                    }

                })
                .always(function() {clearTimeout(ie_timeout);});
            deferred.promise();
        },

        // 获取连接地址
        getUrl: function (strType) {
            return this.options.urlPrefix + this.options.url[strType] + this.options.urlSuffix;
        },

        // 获取语言配置信息
        getLanguage: function() {
            if (arguments.length > 1 && this.language[this.options.language][arguments[0]]) {
                return arguments[1] == "*" ?
                    this.language[this.options.language][arguments[0]] :
                    this.language[this.options.language][arguments[0]][arguments[1]];
            }

            return this.language[this.options.language].meTables[arguments[0]];
        },

        push: function(obj, value, name) {
              if (value !== undefined && !meTables.empty(value)) {
                  if (name == undefined) {
                      for (i in value) {
                          obj.push({"name": i, "value": value[i]});
                      }
                  } else {
                      for (i in value) {
                          obj.push({"name": name + "[" + i + "]", "value": value[i]});
                      }
                  }
              }
        }
    };

    meTables.fn._construct.prototype = meTables.fn;

    meTables.extend = meTables.fn.extend = function () {
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

    meTables.extend({
        // 扩展AJAX
        ajax: function (params) {
            mixLoading = layer.load();
            return $.ajax(params).always(function () {
                layer.close(mixLoading);
            }).fail(function () {
                layer.msg(meTables().getLanguage("sServerError"), {icon: 5});
            });
        },

        // 判断是否存在数组中
        inArray: function (value, array) {
            if (typeof array == "object") {
                for (var i in array) {
                    if (array[i] === value) return true;
                }
            }

            return false;
        },

        // 首字母大写
        ucFirst: function (str) {
            return str.substr(0, 1).toUpperCase() + str.substr(1);
        },

        // 是否为空
        empty: function (value) {
            return value === undefined || value === "" || value === null;
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

        inputCreate: function(params) {
            if (!params.type) params.type = "text";
            return "<input" + this.handleParams(params) + "/>";
        },

        iFrameRequest: function() {

        },

        passwordCreate: function(params) {
            params.type = "password";
            return this.inputCreate(params);
        },

        radioCreate: function(params, label) {
            html = "";
            if (params.value) {
                params.type = "radio";
                var v = params.value;
                for (var i in v) {
                    params.value = i;
                    html += "<label" + this.handleParams(label) + ">" + this.inputCreate(params) + " " + v[i] + " </label>";
                }
            }

            return html;
        },

        checkboxCreate: function(params, label) {
            html = "";
            if (params.value) {
                params.type = "checkbox";
                var v = params.value;
                for (var i in v) {
                    params.value = i;
                    html += "<label" + this.handleParams(label) + ">" + this.inputCreate(params) + " " + v[i] + " </label>";
                }
            }

            return html;
        },

        selectCreate: function(params, options) {
            html = "";
            if (params.value) {
                var v = params.value;
                delete params.value;
                html += "<select " + this.handleParams(params) + ">";
                for (var i in v) {
                    html += "<option value=\""+ i +"\"" + this.handleParams(options) + "> " + v[i] + " </option>"
                }
            }

            return html;
        },

        textareaCreate: function(params) {
            html = params.value + "</textarea>";
            delete params.value;
            console.info(html);
            return "<textarea" + this.handleParams(params) + ">" + html;
        }
    });

    // 设置默认配置信息
    meTables.fn.extend({
        options: {
            title: "",// 表格的标题
            language: "zh-cn",      // 使用语言
            sModal: "#table-model", // 编辑Modal选择器
            sTable:  "#show-table", 	// 显示表格选择器
            sFormId: "#edit-form",		// 编辑表单选择器
            sMethod: "POST",			// 查询数据的请求方式
            bCheckbox: true,			// 需要多选框
            params: null,				// 请求携带参数
            sSearchHtml: "",				// 搜索信息额外HTML
            sSearchType: "middle",			// 搜索表单位置
            sSearchForm: "#search-form",	// 搜索表单选择器

            aFileSelector: [],				// 上传文件选择器

            // 编辑表单信息
            form: {
                "method": "post",
                "id": "edit-form",
                "class":  "form-horizontal",
                "name":   "edit-form"
            },

            // 表单编辑其他信息
            editFormOther: {				// 编辑表单配置
                bMultiCols: false,          // 是否多列
                iColsLength: 1,             // 几列
                aCols: [3, 9],              // label 和 input 栅格化设置
                sModalClass: "",			// 弹出模块框配置
                sModalDialogClass: ""		// 弹出模块的class
            },

            // 关于详情的配置
            bViewFull: false, // 详情打开的方式 1 2 打开全屏
            oViewConfig: {
                type: 1,
                shade: 0.3,
                shadeClose: true,
                maxmin: true,
                area: ['50%', 'auto']
            },

            oViewTable: {                   // 查看详情配置信息
                bMultiCols: false,
                iColsLength: 1
            },

            // 行内编辑
            bInline: false,	// 是否开启行内编辑
            oInline: {},	// 行内编辑对象信息
            pk: "id",		// 行内编辑pk索引值

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
                editable: "editable",
                deleteAll: "deleteAll"
            },

            // dataTables 表格默认配置对象信息
            table: {
                // "fnServerData": fnServerData,		// 获取数据的处理函数
                // "sAjaxSource":      "search",		// 获取数据地址
                "bLengthChange": true, 				// 是否可以调整分页
                "bAutoWidth": false,           	// 是否自动计算列宽
                "bPaginate": true,			    // 是否使用分页
                "iDisplayStart":  0,
                "iDisplayLength": 10,
                "bServerSide": true,		 		// 是否开启从服务器端获取数据
                "bRetrieve": true,
                "bDestroy": true,
                // "processing": true,				    // 是否使用加载进度条
                "sPaginationType":  "full_numbers"     // 分页样式
                // "oLanguage": meTables().getLanguage("dataTables", "*")
                // "oLanguage":        oTableLanguage,	// 语言配置
                // "order": [[1, "desc"]]       // 默认排序
            },

            // 详情配置信息
            bDetail: false, // 是否开启详情
            details: {
                sTable: "#detail-table",
                sModal: "#detail-modal",
                sFormId: "#detail-form",
                urlPrefix: "", //self.options.sBaseUrl, // 详情编辑的统一前缀
                urlSuffix: "",
                url: {
                    "search": "view",  // 查询
                    "insert": "create", // 创建
                    "update": "update",	// 修改
                    "delete": "delete" // 删除
                },
                sClickSelect: "td.details-control",
                table: 	{
                    "bPaginate": false,             // 不使用分页
                    "bLengthChange": false,         // 是否可以调整分页
                    "bServerSide": true,		 	// 是否开启从服务器端获取数据
                    "bAutoWidth": false,
                    "searching": false,				// 搜索
                    "ordering": false			 	// 排序
                }
            }
        },

        // 语言配置
        language: {
            "zh-cn": {
                // 我的信息
                meTables: {
                    "oOperation": {
                        "sTitle": "操作",
                        "sView": "查看",
                        "sEdit": "编辑",
                        "sDelete": "删除"
                    },

                    "sInfo": "详情",
                    "sInsert": "新增",
                    "sEdit": "编辑",
                    "sExport": "数据正在导出, 请稍候...",
                    "sAppearError": "出现错误",
                    "sServerError": "服务器繁忙,请稍候再试...",
                    "oDelete": {
                        "determine": "确定",
                        "cancel": "取消",
                        "confirm": "您确定需要删除这_LENGTH_条数据吗?",
                        "confirmOperation": "确认操作",
                        "cancelOperation": "您取消了删除操作!",
                        "noSelect": "没有选择需要删除的数据"
                    },

                    "operationError": "操作有误"
                },

                // dataTables 表格
                dataTables: {
                    // 显示
                    "sLengthMenu": 	 "每页 _MENU_ 条记录",
                    "sZeroRecords":  "没有找到记录",
                    "sInfo": 		 "显示 _START_ 到 _END_ 共有 _TOTAL_ 条数据",
                    "sInfoEmpty": 	 "无记录",
                    "sInfoFiltered": "(从 _MAX_ 条记录过滤)",
                    "sSearch": 		"搜索：",
                    // 分页
                    "oPaginate": {
                        "sFirst": 	 "首页",
                        "sPrevious": "上一页",
                        "sNext": 	 "下一页",
                        "sLast": 	 "尾页"
                    }
                }
            }
        }
    });

    window.meTables = window.metables = window.mt = meTables;

    return meTables;
})(window, jQuery);