/**
 * MeTable
 * Desc: dataTables 表格操作信息
 * User: liujx
 * Date: 2016-07-21
 */


var MeTable = (function() {
	// 构造函数初始化配置
	function MeTable(options, tableOptions, detailOptions) {
        // 表格信息配置
        this.tableOptions = {
            // "fnServerData": fnServerData,		// 获取数据的处理函数
            // "sAjaxSource":      "search",			// 获取数据地址
            "bLengthChange":    true, 				// 是否可以调整分页
            "bAutoWidth":       false,           	// 是否自动计算列宽
            "bPaginate":        true,			    // 是否使用分页
            "iDisplayStart":    0,
            "iDisplayLength":   10,
            "bServerSide":      true,		 		// 是否开启从服务器端获取数据
            "bRetrieve":        true,
            "bDestroy":         true,
            // "processing": true,				    // 是否使用加载进度条
            "sPaginationType":  "full_numbers",     // 分页样式
            // "oLanguage":        oTableLanguage,	// 语言配置
            "order":            [[1, "desc"]]       // 默认排序
        };

        // 自定义信息配置
        this.options = {
            sModal: 	  "#myModal", 		// 编辑Modal选择器
            sTitle: 	  "",				// 表格的标题
            sTable: 	  "#showTable", 	// 显示表格选择器
            sFormId:  	  "#editForm",		// 编辑表单选择器
            sMethod:	  "POST",			// 查询数据的请求方式,
            sBaseUrl:	  "",				// 编辑的统一路由地址前缀
            aActionUrl:	  {					// 编辑数据提交URL 请求地址
                "search": "search",			// 查询
                "insert": "create",			// 创建
                "update": "update",			// 修改
                "delete": "delete",			// 删除
                "deleteAll": "delete-all",  // 删除全部
                "export": "export", 		// 导出
                "upload": "upload",  		// 下载
                "inline": "editable" 		// 行内编辑
            },
            isCheckbox:   true,				// 需要多选框
            aParams:	  null,				// 请求携带参数
            sExportUrl:   "export",         // 数据导出地址
            sSearchHtml:  "",				// 搜索信息
            sSearchType:  "middle",			// 搜索表单位置
            sSearchForm:  "#searchForm",	// 搜索表单选择器
            aFileSelector: [],				// 上传文件选择器
            oEditFormParams: {				// 编辑表单配置
                bMultiCols: false,          // 是否多列
                iColsLength: 1,             // 几列
                aCols: [3, 9],              // label 和 input 栅格化设置
                sModalClass: "",			// 弹出模块框配置
                sModalDialogClass: ""		// 弹出模块的class
            },
            oViewLayerConfig: {
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
            bRenderH1: 	  true,				// 是否渲染H1内容
            bEditTable:   false,			// 是否开启行内编辑
            oEditTable:   {},				// 行内编辑对象信息
            sEditPk: 	  "id",				// 行内编辑pk索引值
            iViewLoading: 0, 				// 详情加载Loading
            iLoading:     0, 				// 页面加载Loading
            bViewFull: 	  false,			// 详情打开的方式 1 2 打开全屏
            bColResize:   false,            // 是否运行列宽拖拽

            oLanguage: {
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
					"sServerErrorMessage": "服务器繁忙,请稍候再试...",
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
        };

        // 语言
        this.language = options.oLanguage ? options.oLanguage : this.options.oLanguage;
        var self = this;

        this.options.oOperation = {
            isOpen: true,
                width: "120px",
                title: self.language.meTables.oOperation.sTitle,
                buttons: [
                {"title": self.language.meTables.oOperation.sView, "className": "btn-success", "cClass":"me-table-view",  "icon":"fa-search-plus",  "sClass":"blue"},
                {"title": self.language.meTables.oOperation.sEdit, "className": "btn-info", "cClass":"me-table-edit", "icon":"fa-pencil-square-o",  "sClass":"green"},
                {"title": self.language.meTables.oOperation.sDelete, "className": "btn-danger", "cClass":"me-table-del", "icon":"fa-trash-o",  "sClass":"red"}
            ]
        };

        // 关闭Layer.load()
		this.closeLoading = function(){
			layer.close(self.options.iLoading);
		};

		// AJAX 错误处理
		this.ajaxFail = function(err) {
            layer.msg(self.language.meTables.sServerErrorMessage, {time:1000, icon:2});
		};

		// ajax deferred
		this.ajaxDeferred = function(obj) {
            self.options.iLoading = layer.load();
			return $.ajax(obj).always(self.closeLoading).fail(self.ajaxFail);
		};

        // 服务器数据处理
        this.tableOptions.fnServerData = function (sSource, aoData, fnCallback) {
            var attributes = aoData[2].value.split(","),
            	mSort 	   = (attributes.length + 1) * 5 + 2;

            // 添加查询条件
            var data = $(self.options.sSearchForm).serializeArray();
            for (var i in data) {
                if (empty(data[i]["value"]) || data[i]["value"] == "All") continue;
                aoData.push({"name":"params[" + data[i]['name'] + "]", "value":data[i]["value"]});
            }

            // 添加排序字段信息
            if (aoData[mSort].value != undefined && aoData[mSort].value != "") aoData.push({"name":'params[orderBy]', "value": attributes[parseInt(aoData[mSort].value)]});

            // ajax请求
            self.ajaxDeferred({
                url: sSource,
                data: aoData,
                type: self.options.sMethod,
                dataType: 'json'
            }).done(function(data){
                if (data.errCode != 0) return layer.msg(self.language.sAppearError + data.errMsg, {time:2000, icon:5});
                $.fn.dataTable.defaults['bFilter'] = true;
                fnCallback(data.data);
            });
        };

        // 对象配置重写
        options.oEditFormParams = $.extend(this.options.oEditFormParams, options.oEditFormParams);
        options.aActionUrl = $.extend(this.options.aActionUrl, options.aActionUrl);
        options.oOperation = $.extend(this.options.oOperation, options.oOperation);

		// 配置信息修改和继承
		this.tableOptions = $.extend(this.tableOptions, tableOptions);
		this.options 	  = $.extend(this.options, options);
		this.formOptions  = $.extend({
			"method": "post",
			"id": 	  "editForm",
			"class":  "form-horizontal",
			"name":   "editForm",
			"action": "update"
		}, this.options.formOptions);

		// 查询数据地址
        if (empty(this.tableOptions.sAjaxSource)) {
        	this.tableOptions.sAjaxSource = this.options.sBaseUrl + this.options.aActionUrl.search;
        }

		// 判断添加数据(多选)
		if (this.options.isCheckbox) {
			this.tableOptions.aoColumns.unshift({
                "data": 	 null,
                "bSortable": false,
                "class": 	 "center",
                "title": 	 '<label class="position-relative"><input type="checkbox" class="ace" /><span class="lbl"></span></label>',
                "bViews":    false,
                "render": 	 function(data){
                    return '<label class="position-relative"><input type="checkbox" value="' + data["id"] + '" class="ace" /><span class="lbl"></span></label>';
                }
            })
		}

		// 语言配置
		this.tableOptions.oLanguage = this.language.dataTables;

		// 判断添加数据(操作选项)
		if (this.options.oOperation.isOpen) {
            this.tableOptions.aoColumns.push({
                "data": 	 null,
                "bSortable": false,
                "title": this.options.oOperation.title,
				"width": this.options.oOperation.width,
                "createdCell": function(td, data, rowArr, row, col) {
                    $(td).html(createButtons(row, self.options.oOperation.buttons));
                }
            })
		}


		// 操作类型
		this.actionType     = "";	  // 默认没有类型
		this.bHandleDetails = false;  // 默认没有开启详情处理
		this.oDetails 		= null;   // 详情配置为空
		this.bDetail  		= false;

		// 详情配置的处理
		if (detailOptions != undefined && typeof detailOptions == "object") {
			this.bHandleDetails = true;
			this.oDetailParams  = null;
			this.oDetailObject  = null;
			this.oDetails 		= {
				sTable:   		"#detailTable",
				sModal:   		"#myDetailModal",
				sFormId:  		"#myDetailForm",
				sBaseUrl:	  	self.options.sBaseUrl, // 详情编辑的统一前缀
				sActionUrl: 	{
					"insert": "create", // 创建
					"update": "update",	// 修改
					"delete": "delete" // 删除
				},
				sClickSelect:  	"td.details-control",
				oTableOptions: 	{
					"bPaginate": 	 false,             // 不使用分页
					"bLengthChange": false,             // 是否可以调整分页
					"bServerSide": 	 true,		 		// 是否开启从服务器端获取数据
					"bAutoWidth": 	 false,
					"sAjaxSource":	"view",
					"fnServerData": function(sSource, aoData, fnCallback) {
						if (self.oDetailParams) {
							for (var i in self.oDetailParams) aoData.push({name:i, value:self.oDetailParams[i]})
							// ajax请求
                            self.ajaxDeferred({
								url: sSource,
								data: aoData,
								type: 'post',
								dataType: 'json'
							}).done(function(data){
								if (data.errCode != 0) {
                                    return layer.msg(self.language.meTables.sAppearError + data.errMsg, {time:2000, icon:5});
								}
								$.fn.dataTable.defaults['bFilter'] = true;
								fnCallback(data.data);
								if (self.oDetailObject) self.oDetailObject.child(function(){return $('#detailTable').parent().html();}).show()
							});
						}

					},		// 获取数据的处理函数
					"searching": 	 false,				// 搜索
					"ordering":  	 false,			 	// 排序
					"oLanguage": 	 self.options.oLanguage[this.options.sDefaultLanguage].dataTables		// 语言配置
				}
			};

			detailOptions.oTableOptions = $.extend(this.oDetails.oTableOptions, detailOptions.oTableOptions)
			this.oDetails = $.extend(this.oDetails, detailOptions)
		}
	}

	// 处理表单信息
	MeTable.prototype.CreateForm = function() {
		var self       = this,
			formParams = handleParams(this.formOptions),
			form 	   = '<form ' + formParams + '><fieldset>',
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
	};

	// 生成表格对象
	MeTable.prototype.init = function() {
		var self = this;this.CreateForm();
        // 初始化函数的处理
		this.table = $(this.options.sTable).DataTable(this.tableOptions);	// 初始化主要表格
        if (this.options.bRenderH1) $('h1').html(this.options.sTitle);		// 判断是否渲染H1

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
		$('.me-table-insert').click(function(evt){evt.preventDefault();self.insert();});
		$(document).on('click', '.me-table-edit', function(evt){evt.preventDefault();self.update($(this).attr('table-data'))});
		$(document).on('click', '.me-table-del', function(evt){evt.preventDefault();self.delete($(this).attr('table-data'))});
		$(document).on('click', '.me-table-view', function(evt){evt.preventDefault();self.view($(this).attr('table-data'))});
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
			$('.me-table-insert-detail').click(function(evt){evt.preventDefault();self.insert(true);});
			$(document).on('click', '.me-table-view-detail', function(evt){evt.preventDefault();self.view($(this).attr('table-data'), true)});
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
	};

	// 表格搜索
	MeTable.prototype.search = function() {this.table.draw();};

	// 初始化表单对象
	MeTable.prototype.initForm = function(data, isDetail)
	{
		this.bDetail = isDetail;
        // 显示之前的处理
        if (typeof this.beforeShow == 'function' && ! this.beforeShow(data, isDetail)) return false;
		layer.close(this.options.iViewLoading);
		// 确定操作的表单和模型
		var f = this.options.sFormId, m = this.options.sModal;

		// 是否操作详情信息
		if (isDetail) {
			f = this.oDetails.sFormId;
			m = this.oDetails.sModal;
		} else {
			$(m).find('h4').html(this.options.sTitle + (this.actionType == "insert" ? this.language.meTables.sInsert : this.language.meTables.sEdit));
		}

		InitForm(f, data);					// 初始化表单
		// 显示之后的处理
		if (typeof this.afterShow == 'function' && ! this.afterShow(data, isDetail)) return false;
		$(m).modal({backdrop: "static"});   // 弹出信息
	};

	// 查看详情
	MeTable.prototype.view = function(row, isDetail) {
        if (this.options.iViewLoading != 0) return false; // 存在直接返回
		var self = this, data = this.table.data()[row], obj = this.tableOptions.aoColumns, t = self.options.sTitle, c = '.data-info-', i = "#data-info";
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
			    title: 		t + self.language.meTables.sInfo,
			    content: 	$(i), 			// 捕获的元素
			    area:	 	this.options.oViewLayerConfig.area,
			    cancel: 	function(index){layer.close(index);},
                end: 		function(){$('.views-info').html('');self.options.iViewLoading = 0;},
				maxmin: 	this.options.oViewLayerConfig.maxmin
			});

			// 展开全屏(解决内容过多问题)
			if (this.options.bViewFull) layer.full(this.options.iViewLoading)
		}
	};

	// 表格数据的添加
	MeTable.prototype.insert = function(isDetail) {
		this.actionType = "insert";
		this.initForm(undefined, isDetail);
	};


	// 修改数据信息
	MeTable.prototype.update = function(row, isDetail) {
		this.actionType = "update"; 											// 类型
		this.initForm(isDetail ? this.details.data()[row] : this.table.data()[row], isDetail); 											// 初始化表单
	};

	// 删除数据
	MeTable.prototype.delete = function(row, isDetail) {
		var self = this;			// 对象
		this.actionType = "delete";	// 操作类型

		// 询问框
		layer.confirm(self.language.meTables.oDelete.confirm.replace("_LENGTH_", ""), {
			title: self.language.meTables.oDelete.confirmOperation,
			btn: [self.language.meTables.oDelete.determine, self.language.meTables.oDelete.cancel],
			shift: 4,
			icon: 0
			// 确认删除
		}, function(){
			self.save(isDetail ? self.details.data()[row] : self.table.data()[row]);
			// 取消删除
		}, function(){
			layer.msg(self.language.meTables.oDelete.cancelOperation, {time:800});
		});
	};

	// 删除全部数据
	MeTable.prototype.deleteAll = function() {
        var data = [], self = this;
        this.actionType = "deleteAll";

        // 数据添加
        $(this.options.sTable + " tbody input:checkbox:checked").each(function(){data.push($(this).val());});

        // 数据为空提醒
        if (data.length < 1)  {
        	layer.msg(self.language.meTables.oDelete.noSelect, {icon:5});
        	return false;
        }

        // 询问框
        layer.confirm(self.language.meTables.oDelete.confirm.replace("_LENGTH_", data.length), {
            title: self.language.meTables.oDelete.confirmOperation,
            btn: [self.language.meTables.oDelete.determine, self.language.meTables.oDelete.cancel],
            shift: 4,
            icon: 0
            // 确认删除
        }, function(){
            self.save({"ids":data.join(',')});
            // 取消删除
        }, function(){
            layer.msg(self.language.meTables.oDelete.cancelOperation, {time:800});
        });
	};

	// 数据新增和修改的执行
	MeTable.prototype.save = function(data) {
        // 初始化判断和数据处理
		if (in_array(this.actionType, ["insert", "update", "delete", "deleteAll"])) {
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
			if (in_array(this.actionType, ["insert", "update"])) {
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
                    self.ajaxDeferred({
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
	};

    // 数据导出
    MeTable.prototype.export = function(bAll) {
        var self = this,
            html = '<form action="' + self.options.sBaseUrl + self.options.aActionUrl.export + '" target="_blank" method="POST" class="me-export" style="display:none">';
        html += '<input type="hidden" name="iSize" value="' + (bAll ? 0 : $('select[name=' + self.options.sTable.replace('#', '') + '_length]').val()) + '"/>';
        html += '<input type="hidden" name="sTitle" value="' + self.options.sTitle + '"/>';
		html += '<input type="hidden" name="_csrf" value="' + $('meta[name=csrf-token]').attr('content') + '"/>';

        // 添加字段信息
        this.tableOptions.aoColumns.forEach(function(k, v){
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
    };
	return MeTable;
})();
