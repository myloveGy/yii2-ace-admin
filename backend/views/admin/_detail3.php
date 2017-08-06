<div class="hide">
    <div id="user-profile-3" class="user-profile row">
        <div class="col-sm-offset-1 col-sm-10">
            <div class="well well-sm">
                <div class="inline middle blue bigger-110"> 你已经完成配置信息的<span id="sProgressHtml"> 0% </div>
                &nbsp; &nbsp; &nbsp;
                <div style="width:60%;"  class="inline middle no-margin progress progress-striped active">
                    <div class="progress-bar progress-bar-success" id="sProgress"></div>
                </div>
            </div>

            <div class="space"></div>
            <div class="tabbable">
                <ul class="nav nav-tabs padding-16">
                    <li class="active">
                        <a data-toggle="tab" href="#edit-basic">
                            <i class="green ace-icon fa fa-pencil-square-o bigger-125"></i>
                            基本信息
                        </a>
                    </li>
                    <li>
                        <a data-toggle="tab" href="#edit-password">
                            <i class="blue ace-icon fa fa-key bigger-125"></i>
                            密码
                        </a>
                    </li>
                </ul>

                <div class="tab-content profile-edit-tab-content">
                    <div id="edit-basic" class="tab-pane in active">
                        <h4 class="header blue bolder smaller">基本</h4>
                        <form class="form-horizontal" name="user" id="sUserForm">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4">
                                <input type="file" />
                            </div>
                            <div class="vspace-12-sm"></div>
                            <div class="col-xs-12 col-sm-8">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label no-padding-right" for="form-field-username">账号名</label>
                                    <div class="col-sm-8">
                                        <input class="col-xs-12 col-sm-10" type="text" id="form-field-username" name="username" required="true" rangelength="[2, 20]" placeholder="Username" value="<?=$this->params['user']->username?>" />
                                    </div>
                                </div>
                                <div class="space-4"></div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label no-padding-right" for="form-field-first">真实姓名</label>
                                    <div class="col-sm-8">
                                        <input class="input-small" type="text" name="firstName" id="form-field-first" placeholder="性" rangelength="[1, 2]" value="" />
                                        <input class="input-small" type="text" name="lastName" id="form-field-last" placeholder="名" rangelength="[1, 2]" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr />
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-date">生日</label>

                            <div class="col-sm-9">
                                <div class="input-medium">
                                    <div class="input-group">
                                        <input class="input-medium" id="form-field-date" type="text" value="" name="birthday"  placeholder="2016-06-01" />
                                        <span class="input-group-addon">
                                            <i class="ace-icon fa fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="space-4"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right">性别</label>
                            <div class="col-sm-9">
                                <label class="inline">
                                    <input type="radio" name="sex" value="1" checked="true" required="true" number="true" class="ace" />
                                    <span class="lbl middle"> 男 </span>
                                </label>

                                &nbsp; &nbsp; &nbsp;
                                <label class="inline">
                                    <input  type="radio" name="sex" value="0"  required="true" number="true" class="ace" />
                                    <span class="lbl middle"> 女 </span>
                                </label>
                            </div>
                        </div>
                        <div class="space-4"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-comment">座右铭</label>
                            <div class="col-sm-9">
                                <textarea id="form-field-comment" name="maxim" rows="3" cols="50" rangelength="[2, 255]"></textarea>
                            </div>
                        </div>
                        <div class="space"></div>
                        <h4 class="header blue bolder smaller">内容信息</h4>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-email">电子邮件</label>
                            <div class="col-sm-9">
                                <span class="input-icon input-icon-right">
                                    <input type="email" name="email" required="true" email="true" rangelength="[2, 40]" id="form-field-email" value="<?=$this->params['user']->email?>" />
                                    <i class="ace-icon fa fa-envelope"></i>
                                </span>
                            </div>
                        </div>
                        <div class="space-4"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-website">个人主页</label>
                            <div class="col-sm-9">
                                <span class="input-icon input-icon-right">
                                    <input type="url" id="form-field-website" name="home_url" rangelength="[2, 50]" url="true" value="" />
                                    <i class="ace-icon fa fa-globe"></i>
                                </span>
                            </div>
                        </div>
                        <div class="space-4"></div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label no-padding-right" for="form-field-facebook">Facebook</label>
                            <div class="col-sm-9">
                                <span class="input-icon">
                                    <input type="text" value="" name="facebook" rangelength="[2, 50]" id="form-field-facebook" />
                                    <i class="ace-icon fa fa-facebook blue"></i>
                                </span>
                            </div>
                        </div>
                        <div class="space-4"></div>
                        <div class="clearfix form-actions">
                            <input type="hidden" name="id" value="<?=$this->params['user']->id?>">
                            <div class="col-md-offset-3 col-md-9">
                                <button class="btn btn-info" type="submit">
                                    <i class="ace-icon fa fa-check bigger-110"></i>
                                    保存
                                </button>
                                &nbsp; &nbsp;
                                <button class="btn" type="reset">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    重置
                                </button>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div id="edit-password" class="tab-pane">
                        <div class="space-10"></div>
                        <form class="form-horizontal" name="userPassword" id="sUserPass">
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">新密码</label>
                                <div class="col-sm-9">
                                    <input type="password" name="password"  required="true" rangelength="[6, 20]" id="form-field-pass1" />
                                </div>
                            </div>
                            <div class="space-4"></div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">确认密码</label>
                                <div class="col-sm-9">
                                    <input type="password" name="repassword" required="true" equalTo="#form-field-pass1" rangelength="[6, 20]" id="form-field-pass2" />
                                </div>
                            </div>
                            <div class="space-4"></div>
                            <div class="clearfix form-actions">
                                <input type="hidden" name="id" value="<?=$this->params['user']->id?>">
                                <div class="col-md-offset-3 col-md-9">
                                    <button class="btn btn-info" type="submit">
                                        <i class="ace-icon fa fa-check bigger-110"></i>
                                        保存
                                    </button>
                                    &nbsp; &nbsp;
                                    <button class="btn" type="reset">
                                        <i class="ace-icon fa fa-undo bigger-110"></i>
                                        重置
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->beginBlock('javascript-3') ?>
<script type="text/javascript">
    var $form  = $('#sUserForm'),
        $input = $form.find('input[type!=hidden]').add('textarea');
    // 修改进度
    function setProgress()
    {
        var iHave  = 1;
        $input.each(function(){if ( ! empty($(this).val())) iHave ++;});
        var sProgress = Math.min(parseInt(iHave / $input.length  * 100), 100) + '%';
        $('#sProgressHtml').html(sProgress);
        $('#sProgress').css('width', sProgress);
    }

    $(function(){
        setProgress();

        // 填写表单
        $input.bind('blur', function(){setProgress();});

        // 表单提交
        $form.add('#sUserPass').submit(function(){
            var self = $(this);
            if ($(this).validate(validatorError).form()) {
                var data = $(this).serializeArray(),
                    first = $('#form-field-first').val(),
                    last = $('#form-field-last').val();
                data.push({"name": "actionType", "value": "update"});
                if (!empty(first) && !empty(last)) data.push({"name": "nickname", "value": first + last});
                var oLoading = layer.load();
                $.ajax({
                    url: "<?=\yii\helpers\Url::toRoute(['admin/update'])?>",
                    type: "POST",
                    dataType: "json",
                    data: data
                })
                    .always(function () {
                        layer.close(oLoading);
                    })
                    .done(function (json) {
                        self.get(0).reset();
                        layer.msg(json.errMsg, {icon: json.errCode == 0 ? 6 : 5});
                        $("div.btn-toolbar div.btn-group label:first").trigger("click")
                    })
                    .fail(function () {
                        layer.msg("服务器繁忙，请稍后再试...");
                    });
            }

            return false;
        });

        // 生日时间选择
        $('#form-field-date').datepicker({
            format: 'yyyy-mm-dd',
            weekStart: 1,
            autoclose: true,
            todayBtn: 'linked',
            language: 'zh-CN'
        });
    })
</script>
<?php $this->endBlock(); ?>
