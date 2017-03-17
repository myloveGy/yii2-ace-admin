/**
 * Created by liujinxing on 2017/3/14.
 */

(function(window, $) {
    var other, html, mixLoading = null,
        meTables = function (options) {
            return new meTables.fn.init(options);
        };

    meTables.fn = meTables.prototype = {
        constructor: meTables,

        // 初始化配置信息
        init: function (options) {
            if (options !== undefined) this.extend({options: options});
            return this;
        },

        // 搜索
        search: function(){

        },

        // 数据新增
        create: function(){

        },

        // 数据修改
        update: function () {

        },

        // 数据删除
        delete: function() {

        },

        // 数据导出
        export: function(){

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
        }
    };

    meTables.fn.init.prototype = meTables.fn;

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

    // 设置默认配置信息
    meTables.fn.extend({
        options: {
            // 关于地址配置信息
            urlPrefix: "",
            urlSuffix: "",
            url: {
                search: "search",
                create: "create",
                update: "update",
                delete: "delete",
                export: "export",
                editable: "editable",
                deleteAll: "deleteAll"
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

    window.meTables = window.metables = window.mt = meTables;

    return meTables;
})(window, jQuery);