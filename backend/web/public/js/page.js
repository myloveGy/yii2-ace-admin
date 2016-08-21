var MePage = (function($){
	// 初始化函数
	function MePage(options)
	{
		// 默认参数
		this.options = {
			divId    : '', 				 // 选择显示容器
			ajaxUrl  : '?',				 // 请求的地址
			ajaxType : 'GET',			 // 请求的方式
			html 	 : '', 				 // HTML
			pageNum  : 5,				 // 数字分页条数
			pageSize : 10,				 // 分页长度
			pageHtml : {				 // 其他配置
				first : '首页',
				prev  : '上一页',
				next  : '下一页',
				last  : '尾页'
			},
			callback:function(data, size) {
				layer.msg('success !');
			}
		};

		this.options = $.extend(this.options, options);
		this._iTotal  = 0;
		this._iCurr   = 1;
		this._iMin    = 1;
		this._iMax    = 0;
		this._iMiddle = Math.floor(this.options.pageNum / 2);
		this._iEnd    = 0;
		this.aData    = {};  

		// 请求参数
		this.Params = {
			sEcho   : 1, 				 		// 请求次数
			iStart  : 0,						// 开始位置
			iLength : this.options.pageSize,	// 查询条数
		};

		// 获取数据
		this.ajax();
	}

	// 请求页面获取数据
	MePage.prototype.ajax = function(){
		self = this;
		this.load = layer.load();
		$.ajax({
			url  : this.options.ajaxUrl,
			type : this.options.ajaxType,
			data : this.Params,
			success:function(data) {
				layer.close(self.load)
				if (data.status == 1 && data.data != undefined && data.data != null)
				{
					self.aData = data.data;
					self.options.callback(data.data.aData, data.data.iTotalRecords);
					self.page();
					return false;
				} 

				layer.msg(data.msg, {icon:5});
			},
			error: function(){
				layer.close(self.load);
				layer.msg('请求页面没有响应', {icon:5});
			}
		});
	};

	// page 负责显示分页信息
	MePage.prototype.page = function() {
		this.compute();
		// 判断添加首页
		var first = this._iCurr == 1 ? 'class="disabled"' : '',
			last  = this._iCurr == this._iTotal ? 'class="disabled"' : '',
			li    =  '<li ' + first + '><a href="javascript:;" ' + (this._iCurr == 1 ? '' : 'onclick="self.skip(1)"') + ' aria-label="Previous"><span aria-hidden="true">' + this.options.pageHtml.first + '</span></a></li><li ' + first + '><a href="javascript:;" ' + (this._iCurr == 1 ? '' : 'onclick="self.skip(\'prev\')"') + '>' + this.options.pageHtml.prev + '</a></li>';

		// 添加数据分页
		for (var i = this._iMin; i <= this._iMax; i ++)
		{	
			li += '<li '+(this._iCurr == i ? 'class="active"' : '')+'><a href="javascript:;" '+(this._iCurr == i ? '' : 'onclick="self.skip('+i+')"')+'>' + i + '</a></li>';
		}

		// 添加尾页
		li += '<li '+last+'><a href="javascript:;" ' + (this._iCurr == this._iTotal ? '' : 'onclick="self.skip(\'next\')"') + '>' + this.options.pageHtml.next + '</a></li><li '+last+'><a href="javascript:;" ' + (this._iCurr == this._iTotal ? '' : 'onclick="self.skip('+this._iTotal+')"') + ' aria-label="Next"><span aria-hidden="true">' + this.options.pageHtml.last + '</span></a></li>';

		this.options.html = '<nav><ul class="pagination">'+li+'</ul></nav>';

		// 显示分页
		$(this.options.divId).html(this.options.html);
		this.Params.sEcho ++;
	};

	// 处理当前页 和 查询的参数信息
	MePage.prototype.current = function() {
		this._iCurr  = Math.min(Math.max(1, this._iCurr), this._iTotal);		  // 当前页的容错处理保证页面的有效性
		this.Params.iStart = (this._iCurr - 1) * this.options.pageSize;		      // 查询的开始位置
	}

	// compute负责数据的计算
	MePage.prototype.compute = function() {
		this._iTotal = Math.ceil(this.aData.iTotal / this.options.pageSize) 	  // 分页总条数
		this.current();
		this._iMin   = Math.max(this._iCurr - this._iMiddle, 1);				  // 显示开始页数
		this._iEnd   = this._iTotal - this.options.pageNum;						  // 最后显示分页开始值
		if (this._iMin > this._iEnd) this._iMin = this._iEnd > 1 ? this._iEnd : 1;
		this._iMax   = Math.min(this.options.pageNum + this._iMin, this._iTotal); // 分页显示结束页面
	};

	// 指定跳转到那一页
	MePage.prototype.skip = function(params) {
		typeof params == 'string' ? (params == 'prev' ? this._iCurr -- : this._iCurr ++) : this._iCurr = params;
		this.current();
		this.ajax();
	};

	return MePage;
})($); 