<?php

use yii\widgets\DetailView;
use common\widgets\Alert;
use \backend\widgets\Nestable;

$this->title = '角色信息详情';

$this->registerJsFile('@web/public/assets/js/jquery.nestable.min.js', [
    'depends' => 'backend\assets\AdminAsset'
]);

/* @var $model \backend\models\Auth */
?>
<?= Alert::widget() ?>
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
                            'model' => $model,
                            'attributes' => [
                                'name',
                                'description',
                                ['label' => '添加时间', 'value' => date('Y-m-d H:i:s', $model->created_at)],
                                ['label' => '修改时间', 'value' => date('Y-m-d H:i:s', $model->updated_at)],
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
                            <?=Nestable::widget([
                                'items' => $menus,
                                'labelName' => 'menu_name',
                                'itemsName' => 'child'
                            ])?>
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
                                <?php foreach ($permissions as $value): ?>
                                    <div class="col-xs-12 col-sm-6">
                                        <div class="alert alert-success" style="padding:5px; margin:3px;">
                                            <i class="ace-icon fa fa-check bigger-110 green"></i>
                                            <?= $value->name . $value->description ?>
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

<?php $this->beginBlock('javascript') ?>
    <script type="text/javascript">
        $(function () {
            $('.dd').add('.myclass').nestable();

            $('.dd-handle a').on('mousedown', function (e) {
                e.stopPropagation();
            });
        });
    </script>
<?php $this->endBlock(); ?>