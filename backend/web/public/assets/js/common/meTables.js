/**
 * Created by liujinxing on 2017/3/14.
 */

(function(window) {
    var meTables = function (options) {
        return new meTables.fn.init(options);
    };

    meTables.fn = meTables.prototype = {
        constructor: meTables,

        // 初始化配置信息
        init: function (options) {
            this.options = meTables.extends(this.options, options);
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
        }
    });

    var mixLoading = null;

    meTables.extend({
        // 扩展AJAX
        ajax: function (params) {
            mixLoading = layer.load();
            return $.ajax(params).always(function () {
                layer.close(mixLoading);
            }).fail(function () {
                layer.msg("123love lllllllllll", {icon: 5});
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
            var other = "";
            prefix = prefix ? prefix : '';
            if (params != undefined && typeof params == "object") {
                for (var i in params) {
                    other += " " + i + '="' + prefix + params[i] + '"'
                }

                other += " ";
            }

            return other;
        }
    });

    window.meTables = window.metables = window.mt = meTables;

    return meTables;
})(window);