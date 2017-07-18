/**
 * Created by liujinxing on 2017/3/23.
 */

function empty(value) {
    return value === undefined || value === null || value === "";
}

function validateFile(info) {
    var error = [];
    if (info && typeof info == "object") {
        // 判断错误类型
        if (info.error_count['ext'] || info.error_count['mime']) error.push("上传文件类型错误");
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
    if (!files || files.length == 0) { deferred.resolve();return deferred.promise();}

    // 数据提交的处理
    if ("FormData" in window ) {
        formData_object = new FormData();
        // 表单数据
        $.each($form.serializeArray(), function(i, item) {formData_object.append(item.name, item.value);});
        // 上传文件信息
        formData_object.append(file_input.attr('name'), files[0]);
        file_input.ace_file_input('loading', true);

        // 提交数据
        deferred = $.ajax({
            url: url,
            type: 'Post',
            processData: false,//important
            contentType: false,//important
            dataType: 'json',
            data: formData_object
        });
    } else {
        var temporary_iframe_id = 'temporary-iframe-'+(new Date()).getTime()+'-'+(parseInt(Math.random()*1000));
        var temp_iframe =
            $('<iframe id="'+temporary_iframe_id+'" name="'+temporary_iframe_id+'" \
								frameborder="0" width="0" height="0" src="about:blank"\
								style="position:absolute; z-index:-1; visibility: hidden;"></iframe>')
                .insertAfter($form);

        $form.append('<input type="hidden" name="temporary-iframe-id" value="'+temporary_iframe_id+'" />');
        temp_iframe.data('deferrer', deferred);
        $form.attr({
            method:  'POST',
            enctype: 'multipart/form-data',
            target:  temporary_iframe_id
        });

        file_input.ace_file_input('loading', true);
        $form.get(0).submit();
        ie_timeout = setTimeout(function(){
            ie_timeout = null;
            temp_iframe.attr('src', 'about:blank').remove();
            deferred.reject({'status':'fail', 'message':'Timeout!'});
        }, 30000);
    }

    return deferred;
}

function aceFileUpload(select, sFileUploadUrl) {
    var $input = $(select),
        $file = $('input[type=hidden][name=' + $input.attr('input-name') + ']'),
        allowExt = $input.attr('allowExt') ? $input.attr('allowExt').split(',') : null,      // 允许类型
        allowMime = $input.attr('allowMime') ? $input.attr('allowMime').split(',') : null,   // Mime 类型
        maxSize = parseInt($input.attr('maxSize')),         // 允许大小
        denyExt = $input.attr('denyExt') ? $input.attr('denyExt').split(',') : null,        // 不允许类型
        ie_timeout = null,
        aParams = {
            // 允许上传的文件类型
            allowExt: allowExt ? allowExt : ['jpg', 'jpeg', 'png', 'gif'],
            maxSize: maxSize ? maxSize : 200000000,
            denyExt: denyExt ? denyExt : ['exe', 'php']
        },
        oOther,
        field = $input.attr("input-name");

    if (allowMime) aParams["allowMime"] = allowMime;
    if ($input.attr('input-type') == 'ace_file') {
        oOther = {
            no_file: '没有选择文件 ...',
            btn_choose: '选择',
            btn_change: '更换文件',
            droppable: false,
            thumbnail: false //| true | large
        };
    } else {
        oOther = {
            style: 'well',
            btn_choose: '单击此处删除文件或单击“选择”',
            btn_change: null,
            no_icon: 'ace-icon fa fa-cloud-upload',
            droppable: true,
            thumbnail: 'small'
        };
    }

    aParams = $.extend(aParams, oOther);

    // 处理请求地址
    sFileUploadUrl += sFileUploadUrl.indexOf("?") >= 0 ? "&" : "?";
    sFileUploadUrl += "sField=" + field;

    // 删除操作
    aParams["before_remove"] = function(){
        var v = $file.val();
        if (v) {
            var arr = {};
            arr[field] = v;
            $.ajax({
                type: "POST",
                url: sFileUploadUrl,
                data: arr
            });

        }

        $file.val('');
        return true;
    };

    $input.ace_file_input(aParams).on('change', function() {
        var deferred = aceFileInputAjax($input, sFileUploadUrl);
        // 成功执行
        deferred.done(function(json) {
            if (json.errCode == 0) {
                layer.msg("上传文件的地址为：" + json.data.sFilePath, {icon: 6});
                $file.val(json.data.sFilePath);
            } else {
                layer.msg("上传文件出现错误Error:" + json.errMsg, {icon: 5});
                $input.ace_file_input('apply_settings').ace_file_input('reset_input');
            }
        }).fail(function() {
            ajaxFail();
        }).always(function() {
            if(ie_timeout) clearTimeout(ie_timeout);
            ie_timeout = null;
            $input.ace_file_input('loading', false);
        });

        deferred.promise();
        // 错误处理
    }).on('file.error.ace', function(event, info) {
        // 判断错误
        layer.msg('文件上传出现错误：' + validateFile(info), {icon: 5});
        event.preventDefault();
    });
}