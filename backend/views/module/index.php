<?php
use yii\helpers\Url;
// 定义标题和面包屑信息
$this->title = '模块生成';

// 注入需要的JS
$url = '@web/public/assets';
$depends = ['depends' => 'backend\assets\AdminAsset'];

$this->registerJsFile($url.'/js/fuelux/fuelux.spinner.min.js', $depends);
$this->registerJsFile($url.'/js/fuelux/fuelux.wizard.min.js', $depends);
$this->registerJsFile($url.'/js/bootstrap-wysiwyg.min.js', $depends);
$this->registerJsFile($url.'/js/chosen.jquery.min.js', $depends);
$this->registerJsFile($url.'/js/jquery.validate.min.js', $depends);
$this->registerJsFile($url.'/js/validate.message.js', $depends);
$this->registerJsFile($url.'/js/chosen.jquery.min.js', $depends);
$this->registerCssFile($url.'/css/chosen.css', $depends);

?>
<div class="widget-box widget-color-blue">
    <div class="widget-header widget-header-blue  widget-header-flat">
        <h4 class="widget-title lighter">模块生成自动向导</h4>
        <div class="widget-toolbar">
            <a data-action="reload" href="#">
                <i class="ace-icon fa fa-refresh"></i>
            </a>
            <a data-action="collapse" href="#">
                <i class="ace-icon fa fa-chevron-up"></i>
            </a>
        </div>
    </div>

    <div class="widget-body">
        <div class="widget-main">
            <!-- #section:plugins/fuelux.wizard -->
            <div id="fuelux-wizard" data-target="#step-container">
                <!-- #section:plugins/fuelux.wizard.steps -->
                <ul class="wizard-steps">
                    <li data-target="#step1" class="active">
                        <span class="step">1</span>
                        <span class="title">确认表信息</span>
                    </li>
                    <li data-target="#step2">
                        <span class="step">2</span>
                        <span class="title">填写表单信息</span>
                    </li>

                    <li data-target="#step3">
                        <span class="step">3</span>
                        <span class="title">生成模块信息</span>
                    </li>
                </ul>
            </div>

            <hr />

            <!-- #section:plugins/fuelux.wizard.container -->
            <div class="step-content pos-rel" id="step-container">
                <div class="step-pane active" id="step1">
                    <h3 class="lighter block green">请输入以下信息</h3>
                    <form class="form-horizontal" id="sample-form" action="<?=Url::toRoute(['module/create'])?>">
                        <div class="form-group has-success">
                            <label for="me-title" class="col-xs-12 col-sm-3 control-label no-padding-right">标题名称</label>
                            <div class="col-xs-12 col-sm-5">
                                <span class="block input-icon input-icon-right">
                                    <input type="text" id="me-title" name="title" required="true" rangelength="[2, 20]" class="width-100" />
                                    <i class="ace-icon fa fa-check-circle"></i>
                                </span>
                            </div>
                            <div class="help-block col-xs-12 col-sm-reset inline text-danger">( * 标题、权限、导航都基于该字段生成说明) </div>
                        </div>
                        <div class="form-group has-success">
                            <label for="me-table" class="col-xs-12 col-sm-3 control-label no-padding-right">数据库表名</label>
                            <div class="col-xs-12 col-sm-5">
                                <?php $tables[''] = '';?>
                                <?=\yii\helpers\Html::dropDownList('table', '', $tables, [
                                        'id' => 'select-table',
                                        'class' => 'chosen-select',
                                        'required' => true,
                                        'data-placeholder' => '请选择一个数据表',
                                ])?>
                            </div>
                            <div class="help-block col-xs-12 col-sm-reset inline text-danger">( * 控制器、模型、权限都基于该字段命名 ) </div>
                        </div>
                    </form>
                </div>

                <div class="step-pane" id="step2">
                    <div>
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">
                                <i class="ace-icon fa fa-times"></i>
                            </button>
                            <strong>
                                <i class="ace-icon fa fa-check"></i>
                                温馨提醒
                            </strong>
                            请认真填写数据信息
                            <br />
                        </div>
                        <form class="form-horizontal" action="<?=Url::toRoute(['module/update'])?>" method="POST">
                            <fieldset id="my-content">
                            </fieldset>
                        </form>
                    </div>
                </div>

                <div class="step-pane" id="step3">
                    <div class="alert alert-info">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>温馨提醒 ! </strong> 确认生成文件没有问题
                    </div>
                    <form class="form-horizontal produce" action="<?=Url::toRoute('module/produce')?>" method="POST">
                        <div class="form-group">
                            <label for="input-html" class="control-label col-xs-12 col-sm-3 no-padding-right">HTML文件</label>
                            <div class="col-xs-12 col-sm-9">
                                <div class="clearfix">
                                    <input type="text" class="col-xs-12 col-sm-6" id="input-html" name="html" required="true" rangelength="[2, 12]" />
                                    <label class="m_error"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="input-controller" class="control-label col-xs-12 col-sm-3 no-padding-right">控制器(Controller)</label>
                            <div class="col-xs-12 col-sm-9">
                                <div class="clearfix">
                                    <input type="text" class="col-xs-12 col-sm-6" id="input-controller" name="controller" required="true" rangelength="[2, 30]" />
                                    <label class="m_error"></label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-3 no-padding-right"> 导航栏目 </label>
                            <div class="col-xs-12 col-sm-9">
                                &#12288;
                                <label class="line-height-1 blue">
                                    <input type="radio" value="1" class="ace" name="menu" checked="checked" number="1" required="1">
                                    <span class="lbl"> 生成 </span>
                                </label>
                                &#12288;
                                <label class="line-height-1 blue">
                                    <input type="radio" value="0"  class="ace" name="menu" number="1" required="1">
                                    <span class="lbl"> 不生成 </span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-3 no-padding-right"> 权限操作 </label>
                            <div class="col-xs-12 col-sm-9">
                                &#12288;
                                <label class="line-height-1 blue">
                                    <input type="radio" value="1" class="ace" name="auth" checked="checked" number="1" required="1">
                                    <span class="lbl"> 生成 </span>
                                </label>
                                &#12288;
                                <label class="line-height-1 blue">
                                    <input type="radio" value="0"  class="ace" name="auth" number="1" required="1">
                                    <span class="lbl"> 不生成 </span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-12 col-sm-3 no-padding-right"> 允许文件覆盖 </label>
                            <div class="col-xs-12 col-sm-9">
                                &#12288;
                                <label class="line-height-1 blue">
                                    <input type="radio" value="1"  class="ace" name="allow" number="1" required="1">
                                    <span class="lbl"> 允许 </span>
                                </label>
                                &#12288;
                                <label class="line-height-1 blue">
                                    <input type="radio" value="0" class="ace" name="allow" checked="checked" number="1" required="1">
                                    <span class="lbl"> 不允许 </span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12 col-sm-4 col-sm-offset-3">
                                <label>
                                    <input type="checkbox" class="ace" id="agree" name="agree" required="true">
                                    <span class="lbl"> 同意生成 </span>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <hr />
            <div class="wizard-actions">
                <button class="btn btn-prev">
                    <i class="ace-icon fa fa-arrow-left"></i>上一步
                </button>
                <button class="btn btn-success btn-next" data-last="确认生成">
                    下一步<i class="ace-icon fa fa-arrow-right icon-on-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>
<div class="hr hr-18 hr-double dotted"></div>
<div class="alert alert-success isHide">
    <div class="code"></div>
</div>

<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    var file       = null,
        controller = null;
    $(function(){
        // 选择表
        $("#select-table").chosen({allow_single_deselect:true});

        $(window)
            .off('resize.chosen')
            .on('resize.chosen', function() {
                $('#select-table').each(function() {
                    var $this = $(this);
                    $this.next().css({'width': $this.parent().width()});
                })
            }).trigger('resize.chosen');


        $('#fuelux-wizard')
            .ace_wizard()
            .on('change' , function(e, info){
                if (info.direction === 'next') {
                    var f = $('#step' + info.step +' form');
                    if (f.validate({
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

                        $.ajax({
                            'async': false,
                            'url': f.attr('action'),
                            'data': $('form').serialize(),
                            'type': 'POST',
                            'dataType': 'json'
                        }).done(function(json){
                            layer.msg(json.errMsg, {icon:json.errCode == 0 ? 6 : 5});
                            if (json.errCode == 0) {
                                // 第一步提交
                                if (info.step === 1) $('#my-content').html(json.data);
                                // 第二步提交
                                if (info.step === 2) {
                                    $('.code').html(json.data.html).parent().show();
                                    // HTML
                                    $('#input-html').val(json.data.file[0]);
                                    if (json.data.file[1] == true) {
                                        file = json.data.file[0]
                                        $('#input-html').next().html(' ( * 文件已经存在,需要重新定义文件名 )');
                                    }

                                    // Controller
                                    $('#input-controller').val(json.data.controller[0]);
                                    if (json.data.controller[1] == true) {
                                        controller = json.data.controller[0]
                                        $('#input-controller').next().html(' ( * 文件已经存在,需要重新定义文件名 )');
                                    }
                                }
                                return true;
                            } else {
                                event.preventDefault()
                            }
                        });
                    } else {
                        return false;
                    }
                }
            })
            .on('finished', function(e) {
                // 初始验证
                if ($('.produce').validate(validatorError).form())
                {
                    // 自己验证
                    if ($('input[name=allow]:checked').val() == 1 || ($('#input-html').val() != file && $('#input-controller').val() != controller))
                    {
                        $.ajax({
                            url: "<?=Url::toRoute('module/produce')?>",
                            data: $('form').serialize(),
                            dataType: "json",
                            type: "POST"
                        }).done(function(json){
                            layer.msg(json.errMsg, {icon:json.errCode == 0 ? 6 : 5});
                            if (json.errCode == 0)
                            {
                                if ($('input[name=menu]:checked').val() == 1)
                                    window.location.href = json.data;
                                else
                                    window.location.reload();
                                $('form').each(function(){this.reset()});
                            }
                        });
                    } else {
                        layer.msg('文件名存在, 不能执行覆盖操作...');
                    }
                }
            }).on('stepclick', function(e){
            //e.preventDefault();//this will prevent clicking and selecting steps
        });
        // 表单编辑的显示与隐藏
        $(document).on('change', '.is-hide', function(){
            if ($(this).val() == 0)
                $(this).next('select').hide().next('input').hide();
            else
                $(this).next('select').show().next('input').show();
        });
    });
</script>
<?php $this->endBlock(); ?>