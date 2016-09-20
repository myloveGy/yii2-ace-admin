<?php

use yii\helpers\Html;
use kartik\icons\Icon;
use yii\widgets\DetailView;

$this->title = '角色信息详情';
$this->params['breadcrumbs'] = [
    [
        'label' => '角色信息',
        'url'   => ['/role/index']
    ],

    '角色信息详情'
];
$this->registerJsFile('@web/public/assets/js/jquery.nestable.min.js');
?>
<div class="col-xs-12 col-sm-4">
    <div class="col-xs-12 col-sm-12 widget-container-col  ui-sortable">
        <!-- #section:custom/widget-box -->
        <div class="widget-box  ui-sortable-handle">
            <div class="widget-header">
                <h5 class="widget-title"> 角色信息 </h5>
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
                    echo DetailView::widget([
                        'model'      => $model,
                        'attributes' => [
                            'name',
                            'description',
                            ['label' => '添加时间',  'value' => date('Y-m-d H:i:s', $model->created_at)],
                            ['label' => '修改时间',  'value' => date('Y-m-d H:i:s', $model->updated_at)],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 widget-container-col  ui-sortable">
        <!-- #section:custom/widget-box -->
        <div class="widget-box  ui-sortable-handle">
            <div class="widget-header">
                <h5 class="widget-title">导航栏信息</h5>
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
                    <div id="nestable" class="dd">
                        <ol class="dd-list">
                            <?php
                                $key = 1;
                                foreach ($menus as  $value) :
                                    $isChild = count($value['child']) > 0 ? true : false;
                            ?>

                            <li data-id="<?=$key?>" class="dd-item">
                                <div class="dd-handle">
                                    <?=$value['name']?>
                                    <span class="sticker">
                                        <span class="label label-success arrowed-in">
                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                        </span>
                                    </span>
                                </div>
                                <?php if ($isChild) : ?>
                                <ol class="dd-list">
                                    <?php foreach ($value['child'] as $val) :
                                    $key ++;
                                    ?>
                                    <li class="dd-item item-red" data-id="<?=$key?>">
                                        <div class="dd-handle"><?=$val['name']?></div>
                                    </li>
                                    <?php endforeach; ?>
                                </ol>
                                <?php else: $key ++; endif; ?>
                            </li>
                            <?php
                                endforeach;
                            ?>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xs-12 col-sm-8">
    <div class="col-xs-12 col-sm-12 widget-container-col  ui-sortable">
        <!-- #section:custom/widget-box -->
        <div class="widget-box  ui-sortable-handle">
            <div class="widget-header">
                <h5 class="widget-title"> 权限信息 </h5>
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
                    <div class="row">
                        <div class="col-xs-12 col-sm-12">
                            <?php foreach($model->_permissions as $key): ?>
                            <div class="col-xs-12 col-sm-6">
                                <div class="alert alert-success"  style="padding:5px; margin:3px;">
                                    <i class="ace-icon fa fa-check bigger-110 green"></i>
                                    <?= $permissions[$key]?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function(){
        $('.dd').add('.myclass').nestable();

        $('.dd-handle a').on('mousedown', function(e){
            e.stopPropagation();
        });

        $('[data-rel="tooltip"]').tooltip();

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

        // 导航栏样式装换
        handleMenuActive('\\/role\\/index');
    });
</script>