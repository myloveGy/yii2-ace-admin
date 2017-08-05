<?php

use yii\helpers\Url;
use \backend\models\AdminLog;

?>
<div>
    <div class="user-profile row" id="user-profile-1">
        <div class="col-xs-12 col-sm-3 center">
            <div>
                <span class="profile-picture">
                    <img id="avatar" class="editable img-responsive editable-click editable-empty" alt="Alex's Avatar" src="<?=$this->params['user']->face ? dirname($this->params['user']->face).'/thumb_'.basename($this->params['user']->face) : '/public/assets/avatars/profile-pic.jpg'?>" />
                </span>
                <div class="space-4"></div>
                <div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
                    <div class="inline position-relative">
                        <a href="#" class="user-title-label dropdown-toggle" data-toggle="dropdown">
                            <i class="ace-icon fa fa-circle light-green"></i>
                            &nbsp;
                            <span class="white"><?=$this->params['user']->username?></span>
                        </a>

                        <ul class="align-left dropdown-menu dropdown-caret dropdown-lighter">
                            <li class="dropdown-header"> 切换状态 </li>

                            <li>
                                <a href="#">
                                    <i class="ace-icon fa fa-circle green"></i>
                                    &nbsp;
                                    <span class="green">启用</span>
                                </a>
                            </li>

                            <li>
                                <a href="#">
                                    <i class="ace-icon fa fa-circle red"></i>
                                    &nbsp;
                                    <span class="red">正在审核</span>
                                </a>
                            </li>

                            <li>
                                <a href="#">
                                    <i class="ace-icon fa fa-circle grey"></i>
                                    &nbsp;
                                    <span class="grey">停用</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="space-6"></div>

            <!-- #section:pages/profile.contact -->
            <div class="profile-contact-info">
                <div class="profile-contact-links align-left">
                    <a href="#" class="btn btn-link">
                        <i class="ace-icon fa fa-plus-circle bigger-120 green"></i>
                        添加朋友
                    </a>

                    <a href="#" class="btn btn-link">
                        <i class="ace-icon fa fa-envelope bigger-120 pink"></i>
                        发送邮件
                    </a>

                    <a href="#" class="btn btn-link">
                        <i class="ace-icon fa fa-globe bigger-125 blue"></i>
                        我的主页
                    </a>
                </div>

                <div class="space-6"></div>

                <div class="profile-social-links align-center">
                    <a href="#" class="tooltip-info" title="" data-original-title="Visit my Facebook">
                        <i class="middle ace-icon fa fa-facebook-square fa-2x blue"></i>
                    </a>

                    <a href="#" class="tooltip-info" title="" data-original-title="Visit my Twitter">
                        <i class="middle ace-icon fa fa-twitter-square fa-2x light-blue"></i>
                    </a>

                    <a href="#" class="tooltip-error" title="" data-original-title="Visit my Pinterest">
                        <i class="middle ace-icon fa fa-pinterest-square fa-2x red"></i>
                    </a>
                </div>
            </div>

            <!-- /section:pages/profile.contact -->
            <div class="hr hr12 dotted"></div>

            <!-- #section:custom/extra.grid -->
            <div class="clearfix">
                <div class="grid2">
                    <span class="bigger-175 blue">25</span>
                    <br>
                    Followers
                </div>

                <div class="grid2">
                    <span class="bigger-175 blue">12</span>
                    <br>
                    Following
                </div>
            </div>

            <!-- /section:custom/extra.grid -->
            <div class="hr hr16 dotted"></div>
        </div>

        <div class="col-xs-12 col-sm-9">


            <!-- #section:pages/profile.info -->
            <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                    <div class="profile-info-name"> 用户名 </div>

                    <div class="profile-info-value">
                        <span id="username" class="editable editable-click"><?=$this->params['user']->username?></span>
                    </div>
                </div>

                <div class="profile-info-row">
                    <div class="profile-info-name"> 国籍 </div>

                    <div class="profile-info-value">
                        <i class="fa fa-map-marker light-orange bigger-110"></i>
                        <span class="editable editable-click">中国</span>
                        <span id="country" class="editable editable-click"><?=isset($china[0]) ? $china[0]->name : '选择省'?></span>
                        <span id="city" class="editable editable-click"><?=isset($china[1]) ? $china[1]->name : '选择市'?></span>
                        <span id="address" class="editable editable-click" <?=$address == '选择县' ? 'style="display:none"': ''?>><?=$address?></span>
                    </div>
                </div>

                <div class="profile-info-row">
                    <div class="profile-info-name"> 年龄 </div>

                    <div class="profile-info-value">
                        <span id="age" class="editable editable-click">20</span>
                    </div>
                </div>

                <div class="profile-info-row">
                    <div class="profile-info-name"> 添加时间 </div>
                    <div class="profile-info-value">
                        <span id="login_time"  class="editable editable-click"><?=date('Y-m-d H:i:s', $this->params['user']->created_at)?></span>
                    </div>
                </div>

                <div class="profile-info-row">
                    <div class="profile-info-name"> 上一次登录时间 </div>

                    <div class="profile-info-value">
                        <span id="login" class="editable editable-click"><?=date('Y-m-d H:i:s', $this->params['user']->last_time)?></span>
                    </div>
                </div>

                <div class="profile-info-row">
                    <div class="profile-info-name"> 上一次登录IP </div>

                    <div class="profile-info-value">
                        <span id="login" class="editable editable-click"><?=$this->params['user']->last_ip?></span>
                    </div>
                </div>

                <div class="profile-info-row">
                    <div class="profile-info-name"> 座右铭 </div>

                    <div class="profile-info-value">
                        <span id="about" class="editable editable-click">这个家伙很懒, 什么也没有留下</span>
                    </div>
                </div>
            </div>

            <!-- /section:pages/profile.info -->
            <div class="space-20"></div>

            <div class="widget-box transparent">
                <div class="widget-header widget-header-small">
                    <h4 class="widget-title blue smaller">
                        <i class="ace-icon fa fa-rss orange"></i>
                        操作记录
                    </h4>

                    <div class="widget-toolbar action-buttons">
                        <a data-action="reload" href="#">
                            <i class="ace-icon fa fa-refresh blue"></i>
                        </a>&nbsp;
                        <a class="pink" href="#">
                            <i class="ace-icon fa fa-trash-o"></i>
                        </a>
                    </div>
                </div>

                <div class="widget-body">
                    <div class="widget-main padding-8">
                        <div id="profile-feed-1" class="profile-feed">
                            <?php if ($logs) : foreach ($logs as $value) : ?>
                            <div class="profile-activity clearfix">
                                <div>
                                    <img class="pull-left" alt="用户头像" src="<?=$this->params['user']->face?>" />
                                    <a class="user" href="#"> <?=AdminLog::getTypeDescription($value['type'])?> -- <?=$value['index']?> </a>
                                    <?=$value['params']?>
                                    <a href="#"><?=$value['url']?></a>
                                    <div class="time">
                                        <i class="ace-icon fa fa-clock-o bigger-110"></i>
                                        <?=date('Y-m-d H:i:s', $value['created_at'])?>
                                    </div>
                                </div>

                                <div class="tools action-buttons">
                                    <a href="#" class="blue">
                                        <i class="ace-icon fa fa-pencil bigger-125"></i>
                                    </a>

                                    <a href="#" class="red">
                                        <i class="ace-icon fa fa-times bigger-125"></i>
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hr hr2 hr-double"></div>
            <div class="space-6"></div>
        </div>
    </div>
</div>
<?php $this->beginBlock('javascript-1') ?>
<script type="text/javascript">
    var sBaseUrl = "<?=Url::toRoute(['admin/editable'])?>",                             // 行内编辑提交地址
        iUserId  = <?=$this->params['user']->id?>,                                      // 用户唯一ID
        fSuccess = function(response, newValue) {// 成功执行函数
            if (response.errCode == 0) return true;
            layer.msg(response.errMsg, {icon: 5, time:1000});
            return false;
        },

        sAddressUrl = "<?=Url::toRoute(['admin/address'])?>",                           // 三级联动提交地址
        sUploadUrl  = "<?=Url::toRoute(['admin/upload', 'sField' => 'avatar'])?>";      // 上传图片地址

    // 公共的方法用来处理编辑的信息
    function EditError() {
        $.gritter.add({
            title: '编辑出现错误!',
            text: '服务器没有响应',
            class_name: 'gritter-error gritter-center',
            time: 800
        });
    }

    // 选择县
    function selectAddress(iPid, sName)
    {
        if (empty(sName)) sName = '选择县';
        var address = $('#address').removeAttr('id').show().get(0);
        $(address).clone().attr('id', 'address').text(sName).editable({
            type: 'select2',
            value: null,
            source: sAddressUrl + '?iPid=' + iPid,
            select2: {
                'width': 150
            },
            name:           "address",
            url:            sBaseUrl,
            pk:             iUserId,
            send:           "always",
            ajaxOptions:    {type:"POST",dataType: "json"},
            params: function(params) {
                var arr = $(this).data('editable').input.sourceData, value = params.value;
                for (var i in arr){if (arr[i].id == value) {value = arr[i].text;break;}}
                // 没有选择数据
                if (empty(value)) return false;
                params.value = $('#country').html() + "," + $('#city').html() + "," + value;
                return params;
            },
            validate: function(x){
                if (x == false)
                {
                    layer.msg('你没有选择您所在的地址信息', {icon: 5, "title": "温馨提醒!"});
                    return '你没有选择您所在的地址信息';
                }
            },
            success:        fSuccess,
            error:          EditError
        }).insertAfter(address);
        $(address).remove();
    }

    // 选择市函数
    function selectCity(iPid, sName)
    {
        if (empty(sName)) sName = '选择市';
        var city = $('#city').removeAttr('id').get(0);
        $(city).clone().attr('id', 'city').text(sName).editable({
            type: 'select2',
            value : null,
            //onblur:'ignore',
            source: sAddressUrl + '?iPid=' + iPid,
            select2: {
                'width': 140
            },
            success: function(response, newValue) {
                // 没有选择数据
                if (empty(newValue)) {
                    layer.alert('你没有选择您所在的地址信息', {
                        title: "温馨提醒",
                        icon: 5
                    });
                    return false;
                }

                // 查询是否还存在县的数据
                $.ajax({
                    "url":      sAddressUrl + "?iPid=" + newValue,
                    "type":     "GET",
                    "dataType": "json"
                }).done(function(json){
                    if (json.length > 0)
                    {
                        selectAddress(newValue);
                    } else {
                        $('#address').hide();
                        // 修改数据
                        $.ajax({
                            url:        sBaseUrl,
                            type:       "POST",
                            dataType:   "json",
                            data:       {
                                "name":  "address",
                                "pk":    iUserId,
                                "value": $('#country').html() + ',' + $('#city').html()
                            }
                        }).done(function(json){
                            layer.msg(json.errMsg, {icon: json.errCode == 0 ? 6 : 5})
                        });
                    }
                    return true;
                });
            }
        }).insertAfter(city);//insert it after previous instance
        $(city).remove();//remove previous instance

    }

    $(function(){
        // 单个修改表单信息
        $.fn.editable.defaults.mode = 'inline';
        $.fn.editableform.loading = "<div class='editableform-loading'><i class='ace-icon fa fa-spinner fa-spin fa-2x light-blue'></i></div>";
        $.fn.editableform.buttons = '<button type="submit" class="btn btn-info editable-submit"><i class="ace-icon fa fa-check"></i></button>'+
            '<button type="button" class="btn editable-cancel"><i class="ace-icon fa fa-times"></i></button>';
        $.fn.editable.defaults.ajaxOptions = {type: "POST", dataType:'json'};

        // 修改用户名
        $('#username').editable({
            type:           'text',
            name:           'username',
            url:            sBaseUrl,
            pk:             iUserId,
            send:           "always",
            ajaxOptions:    {type: "POST", dataType:'json'},
            success:        fSuccess,
            error:           EditError,
        });

        // 省
        $('#country').editable({
            type: 'select2',
            value : 'NL',
            //onblur:'ignore',
            source: sAddressUrl,
            select2: {
                'width': 140
            },
            success: function(response, newValue)
            {
                console.info(response);
                selectCity(newValue);
            }
        });

        // 市
        <?php if (isset($china[0]) && isset($china[1])) : ?>
        selectCity(<?=$china[0]->id?>, '<?=$china[1]->name?>');
        <?php endif; ?>

        // 县
        <?php if (isset($china[1]) && $address !== '选择县') : ?>
        selectAddress(<?=$china[1]->id?>, '<?=$address?>');
        <?php endif; ?>


        // 上一次登录时间
        $('#login_time').editable({
            type:           'adate',
            date: {
                format:     'yyyy-mm-dd',
                viewformat: 'yyyy-mm-dd',
                weekStart:  1,
                language:   'zh-CN',
            }
        });

        $('#age').editable({
            type:        'spinner',
            name :       'age',
            pk:          iUserId,
            url:         sBaseUrl,
            send:        "always",
            ajaxOptions: {type: "POST", dataType:'json'},
            spinner : {
                min : 16,
                max : 99,
                step: 1,
                on_sides: true
            },
            success:    fSuccess,
            error:      EditError,
        });


        $('#login').editable({
            type: 'slider',
            name : 'login',
            slider : {
                min : 1,
                max: 50,
                width: 100
            },
            success: function(response, newValue) {
                if(parseInt(newValue) == 1)
                    $(this).html(newValue + " 小时之前 ");
                else $(this).html(newValue + " 小时之前");
            }
        });

        // 座右铭
        $('#about').editable({
            mode:        'inline',
            type:        'wysiwyg',
            name :       'maxim',
            pk:          iUserId,
            url:         sBaseUrl,
            send:        "always",
            ajaxOptions: {type: "POST", dataType:'json'},
            wysiwyg : {
                //css : {'max-width':'300px'}
            },

            success: fSuccess,
            error:   EditError,
        });

        try {
            // 修改头像
            try {
                document.createElement('IMG').appendChild(document.createElement('B'));
            } catch(e) {
                Image.prototype.appendChild = function(el){}
            }

            var last_gritter;
            $('#avatar').editable({
                type: 'image',
                name: 'avatar',
//                value: null,
                pk:    iUserId,
                image: {
                    //specify ace file input plugin's options here
                    btn_choose: '选择头像',
                    droppable: true,
                    maxSize: 11000000,//~100Kb
                    //and a few extra ones here
                    name: 'avatar',//put the field name here as well, will be used inside the custom plugin
                    on_error : function(error_type) {//on_error function will be called when the selected file has a problem
                        if(last_gritter) layer.close(last_gritter);
                        if(error_type === 1) {//file format error
                            layer.alert("上传文件类型错误!请选择一个JPG、GIF、PNG图片格式文件!", {
                                icon: 2,
                                title: "温馨提醒",
                            });
                        } else if(error_type === 2) {//file size rror
                            layer.alert("上传图片文件过大!图像大小不超过100KB!", {
                                icon: 2,
                                title: "温馨提醒",
                            });
                        }
                        else {//other error
                        }
                    },
                    on_success : function() {
                        layer.close(last_gritter);
                    }
                },
                url: function(params) {
                    var submit_url = sUploadUrl, // 提交页面
                        deferred   = null,
                        avatar 	   = '#avatar',	      // 选择对象
                        value 	   = $(avatar).next().find('input[type=hidden]:eq(0)').val();

                    // 数据验证
                    if(!value || value.length == 0) {
                        deferred = new $.Deferred
                        deferred.resolve();
                        return deferred.promise();
                    }

                    // 提交表单
                    var $form 	   = $(avatar).next().find('.editableform:eq(0)'),
                        file_input = $form.find('input[type=file]:eq(0)'),
                        pk 		   = iUserId,	//primary key to be sent to server
                        ie_timeout = null;


                    if( "FormData" in window ) {
                        var formData_object = new FormData();//create empty FormData object

                        //serialize our form (which excludes file inputs)
                        $.each($form.serializeArray(), function(i, item) {
                            //add them one by one to our FormData
                            formData_object.append(item.name, item.value);
                        });
                        //and then add files
                        $form.find('input[type=file]').each(function(){
                            var field_name = 'UploadForm[' + $(this).attr('name') + ']';
                            var files = $(this).data('ace_input_files');
                            if(files && files.length > 0) {
                                formData_object.append(field_name, files[0]);
                            }
                        });

                        //append primary key to our formData
                        formData_object.append('pk', pk);

                        deferred = $.ajax({
                            url: submit_url,
                            type: 'POST',
                            processData: false,//important
                            contentType: false,//important
                            dataType: 'json',//server response type
                            data: formData_object
                        })
                    }
                    else {
                        deferred = new $.Deferred
                        $($form).find('input[type=file]').each(function(){
                            $(this).attr('name', 'UploadForm[' + $(this).attr('name') + ']');
                        });
                        var temporary_iframe_id = 'temporary-iframe-'+(new Date()).getTime()+'-'+(parseInt(Math.random()*1000));
                        var temp_iframe =
                            $('<iframe id="'+temporary_iframe_id+'" name="'+temporary_iframe_id+'" \
							frameborder="0" width="0" height="0" src="about:blank"\
							style="position:absolute; z-index:-1; visibility: hidden;"></iframe>')
                                .insertAfter($form);

                        $form.append('<input type="hidden" name="temporary-iframe-id" value="'+temporary_iframe_id+'" />');

                        //append primary key (pk) to our form
                        $('<input type="hidden" name="pk" />').val(pk).appendTo($form);
                        temp_iframe.data('deferrer' , deferred);

                        // 表单添加属性
                        $form.attr({
                            action: submit_url,
                            method: 'POST',
                            enctype: 'multipart/form-data',
                            target: temporary_iframe_id //important
                        });

                        $form.get(0).submit();

                        //if we don't receive any response after 30 seconds, declare it as failed!
                        ie_timeout = setTimeout(function(){
                            ie_timeout = null;
                            temp_iframe.attr('src', 'about:blank').remove();
                            deferred.reject({'status':'fail', 'message':'Timeout!'});
                        } , 30000);
                    }


                    //deferred callbacks, triggered by both ajax and iframe solution
                    deferred
                        .done(function(result) {
                            if(result.errCode == 0)
                                $(avatar).get(0).src = result.data.sFilePath;
                            else
                                layer.msg(result.errMsg, {icon:5, time:1000})
                        })
                        .fail(function(error) {
                            layer.alert("服务器繁忙,请稍后再试...", {
                                title: "温馨提醒",
                                icon: 2
                            });
                        })
                        .always(function() {//called on both success and failure
                            if(ie_timeout) clearTimeout(ie_timeout)
                            ie_timeout = null;
                        });

                    return deferred.promise();
                },
                success: function(response, newValue) {

                }
            })
        }catch(e) {}

        // 最近活动的信息下拉
        $('#profile-feed-1').ace_scroll({
            height: '250px',
            mouseWheelLock: true,
            alwaysVisible : true
        });
        $('a[ data-original-title]').tooltip();
        $('.easy-pie-chart.percentage').each(function(){
            var barColor = $(this).data('color') || '#555';
            var trackColor = '#E2E2E2';
            var size = parseInt($(this).data('size')) || 72;
            $(this).easyPieChart({
                barColor: barColor,
                trackColor: trackColor,
                scaleColor: false,
                lineCap: 'butt',
                lineWidth: parseInt(size/10),
                animate:false,
                size: size
            }).css('color', barColor);
        });
    })
</script>
<?php $this->endBlock(); ?>
