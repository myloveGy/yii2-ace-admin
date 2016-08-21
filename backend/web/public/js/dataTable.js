// 状态信息
function statusToString(td, data) {$(td).html('<span class="label label-' + (data == 1 ? 'success">启用' : 'warning">禁用') + '</span>');}

// 时间戳列，值转换
function dateTimeString(td, cellData) {$(td).html(timeFormat(cellData, 'yyyy-MM-dd hh:mm:ss'));}

// 推荐信息
function recommendToString(td, data) {$(td).html('<span class="label label-' + (data == 1 ? 'success">推荐' : 'warning">不推荐') + '</span>');}

// 用户显示
function adminToString(td, data, rowArr, row, col) {$(td).html(aAdmins[data]);}

// 图片显示
function stringToImage(td, data, rowdatas, row)
{
    if ( ! empty(data)) {
        var alt = empty(rowdatas) ? '图片详情信息' : rowdatas.Title;
        $(td).html('<img width="100px" layer-src="' + data + '" src="' + data + '" alt="' + alt + '" onclick="myTable.seeImage(' + row + ');" />')
    }
}

// 设置表单信息
function setOperate(td, data, rowArr, row, col)
{
	$(td).html(createButtons([
		{"data":row, "title":"查看", "className":"btn-success", "cClass":"me-table-view",  "icon":"fa-search-plus",  "sClass":"blue"},
		{"data":row, "title":"编辑", "className":"btn-info", "cClass":"me-table-edit", "icon":"fa-pencil-square-o",  "sClass":"green"},
		{"data":row, "title":"删除", "className":"btn-danger", "cClass":"me-table-del", "icon":"fa-trash-o",  "sClass":"red"}
	]));
}

// 多选按钮信息
var oCheckBox = {
		"data": 	 null,
		"bSortable": false,
		"class": 	 "center",
		"title": 	 '<label class="position-relative"><input type="checkbox" class="ace" /><span class="lbl"></span></label>',
        "bViews":    false,
		"render": 	 function(data){
			return '<label class="position-relative"><input type="checkbox" value="' + data["id"] + '" class="ace" /><span class="lbl"></span></label>';
        }
    };

// 默认操作选项
var oOperate = {"data": null, "title":"操作", "bSortable":false, "width":"120px", "createdCell":setOperate};
var oOperateDetails = {"data":null, "title":"操作", "bSortable":false, "createdCell":function(td, data, rowArr, row, col){
	$(td).html(createButtons([
		{"data":row, "title":"查看", "className":"btn-success", "cClass":"me-table-view-detail",  "icon":"fa-search-plus",  "sClass":"blue"},
		{"data":row, "title":"编辑", "className":"btn-info", "cClass":"me-table-edit-detail", "icon":"fa-pencil-square-o",  "sClass":"green"},
		{"data":row, "title":"删除", "className":"btn-danger", "cClass":"me-table-del-detail", "icon":"fa-trash-o",  "sClass":"red"}
	]));
}};

var oTableLanguage = {
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
};

/**
 * MeTable
 * Desc: dataTables 表格操作信息
 * User: liujx
 * Date: 2016-07-21
 */
var MeTable = (function($) {
	// 构造函数初始化配置
	function MeTable(options, tableOptions, detailOptions) {
		// 表格信息配置
		this.tableOptions = {
			// "fnServerData": fnServerData,		// 获取数据的处理函数
			"sAjaxSource":      "search",			// 获取数据地址
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
            "oLanguage":        oTableLanguage,		// 语言配置
            "order":            [[1, "desc"]],      // 默认排序
		};

        var self = this;

		// 自定义信息配置
		this.options = {
			sModal: 	  "#myModal", 		// 编辑Modal选择器
			sTitle: 	  "",				// 表格的标题
			sTable: 	  "#showTable", 	// 显示表格选择器
			sFormId:  	  "#editForm",		// 编辑表单选择器
			sBaseUrl:     "update",			// 编辑数据提交URL
			sExportUrl:   "export",         // 数据导出地址
			sSearchHtml:  "",				// 搜索信息
			sSearchType:  "middle",			// 搜索表单位置
			sSearchForm:  "#searchForm",	// 搜索表单选择器
			bRenderH1: 	  true,				// 是否渲染H1内容
			bEditTable:   true,				// 是否开启行内编辑
			oEditTable:   {},				// 行内编辑对象信息
			sEditUrl:	  "editable",	    // 行内编辑请求地址
			sEditPk: 	  "id",				// 行内编辑pk索引值
			iViewLoading: 0, 				// 详情加载Loading
			iLoading:     0, 				// 页面加载Loading
			bViewFull: 	  false,			// 详情打开的方式 1 2 打开全屏
            bColResize:   false,            // 是否运行列宽拖拽
		};

        // 服务器数据处理
        this.tableOptions.fnServerData = function (sSource, aoData, fnCallback)
        {
            self.options.iLoading = layer.load(),
                attributes = aoData[2].value.split(","),
                mSort 	   = (attributes.length + 1) * 5 + 2;

            // 添加查询条件
            var data = $(self.options.sSearchForm).serializeArray();
            for (var i in data)
            {
                if (empty(data[i]["value"]) || data[i]["value"] == "All") continue;
                aoData.push({"name":"params[" + data[i]['name'] + "]", "value":data[i]["value"]});
            }


            // 添加排序字段信息
            if (aoData[mSort].value != undefined && aoData[mSort].value != "") aoData.push({"name":'params[orderBy]', "value": attributes[parseInt(aoData[mSort].value)]});

            // ajax请求
            $.ajax({
                url: sSource,
                data: aoData,
                type: 'post',
                dataType: 'json',
            }).always(function(){
                layer.close(self.options.iLoading);
            }).done(function(data){
                if (data.status != 1) return layer.msg('出现错误:' + data.msg, {time:2000, icon:5});
                $.fn.dataTable.defaults['bFilter'] = true;
                fnCallback(data.data);
            }).fail(ajaxFail);
        };

		// 配置信息修改和继承
		this.tableOptions = $.extend(this.tableOptions, tableOptions);
		this.options 	  = $.extend(this.options, options);
		this.formOptions  = $.extend({
			"method": "post",
			"id": 	  "editForm",
			"class":  "form-horizontal",
			"name":   "editForm",
			"action": this.options.sBaseUrl,
		}, this.options.formOptions);

		// 操作类型
		this.actionType     = "";	  // 默认没有类型
		this.bHandleDetails = false;  // 默认没有开启详情处理
		this.oDetails 		= null;   // 详情配置为空

		// 详情配置的处理
		if (detailOptions != undefined && typeof detailOptions == "object")
		{
			this.bHandleDetails = true;
			this.oDetailParams  = null;
			this.oDetailObject  = null;
			this.oDetails 		= {
				sTable:   		"#detailTable",
				sModal:   		"#myDetailModal",
				sFormId:  		"#myDetailForm",
				sBaseUrl: 		"update",
				sClickSelect:  	"td.details-control",
				oTableOptions: 	{
					"bPaginate": 	 false,             // 不使用分页
					"bLengthChange": false,             // 是否可以调整分页
					"bServerSide": 	 true,		 		// 是否开启从服务器端获取数据
					"bAutoWidth": 	 false,
					"sAjaxSource":	"view",
					"fnServerData": function(sSource, aoData, fnCallback) {
						if (self.oDetailParams)
						{
							self.options.iLoading = layer.load()
							for (var i in self.oDetailParams) aoData.push({name:i, value:self.oDetailParams[i]})
							// ajax请求
							$.ajax({
								url: sSource,
								data: aoData,
								type: 'post',
								dataType: 'json',
							}).always(function(){
								layer.close(self.options.iLoading);
							}).done(function(data){
								if (data.status != 1) return layer.msg('出现错误:' + data.msg, {time:2000, icon:5});
								$.fn.dataTable.defaults['bFilter'] = true;
								fnCallback(data.data);
								if (self.oDetailObject) self.oDetailObject.child(function(){return $('#detailTable').parent().html();}).show()
							}).fail(ajaxFail);
						}

					},		// 获取数据的处理函数
					"searching": 	 false,
					"ordering":  	 false,
					"oLanguage": 	 oTableLanguage,		// 语言配置
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
			views 	   = '<table class="table table-bordered table-striped table-detail">';

		// 处理生成表单
		this.tableOptions.aoColumns.forEach(function(k, v) {
			if (k.bViews !== false) views += createViewTr(k.title, k.data);				    // 查看详情信息
			if (k.edit != undefined) form += createForm(k);									// 编辑表单信息
			if (k.search != undefined) self.options.sSearchHtml += createSearchForm(k, v);  // 搜索信息

			// 判断行内编辑
			if (k.editTable != undefined) {
				// 默认修改参数
				self.options.oEditTable[k.sName] = {
					name:    k.sName,
					type:    k.edit.type == "radio" ? "select" : k.edit.type,
					source:  k.value,
					send:    "always",
					url:     self.options.sEditUrl,
					title:   k.title,
					success: function(response) {if (response.status == 0) return response.msg;},
					error:   function(){$.gritter.add({title:'温馨提醒',text:'服务器没有响应',class_name:'gritter-warning gritter-center',time:800,});}
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

		// 生成HTML
		var Modal = createModal({
			"params": {"id":"myModal"},
			"html":   form,
			"bClass": "me-table-save"},
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
				"html":   views,
			});
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
        if (this.options.sSearchType == 'middle')
        {
            $('#showTable_filter').html('<form action="post" id="searchForm">' + self.options.sSearchHtml + '</form>');
            $('.me-search').on('keyup change', function () { self.table.draw();}); 						// 搜索事件
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
			if (this.bHandleDetails) this.details = $(this.oDetails.sTable).DataTable(this.oDetails.oTableOptions)
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
        if (self.options.bColResize)$(self.options.sTable).colResizable();
	};

	// 表格搜索
	MeTable.prototype.search = function() {this.table.draw();};

	// 初始化表单对象
	MeTable.prototype.initForm = function(data, isDetail)
	{
        // 显示之前的处理
        if (typeof this.beforeShow == 'function' && ! this.beforeShow(data, isDetail)) return false;
		layer.close(this.options.iViewLoading);
		var f = ! isDetail ? this.options.sFormId : this.oDetails.sFormId, // 表单
			m = ! isDetail ? this.options.sModal  : this.oDetails.sModal;  // modal
		if ( ! isDetail) $(m).find('h4').html(this.options.sTitle + (this.actionType == "insert" ? "新增" : "编辑"));
		InitForm(f, data);					// 初始化表单
		// 显示之后的处理
		if (typeof this.afterShow == 'function' && ! this.afterShow(data, isDetail)) return false;
		$(m).modal({backdrop: "static"});   // 弹出信息
	};

	// 查看详情
	MeTable.prototype.view = function(row, isDetail) {
        if (this.options.iViewLoading != 0) return false; // 存在直接返回
		var self = this, data = this.table.data()[row], obj = this.tableOptions.aoColumns, t = self.options.sTitle, c = '.data-info-', i = "#data-info";
		if (isDetail) data = this.details.data()[row], obj  = this.oDetails.oTableOptions.aoColumns, t = '', c += 'detail-', i = "#data-info-detail";
		// 处理的数据
		if (data != undefined)
		{
			viewTable(obj, data, c, row);
			// 弹出显示
			this.options.iViewLoading = layer.open({
			    type:		1,
			    shade: 		0.3,
                shadeClose: true,
			    title: 		t + '详情',
			    content: 	$(i), 			// 捕获的元素
			    area:	 	['50%', 'auto'],
			    cancel: 	function(index){layer.close(index);},
                end: 		function(){$('.views-info').html('');self.options.iViewLoading = 0;},
				maxmin: 	true
			});

			// 展开全屏(解决内容过多问题)
			if (this.options.bViewFull) layer.full(this.options.iViewLoading)
		}
	};

	// 表格数据的添加
	MeTable.prototype.insert = function(isDetail) {
		this.actionType = !isDetail ? "insert" : "insertDetail";
		this.initForm(undefined, isDetail);
	};


	// 修改数据信息
	MeTable.prototype.update = function(row, isDetail) {
		var d = ! isDetail ? this.table.data()[row] : this.details.data()[row]; // 数据
		this.actionType = ! isDetail ? "update" : "updateDetail"; 				// 类型
		this.initForm(d, isDetail); 											// 初始化表单
	};

	// 删除数据
	MeTable.prototype.delete = function(row, isDetail) {
		var data = !isDetail ? this.table.data()[row] : this.details.data()[row], // 数据
			self = this;														  // 对象
		this.actionType = ! isDetail ? "delete" : "deleteDetail";				  // 操作类型

		// 询问框
		layer.confirm('您确定需要删除这条数据吗?', {
			title: '确认操作',
			btn: ['确定','取消'],
			shift: 4,
			icon: 0
			// 确认删除
		}, function(){
			self.save(data);
			// 取消删除
		}, function(){layer.msg('您取消了删除操作！', {time:800});});
	};

	// 删除全部数据
	MeTable.prototype.deleteAll = function() {
        var data = [], self = this;
        this.actionType = "deleteAll";

        // 数据添加
        $(this.options.sTable + " tbody input:checkbox:checked").each(function(){data.push($(this).val());});

        // 数据为空提醒
        if (data.length < 1)  return bootbox.alert({title:"温馨提醒",message:"您没有选择需要删除的数据 ! "});

        // 确认操作提醒
        bootbox.dialog({
            title:"温馨提醒",
            size:"small",
            message:'<p style="padding-left:15px; color:red">确定需要删除这' + data.length + '条数据吗?</p>',
            buttons:{
                success:{
                    label:'<span class="ui-button-text"><i class="ace-icon fa fa-trash-o bigger-110"></i> 确定删除 </span>',
                    className:"btn btn-danger",
                    callback:function() {self.save({"ids":data.join(',')});}
                },
                cell:{
                    label:"取消",
                    className:"btn-default",
                    callback:function(){layer.msg("您取消了删除操作！");}
                }
            },
        });
	};

	// 数据新增和修改的执行
	MeTable.prototype.save = function(data) {
        // 初始化判断和数据处理
		if (this.actionType == "" && !in_array(this.actionType, ["insert", "update", "delete", "deleteAll", "insertDetail", "updateDetail", "deleteDetail"])) return false; // 类型验证
		var self = this, sFormId = this.options.sFormId,sBaseUrl = self.options.sBaseUrl, sModal = self.options.sModal;														// 初始化数据
		if (in_array(this.actionType, ["insertDetail", "updateDetail"])) sFormId = self.oDetails.sFormId, sBaseUrl = self.oDetails.sBaseUrl, sModal = self.oDetails.sModal; // 详情处理
		// 新增和修改验证数据、数据的处理
		if (!in_array(this.actionType, ["delete", "deleteAll", "deleteDetail"])){if(!$(sFormId).validate(validatorError).form()){return false;}data = $(sFormId).serializeArray();data.push({"name":"actionType", "value":this.actionType})}else{data.actionType = this.actionType;}

        // 执行之前的数据处理
        if (typeof self.beforeSave == 'function' && ! self.beforeSave(data)) return false;

        self.options.iLoading = layer.load();
		// ajax提交数据
		$.ajax({
			url:        sBaseUrl,
			type:       'POST',
			data:       data,
			dataType:   'json',
		}).always(function(){
			layer.close(self.options.iLoading);
		}).done(function(json){
			layer.msg(json.msg, {icon:json.status == 1 ? 6 : 5});
            // 判断操作成功
            if (json.status == 1)
            {
                // 执行之后的数据处理
                if (typeof self.afterSave == 'function' && ! self.afterSave(json.data)) return false;
                self.table.draw(false);
                if (self.actionType !== "delete") $(sModal).modal('hide');
                self.actionType = "";
            }
		}).fail(ajaxFail);
		return false;
	};

    // 数据导出
    MeTable.prototype.export = function(bAll) {
        var self = this,
            html = '<form action="' + self.options.sExportUrl + '" target="_blank" method="POST" class="me-export" style="display:none">';
        html += '<input type="hidden" name="iSize" value="' + (bAll ? 0 : $('select[name=' + self.options.sTable.replace('#', '') + '_length]').val()) + '"/>';
        html += '<input type="hidden" name="sTitle" value="' + self.options.sTitle + '"/>';


        // 添加字段信息
        this.tableOptions.aoColumns.forEach(function(k, v){
            if (k.data != null && (k.isExport == undefined)) html += '<input type="hidden" name="aFields[' + k.data + ']" value="' + k.title + '"/>';
        });

        // 添加查询条件
        var value = $(self.options.sSearchForm).serializeArray();
        for (var i in value)
        {
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
            //temp_iframe.attr('src', 'about:blank').remove();
            $('.me-export').remove();
        } , 500);

        deferred
        .fail(function(result) {
            if (result)
            {
                try {
                    result = $.parseJSON(result);
                    gAlert("温馨提醒：", result.msg, result.status == 1 ? 'success' : "warning");
                } catch (e) {
                    gAlert("温馨提醒：", '服务器没有响应...');
                }
            }
            else
            {
                gAlert('温馨提醒：', '数据正在导出, 请稍候...', 'success');
            }

        })
        .always(function() {clearTimeout(ie_timeout);});
        deferred.promise();
    };
	return MeTable;
})($);