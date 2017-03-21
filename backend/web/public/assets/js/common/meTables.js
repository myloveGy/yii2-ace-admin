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
            $.fn.dataTable.defaults['bFilter'] = true;
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
                            if (self.options.details.tableObject) self.options.details.tableObject.child(function () {
                                return $(self.options.details.sTable).parent().html();
                            }).show();
                        });
                    }
                }
            }

            return this;
        },

        // 初始化整个 meTables
        init: function (params) {
            this.action = "init";
            this.table = $(this.options.sTable).DataTable(this.options.table);	// 初始化主要表格

            var self = this;this.CreateForm();

            // 判断初始化处理(搜索添加位置)
            if (this.options.sSearchType == 'middle') {
                $('#showTable_filter').html('<form action="post" id="searchForm">' + self.options.sSearchHtml + '</form>');
                $('input.me-search').on('blur', function () { self.table.draw();}); 						// 搜索事件
                $('select.me-search').on('change', function () { self.table.draw();}); 						// 搜索事件
                $('#showTable_wrapper div.row div.col-xs-6:first').removeClass('col-xs-6').addClass('col-xs-2').next().removeClass('col-xs-6').addClass('col-xs-10');	// 处理搜索信息
            } else {
                // 添加搜索表单信息
                $(this.options.sSearchForm).append(self.options.sSearchHtml);
            }

            // 新增、修改、删除、查看、删除全部、保存、刷新、导出
            $('.me-table-insert').click(function(evt){evt.preventDefault();self.create();});
            $(document).on('click', '.me-table-edit', function(evt){evt.preventDefault();self.update($(this).attr('table-data'))});
            $(document).on('click', '.me-table-del', function(evt){evt.preventDefault();self.delete($(this).attr('table-data'))});
            $(document).on('click', '.me-table-view', function(evt){evt.preventDefault();self.detail($(this).attr('table-data'))});
            $('.me-table-delete').click(function(evt){evt.preventDefault();self.deleteAll();});
            $('.me-table-save').click(function(evt){evt.preventDefault();self.save();});
            $('.me-table-reload').click(function(evt){evt.preventDefault();self.search();});
            $('.me-table-export').click(function(evt){evt.preventDefault();self.export();});

            // 行选择
            $(document).on('click', self.options.sTable + ' th input:checkbox' , function(){
                var that = this;$(this).closest('table').find('tr > td:first-child input:checkbox').each(function(){this.checked = that.checked;$(this).closest('tr').toggleClass('selected');});
            });

            // 判断是否开启详情处理
            if (self.bHandleDetails) {
                // 初始化详情表格
                if (this.bHandleDetails) this.details = $(this.oDetails.sTable).DataTable(this.oDetails.oTableOptions);
                // 新增、查看、编辑、删除
                $('.me-table-insert-detail').click(function(evt){evt.preventDefault();self.create(true);});
                $(document).on('click', '.me-table-view-detail', function(evt){evt.preventDefault();self.detail($(this).attr('table-data'), true)});
                $(document).on('click', '.me-table-edit-detail', function(evt){evt.preventDefault();self.update($(this).attr('table-data'), true)});
                $(document).on('click', '.me-table-del-detail', function(evt){evt.preventDefault();self.delete($(this).attr('table-data'), true)});
                // 详情选择
                $(self.options.sTable + ' tbody').on('click', self.oDetails.sClickSelect, function(){
                    var tr = $(this).closest('tr'),row = self.table.row(tr);
                    // 处理已经打开的
                    tr.siblings(tr).each(function(){ if (self.table.row($(this)).child.isShown()) self.table.row($(this)).child.hide();$(this).removeClass('shown');});
                    // 判断处理
                    if (row.child.isShown()){row.child.hide();tr.removeClass('shown');}else{self.oDetailParams = row.data();self.oDetailObject = row;self.details.draw();tr.addClass('shown');}
                });
            }

            // 判断开启editTable
            if (self.options.bEditTable) {
                $.fn.editable.defaults.mode = 'inline';
                $.fn.editableform.loading = "<div class='editableform-loading'><i class='ace-icon fa fa-spinner fa-spin fa-2x light-blue'></i></div>";
                $.fn.editableform.buttons = '<button type="submit" class="btn btn-info editable-submit"><i class="ace-icon fa fa-check"></i></button>'+
                    '<button type="button" class="btn editable-cancel"><i class="ace-icon fa fa-times"></i></button>';
                $.fn.editable.defaults.ajaxOptions = {type: "POST", dataType:'json'};
            }

            // 判断开启列宽拖拽
            if (self.options.bColResize) $(self.options.sTable).colResizable();

            // 文件上传
            if (!empty(self.options.aFileSelector) && self.options.aFileSelector.length > 0) {
                for (var i in self.options.aFileSelector) {
                    aceFileUpload(self.options.aFileSelector[i], self.options.sBaseUrl + self.options.aActionUrl.upload);
                }
            }

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
            this.initForm(undefined, isDetail);
        },

        // 数据修改
        update: function () {
            this.action = "update";
            this.initForm(isDetail ? this.details.data()[row] : this.table.data()[row], isDetail);
        },

        // 数据删除
        delete: function() {
            this.action = "delete";
            // 询问框
            layer.confirm(this.getLanguage("oDelete").confirm.replace("_LENGTH_", ""), {
                title: self.getLanguage("oDelete").confirmOperation,
                btn: [self.getLanguage("oDelete").determine, self.getLanguage("oDelete").cancel],
                shift: 4,
                icon: 0
                // 确认删除
            }, function(){
                self.save(isDetail ? self.details.data()[row] : self.table.data()[row]);
                // 取消删除
            }, function(){
                layer.msg(self.getLanguage("oDelete").cancelOperation, {time:800});
            });
        },

        // 删除全部数据
        deleteAll: function() {
            this.action = "deleteAll";
            var self = this;
            // 数据添加
            $(this.options.sTable + " tbody input:checkbox:checked").each(function(){data.push($(this).val());});

            // 数据为空提醒
            if (data.length < 1)  {
                layer.msg(self.getLanguage("oDelete").noSelect, {icon:5});
                return false;
            }

            // 询问框
            layer.confirm(this.getLanguage("oDelete").confirm.replace("_LENGTH_", data.length), {
                title: self.getLanguage("oDelete").confirmOperation,
                btn: [self.getLanguage("oDelete").determine, self.getLanguage("oDelete").cancel],
                shift: 4,
                icon: 0
                // 确认删除
            }, function(){
                self.save({"ids":data.join(',')});
                // 取消删除
            }, function(){
                layer.msg(self.getLanguage("oDelete").cancelOperation, {time:800});
            });
        },

        // 查看详情
        detail: function(){
            var self = this,
                data = this.table.data()[row],
                obj = this.tableOptions.aoColumns,
                t = self.options.sTitle,
                c = '.data-info-',
                i = "#data-info";
            if (isDetail) {
                data = this.details.data()[row];
                obj  = this.oDetails.oTableOptions.aoColumns;
                t = '';
                c += 'detail-';
                i = "#data-info-detail";
            }
            // 处理的数据
            if (data != undefined)
            {
                viewTable(obj, data, c, row);
                // 弹出显示
                this.options.iViewLoading = layer.open({
                    type:		this.options.oViewLayerConfig.type,
                    shade: 		this.options.oViewLayerConfig.shade,
                    shadeClose: this.options.oViewLayerConfig.shadeClose,
                    title: 		t + self.getLanguange("sInfo"),
                    content: 	$(i), 			// 捕获的元素
                    area:	 	this.options.oViewLayerConfig.area,
                    cancel: 	function(index){layer.close(index);},
                    end: 		function(){$('.views-info').html('');self.options.iViewLoading = 0;},
                    maxmin: 	this.options.oViewLayerConfig.maxmin
                });

                // 展开全屏(解决内容过多问题)
                if (this.options.bViewFull) layer.full(this.options.iViewLoading)
            }
        },

        save: function(data) {
            // 初始化判断和数据处理
            if (meTables.inArray(this.actionType, ["insert", "update", "delete", "deleteAll"])) {
                // 初始化数据
                var self     = this,
                    sFormId  = this.options.sFormId,
                    sBaseUrl = self.options.sBaseUrl + self.options.aActionUrl[self.actionType],
                    sModal   = self.options.sModal;
                // 详情处理
                if (this.bDetail) {
                    sFormId  = self.oDetails.sFormId;
                    sBaseUrl = self.oDetails.sBaseUrl + self.oDetails.aActionUrl[self.actionType];
                    sModal   = self.oDetails.sModal;
                }

                // 新增和修改验证数据、数据的处理
                if (meTables.inArray(this.actionType, ["insert", "update"])) {
                    if ($(sFormId).validate({
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
                        }).form()) {
                        data = $(sFormId).serializeArray();
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
                            url:      sBaseUrl,
                            type:     'POST',
                            data:     data,
                            dataType: 'json'
                        }).done(function(json){
                            layer.msg(json.errMsg, {icon:json.errCode == 0 ? 6 : 5});
                            // 判断操作成功
                            if (json.errCode == 0) {
                                // 执行之后的数据处理
                                if (typeof self.afterSave != 'function' || self.afterSave(json.data)) {
                                    self.table.draw(false);
                                    if (self.actionType !== "delete") $(sModal).modal('hide');
                                    self.actionType = "";
                                }
                            }
                        });

                        return false;
                    }
                }
            }

            layer.msg(self.language.meTables.operationError);
            return false;
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
                if (k.data != null && (k.isExport == undefined)) {
                    html += '<input type="hidden" name="aFields[' + k.data + ']" value="' + k.title + '"/>';
                }
            });

            // 添加查询条件
            var value = $(self.options.sSearchForm).serializeArray();
            for (var i in value) {
                if (empty(value[i]["value"]) || value[i]["value"] == "All") continue;
                html += '<input type="hidden" name="params[' + value[i]['name'] + ']" value="' + value[i]["value"] + '"/>';
            }

            // 表单提交
            var $form = $(html);
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
            this.tableOptions.aoColumns.forEach(function(k, v) {
                if (k.bViews !== false) views += createViewTr(k.title, k.data, v, self.options.oViewTable);// 查看详情信息
                if (k.edit != undefined) form += createForm(k, self.options.oEditFormParams);	// 编辑表单信息
                if (k.search != undefined) self.options.sSearchHtml += createSearchForm(k, v);  // 搜索信息
                if (k.defaultOrder) aOrders.push([v, k.defaultOrder]);							// 默认排序
                if (k.isHide) aTargets.push(v);													// 是否隐藏

                // 判断行内编辑
                if (k.editTable != undefined) {
                    // 默认修改参数
                    self.options.oEditTable[k.sName] = {
                        name: k.sName,
                        type: k.edit.type == "radio" ? "select" : k.edit.type,
                        source: k.value,
                        send: "always",
                        url: self.options.aActionUrl.inline,
                        title: k.title,
                        success: function(response) {
                            if (response.errCode != 0) return response.errMsg;
                        },
                        error: self.ajaxFail
                    };

                    // 继承修改配置参数
                    self.options.oEditTable[k.sName] = $.extend(self.options.oEditTable[k.sName], k.editTable);
                    k["class"] = "my-edit edit-" + k.sName;
                }
            });

            // 判断添加行内编辑信息
            if (self.options.bEditTable)
            {
                self.tableOptions["fnDrawCallback"] = function() {
                    for (var key in self.options.oEditTable) {
                        $(self.options.sTable + " tbody tr td.edit-" + key).each(function(){
                            var data = self.table.row($(this).closest('tr')).data(), mv = {};
                            // 判断存在重新赋值
                            if (data){
                                mv['value'] = data[key];
                                mv['pk']    = data[self.options.sEditPk];
                            }

                            $(this).editable($.extend(self.options.oEditTable[key], mv))
                        });
                    }
                }
            }

            if (self.options.oEditFormParams.bMultiCols && empty(self.options.oEditFormParams.modalClass)) {
                self.options.oEditFormParams.modalClass = "bs-example-modal-lg";
                self.options.oEditFormParams.modalDialogClass = "modal-lg";
            }

            if (self.options.oEditFormParams.bMultiCols && self.options.oEditFormParams.index % self.options.oEditFormParams.iCols != (self.options.oEditFormParams.iCols - 1)) {
                form += '</div>';
            }

            // 生成HTML
            var Modal = createModal({
                    "params": {"id":"myModal"},
                    "html":   form,
                    "bClass": "me-table-save",
                    "modalClass": self.options.oEditFormParams.modalClass,
                    "modalDialogClass": self.options.oEditFormParams.modalDialogClass
                },
                {
                    "params": {"id":"data-info"}, "html":views
                });

            // 处理详情编辑信息
            if (this.bHandleDetails) {
                form  = '<form id="myDetailForm" class="form-horizontal" action="' + this.oDetails.sBaseUrl + '" name="myDetailForm" method="post" enctype="multipart/form-data"><fieldset>';
                views = '<table class="table table-bordered table-striped table-detail">';
                // 处理生成表单
                this.oDetails.oTableOptions.aoColumns.forEach(function(k) {
                    views += createViewTr(k.title, 'detail-' + k.data);// 查看详情信息
                    if (k.edit != undefined) form += createForm(k);		// 编辑表单信息
                });

                // 添加详情输入框
                Modal += createModal({
                        "params": {"id":"myDetailModal"},
                        "html":	  form,
                        "bClass": "me-table-save"},
                    {
                        "params": {"id":"data-info-detail"},
                        "html":   views
                    });
            }

            // 处理表格配置
            if (aOrders.length > 0) { // 排序
                this.tableOptions.order = aOrders;
            }

            // 隐藏字段
            if (aTargets.length > 0) {
                if (this.tableOptions.columnDefs) {
                    this.tableOptions.columnDefs.push({"targets":aTargets, "visible":false});
                } else {
                    this.tableOptions.columnDefs = [{"targets":aTargets, "visible":false}];
                }
            }

            // 向页面添加HTML
            $("body").append(Modal);
        },

        // 初始化表单信息
        initForm: function() {
            this.bDetail = isDetail;
            // 显示之前的处理
            if (typeof this.beforeShow == 'function' && ! this.beforeShow(data, isDetail)) return false;
            layer.close(mixLoading);
            // 确定操作的表单和模型
            var f = this.options.sFormId, m = this.options.sModal;

            // 是否操作详情信息
            if (isDetail) {
                f = this.oDetails.sFormId;
                m = this.oDetails.sModal;
            } else {
                $(m).find('h4').html(this.options.sTitle + (this.action == "create" ? this.getLanguage("sInsert") : this.getLanguage("sEdit")));
            }

            meTables.InitForm(f, data);					// 初始化表单
            // 显示之后的处理
            if (typeof this.afterShow == 'function' && ! this.afterShow(data, isDetail)) return false;
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

        passwordCreate: function(params) {
            params.type = "password";
            return this.inputCreate(params);
        },

        fileCreate: function(params) {
            html = '<input type="hidden" name="' + params.name + '"/>';
            params["id"] = params["id"] ? "file-input-" + params["id"] : "file-input-" + params.name;
            if (params.fileName) params.name = params.fileName;
            params.type = file;
            return html + this.inputCreate(params);
        },

        radioCreate: function(params) {
            html = "";
            if (params.value && this.isObject(params.value)) {
                params['class'] = "ace valid";
                var d = params.value, c = params.default;
                delete params.value;
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

        checkboxCreate: function(params) {
            html = '';
            if (params.value && this.isObject(params.value)) {
                var d = params.value, o = params.all, c = params.divClass ? params.divClass : "col-xs-6";
                delete params.value;
                delete params.all;
                delete params.divClass;
                params["class"] = "ace m-checkbox";
                params = handleParams(params);
                if (o) {
                    html += '<div class="checkbox col-xs-12">' +
                            '<label>' +
                                '<input type="checkbox" class="ace checkbox-all" />' +
                                '<span class="lbl"> 选择全部 </span>' +
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

        selectCreate: function(params) {
            html = "";
            if (params.value && this.isObject(params.value)) {
                var d = params.value, c = params.default;

                delete params.value;
                delete params.default;

                html += "<select " + this.handleParams(params) + ">";
                for (i in data){
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
                    form += this[k.edit.type + "Create"](k.edit);
                } catch (e) {
                    console.info(e);
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
                                tmp = timeFormat(parseInt(tmp), obj.attr('time-format') ? obj.attr('time-format') : "yyyy-MM-dd hh:mm:ss");
                            }
                            objForm[i].value = tmp;
                        }
                    }
                }
            }
        },

        dateCreate: function (params) {
            return '<div class="input-group bootstrap-datepicker"> \
                <input class="form-control date-picker me-date"  type="text" ' + this.handleParams(params) + '/> \
                <span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span> \
                </div>';
        },

        timeCreate: function (params) {
            return '<div class="input-group bootstrap-timepicker"> \
                <input type="text" class="form-control time-picker" ' + this.handleParams(params) + '/> \
                <span class="input-group-addon"><i class="fa fa-clock-o bigger-110"></i></span> \
                </div>';
        },

        // 添加时间
        dateTimeCreate: function (params) {
            return '<div class="input-group bootstrap-datetimepicker"> \
                <input type="text" class="form-control datetime-picker" ' + this.handleParams(params) + '/> \
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
                <input class="form-control daterange-picker me-daterange" type="text" ' + this.handleParams(params) + ' /> \
            </div>';
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

        // 数据信息
        data: {
            sSearchForm: "", // 搜索表单
            sUpdateModel: "",// 编辑表单Model
            sInfoTable: ""   // 查看Table
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