<?php
// 定义标题和面包屑信息
$this->title = '导航栏目信息';
?>
<?=\backend\widgets\MeTable::widget()?>
<?php $this->beginBlock('javascript') ?>
<script type="text/javascript">
    var aAdmins  = <?=\yii\helpers\Json::encode($this->params['admins'])?>,
        aParents = <?= $parents ?>,
        arrStatus = <?=\yii\helpers\Json::encode(Yii::$app->params['status'])?>;
    // 显示上级分类
    function parentStatus(td, data)
    {
        $(td).html(aParents[data] ? aParents[data] : '顶级分类');
    }

    var m = mt({
        title: "导航栏目",
        table: {
            "aoColumns":[
                {"data": "id", "sName":"id", "title": "Id", "defaultOrder": "desc", "edit":{"type":"hidden"}, "search":{"type":"text"}},
                {"data": "pid", "sName":"pid", "title": "上级分类", "value": aParents,  "edit":{"type":"select", "number":1}, "createdCell": parentStatus},
                {"data": "menu_name", "sName":"menu_name", "title":"栏目名称", "edit":{"required":1, "rangelength":"[2, 50]"}, "search":{"type":"text"}, "bSortable": false},
                {"data": "icons", "sName":"icons", "title":"图标", "edit": {"rangelength":"[2, 50]"}, "bSortable": false},
                {"data": "url", "sName":"url", "title":"访问地址", "edit": {"rangelength":"[2, 50]"}, "search":{"type": "text"}, "bSortable": false},
                {"data": "status", "sName":"status","title": "状态", "value" : arrStatus,
                    "edit": {"type": "radio", "default": 1, "required": 1, "number": 1},
                    "search": {"type": "select"},
                    "createdCell": mt.statusString,
                    "bSortable": false
                },
                {"data": "sort", "sName":"sort","title":"排序", "edit":{"type":"text", "required":1, "number":1, "value": 100}},
                // 公共属性字段信息
                {"data": "created_at", "sName":"created_at","title":"创建时间", "createdCell": mt.dateTimeString},
                {"data": "created_id", "sName":"created_id", "title":"创建用户", "createdCell": mt.adminString, "bSortable": false},
                {"data": "updated_at", "sName":"updated_at", "title":"修改时间", "createdCell": mt.dateTimeString},
                {"data": "updated_id", "sName":"updated_id", "title":"修改用户", "createdCell": mt.adminString, "bSortable": false}
            ]
        }
    });

    // 添加之前之后处理
    mt.fn.extend({
        afterSave: function() {
            window.parent.location.reload();
            return false;
        }
    });

    // 表单初始化
    $(function(){
        m.init();
    });
</script>
<?php $this->endBlock(); ?>