<?php
use yii\helpers\Url;
// 定义标题和面包屑信息
$this->title = '角色信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<!--前面导航信息-->
<p>
    <button class="btn btn-white btn-success btn-bold me-table-insert">
        <i class="ace-icon fa fa-plus bigger-120 blue"></i>
        添加
    </button>
    <button class="btn btn-white btn-warning btn-bold me-table-delete">
        <i class="ace-icon fa fa-trash-o bigger-120 orange"></i>
        删除
    </button>
    <button class="btn btn-white btn-info btn-bold me-hide">
        <i class="ace-icon fa  fa-external-link bigger-120 orange"></i>
        隐藏
    </button>
    <button class="btn btn-white btn-primary btn-bold orange2 me-table-reload">
        <i class="ace-icon fa fa-refresh bigger-120 orange"></i>
        刷新
    </button>
</p>
<!--表格数据-->
<table class="table table-striped table-bordered table-hover" id="showTable"></table>
<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    // 设置表单信息
    function setOperate(td, data, rowArr, row, col)
    {
        var str = '<a class="btn btn-success btn-xs" href="<?=Url::toRoute(['role/view'])?>?name=' + rowArr['name'] + '"><i class="glyphicon glyphicon-zoom-in"></i></a> ';
        str += '<a class="btn btn-info btn-xs" href="javascript:;" onclick="myTable.update('+row+');"><i class="glyphicon glyphicon-edit "></i></a> ';
        str += '<a class="btn btn-warning btn-xs" href="<?=Url::toRoute(['role/edit'])?>?name=' + rowArr['name'] + '" ><i class="glyphicon glyphicon-edit "></i>编辑权限</a> ';
        str += '<a class="btn btn-danger btn-xs" href="javascript:;" onclick="myTable.delete('+row+');"><i class="glyphicon glyphicon-trash "></i></a>';
        $(td).html(str);
    }

    var myTable = new MeTable({
        sTitle:   "角色信息"
    },{
        "aoColumns":[
			oCheckBox,
			{"title": "角色名称", "data": "name", "sName": "name", "edit": {"type": "text", "options": {"required": true, "rangelength": "[2, 64]"}}, "search": {"type": "text"}, "bSortable": false},
			{"title": "说明描述", "data": "description", "sName": "description", "edit": {"type": "text", "options": {"required": true, "rangelength": "[2, 255]"}}, "search": {"type": "text"}, "bSortable": false},
			{"title": "创建时间", "data": "created_at", "sName": "created_at", "bSortable": false, "createdCell" : dateTimeString},
			{"title": "修改时间", "data": "updated_at", "sName": "updated_at", "bSortable": false, "createdCell" : dateTimeString},
            {"data": null, "title":"操作", "bSortable":false, "createdCell":setOperate, "width":"200px"}
        ],

        // 设置隐藏和排序信息
         "order":[[3, "desc"]]
        // "columnDefs":[{"targets":[2,3], "visible":false}],
    });

    // 显示之前的处理
    myTable.afterShow = function(){
        $(this.options.sFormId).find('input[name=name]').attr('readonly', this.actionType == 'update');
        return true;
    };

    $(function(){
        myTable.init();
    })
</script>
<?php $this->endBlock(); ?>
