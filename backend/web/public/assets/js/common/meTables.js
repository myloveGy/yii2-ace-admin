/**
 * Created by liujinxing on 2017/3/14.
 */

(function(window, $) {
    var other, html, i, mixLoading = null,
        meTables = function (options) {
            return new meTables.fn._construct(options);
        };

    // 时间格式化
    Date.prototype.Format = function(fmt) {
        var o = {
            "M+": this.getMonth() + 1,
            "d+": this.getDate(),
            "h+": this.getHours(),
            "m+": this.getMinutes(),
            "s+": this.getSeconds(),
            "q+": Math.floor((this.getMonth() + 3) / 3),
            "S": this.getMilliseconds()
        };

        if (/(y+)/.test(fmt)) {
            fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4-RegExp.$1.length));
        }

        for (var k in o) {
            if (new RegExp("("+k+")").test(fmt)) {
                fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]):(("00"+o[k]).substr((""+o[k]).length)));
            }
        }

        return fmt;
    };

    meTables.fn = meTables.prototype = {
        constructor: meTables,

        // 初始化配置信息
        _construct: function(options) {
            // 初始化数据
            this.table = null;
            this.action = "construct";
            var self = this;
            $.fn.dataTable.defaults['bFilter'] = true;
            this.options.table.fnServerData = function(sSource, aoData, fnCallback) {
                var attributes = aoData[2].value.split(","),
                    mSort 	   = (attributes.length + 1) * 5 + 2;

                // 添加查询条件
                var data = $(meTables.fn.options.sSearchForm).serializeArray();
                for (i in data) {
                    if (!meTables.empty(data[i]["value"]) && data[i]["value"] != "All") {
                        aoData.push({"name": "params[" + data[i]['name'] + "]", "value": data[i]["value"]});
                    }
                }

                // 添加排序字段信息
                self.push(aoData, {"orderBy": attributes[parseInt(aoData[mSort].value)]}, "params");

                // 添加其他字段信息
                meTables.fn.push(aoData, self.options.params, "params");

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

                    fnCallback(data.data);
                });
            };

            // 属性覆盖继承
            if (options !== undefined) this.extend({options: options});
            this.options.table.oLanguage = this.getLanguage("dataTables", "*");
            this.options.table.sAjaxSource = this.getUrl("search");
            this.options.form["id"] = this.options.sFormId.replace("#", "");

            // 判断添加数据(多选)
            if (this.options.bCheckbox) {
                this.options.table.aoColumns.unshift({
                    "data": 	 null,
                    "bSortable": false,
                    "class": 	 "center",
                    "title": 	 '<label class="position-relative"><input type="checkbox" class="ace" /><span class="lbl"></span></label>',
                    "bViews":    false,
                    "render": 	 function(data){
                        return '<label class="position-relative"><input type="checkbox" value="' + data[self.options.pk] + '" class="ace" /><span class="lbl"></span></label>';
                    }
                })
            }

            // 判断添加数据(操作选项)
            if (this.options.operations.isOpen) {
                this.options.table.aoColumns.push({
                    "data": 	 null,
                    "bSortable": false,
                    "title": self.options.operations.title,
                    "width": self.options.operations.width,
                    "createdCell": function(td, data, rowArr, row) {
                        $(td).html(meTables.buttonsCreate(row, self.options.operations.buttons));
                    }
                })
            }

            // 处理子类信息
            if (this.options.bChildTables) {
                this.options.childTables.table.oLanguage = this.options.table.oLanguage;
                if (!this.options.childTables.table.sAjaxSource) this.options.childTables.table.sAjaxSource = this.options.childTables.url.search;
                if (this.options.childTables.table.fnServerData == undefined) {
                    this.options.childTables.table.fnServerData = function(sSource, aoData, fnCallback) {
                        if (self.data.childParams) {

                            self.push(aoData, self.data.childParams);
                            self.push(aoData, self.options.childTables.params);

                            // ajax请求
                            meTables.ajax({
                                url: sSource,
                                data: aoData,
                                type: "post",
                                dataType: "json"
                            }).done(function (data) {
                                if (data.errCode != 0) {
                                    return layer.msg(self.getLanguage("sAppearError") + data.errMsg, {
                                        time: 2000,
                                        icon: 5
                                    });
                                }

                                fnCallback(data.data);
                                if (meTables.fn.data.childObject) self.data.childObject.child(function () {
                                    return $(self.options.childTables.sTable).parent().html();
                                }).show();
                            });
                        }
                    }
                }
            }

            // 处理搜索位置
            if (this.options.sSearchType != "middle") {
                
            }

            // 处理按钮
            for (var i in this.options.buttons) {
                if (this.options.buttons[i] != null && this.options.buttons[i].show == true) {
                    if (!this.options.buttons[i].text) {
                        this.options.buttons[i].text = this.getLanguage(i);
                    }
                    this.options.buttonHtml += '<button class="' + this.options.buttons[i]["className"] + '" id="' + this.options.sTable.replace("#", "") + "-" + i + '">\
                                <i class="' + this.options.buttons[i]["icon"] + '"></i>\
                            ' + this.options.buttons[i]["text"] + '\
                            </button> ';
                }
            }

            return this;
        },

        // 初始化整个 meTables
        init: function (params) {
            this.action = "init";
            this.initRender();
            this.table = $(this.options.sTable).DataTable(this.options.table);	// 初始化主要表格
            var self = this;
            // 判断初始化处理(搜索添加位置)
            if (this.options.sSearchType == 'middle') {
                $(this.options.sTable + '_filter').html('<form action="post" id="' +
                    this.options.sSearchForm.replace("#", "") + '">' + this.options.sSearchHtml + '</form>');
                $(this.options.sSearchForm + ' input').on('blur', function () { self.table.draw();}); 						// 搜索事件
                $(this.options.sSearchForm + ' select').on('change', function () { self.table.draw();}); 						// 搜索事件
                $(this.options.sTable + '_wrapper div.row div.col-xs-6:first')
                    .removeClass('col-xs-6')
                    .addClass('col-xs-2')
                    .next()
                    .removeClass('col-xs-6')
                    .addClass('col-xs-10');	// 处理搜索信息
            } else {
                // 添加搜索表单信息
                $(this.options.sSearchForm).append(this.options.sSearchHtml);
            }

            // 添加按钮
            try {
                $(self.options.buttonSelector)[self.options.buttonType](self.options.buttonHtml);
            } catch (e) {
                $(self.options.buttonSelector).append(self.options.buttonHtml);
            }

            // 添加按钮事件
            for (var m in self.options.buttons) {
                (function(s){
                    if (self.options.buttons[s] && self.options.buttons[s].show == true) {
                        console.info(self.options.sTable + s);
                        $(document).on('click', self.options.sTable + "-" + s, function(evt) {
                            evt.preventDefault();
                            self[s]();
                        });
                    }
                })(m);
            }

            // 新增、修改、删除、查看、删除全部、保存、刷新、导出
            $('.me-table-create').click(function(evt){evt.preventDefault();self.create();});
            $(document).on('click', '.me-table-update', function(evt){evt.preventDefault();self.update($(this).attr('table-data'))});
            $(document).on('click', '.me-table-delete', function(evt){evt.preventDefault();self.delete($(this).attr('table-data'))});
            $(document).on('click', '.me-table-detail', function(evt){evt.preventDefault();self.detail($(this).attr('table-data'))});

            // 行选择
            $(document).on('click', this.options.sTable + ' th input:checkbox' , function(){
                var that = this;
                $(this).closest('table').find('tr > td:first-child input:checkbox').each(function(){
                    this.checked = that.checked;$(this).closest('tr').toggleClass('selected');
                });
            });

            // 判断是否开启子类处理
            if (this.options.bChildTables) {

                // 初始化详情表格
                this.childTable = $(this.options.childTables.sTable).DataTable(this.options.childTables.table);
                // 新增、查看、编辑、删除
                $('.me-table-child-create').click(function(evt){evt.preventDefault();self.create(true);});
                $('.me-table-child-save').click(function(evt){evt.preventDefault();self.save(null, true);});
                $(document).on('click', '.me-table-child-detail', function(evt){evt.preventDefault();self.detail($(this).attr('table-data'), true)});
                $(document).on('click', '.me-table-child-update', function(evt){evt.preventDefault();self.update($(this).attr('table-data'), true)});
                $(document).on('click', '.me-table-child-delete', function(evt){evt.preventDefault();self.delete($(this).attr('table-data'), true)});

                // 详情选择
                $(this.options.sTable + ' tbody').on('click', this.options.childTables.sClickSelect, function(){
                    var tr = $(this).closest('tr'),row = self.table.row(tr);
                    // 处理已经打开的
                    tr.siblings(tr).each(function(){
                        if (self.table.row($(this)).child.isShown()) {
                            self.table.row($(this)).child.hide();$(this).removeClass('shown');
                        }
                    });

                    // 判断处理
                    if (row.child.isShown()) {
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        self.data.childParams = row.data();
                        self.data.childObject = row;
                        self.childTable.draw();
                        tr.addClass('shown');
                    }
                });
            }

            // 判断开启editTable
            if (this.options.editable) {
                // $.fn.editable.defaults.mode = 'inline';
                $.fn.editableform.loading = "<div class='editableform-loading'><i class='ace-icon fa fa-spinner fa-spin fa-2x light-blue'></i></div>";
                $.fn.editableform.buttons = '<button type="submit" class="btn btn-info editable-submit"><i class="ace-icon fa fa-check"></i></button>'+
                    '<button type="button" class="btn editable-cancel"><i class="ace-icon fa fa-times"></i></button>';
                $.fn.editable.defaults.ajaxOptions = {type: "POST", dataType:'json'};
            }

            // 判断开启列宽拖拽
            // if (self.options.bColResize) $(self.options.sTable).colResizable();

            // 文件上传
            console.info(self.options.fileSelector);
            if (!meTables.empty(self.options.fileSelector) && self.options.fileSelector.length > 0) {
                for (var i in self.options.fileSelector) {
                    aceFileUpload(self.options.fileSelector[i], self.getUrl("upload"));
                }
            }

            // 执行处理
            if (typeof params === "function") params();

            return this;
        },

        // 搜索
        search: function(params){
            this.action = "search";
            if (!params) params = false;
            this.table.draw(params);
        },

        // 刷新
        refresh: function() {
            this.action = "refresh";
            this.search(true);
        },

        // 数据新增
        create: function(child){
            this.action = "create";
            this.initForm(null, child);
        },

        // 数据修改
        update: function (row, child) {
            this.action = "update";
            this.initForm(child ? this.childTable.data()[row] : this.table.data()[row], child);
        },

        // 修改
        updateAll: function() {
            var row = 0;
            this.initForm(this.table.data()[row], false);
        },

        // 数据删除
        delete: function(row, child) {
            var self = this;
            this.action = "delete";
            // 询问框
            layer.confirm(this.getLanguage("confirm").replace("_LENGTH_", ""), {
                title: self.getLanguage("confirmOperation"),
                btn: [self.getLanguage("determine"), self.getLanguage("cancel")],
                shift: 4,
                icon: 0
                // 确认删除
            }, function(){
                self.save(child ? self.childTable.data()[row] : self.table.data()[row], child);
                // 取消删除
            }, function(){
                layer.msg(self.getLanguage("cancelOperation"), {time:800});
            });

        },

        // 删除全部数据
        deleteAll: function() {
            this.action = "deleteAll";
            var self = this, data = [];
            // 数据添加
            $(this.options.sTable + " tbody input:checkbox:checked").each(function(){data.push($(this).val());});

            // 数据为空提醒
            if (data.length < 1)  {
                return layer.msg(self.getLanguage("noSelect"), {icon:5});
            }

            // 询问框
            layer.confirm(this.getLanguage("confirm").replace("_LENGTH_", data.length), {
                title: self.getLanguage("confirmOperation"),
                btn: [self.getLanguage("determine"), self.getLanguage("cancel")],
                shift: 4,
                icon: 0
                // 确认删除
            }, function(){
                self.save({"ids":data.join(',')});
                $(self.options.sTable + " input:checkbox:checked").prop("checked", false);
                // 取消删除
            }, function(){
                layer.msg(self.getLanguage("cancelOperation"), {time:800});
            });
        },

        // 查看详情
        detail: function(row, child){
            if (this.options.oLoading) return false;
            var self = this,
                data = this.table.data()[row],
                obj = this.options.table.aoColumns,
                t = self.options.title,
                c = '.data-detail-',
                i = "#data-detail";
            if (child) {
                t += self.getLanguage("sInfo");
                data = this.childTable.data()[row];
                obj  = this.options.childTables.table.aoColumns;
                t = '';
                c += 'child-';
                i += "-child";
            }

            // 处理的数据
            if (data != undefined) {
                meTables.detailTable(obj, data, c, row);
                // 弹出显示
                this.options.oLoading = layer.open({
                    type: this.options.oViewConfig.type,
                    shade: this.options.oViewConfig.shade,
                    shadeClose: this.options.oViewConfig.shadeClose,
                    title: t + self.getLanguage("sInfo"),
                    content: $(i), 			// 捕获的元素
                    area: this.options.oViewConfig.area,
                    cancel: function(index) {
                        layer.close(index);
                    },
                    end: function(){
                        $('.views-info').html('');
                        self.options.oLoading = null;
                    },
                    maxmin: this.options.oViewConfig.maxmin
                });

                // 展开全屏(解决内容过多问题)
                if (this.options.bViewFull) layer.full(this.options.oLoading);
            }
        },

        save: function(data, child) {
            var self = this;
            if (meTables.inArray(this.action, ["create", "update", "delete", "deleteAll"])) {
                var f = this.options.sFormId, u = this.getUrl(this.action), m = this.options.sModal;
                if (child) {
                    f = this.options.childTables.sFormId;
                    u = this.options.childTables.url[this.action];
                    m = this.options.childTables.sModal;
                }
                // 新增和修改验证数据、数据的处理
                if (meTables.inArray(this.action, ["create", "update"])) {
                    if ($(f).validate(self.options.formValidate).form()) {
                        data = $(f).serializeArray();
                    } else {
                        return false;
                    }
                }

                // 数据验证
                if (data) {
                    // 执行之前的数据处理
                    if (typeof self.beforeSave != 'function' || self.beforeSave(data)) {
                        // ajax提交数据
                        meTables.ajax({
                            url: u,
                            type: "POST",
                            data: data,
                            dataType: "json"
                        }).done(function(json){
                            layer.msg(json.errMsg, {icon:json.errCode == 0 ? 6 : 5});
                            // 判断操作成功
                            if (json.errCode == 0) {
                                // 执行之后的数据处理
                                if (typeof self.afterSave != 'function' || self.afterSave(json.data)) {
                                    child ? self.childTable.draw(false) : self.table.draw(false);
                                    if (self.action !== "delete") $(m).modal('hide');
                                    self.action = "save";
                                }
                            }
                        });
                    }
                }
            } else {
                layer.msg(self.getLanguage("operationError"));
            }

            return false;
        },

        // 数据导出
        export: function() {
            this.action = "export";
            var self = this,
                html = '<form action="' + this.getUrl("export") + '" target="_blank" method="POST" class="me-export" style="display:none">';
            html += '<input type="hidden" name="title" value="' + self.options.title + '"/>';
            html += '<input type="hidden" name="_csrf" value="' + $('meta[name=csrf-token]').attr('content') + '"/>';

            // 添加字段信息
            this.options.table.aoColumns.forEach(function(k, v){
                if (k.data != null && (k.isExport == undefined)) {
                    html += '<input type="hidden" name="fields[' + k.data + ']" value="' + k.title + '"/>';
                }
            });

            // 添加查询条件
            var value = $(self.options.sSearchForm).serializeArray();
            for (var i in value) {
                if (meTables.empty(value[i]["value"]) || value[i]["value"] == "All") continue;
                html += '<input type="hidden" name="params[' + value[i]['name'] + ']" value="' + value[i]["value"] + '"/>';
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
                        layer.msg(self.getLanguage("sServerError"), {icon: 5});
                    }
                } else {
                    layer.msg(self.getLanguage("sExport"), {icon: 6});
                }
            }).always(function() {clearTimeout(ie_timeout);});
            deferred.promise();
        },

        // 获取连接地址
        getUrl: function (strType) {
            return this.options.urlPrefix + this.options.url[strType] + this.options.urlSuffix;
        },

        // 初始化页面渲染
        initRender: function() {
            var self       = this,
                form 	   = '<form ' + meTables.handleParams(this.options.form) + '><fieldset>',
                views 	   = '<table class="table table-bordered table-striped table-detail">',
                aOrders = [],
                aTargets = [];

            // 处理生成表单
            this.options.table.aoColumns.forEach(function(k, v) {
                if (k.bViews !== false) views += meTables.detailTableCreate(k.title, k.data, v, self.options.detailTable);// 查看详情信息
                if (k.edit != undefined) form += meTables.formCreate(k, self.options.editFormParams);	// 编辑表单信息
                if (k.search != undefined) self.options.sSearchHtml += meTables.searchInputCreate(k, v);  // 搜索信息
                if (k.defaultOrder) aOrders.push([v, k.defaultOrder]);							// 默认排序
                if (k.isHide) aTargets.push(v);													// 是否隐藏

                // 判断行内编辑
                if (self.options.editable && k.editable != undefined) {
                    // 默认修改参数
                    self.options.editable[k.sName] = {
                        name: k.sName,
                        type: k.edit.type == "radio" ? "select" : k.edit.type,
                        source: k.value,
                        send: "always",
                        url: self.getUrl("editable"),
                        title: k.title,
                        success: function(response) {
                            if (response.errCode != 0) return response.errMsg;
                        },
                        error: function(e) {
                            console.info(e);
                            layer.msg(self.getLanguage("sServerError"), {icon: 5});
                        }
                    };

                    // 继承修改配置参数
                    self.options.editable[k.sName] = self.extend(self.options.editable[k.sName], k.editable);
                    k["class"] = "my-edit edit-" + k.sName;
                }
            });

            // 判断添加行内编辑信息
            if (self.options.editable) {
                self.options.table.fnDrawCallback = function() {
                    for (var key in self.options.editable) {
                        $(self.options.sTable + " tbody tr td.edit-" + key).each(function(){
                            var data = self.table.row($(this).closest('tr')).data(), mv = {};
                            // 判断存在重新赋值
                            if (data) {
                                mv['value'] = data[key];
                                mv['pk']    = data[self.options.pk];
                            }

                            $(this).editable(self.extend(self.options.editable[key], mv))
                        });
                    }
                }
            }

            if (self.options.editFormParams.bMultiCols && meTables.empty(self.options.editFormParams.modalClass)) {
                self.options.editFormParams.modalClass = "bs-example-modal-lg";
                self.options.editFormParams.modalDialogClass = "modal-lg";
            }

            if (self.options.editFormParams.bMultiCols && self.options.editFormParams.index % self.options.editFormParams.iCols != (self.options.editFormParams.iCols - 1)) {
                form += '</div>';
            }

            // 生成HTML
            this.data.sUpdateModel = meTables.modalCreate({
                    "params": {"id": self.options.sModal.replace("#", "")},
                    "html":   form,
                    "bClass": "me-table-save",
                    "modalClass": self.options.editFormParams.modalClass,
                    "modalDialogClass": self.options.editFormParams.modalDialogClass
                },
                {
                    "params": {"id":"data-detail"}, "html":views
                });

            // 处理详情编辑信息
            if (this.options.bChildTables) {
                form  = '<form id="' + this.options.childTables.sFormId.replace("#", "") + '" class="form-horizontal" action="' + this.getUrl("update") + '" name="myDetailForm" method="post" enctype="multipart/form-data"><fieldset>';
                views = '<table class="table table-bordered table-striped table-detail">';
                // 处理生成表单
                this.options.childTables.table.aoColumns.forEach(function(k, v) {
                    views += meTables.createViewTr(k.title, 'child-' + k.data, v, self.options.childTables.detailTable);// 查看详情信息
                    if (k.edit != undefined) form += meTables.formCreate(k, self.options.childTables.editFormParams);		// 编辑表单信息
                });

                // 添加详情输入框
                this.data.sUpdateModel += meTables.medalCreate({
                        "params": {"id": self.options.childTables.sModal.replace("#", "")},
                        "html":	  form,
                        "bClass": "me-table-child-save"},
                    {
                        "params": {"id":"data-detail-child"},
                        "html":   views
                    });
            }

            // 添加处理表格排序配置
            if (aOrders.length > 0) {
                this.options.table.order = aOrders;
            }

            // 添加处理表格隐藏字段
            if (aTargets.length > 0) {
                if (this.options.table.columnDefs) {
                    this.options.table.columnDefs.push({"targets": aTargets, "visible": false});
                } else {
                    this.options.table.columnDefs = [{"targets": aTargets, "visible": false}];
                }
            }

            // 向页面添加HTML
            $("body").append(this.data.sUpdateModel);
        },

        // 初始化表单信息
        initForm: function(data, child) {
            layer.close(this.options.oLoading);
            // 显示之前的处理
            if (typeof this.beforeShowChild == 'function' && ! this.beforeShowChild(data, child)) return false;

            // 确定操作的表单和模型
            var f = this.options.sFormId, m = this.options.sModal, t = this.options.title;

            // 是否操作详情信息
            if (child) {
                f = this.options.childTables.sFormId;
                m = this.options.childTables.sModal;
                t += this.getLanguage("sInfo");
            }

            $(m).find('h4').html(t + this.getLanguage(this.action == "create" ? "sCreate": "sUpdate"));
            meTables.initForm(f, data);

            // 显示之后的处理
            if (typeof this.afterShow == 'function' && ! this.afterShow(data, child)) return false;

            $(m).modal({backdrop: "static"});   // 弹出信息
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
                layer.msg(meTables.fn.getLanguage("sServerError"), {icon: 5});
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

        // 是否为空
        empty: function (value) {
            return value === undefined || value === "" || value === null;
        },

        isObject: function(value) {
            return typeof value == "object";
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

        labelCreate: function(content, params) {
            return "<label" + this.handleParams(params) + "> " + content + " </label>";
        },

        inputCreate: function(params) {
            if (!params.type) params.type = "text";
            return "<input" + this.handleParams(params) + "/>";
        },

        textCreate: function(params) {
            params.type = "text";
            return this.inputCreate(params);
        },

        passwordCreate: function(params) {
            params.type = "password";
            return this.inputCreate(params);
        },

        fileCreate: function(params) {
            var o = params.options;
            delete params.options;
            html = '<input type="hidden" '+ this.handleParams(params) +'/>';
            o.type = "file";
            return html + this.inputCreate(o);
        },

        radioCreate: function(params, d) {
            html = "";
            if (d && this.isObject(d)) {
                params['class'] = "ace valid";
                var c = params.default;
                params = this.handleParams(params);
                for (i in d) {
                    html += '<label class="line-height-1 blue"> ' +
                        '<input type="radio" ' + params + (c == i ? ' checked="checked" ' : "") + ' value="' + i +'"  /> ' +
                        '<span class="lbl"> '+d[i]+" </span> " +
                        "</label>　 "
                }
            }

            return html;
        },

        checkboxCreate: function(params, d) {
            html = '';
            if (d && this.isObject(d)) {
                var o = params.all, c = params.divClass ? params.divClass : "col-xs-6";
                delete params.all;
                delete params.divClass;
                params["class"] = "ace m-checkbox";
                params = handleParams(params);
                if (o) {
                    html += '<div class="checkbox col-xs-12">' +
                            '<label>' +
                                '<input type="checkbox" class="ace checkbox-all" onclick="var isChecked = $(this).prop(\'checked\');$(this).parent().parent().parent().find(\'input[type=checkbox]\').prop(\'checked\', isChecked);" />' +
                                '<span class="lbl"> ' + meTables.fn.getLanguage("sSelectAll") + ' </span>' +
                            '</label>' +
                        '</div>';
                }
                for (i in d) {
                    html += '<div class="checkbox ' + c + '">' +
                            '<label>' +
                                '<input type="checkbox" ' + params + ' value="' + i + '" />' +
                                '<span class="lbl"> ' + d[i] + ' </span>' +
                            '</label>' +
                        '</div>';
                }
            }

            return html;
        },

        selectCreate: function(params, d) {
            html = "";
            if (d && this.isObject(d)) {
                var c = params.default;
                delete params.default;
                html += "<select " + this.handleParams(params) + ">";
                for (i in d){
                    html += '<option value="' + i + '" ' + (i == c ? ' selected="selected" ' : "") + " >" + d[i] + "</option>";
                }

                html += "</select>";
            }

            return html
        },

        textareaCreate: function(params) {
            if (!params["class"]) params["class"] = "form-control";
            if (!params["rows"]) params["rows"] = 5;
            html = params.value + "</textarea>";
            delete params.value;
            return "<textarea" + this.handleParams(params) + ">" + html;
        },

        // 搜索框表单元素创建
        searchInputCreate: function(k, v) {
            k.search.name = k.sName;
            if (k.search.type == "select") {
                k.value["All"] = "全部";
                k.search.default = "All";
            } else {
                k.search.type = "text";
            }

            try {
                html = this[k.search.type + "Create"](k.search, k.value);
            } catch (e) {
                html = this.textCreate(k.search);
            }

            return this.labelCreate(k.title + " : " + html) + ' ';
        },

        buttonsCreate: function(index, data) {
            var div1   = '<div class="hidden-sm hidden-xs btn-group">',
                div2   = '<div class="hidden-md hidden-lg"><div class="inline position-relative"><button data-position="auto" data-toggle="dropdown" class="btn btn-minier btn-primary dropdown-toggle"><i class="ace-icon fa fa-cog icon-only bigger-110"></i></button><ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">';
            // 添加按钮信息
            if(data != undefined && typeof data == "object") {
                for(var i in data) {
                    div1 += ' <button class="btn ' + data[i]['className'] + ' '+  data[i]['cClass'] + ' btn-xs" table-data="' + index + '"><i class="ace-icon fa ' + data[i]["icon"] + ' bigger-120"></i> ' + (data[i]["button-title"] ? data[i]["button-title"] : '') + '</button> ';
                    div2 += '<li><a title="' + data[i]['title'] + '" data-rel="tooltip" class="tooltip-info ' + data[i]['cClass'] + '" href="javascript:;" data-original-title="' + data[i]['title'] + '" table-data="' + index + '"><span class="' + data[i]['sClass'] + '"><i class="ace-icon fa ' + data[i]['icon'] + ' bigger-120"></i></span></a></li>';
                }
            }

            return div1 + '</div>' + div2 + '</ul></div></div>';
        },

        formCreate: function(k, oParams) {
            var form = '';
            if (!oParams.index) oParams.index = 0;

            // 处理其他参数
            if (!k.edit.type) k.edit.type = "text";
            if (!k.edit.name) k.edit.name = k.sName;

            if (k.edit.type == "hidden" ) {
                form += this.inputCreate(k.edit);
            } else {
                k.edit["class"] = "form-control " + (k.edit["class"] ? k.edit["class"] : "");
                // 处理多列
                if (oParams.iMultiCols > 1 && !oParams.aCols) {
                    oParams.aCols = [];
                    var iLength = Math.ceil(12 / oParams.iMultiCols);
                    oParams.aCols[0] =  Math.floor(iLength * 0.3);
                    oParams.aCols[1] =  iLength - oParams.aCols[0];
                }

                if (!oParams.bMultiCols || (oParams.iColsLength > 1 && oParams.index % oParams.iColsLength == 0)) {
                    form += '<div class="form-group">';
                }

                form += this.labelCreate(k.title, {"class": "col-sm-" + oParams.aCols[0] + " control-label"});
                form += '<div class="col-sm-'+ oParams.aCols[1] + '">';

                // 使用函数
                try {
                    form += this[k.edit.type + "Create"](k.edit, k.value);
                } catch (e) {
                    k.edit.type = "text";
                    form += this["inputCreate"](k.edit);
                }

                form += '</div>';

                if (!oParams.bMultiCols || (oParams.iColsLength > 1 && oParams.index % oParams.iColsLength == (oParams.iColsLength - 1))) {
                    form += '</div>';
                }

                oParams.index ++;
            }

            return form;
        },

        // 初始化表单信息
        initForm: function(select, data) {
            var $fm = $(select);
            objForm = $fm.get(0); // 获取表单对象
            if (objForm != undefined) {
                $fm.find('input[type=hidden]').val('');                                  // 隐藏按钮充值
                $fm.find('input[type=checkbox]').each(function(){$(this).attr('checked', false);if ($(this).get(0)) $(this).get(0).checked = false;});                                                                             // 多选菜单
                objForm.reset();                                                                // 表单重置
                if (data != undefined) {
                    for (var i in data) {
                        // 多语言处理 以及多选配置
                        if (typeof data[i]  ==  'object') {
                            for (var x in data[i]){
                                var key = i + '[' + x + ']';
                                // 对语言
                                if (objForm[key] != undefined) {
                                    objForm[key].value = data[i][x];
                                } else {
                                    // 多选按钮
                                    if (parseInt(data[i][x]) > 0) {
                                        $('input[type=checkbox][name=' + i + '\\[\\]][value=' + data[i][x] + ']').attr('checked', true).each(function(){this.checked=true});
                                    }
                                }
                            }
                        }

                        // 其他除密码的以外的数据
                        if (objForm[i] != undefined && objForm[i].type != "password") {
                            var obj = $(objForm[i]), tmp = data[i];
                            // 时间处理
                            if (obj.hasClass('time-format')) {
                                tmp = mt.timeFormat(parseInt(tmp), obj.attr('time-format') ? obj.attr('time-format') : "yyyy-MM-dd hh:mm:ss");
                            }
                            objForm[i].value = tmp;
                        }
                    }
                }
            }
        },

        divCreate: function(params) {
            return '<div' + this.handleParams(params) + '></div>'
        },

        dateCreate: function (params) {
            return '<div class="input-group bootstrap-datepicker"> \
                <input class="form-control date-picker ' + (params["class"] ? params["class"] : "") + '"  type="text" ' + this.handleParams(params) + '/> \
                <span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span> \
                </div>';
        },

        timeCreate: function (params) {
            return '<div class="input-group bootstrap-timepicker"> \
                <input type="text" class="form-control time-picker ' + (params["class"] ? params["class"] : "") + '" ' + this.handleParams(params) + '/> \
                <span class="input-group-addon"><i class="fa fa-clock-o bigger-110"></i></span> \
                </div>';
        },

        // 添加时间
        dateTimeCreate: function (params) {
            return '<div class="input-group bootstrap-datetimepicker"> \
                <input type="text" class="form-control datetime-picker ' + (params["class"] ? params["class"] : "") + '" ' + this.handleParams(params) + '/> \
                <span class="input-group-addon"><i class="fa fa-clock-o bigger-110"></i></span> \
                </div>';
        },

        // 时间段
        timeRangeCreate: function (params) {
            return '<div class="input-daterange input-group"> \
                <input type="text" class="input-sm form-control" name="start" /> \
                <span class="input-group-addon"><i class="fa fa-exchange"></i></span> \
                <input type="text" class="input-sm form-control" name="end" /> \
            </div>';
        },

        // 添加时间段
        dateRangeCreate: function (params) {
            return '<div class="input-group"> \
                <span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span> \
                <input class="form-control daterange-picker ' + (params["class"] ? params["class"] : "") + '" type="text" ' + this.handleParams(params) + ' /> \
            </div>';
        },

        detailTable: function(object, data, tClass, row) {
            // 循环处理显示信息
            object.forEach(function(k) {
                var tmpKey = k.data,tmpValue = data[tmpKey],dataInfo = $(tClass + tmpKey);
                if (k.edit != undefined && k.edit.type == 'password') tmpValue = "******";
                (k.createdCell != undefined && typeof k.createdCell == "function") ? k.createdCell(dataInfo, tmpValue, data, row, undefined) : dataInfo.html(tmpValue);
            });
        },

        detailTableCreate: function(title, data, iKey,  aParams) {
            html = '';
            if (aParams && aParams.bMultiCols) {
                if (aParams.iColsLength > 1 && iKey % aParams.iColsLength == 0) {
                    html += '<tr>';
                }

                html += '<td width="25%">' + title + '</td><td class="views-info data-detail-' + data + '"></td>';

                if (aParams.iColsLength > 1 && iKey % aParams.iColsLength == (aParams.iColsLength - 1)) {
                    html += '</tr>';
                }
            } else {
                html += '<tr><td width="25%">' + title + '</td><td class="views-info data-detail-' + data + '"></td></tr>';
            }

            return html;
        },

        modalCreate: function(oModal, oViews) {
            return '<div class="isHide" '+ this.handleParams(oViews['params']) +'> ' + oViews['html'] +  ' </table></div> \
            <div class="modal fade ' + (oModal["modalClass"] ? oModal["modalClass"] : "") + '" '+ this.handleParams(oModal['params']) +' tabindex="-1" role="dialog" > \
                <div class="modal-dialog ' + (oModal["modalDialogClass"] ? oModal["modalDialogClass"] : "") + '" role="document"> \
                    <div class="modal-content"> \
                        <div class="modal-header"> \
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> \
                            <h4 class="modal-title"></h4> \
                        </div> \
                        <div class="modal-body">' + oModal['html'] + '</fieldset></form></div> \
                        <div class="modal-footer"> \
                            <button type="button" class="btn btn-default" data-dismiss="modal">' + meTables.fn.getLanguage("sBtnCancel") + '</button> \
                            <button type="button" class="btn btn-primary btn-image ' + oModal['bClass'] + '">' + meTables.fn.getLanguage("sBtnSubmit") + '</button> \
                        </div> \
                    </div> \
                </div> \
            </div>';
        },

        // 根据时间戳返回时间字符串
        timeFormat: function(time, str) {
            if (!str) str = "yyyy-MM-dd";
            var date = new Date(time * 1000);
            return date.Format(str);
        },

        // 时间戳转字符日期
        dateTimeString: function(td, data) {
            $(td).html(mt.timeFormat(data, 'yyyy-MM-dd hh:mm:ss'));
        },

        // 状态信息
        statusString: function(td, data) {
            $(td).html('<span class="label label-' + (data == 1 ? 'success">启用' : 'warning">禁用') + '</span>');
        },

        // 用户显示
        adminString: function(td, data) {
            $(td).html(aAdmins[data]);
        },

        // 显示标签
        valuesString: function(data, color, value, defaultClass) {
            if (defaultClass == undefined) defaultClass = 'label label-sm ';
            return '<span class="' + defaultClass + ' ' + (color[value] ? color[value] : '') + '"> ' + (data[value] ? data[value] : value) + ' </span>';
        }

    });

    // 设置默认配置信息
    meTables.fn.extend({
        options: {
            title: "",// 表格的标题
            language: "zh-cn",      // 使用语言
            pk: "id",		// 行内编辑pk索引值
            sModal: "#table-modal", // 编辑Modal选择器
            sTable:  "#show-table", 	// 显示表格选择器
            sFormId: "#edit-form",		// 编辑表单选择器
            sMethod: "POST",			// 查询数据的请求方式
            bCheckbox: true,			// 需要多选框
            params: null,				// 请求携带参数
            sSearchHtml: "",				// 搜索信息额外HTML
            sSearchType: "middle",			// 搜索表单位置
            sSearchForm: "#search-form",	// 搜索表单选择器

            fileSelector: [],				// 上传文件选择器

            // 编辑表单信息
            form: {
                "method": "post",
                "class":  "form-horizontal",
                "name":   "edit-form"
            },

            // 编辑表单验证方式
            formValidate: {
                errorElement: 'div',
                errorClass: 'help-block',
                focusInvalid: false,
                highlight: function (e) {
                    $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                },
                success: function (e) {
                    $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
                    $(e).remove();
                }
            },

            // 表单编辑其他信息
            editFormParams: {				// 编辑表单配置
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

            detailTable: {                   // 查看详情配置信息
                bMultiCols: false,
                iColsLength: 1
            },

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
                // "searching": false,
                "sPaginationType":  "full_numbers"     // 分页样式
                // "order": [[1, "desc"]]       // 默认排序
            },

            // 字表格配置信息
            bChildTables: false, // 是否开启
            childTables: {
                sTable: "#child-table",
                sModal: "#child-modal",
                sFormId: "#child-form",
                urlPrefix: "", //self.options.sBaseUrl, // 详情编辑的统一前缀
                urlSuffix: "",
                url: {
                    "search": "view",  // 查询
                    "create": "create", // 创建
                    "update": "update",	// 修改
                    "delete": "delete" // 删除
                },
                sClickSelect: "td.child-control",
                table: 	{
                    "bPaginate": false,             // 不使用分页
                    "bLengthChange": false,         // 是否可以调整分页
                    "bServerSide": true,		 	// 是否开启从服务器端获取数据
                    "bAutoWidth": false,
                    "searching": false,				// 搜索
                    "ordering": false			 	// 排序
                },

                detailTable: {                   // 查询详情配置信息
                    bMultiCols: false,
                    iColsLength: 1
                },

                editFormParams: {				// 编辑表单配置
                    bMultiCols: false,          // 是否多列
                    iColsLength: 1,             // 几列
                    aCols: [3, 9],              // label 和 input 栅格化设置
                    sModalClass: "",			// 弹出模块框配置
                    sModalDialogClass: ""		// 弹出模块的class
                }
            },

            // 开启行处理
            editable: null,

            // 默认按钮信息
            buttonHtml: "",
            // 按钮添加容器
            buttonSelector: "#me-table-buttons",
            // 按钮添加方式
            buttonType: "append",
            // 默认按钮信息
            buttons: {
                create: {
                    show: true,
                    icon: "ace-icon fa fa-plus-circle blue",
                    className: "btn btn-white btn-primary btn-bold"
                },
                updateAll: {
                    show: true,
                    icon: "ace-icon fa fa-pencil-square-o orange",
                    className: "btn btn-white btn-info btn-bold"
                },
                deleteAll: {
                    show: true,
                    icon: "ace-icon fa fa-trash-o red",
                    className: "btn btn-white btn-danger btn-bold"
                },
                refresh: {
                    show: true,
                    icon: "ace-icon fa  fa-refresh",
                    className: "btn btn-white btn-success btn-bold"
                },
                export: {
                    show: true,
                    icon: "ace-icon glyphicon glyphicon-export",
                    className: "btn btn-white btn-warning btn-bold"
                }
            }
        },

        // 数据信息
        data: {
            sSearchForm: "", // 搜索表单
            sUpdateModel: "",// 编辑表单Model
            sInfoTable: "",   // 查看Table,
            childParams: null, // 子类表格参数
            childObject: null  // 子类对
        },

        // 语言配置
        language: {
            "zh-cn": {
                // 我的信息
                meTables: {
                    "sOperation": "操作",
                    "sBtnView": "查看",
                    "sBtnEdit": "编辑",
                    "sBtnDelete": "删除",
                    "sBtnCancel": "取消",
                    "sBtnSubmit": "确定",
                    "sSelectAll": "选择全部",
                    "sInfo": "详情",
                    "sCreate": "新增",
                    "sUpdate": "编辑",
                    "sExport": "数据正在导出, 请稍候...",
                    "sAppearError": "出现错误",
                    "sServerError": "服务器繁忙,请稍候再试...",
                    "determine": "确定",
                    "cancel": "取消",
                    "confirm": "您确定需要删除这_LENGTH_条数据吗?",
                    "confirmOperation": "确认操作",
                    "cancelOperation": "您取消了删除操作!",
                    "noSelect": "没有选择需要删除的数据",
                    "operationError": "操作有误",
                    "create": "添加",
                    "updateAll": "修改",
                    "deleteAll": "删除",
                    "refresh": "刷新",
                    "export": "导出"
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

    meTables.fn.extend({
        options: {
            operations: {
                isOpen: true,
                width: "120px",
                title: meTables.fn.getLanguage("sOperation"),
                defaultContent: "",
                buttons: [
                    {"title": meTables.fn.getLanguage("sBtnView"), "className": "btn-success", "cClass":"me-table-detail",  "icon":"fa-search-plus",  "sClass":"blue"},
                    {"title": meTables.fn.getLanguage("sBtnEdit"), "className": "btn-info", "cClass":"me-table-update", "icon":"fa-pencil-square-o",  "sClass":"green"},
                    {"title": meTables.fn.getLanguage("sBtnDelete"), "className": "btn-danger", "cClass":"me-table-delete", "icon":"fa-trash-o",  "sClass":"red"}
                ]
            },
            childTables: {
                operations: {
                    "data": null,
                    "title": meTables.fn.getLanguage("sOperation"),
                    "bSortable": false,
                    "width": "120px",
                    "createdCell": function (td, data, rowArr, row, col) {
                        $(td).html(meTables.buttonsCreate(row, [
                            {
                                "data": row,
                                "title": meTables.fn.getLanguage("sBtnView"),
                                "className": "btn-success",
                                "cClass": "me-table-child-detail",
                                "icon": "fa-search-plus",
                                "sClass": "blue"
                            },
                            {
                                "data": row,
                                "title": meTables.fn.getLanguage("sBtnEdit"),
                                "className": "btn-info",
                                "cClass": "me-table-child-update",
                                "icon": "fa-pencil-square-o",
                                "sClass": "green"
                            },
                            {
                                "data": row,
                                "title": meTables.fn.getLanguage("sBtnDelete"),
                                "className": "btn-danger",
                                "cClass": "me-table-child-delete",
                                "icon": "fa-trash-o",
                                "sClass": "red"
                            }
                        ]));
                    }
                }
            }
        }
    });

    window.meTables = window.mt = meTables;

    return meTables;
})(window, jQuery);