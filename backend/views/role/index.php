<?php
use yii\helpers\Url;

// 定义标题和面包屑信息
$this->title = '角色信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<!-- 表格按钮 -->
<p id="me-table-buttons"></p>
<!-- 表格数据 -->
<table class="table table-striped table-bordered table-hover" id="show-table"></table>
<?php $this->beginBlock('javascript');?>
<script type="text/javascript">
    var m = mt({
        title: "角色信息",
        operations: {
            width: "200px",
            buttons: {
                "see": {"cClass": "role-see"},
                "update": {"cClass": "role-update"},
                "other": {
                    "title": "编辑权限",
                    "button-title": "编辑权限",
                    "className": "btn-warning",
                    "cClass":"role-edit",
                    "icon":"fa-pencil-square-o",
                    "sClass":"yellow"
                }
            }
        },
        table: {
            "aoColumns":[
                {"title": "角色名称", "data": "name", "sName": "name", "edit": {"type": "text", "options": {"required": true, "rangelength": "[2, 64]"}}, "search": {"type": "text"}, "bSortable": false},
                {"title": "说明描述", "data": "description", "sName": "description", "edit": {"type": "text", "options": {"required": true, "rangelength": "[2, 255]"}}, "search": {"type": "text"}, "bSortable": false},
                {"title": "创建时间", "data": "created_at", "sName": "created_at", "defaultOrder": "desc", "createdCell" : mt.dateTimeString},
                {"title": "修改时间", "data": "updated_at", "sName": "updated_at", "createdCell" : mt.dateTimeString}
            ]
        }
    });

    mt.fn.extend({
        afterShow: function(){
            $(this.options.sFormId).find('input[name=name]').attr('readonly', this.action == 'update');
            return true;
        }
    });

    $(function(){
        m.init();

        // 添加查看事件
        $(document).on('click', '.role-see', function(){
            var data = m.table.data()[$(this).attr('table-data')];
            if (data) {
                window.location.href = "<?=Url::toRoute(['role/view'])?>?name=" + data['name'];
            }
        });

        // 添加修改权限事件
        $(document).on('click', '.role-edit', function(){
            var data = m.table.data()[$(this).attr('table-data')];
            if (data) {
                window.location.href = "<?=Url::toRoute(['role/edit'])?>?name=" + data['name'];
            }
        })
    })
</script>
<?php $this->endBlock(); ?>
