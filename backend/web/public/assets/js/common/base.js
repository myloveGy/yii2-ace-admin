/**
 * file: base.js
 * desc: 主要函数库
 * user: liujx
 * date: 2016-6-15
 * 注 ： create开头函数用来配合生成HTML 第一个参数接收配置信息, 第二个接收值, 第三个接收默认值
 */
var oLoading = null;
// 关闭全局的loading
function alwaysClose(){layer.close(oLoading)}
// ajax出现错误调用
function ajaxFail(){ return layer.msg("服务器繁忙,请稍候再试...", {time:1000, icon:2})}
// 验证数据是否为空
function empty(val){return val==undefined||val==""}
// 判断值是否存在数组或者对象中
function in_array(val,arr){for(var i in arr){if(arr[i]===val){return true}}return false}
// 首字母大写
function ucfirst(str){return str.substr(0, 1).toUpperCase() + str.substr(1)}
// 连接参数为字符串
function handleParams(params, prefix){var other=""; prefix = prefix ? prefix : '';if(params!=undefined&&typeof params=="object"){for(var i in params){other+=" "+i+'="'+prefix + params[i]+'" '}}return other}
// 生成label
function Label(content,params){return"<label "+handleParams(params)+"> "+content+" </label>"}
// 生成Input
function createInput(params, type){return'<input type="'+type+'" '+handleParams(params)+" />"}
// 生成密码
function createPassword(params){ return createInput(params, 'password');}
// 生成text
function createText(params) {return createInput(params, 'text')}
// 生成textarea
function createTextarea(params){if(empty(params)){params={"class":" form-control","rows":5}}else{params["class"]+=" form-control";params["rows"]=5}return"<textarea "+handleParams(params)+"></textarea>"}
// 生成radio
function createRadio(params, data, checked){
    var html="",params=handleParams(params);
    if(data!=undefined&&typeof data=="object"){
        for(var i in data){
            var check = checked == i ? ' checked="checked" ':"";
            html += '<label class="line-height-1 blue"> <input type="radio" '+params+check+' value="'+i+'"  /> <span class="lbl"> '+data[i]+" </span> </label>　 "
        }
    }
    return html
}

// 生成select
function createSelect(params, data, selected){
    var html = "", params = handleParams(params);
    if(data != undefined && typeof data == "object") {
        html += "<select "+params+">";
        for(var i in data){
            var select = i == selected ? ' selected="selected" ':"";
            html += '<option value="'+i+'" '+select+" >"+data[i]+"</option>"
        }

        html += "</select>"}
    return html
}

// 生成上传文件类型 file
function createFile(params){
    var tmp = $.extend({}, params);
    if (params && params.type && params.type == 'ace_input')
    {
        tmp.name = 'UploadForm[' + tmp.name + ']';
        tmp.id   = 'ace_' + tmp.id;
    }

    return '<input type="file" ' + handleParams(tmp) + '/><input type="hidden" ' + handleParams(params) + '/>';
}

// 添加时间天
function createDate(params) {
    return '<div class="input-group bootstrap-datepicker"> \
        <input class="form-control date-picker me-date"  type="text" ' + handleParams(params) + '/> \
        <span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span> \
        </div>';
}

// 添加时间分秒
function createTime(params) {
    return '<div class="input-group bootstrap-timepicker"> \
        <input type="text" class="form-control time-picker me-time" ' + handleParams(params) + '/> \
        <span class="input-group-addon"><i class="fa fa-clock-o bigger-110"></i></span> \
        </div>';
}

// 添加时间
function createDatetime(params) {
    return '<div class="input-group bootstrap-datetimepicker"> \
        <input type="text" class="form-control datetime-picker me-datetime" ' + handleParams(params) + '/> \
        <span class="input-group-addon"><i class="fa fa-clock-o bigger-110"></i></span> \
        </div>';
}

// 时间段
function createTimerange(params) {
    return '<div class="input-daterange input-group"> \
        <input type="text" class="input-sm form-control" name="start" /> \
        <span class="input-group-addon"><i class="fa fa-exchange"></i></span> \
        <input type="text" class="input-sm form-control" name="end" /> \
        </div>'
}

// 添加时间段
function createDaterange(params) {
    return '<div class="input-group"> \
        <span class="input-group-addon"><i class="fa fa-calendar bigger-110"></i></span> \
        <input class="form-control daterange-picker me-daterange" type="text" ' + handleParams(params) + ' /> \
        </div>';
}

// 生成多语言配置信息
function createLang(obj)
{
    var html = '<div class="col-sm-12 m-pl-0"><div class="tabbable">', mli = '', mDiv = '';
    if (language)
    {
        obj['class'] = 'form-control';
        var n = obj.name, m = 1;
        for (var i in language)
        {
            obj.name = n;
            obj.name += '[' + i + ']';
            var params = handleParams(obj), mid = n + m, input = obj.type == undefined ? "<textarea " + params + "></textarea>" : "<input type=\"text\" "+ params +"/>";
            mli += '<li class="' + (m == 1 ? 'active' : '') + '">\
                    <a href="#' + mid + '" data-toggle="tab">' + language[i] + '</a>\
                </li>';
            mDiv += '<div class="tab-pane ' + (m == 1 ? 'active' : '') + '" id="'+ mid + '">\
                    ' + input + '\
                </div>';
            m ++;
        }
    }

    html += '<ul class="nav nav-tabs padding-12 tab-color-blue background-blue">\
            ' + mli + '\
            </ul>\
            <div class="tab-content">\
            ' + mDiv + '\
            </div>\
        </div></div>';

    return html;
}

// 多选按钮 checkbox
function createCheckbox(params, data, checked, arr, isHave)
{
    if (arr == undefined) arr = 'col-xs-6';
    var html = '', params = handleParams(params);
    if (data != undefined && typeof data=="object")
    {
        if (isHave == undefined) html += '<div class="checkbox col-xs-12"><label><input type="checkbox" class="ace checkbox-all" /><span class="lbl"> 选择全部 </span></label></div>';
        for (var i in data)
        {
            html += '<div class="checkbox ' + arr + '"><label><input type="checkbox" ' + params + ' value="' + i + '" /><span class="lbl"> ' + data[i] + ' </span></label></div>';
        }
    }

    return html;
}

// 生成按钮
function createButtons(data) {
    var div1   = '<div class="hidden-sm hidden-xs btn-group">',
        div2   = '<div class="hidden-md hidden-lg"><div class="inline position-relative"><button data-position="auto" data-toggle="dropdown" class="btn btn-minier btn-primary dropdown-toggle"><i class="ace-icon fa fa-cog icon-only bigger-110"></i></button><ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">';
    // 添加按钮信息
    if(data != undefined && typeof data == "object")
    {
        for(var i in data)
        {
            div1 += ' <button class="btn ' + data[i]['className'] + ' '+  data[i]['cClass'] + ' btn-xs" table-data="' + data[i]['data'] + '"><i class="ace-icon fa ' + data[i]["icon"] + ' bigger-120"></i></button> ';
            div2 += '<li><a title="' + data[i]['title'] + '" data-rel="tooltip" class="tooltip-info ' + data[i]['cClass'] + '" href="javascript:;" data-original-title="' + data[i]['title'] + '" table-data="' + data[i]['data'] + '"><span class="' + data[i]['sClass'] + '"><i class="ace-icon fa ' + data[i]['icon'] + ' bigger-120"></i></span></a></li>'; 
        }
    }

    return div1 + '</div>' + div2 + '</ul></div></div>';
}

// 生成表单对象
function createForm(k)
{
    var form = '';
    // 处理其他参数
    if (k.edit.options == undefined) k.edit.options = {}; // 容错处理
    if (!k.edit.type) k.edit.type = "text";
    k.edit.options["name"]  = k.sName;
    k.edit.options["class"] = "form-control";
    if (k.edit.type == undefined) k.edit.type = "text";

    if ( k.edit.type == "hidden" )
        form += createInput(k.edit.options, 'hidden');
    else
    {
        // 判断类型
        form += '<div class="form-group">' + Label(k.title, {"class":"col-sm-3 control-label"}) + '<div class="col-sm-9">';

        // 单选选按钮添加样式
        if (k.edit.type == "radio") k.edit.options['class'] = 'ace valid';
        // 多选按钮处理
        if (k.edit.type == "checkbox") {
            k.edit.options['class'] = 'ace m-checkbox';
            k.edit.options['name']  = k.sName + '[]';
        }

        // 默认输入框处理
        if (k.edit.type == "text") if (!empty(k.value)) k.edit.options["value"] = k.value;

        // 使用函数
        form += window['create' + ucfirst(k.edit.type)](k.edit.options, k.value, k.edit.default) + '</div></div>';
    }

    return form;
}

// 生成查看详情的Table
function createViewTr(title, data) {
    return '<tr><td width="25%">' + title + '</td><td class="views-info data-info-' + data + '"></td></tr>'
}

// 生成查表单信息
function createSearchForm(k, v) {
    k.search.options = $.extend({"name":k.sName, "vid":v, "class":"me-search"}, k.search.options);
    if (k.search.type == "select") {k.value["All"] = "全部";}
    var html = window['create' + ucfirst(k.search.type)](k.search.options, k.value, "All");
    if (k.search.type == "select") delete k.value["All"]
    return Label(k.title + " : " + html) + ' ';
}

// 生成编辑和查看详细modal
function createModal(oModal, oViews) {
    return '<div class="isHide" '+ handleParams(oViews['params']) +'> ' + oViews['html'] +  ' </table></div> \
            <div class="modal fade" '+ handleParams(oModal['params']) +' tabindex="-1" role="dialog" > \
                <div class="modal-dialog" role="document"> \
                    <div class="modal-content"> \
                        <div class="modal-header"> \
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> \
                            <h4 class="modal-title"> 编 辑 </h4> \
                        </div> \
                        <div class="modal-body">' + oModal['html'] + '</fieldset></form></div> \
                        <div class="modal-footer"> \
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button> \
                            <button type="button" class="btn btn-primary btn-image ' + oModal['bClass'] + '">确定</button> \
                        </div> \
                    </div> \
                </div> \
            </div>';
}

// 提示信息
function gAlert(sTitle, sMessage, className, iTime) {
    className = className ? className : "warning"; iTime = iTime ? iTime : 1500; // 默认值
    return $.gritter.add({title: sTitle,text: sMessage,class_name: 'gritter-' + className + ' gritter-center',time: iTime});
}

// 上传错误信息处理
function validateFile(info) {
    var error = [];
    if (info && typeof info == "object") {
        // 判断错误类型
        if (info.error_count['ext'] || info.error_count['mime']) error.push("上传文件类型错误")
        // 判断上传文件大小
        if (info.error_count['size']) error.push("上传文件过大");
    }
    return error.join(";");
}

// 文件上传
function aceFileInputAjax(file_input, url) {
    var $form      = file_input.closest('form'),
        files      = file_input.data('ace_input_files'),
        deferred   = new $.Deferred;
    // 没有上传文件
    if ( !files || files.length == 0 ) { deferred.resolve();return deferred.promise();}

    // 数据提交的处理
    if ( "FormData" in window )
    {
        formData_object = new FormData();
        // 表单数据
        $.each($form.serializeArray(), function(i, item) {formData_object.append(item.name, item.value);});
        // 上传文件信息
        formData_object.append(file_input.attr('name'), files[0]);
        file_input.ace_file_input('loading', true);

        // 提交数据
        deferred = $.ajax({
            url:         url,
            type:        'Post',
            processData: false,//important
            contentType: false,//important
            dataType:   'json',
            data:       formData_object,
        })
    } else {
        var temporary_iframe_id = 'temporary-iframe-'+(new Date()).getTime()+'-'+(parseInt(Math.random()*1000));
        var temp_iframe =
            $('<iframe id="'+temporary_iframe_id+'" name="'+temporary_iframe_id+'" \
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

        file_input.ace_file_input('loading', true);
        $form.get(0).submit();
        ie_timeout = setTimeout(function(){
            ie_timeout = null;
            temp_iframe.attr('src', 'about:blank').remove();
            deferred.reject({'status':'fail', 'message':'Timeout!'});
        } , 30000);
    }

    return deferred;
}

// 文件上传
function aceFileInput(select, url, fun) {
    var $input = $(select), ie_timeout = null, objHide = $(select.replace('ace_', '')), conf = {
        no_file:        '没有选择文件 ...',
        btn_choose:     '选择',
        btn_change:     '更换文件',
        droppable:      false,
        thumbnail:      false, //| true | large
        // 允许上传的文件类型
        allowExt:      ['jpg', 'jpeg', 'png', 'gif'],
        allowMime:     ['image/jpg', 'image/jpeg', 'image/png', 'image/gif'],
        maxSize:       200000000,
        denyExt:       ['exe', 'php'],
    };
    if (arguments[3]) conf = $.extend(conf, arguments[3]);
    if (!fun) fun = function(result) {
        if (result.status == 1) {
            gAlert("上传文件成功", "上传文件的地址为：" + result.data.sFilePath, "success");
            objHide.val(result.data.sFilePath)
        } else {
            gAlert("上传文件出现错误Error:", result.msg);
            $input.ace_file_input('apply_settings').ace_file_input('reset_input');
        }
        // 失败执行
    };
    $input.ace_file_input(conf).on('change', function() {
        var deferred = aceFileInputAjax($input, url);
        // 成功执行
        deferred.done(fun).fail(function(result) {
            gAlert("温馨提醒：", "页面没有响应...");
            // 请求完成执行
        }).always(function() {
            if(ie_timeout) clearTimeout(ie_timeout)
            ie_timeout = null;
            $input.ace_file_input('loading', false);
        });

        deferred.promise();
        // 错误处理
    }).on('file.error.ace', function(event, info) {
        // 判断错误
        gAlert('文件上传出现错误：', validateFile(info));
        event.preventDefault();
    });
}

// 时间格式化
Date.prototype.Format=function(fmt){var o={"M+":this.getMonth()+1,"d+":this.getDate(),"h+":this.getHours(),"m+":this.getMinutes(),"s+":this.getSeconds(),"q+":Math.floor((this.getMonth()+3)/3),"S":this.getMilliseconds()};if(/(y+)/.test(fmt)){fmt=fmt.replace(RegExp.$1,(this.getFullYear()+"").substr(4-RegExp.$1.length))}for(var k in o){if(new RegExp("("+k+")").test(fmt)){fmt=fmt.replace(RegExp.$1,(RegExp.$1.length==1)?(o[k]):(("00"+o[k]).substr((""+o[k]).length)))}}return fmt};
// 根据时间戳返回时间字符串
function timeFormat(time,str){if(empty(str)){str="yyyy-MM-dd"}var date=new Date(time*1000);return date.Format(str)}
// 值的转换
function stringTo(type,value){switch(type){case"int":case"int8":case"int16":case"int32":case"int64":case"uint":case"uint8":case"uint16":case"uint32":case"uint64":return parseInt(value);case"bool":return value==="true"||value===true||value===1||value=="1";case"float32":case"float64":}return value};
// 初始化表单信息
function InitForm(select, data) {
    objForm = $(select).get(0); // 获取表单对象
    if (objForm != undefined)
    {
        $(objForm).find('input[type=hidden]').val('');                                  // 隐藏按钮充值
        $(objForm).find('input[type=checkbox]').each(function(){$(this).attr('checked', false);if ($(this).get(0)) $(this).get(0).checked = false;});                                                                             // 多选菜单
        objForm.reset();                                                                // 表单重置
        if (data != undefined)
        {
            for (var i in data)
            {
                // 多语言处理 以及多选配置
                if (typeof data[i]  ==  'object')
                {
                    for (var x in data[i])
                    {
                        var key = i + '[' + x + ']';
                        // 对语言
                        if (objForm[key] != undefined)
                            objForm[key].value = data[i][x];
                        else {
                            // 多选按钮
                            if (parseInt(data[i][x]) > 0) {
                                $('input[type=checkbox][value=' + data[i][x] + ']').attr('checked', true).each(function(){this.checked=true});
                            }
                        }
                    }
                }

                // 其他除密码的以外的数据
                if (objForm[i] != undefined && objForm[i].type != "password")
                {
                    var obj = $(objForm[i]), tmp = data[i];
                    // 时间处理
                    if (obj.hasClass('time')) tmp = timeFormat(parseInt(tmp), 'yyyy/MM/dd hh:mm:ss');
                    objForm[i].value = tmp;
                }
            }
        }
    }
}

// 详情表单赋值
function viewTable(object, data, tClass, row)
{
    // 循环处理显示信息
    object.forEach(function(k) {
        var tmpKey = k.data,tmpValue = data[tmpKey],dataInfo = $(tClass + tmpKey);
        if (k.edit != undefined && k.edit.type == 'password') tmpValue = "******";
        (k.createdCell != undefined && typeof k.createdCell == "function") ? k.createdCell(dataInfo, tmpValue, data, row, undefined) : dataInfo.html(tmpValue);
    });
}