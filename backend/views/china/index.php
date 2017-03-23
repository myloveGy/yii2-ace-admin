<?php

// 定义标题和面包屑信息
$this->title = '地址信息';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('@web/public/assets/js/colResizable.min.js', ['depends' => 'backend\assets\AppAsset']);
?>
<!--前面导航信息-->
<p>
    <button class="btn btn-white btn-info btn-bold me-hide">
        <i class="ace-icon fa  fa-external-link bigger-120 orange"></i>
        隐藏
    </button>
    <button class="btn btn-white btn-pink btn-bold  me-table-reload">
        <i class="ace-icon fa fa-refresh bigger-120 pink"></i>
        刷新
    </button>
    <button class="btn btn-white btn-warning btn-bold me-table-export">
        <i class="ace-icon glyphicon glyphicon-export bigger-120 orange2"></i>
        导出Excel
    </button>
</p>
<!--表格数据-->
<table class="table table-striped table-bordered table-hover" id="show-table"></table>
<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    var arrParent = <?=\yii\helpers\Json::encode($parent)?>,
        m = meTables({
        title: "地址信息",
        bCheckbox: false,
        table: {
            "aoColumns":[
                {"title": "id", "data": "id", "sName": "id",  "defaultOrder": "desc", "edit": {"type": "text", "options": {"required":true,"number":true,}}},
                {"title": "地址名称", "data": "name", "sName": "name", "edit": {"type": "text", "options": {"rangelength":"[2, 40]"}}, "search": {"type": "text"}, "bSortable": false},
                {"title": "父类ID", "data": "pid", "sName": "pid", "value": arrParent, "edit": {"type": "text", "options": {"number":true}}, "search": {"type":"select"}},
            ]
        }
    });

    $(function(){
        m.init();
    })
</script>
<?php $this->endBlock(); ?>