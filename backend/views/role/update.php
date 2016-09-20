<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
$this->title = '角色信息分配权限';
$this->params['breadcrumbs'] = [
    [
        'label' => '角色信息',
        'url'   => ['/role/index']
    ],

    $this->title
];
// 注册fuelux.trer.min.js
$this->registerJsFile('@web/public/assets/js/fuelux/fuelux.tree.min.js');
?>
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
                    <div id="tree1" class="tree tree-selectable"></div>
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
                                <input class="ace ace-checkbox-2" type="checkbox" name="Auth[_permissions][]"  value="<?= $key ?>" <?php echo in_array($key, $model->_permissions) ? 'checked="checked"' : ''; ?>/>
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
<script type="text/javascript">
    $(function(){
        var DataSourceTree = function(options) {
            this._data 	= options.data; // 数据信息
            this._delay = options.delay;
        };

        DataSourceTree.prototype.data = function(options, callback) {
            var self = this;
            var $data = null;

            // 首先显示数据
            if ( ! ("name" in options) && ! ("type" in options)) {
                $data = this._data;
                callback({data:$data});
                return;

                // 点击选择类型
            } else if ("type" in options && options.type == "folder") {
                $data = options.child != undefined ? options.child : {};
            }

            if ($data != null) setTimeout(function(){callback({ data: $data });} , parseInt(Math.random() * 500) + 200);
        };

        var treeDataSource = new DataSourceTree({data: <?=json_encode($trees)?>});

        // 全部选择
        $('.allChecked').click(function(){
            var isChecked = this.checked;
            $('input[type=checkbox]').each(function(){if ($(this).attr('checked', isChecked).get(0)) $(this).get(0).checked = isChecked;});
        });

        // 导航树
        $('#tree1').ace_tree({
            dataSource :treeDataSource ,
            multiSelect:true,
            loadingHTML:'<div class="tree-loading"><i class="ace-icon fa fa-refresh fa-spin blue"></i></div>',
            'open-icon' : 'ace-icon tree-minus',
            'close-icon' : 'ace-icon tree-plus',
            'selectable' : true,
            'selected-icon' : 'ace-icon fa fa-check',
            'unselected-icon' : 'ace-icon fa fa-check'
        });

        // 导航数的显示
        $('#tree1').on('selected', function(e, data) {
            console.log('sub-folder select: ', data);
            if (data['info'] && data['info'])
            {
                for (var i in data['info'])
                {
                    if ( ! empty(data['info'][i]['data']))
                    {
                        var s  = data['info'][i]['data'].replace(/\//g, '\\/'),
                            $o = $('input[value=' + s + ']').attr('checked', true);
                        if ($o.get(0)) $o.get(0).checked = true;
                    }
                }
            }
        });

        // 拖动控件
        $('.widget-container-col').sortable({
            connectWith: '.widget-container-col',
            items:'> .widget-box',
            handle: ace.vars['touch'] ? '.widget-header' : false,
            cancel: '.fullscreen',
            opacity:0.8,
            revert:true,
            forceHelperSize:true,
            placeholder: 'widget-placeholder',
            forcePlaceholderSize:true,
            tolerance:'pointer',
            start: function(event, ui) {
                ui.item.parent().css({'min-height':ui.item.height()})
            },
            update: function(event, ui) {
                ui.item.parent({'min-height':''})
            }
        });
    });

    // 导航栏样式装换
    handleMenuActive('\\/role\\/index');
</script>
