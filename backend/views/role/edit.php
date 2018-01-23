<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\Alert;
use yii\helpers\Json;

$this->title = '角色信息分配权限';

$depends = ['depends' => 'backend\assets\AdminAsset'];
$this->registerJsFile('@web/public/assets/js/jstree/jstree.min.js', $depends);
$this->registerCssFile('@web/public/assets/js/jstree/default/style.css', $depends);

?>
<?=Alert::widget()?>
<?php $form = ActiveForm::begin(['enableClientValidation' => true]);?>
<div class="col-xs-12 col-sm-3">
    <div class="col-xs-12 col-sm-12 widget-container-col  ui-sortable">
        <!-- #section:custom/widget-box -->
        <div class="widget-box  ui-sortable-handle">
            <div class="widget-header">
                <h5 class="widget-title"><?= Yii::t('app', 'Role'); ?></h5>
                <!-- #section:custom/widget-box.toolbar -->
                <div class="widget-toolbar">
                    <a class="orange2" data-action="fullscreen" href="#">
                        <i class="ace-icon fa fa-expand"></i>
                    </a>
                    <a data-action="reload" href="#">
                        <i class="ace-icon fa fa-refresh"></i>
                    </a>
                    <a data-action="collapse" href="#">
                        <i class="ace-icon fa fa-chevron-up"></i>
                    </a>
                </div>

                <!-- /section:custom/widget-box.toolbar -->
            </div>

            <div class="widget-body">
                <div class="widget-main">
                    <input type="hidden" name="Auth[type]" value="<?=$model->type?>" />
                    <?php
                    echo $form->field($model, 'name')->textInput($model->isNewRecord ? [] : ['disabled' => 'disabled']) .
                        $form->field($model, 'description')->textarea(['style' => 'height: 100px']) .
                        Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), [
                            'class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'
                        ]);
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 widget-container-col  ui-sortable">
        <!-- #section:custom/widget-box -->
        <div class="widget-box ui-sortable-handle">
            <div class="widget-header">
                <h5 class="widget-title">导航栏</h5>
                <!-- #section:custom/widget-box.toolbar -->
                <div class="widget-toolbar">
                    <a class="orange2" data-action="fullscreen" href="#">
                        <i class="ace-icon fa fa-expand"></i>
                    </a>
                    <a data-action="reload" href="#">
                        <i class="ace-icon fa fa-refresh"></i>
                    </a>
                    <a data-action="collapse" href="#">
                        <i class="ace-icon fa fa-chevron-up"></i>
                    </a>
                </div>

                <!-- /section:custom/widget-box.toolbar -->
            </div>

            <div class="widget-body">
                <div class="widget-main">
                    <div id="tree-one" class="tree tree-selectable"></div>
                </div>
            </div>
        </div>
    </div>
 </div>

<div class="col-xs-12 col-sm-9 widget-container-col  ui-sortable">
    <!-- #section:custom/widget-box -->
    <div class="widget-box ui-sortable-handle">
        <div class="widget-header">
            <h5 class="widget-title"><?= Yii::t('app', 'Permissions'); ?></h5>
            <!-- #section:custom/widget-box.toolbar -->
            <div class="widget-toolbar">
                <a class="orange2" data-action="fullscreen" href="#">
                    <i class="ace-icon fa fa-expand"></i>
                </a>
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
                <div class="row">
                    <div class="col-xs-12 col-sm-12">
                    <div class="checkbox col-sm-10" style="padding:5px;">
                        <label>
                            <input class="ace ace-checkbox-2 allChecked" type="checkbox"/>
                            <span class="lbl">  全部选择 </span>
                        </label>
                    </div>
                    <?php foreach ($permissions as $key => $value) : ?>
                        <div class="checkbox col-sm-4" style="padding:5px;">
                            <label>
                                <input class="ace ace-checkbox-2"
                                       type="checkbox"
                                       name="Auth[_permissions][]"
                                       value="<?= $key ?>"
                                    <?=in_array($key, $model->_permissions) ? 'checked="checked"' : ''?>
                                />
                                <span class="lbl"> <?= $value ?></span>
                            </label>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    $(function(){
        $("#tree-one").jstree({
            "plugins" : ["checkbox" ],
            core: {
                "animation" : 0,
                "check_callback" : true,
                 data: <?=Json::encode($trees)?>
            }
        }).on('changed.jstree', function(e, data){
            if (data.action === "select_node" || data.action === "deselect_node") {
                var isChecked = data.action === "select_node",
                    // 选中的是目录
                    length = data.node.children.length,
                    attributes = [];
                if (length > 0) {
                    for (var i = 0; i < length; i ++) {
                        attributes.push(data.instance.get_node(data.node.children[i]).data.split("/")[0]);
                    }
                } else {
                    attributes.push(data.node.data.split("/")[0]);
                }

                attributes.forEach(function(attribute) {
                    $("input[value^='" + attribute + "/']").prop('checked', isChecked);
                });
            }
        });

        // 全部选择
        $('.allChecked').click(function(){
            $('input[type=checkbox]').prop('checked', this.checked);
        });
    });
</script>
<?php $this->endBlock(); ?>
