<?php
// 定义标题和面包屑信息
$this->title = '角色信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<!--前面导航信息-->
<p>
    <button class="btn btn-white btn-success btn-bold me-table-create">
        <i class="ace-icon fa fa-plus bigger-120 blue"></i>
        添加
    </button>
    <button class="btn btn-white btn-danger btn-bold me-table-delete">
        <i class="ace-icon fa fa-trash-o bigger-120 red"></i>
        删除
    </button>
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
    var m = mt({
        title: "权限信息",
        table: {
            "aoColumns":[
                {"title": "权限名称", "data": "name", "sName": "name", "edit": {"type": "text", "required": true,"rangelength":"[2, 64]"}, "search": {"type": "text"}, "bSortable": false},
                {"title": "说明描述", "data": "description", "sName": "description", "edit": {"type": "text", "required": true,"rangelength":"[2, 64]"}, "search": {"type": "text"}, "bSortable": false},
                {"title": "创建时间", "data": "created_at", "sName": "created_at", "createdCell": mt.dateTimeString, "defaultOrder": "desc"},
                {"title": "修改时间", "data": "updated_at", "sName": "updated_at", "createdCell": mt.dateTimeString}
            ]
        }
    });

    mt.fn.extend({
        afterShow: function() {
            $(this.options.sFormId).find('input[name=name]').attr('readonly', this.action == 'update');
            return true;
        }
    });

    $(function(){
        m.init();
    })
</script>
<?php $this->endBlock(); ?>