<?php

$this->title = '管理员个人信息';

$url = '@web/public/assets';
$depends = ['depends' => 'backend\assets\AdminAsset'];

$this->registerCssFile($url.'/css/bootstrap-editable.css', $depends);
$this->registerCssFile($url.'/css/jquery-ui.custom.min.css', $depends);
$this->registerCssFile($url.'/css/select2.css', $depends);
$this->registerCssFile($url.'/css/jquery.gritter.css', $depends);
$this->registerJsFile($url.'/js/date-time/moment.min.js', $depends);
$this->registerJsFile($url.'/js/date-time/bootstrap-datepicker.min.js', $depends);
$this->registerJsFile($url.'/js/date-time/locales/bootstrap-datepicker.zh-CN.js', $depends);
$this->registerJsFile($url.'/js/date-time/bootstrap-timepicker.min.js', $depends);
$this->registerJsFile($url.'/js/date-time/bootstrap-datetimepicker.min.js', $depends);
$this->registerJsFile($url.'/js/jquery-ui.custom.min.js', $depends);
$this->registerJsFile($url.'/js/jquery.ui.touch-punch.min.js', $depends);
$this->registerJsFile($url.'/js/jquery.gritter.min.js', $depends);
$this->registerJsFile($url.'/js/bootbox.min.js', $depends);
$this->registerJsFile($url.'/js/jquery.easypiechart.min.js', $depends);
$this->registerJsFile($url.'/js/jquery.hotkeys.min.js', $depends);
$this->registerJsFile($url.'/js/bootstrap-wysiwyg.min.js', $depends);
$this->registerJsFile($url.'/js/select2.min.js', $depends);
$this->registerJsFile($url.'/js/fuelux/fuelux.spinner.min.js', $depends);
$this->registerJsFile($url.'/js/x-editable/bootstrap-editable.min.js', $depends);
$this->registerJsFile($url.'/js/x-editable/ace-editable.min.js', $depends);
$this->registerJsFile($url.'/js/jquery.maskedinput.min.js', $depends);
$this->registerJsFile($url.'/js/jquery.validate.min.js', $depends);
$this->registerJsFile($url.'/js/validate.message.js', $depends);

?>
<div class="clearfix">
    <div class="pull-left alert alert-success no-margin">
        <button data-dismiss="alert" class="close" type="button">
            <i class="ace-icon fa fa-times"></i>
        </button>
        <i class="ace-icon fa fa-umbrella bigger-120 blue"></i>
        点击下面的图片或配置文件领域来编辑 ...
    </div>

    <div class="pull-right">
        <span class="green middle bolder">选择配置文件: &nbsp;</span>
        <div class="btn-toolbar inline middle no-margin">
            <div class="btn-group no-margin" data-toggle="buttons">
                <label class="btn btn-sm btn-yellow active">
                    <span class="bigger-110">1</span>
                    <input type="radio" value="1">
                </label>
                <label class="btn btn-sm btn-yellow">
                    <span class="bigger-110">2</span>
                    <input type="radio" value="2">
                </label>
                <label class="btn btn-sm btn-yellow">
                    <span class="bigger-110">3</span>
                    <input type="radio" value="3">
                </label>
            </div>
        </div>
    </div>
</div>

<div class="hr dotted"></div>
<!-- 用户信息的显示 -->
<?=$this->render('_detail1', ['address' => $address, 'china' => $china, 'logs' => $logs])?>
<?=$this->render('_detail2')?>
<?=$this->render('_detail3')?>
<?php $this->beginBlock('javascript') ?>
<?=$this->blocks['javascript-1']?>
<?=$this->blocks['javascript-3']?>
<script type="text/javascript">
    $(function(){
        // 详情中图片上传
        $('#avatar2').on('click', function(){
            var modal =
                '<div class="modal fade">\
                  <div class="modal-dialog">\
                   <div class="modal-content">\
                    <div class="modal-header">\
                        <button type="button" class="close" data-dismiss="modal">&times;</button>\
                        <h4 class="blue">跟换头像</h4>\
                    </div>\
                    \
                    <form class="no-margin m-image" action="' + sUploadUrl + '" method="post">\
                     <div class="modal-body">\
                        <div class="space-4"></div>\
                        <div style="width:75%;margin-left:12%;"><input type="file" name="UploadForm[avatar]" /></div>\
                     </div>\
                    \
                     <div class="modal-footer center">\
                        <button type="submit" class="btn btn-sm btn-success"><i class="ace-icon fa fa-check"></i> 确定 </button>\
                        <button type="button" class="btn btn-sm" data-dismiss="modal"><i class="ace-icon fa fa-times"></i> 取消 </button>\
                     </div>\
                    </form>\
                  </div>\
                 </div>\
                </div>';
            var modal = $(modal);
            // 取消
            modal.modal("show").on("hidden", function(){ modal.remove();});

            var working = false,
                form 	= modal.find('form:eq(0)');
            file 	= form.find('input[type=file]').eq(0);

            // 图片上传
            file.ace_file_input({
                style:'well',
                btn_choose:'点击选择新的头像',
                btn_change:null,
                no_icon:'ace-icon fa fa-picture-o',
                thumbnail:'small',
                before_remove: function() {
                    return !working;
                },

                // 允许上传的头像
                allowExt: ['jpg', 'jpeg', 'png', 'gif'],
                allowMime: ['image/jpg', 'image/jpeg', 'image/png', 'image/gif']
            });

            // 表单提交
            var ie_timeout = null;
            form.on('submit', function(){
                if( ! file.data('ace_input_files')) return false;
                // return false;
                var $form = $(form);
                var file_input = file;
                var upload_in_progress = false;
                var deferred ;
                if( "FormData" in window ) {
                    formData_object = new FormData();
                    $.each($form.serializeArray(), function(i, item) {
                        formData_object.append(item.name, item.value);
                    });

                    $form.find('input[type=file]').each(function(){
                        var field_name = $(this).attr('name');
                        var files = $(this).data('ace_input_files');
                        if(files && files.length > 0) {
                            for(var f = 0; f < files.length; f++) {
                                formData_object.append(field_name, files[f]);
                            }
                        }
                    });

                    upload_in_progress = true;
                    file_input.ace_file_input('loading', true);

                    deferred = $.ajax({
                        url: $form.attr('action'),
                        type: $form.attr('method'),
                        processData: false,//important
                        contentType: false,//important
                        dataType: 'json',
                        data: formData_object
                    })

                }
                else
                {
                    deferred = new $.Deferred;
                    var temporary_iframe_id = 'temporary-iframe-'+(new Date()).getTime()+'-'+(parseInt(Math.random()*1000));
                    var temp_iframe =
                        $('<iframe id="'+temporary_iframe_id+'" name="'+temporary_iframe_id+'" \
							frameborder="0" width="0" height="0" src="about:blank"\
							style="position:absolute; z-index:-1; visibility: hidden;"></iframe>')
                            .insertAfter($form)

                    $form.append('<input type="hidden" name="temporary-iframe-id" value="'+temporary_iframe_id+'" />');
                    temp_iframe.data('deferrer' , deferred);
                    $form.attr({
                        method: 'POST',
                        enctype: 'multipart/form-data',
                        target: temporary_iframe_id //important
                    });

                    upload_in_progress = true;
                    file_input.ace_file_input('loading', true);
                    $form.get(0).submit();
                    ie_timeout = setTimeout(function(){
                        ie_timeout = null;
                        temp_iframe.attr('src', 'about:blank').remove();
                        deferred.reject({'status':'fail', 'message':'Timeout!'});
                    } , 30000);
                }

                deferred
                    .done(function(result) {// 成功
                        if (result.errCode == 0)
                        {
                            form.find('button').removeAttr('disabled');
                            form.find('input[type=file]').ace_file_input('enable');
                            form.find('.modal-body > :last-child').remove();
                            modal.modal("hide");
                            $('#avatar2').get(0).src = result.data.sFilePath;
                            working = false;
                        } else {
                            layer.msg(result.errMsg, {icon:5})
                        }

                    })
                    .fail(function(result) {//failure
                        layer.msg("页面没有响应");
                    })
                    .always(function() {//called on both success and failure
                        if(ie_timeout) clearTimeout(ie_timeout)
                        ie_timeout = null;
                        upload_in_progress = false;
                        file_input.ace_file_input('loading');
                    });

                deferred.promise();
                return false;
            });

        });

        $('#user-profile-2 .memberdiv').on('mouseenter touchstart', function(){
            var $this = $(this);
            var $parent = $this.closest('.tab-pane');
            var off1 = $parent.offset();
            var w1 = $parent.width();
            var off2 = $this.offset();
            var w2 = $this.width();
            var place = 'left';
            if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) place = 'right';
            $this.find('.popover').removeClass('right left').addClass(place);
        }).on('click', function(e) {
            e.preventDefault();
        });

        // 图片上传
        $('#user-profile-3')
            .find('input[type=file]').ace_file_input({
                style:'well',
                btn_choose:'Change avatar',
                btn_change:null,
                no_icon:'ace-icon fa fa-picture-o',
                thumbnail:'large',
                droppable:true,
                allowExt: ['jpg', 'jpeg', 'png', 'gif'],
                allowMime: ['image/jpg', 'image/jpeg', 'image/png', 'image/gif']
            })
            .end().find('button[type=reset]').on(ace.click_event, function(){
                $('#user-profile-3 input[type=file]').ace_file_input('reset_input');
            })
            .end().find('.date-picker').datepicker().next().on(ace.click_event, function(){
            $(this).prev().focus();
        });

        $('.input-mask-phone').mask('(999) 999-9999');

        ////////////////////
        //change profile
        $('[data-toggle="buttons"] .btn').on('click', function(e){
            var target = $(this).find('input[type=radio]');
            var which = parseInt(target.val());
            $('.user-profile').parent().addClass('hide');
            $('#user-profile-'+which).parent().removeClass('hide');
        });
    });
</script>
<?php $this->endBlock(); ?>
