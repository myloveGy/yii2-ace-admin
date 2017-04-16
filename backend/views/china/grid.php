<?php
$this->title = '我国省份地址信息';
$this->registerCssFile('@web/public/assets/css/ui.jqgrid.css', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/jqGrid/jquery.jqGrid.min.js', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/jqGrid/i18n/grid.locale-cn.js', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/common/meGrid.js', ['depends' => 'backend\assets\AppAsset']);
$this->registerJsFile('@web/public/assets/js/date-time/bootstrap-datepicker.min.js', ['depends' => 'backend\assets\AppAsset']);
?>
<!--显示按钮-->
<p id="grid-buttons"></p>
<div class="well well-sm" style="margin-bottom: 0">
    <form class="form-inline" role="form" id="grid-search-form"></form>
</div>
<!--显示表格-->
<table id="grid-table"></table>
<!--显示分页-->
<div id="grid-pager"></div>
<?php $this->beginBlock('javascript');?>
<script>
    var arrParent = <?=\yii\helpers\Json::encode($parent)?>;
    var g = meGrid({
        title: "中国地址信息",
        buttons: {
            export: {show: false}
        },
//        language: "en-us",
        grid: {
            // 子表处理
//            subGrid : false,
            // 选项
//            subGridOptions : {
//                plusicon : "ace-icon fa fa-plus center bigger-110 blue",
//                minusicon  : "ace-icon fa fa-minus center bigger-110 blue",
//                openicon : "ace-icon fa fa-chevron-right center orange"
//            },
//            // 子表加载数据处理
//            subGridRowExpanded: function (subgridDivId, rowId) {
//                console.info(rowId);
//                var subgridTableId = subgridDivId + "_t";
//                $("#" + subgridDivId).html("<table id='" + subgridTableId + "'></table>");
//                $("#" + subgridTableId).jqGrid({
//                    datatype: "json",
//                    url: "<?//=\yii\helpers\Url::toRoute('search')?>//",
//                    mtype: "post",
//                    colNames: ["id", "名称", "父类ID"],
//                    postData: {
//                        "params[pid]":rowId
//                    },
//                    colModel: [
//                        { name: 'id', index: "id",  width: 200},
//                        { name: 'name', index: "name", width: 200 },
//                        { name: 'pid', index: "pid",  width: 200 }
//                    ],
//                    jsonReader: {
//                        id: "id",
//                        root: "data.rows",
//                        page: "data.page",
//                        total: "data.total",
//                        records: "data.records",
//                        repeatiems: false
//                    }
//                });
//            },

            colModel: [
                {name:'id', index: 'id', title: "ID", width: 60, editable: true,
                    gridSearch: {type: "text"}
                },
                {name:'name', index: 'name', title: "名称", width: 90, editable: true,
                    gridSearch: {type: "text"},
                    editoptions: {size: "20", "minlength": "2", "maxlength": "255"}
                },
                {name:'pid', index: 'pid', title: "父类ID", width: 50, editable: true, value: arrParent,
                    gridSearch: {type: "select"},
                    editoptions: {size: "20", maxlength:"30"}
                }
            ]
        }
    });

    $(function(){
        g.init();
    });
</script>
<?php $this->endBlock(); ?>
