<?php
// 定义标题和面包屑信息
$this->title = '导航栏目信息';
$this->params['breadcrumbs'][] = $this->title;
?>
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
<div class="share-index">
    <!--表格数据-->
    <table class="table table-striped table-bordered table-hover" id="showTable">
    </table>
</div>

<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    var aAdmins  = <?=json_encode($this->params['admins'])?>,
        aParents = <?= $parents ?>;
    // 显示上级分类
    function parentStatus(td, data, rowArr, row, col)
    {
        $(td).html(aParents[data] ? aParents[data] : '顶级分类');
    }

    var myTable = new MeTable({sTitle: "导航栏目信息", bColResize:false},{
        "aoColumns":[
            oCheckBox,
            {"data": "id", "sName":"id", "title": "Id",  "edit":{"type":"hidden"}, "search":{"type":"text"}},
            {"data": "pid", "sName":"pid", "title": "上级分类", "value": aParents,  "edit":{"type":"select", "options":{"number":1}}, "createdCell": parentStatus},
            {"data": "menu_name", "sName":"menu_name", "title":"栏目名称", "edit":{"options":{"required":1, "rangelength":"[2, 50]"}}, "search":{"type":"text"}, "bSortable": false},
            {"data": "icons", "sName":"icons", "title":"图标", "edit":{"options":{"rangelength":"[2, 50]"}}, "bSortable": false},
            {"data": "url", "sName":"url", "title":"访问地址", "edit":{"options":{"rangelength":"[2, 50]"}},"search":{"type":"text"}, "bSortable": false},
            {"data": "status", "sName":"status","title": "状态", "value" : <?=json_encode(Yii::$app->params['status'])?>, "edit":{"type":"radio", "default":1, "options":{"required":1, "number":1}},"search":{"type":"select"}, "createdCell":statusToString},
            {"data": "sort", "sName":"sort","title":"排序", "value" : 100, "edit":{"type":"text", "options":{"required":1, "number":1}}},
            // 公共属性字段信息
            {"data": "created_at", "sName":"created_at","title":"创建时间", "createdCell":dateTimeString},
            {"data": "created_id", "sName":"created_id", "title":"创建用户", "createdCell":adminToString, "bSortable": false},
            {"data": "updated_at", "sName":"updated_at", "title":"修改时间", "createdCell":dateTimeString},
            {"data": "updated_id", "sName":"updated_id", "title":"修改用户", "createdCell":adminToString, "bSortable": false},
            oOperate
        ],
    });

    // 保存之后的处理
    myTable.afterSave = function(data){
        window.location.reload();
        return false;
    };

    $(function(){
        // 表单初始化
        myTable.init();
    })
</script>
<?php $this->endBlock(); ?>