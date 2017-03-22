<?php
$this->title = 'Yii2 Admin 登录信息';
?>
<p>
    <button class="btn btn-white btn-success btn-bold me-table-create">
        <i class="ace-icon fa fa-plus bigger-120 blue"></i>
        添加
    </button>
    <button class="btn btn-white btn-warning btn-bold me-table-delete-all">
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
<table class="table table-striped table-bordered table-hover" id="show-table"></table>
<div class="col-xs-12 hidden">
    <table id="child-table" class="table table-striped table-bordered table-hover"></table>
</div>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <h4 class="blue">
            <span class="middle"><i class="ace-icon glyphicon glyphicon-user light-blue bigger-110"></i></span>
            账号信息
        </h4>
        <div class="profile-user-info">
            <div class="profile-info-row">
                <div class="profile-info-name"> 账号  </div>
                <div class="profile-info-value">
                    <span><?=$this->params['user']->username?></span>
                </div>
            </div>
            <div class="profile-info-row">
                <div class="profile-info-name"> 角色  </div>
                <div class="profile-info-value">
                    <span><?=$this->params['user']->role?></span>
                </div>
            </div>
            <div class="profile-info-row">
                <div class="profile-info-name"> 上次登录时间  </div>
                <div class="profile-info-value">
                    <span><?=date('Y-m-d H:i:s', $this->params['user']->last_time)?></span>
                </div>
            </div>
            <div class="profile-info-row">
                <div class="profile-info-name"> 上次登录IP  </div>
                <div class="profile-info-value">
                    <span><?=$this->params['user']->last_ip?></span>
                </div>
            </div>
        </div>
        <div class="hr hr16 dotted"></div>

        <h4 class="blue">
            <span class="middle"><i class="fa fa-desktop light-blue bigger-110"></i></span>
            系统信息
        </h4>

        <div class="profile-user-info">
            <div class="profile-info-row">
                <div class="profile-info-name"> 操作系统  </div>
                <div class="profile-info-value">
                    <span><?=$system?></span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name"> 服务器软件 </div>

                <div class="profile-info-value">
                    <span><?=$server?></span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name"> MySQL版本 </div>

                <div class="profile-info-value">
                    <span><?=$mysql?></span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name"> PHP版本 </div>

                <div class="profile-info-value">
                    <span><?=$php?></span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name"> Yii版本 </div>
                <div class="profile-info-value">
                    <span><?=$yii?></span>
                </div>
            </div>

            <div class="profile-info-row">
                <div class="profile-info-name"> 上传文件 </div>
                <div class="profile-info-value">
                    <span><?=$upload?></span>
                </div>
            </div>
        </div>
        <div class="hr hr-8 dotted"></div>
        <div class="profile-user-info">
            <div class="profile-info-row">
                <div class="profile-info-name"> 个人主页 </div>
                <div class="profile-info-value">
                    <a target="_blank" href="http://821901008.qzone.qq.com">http://821901008.qzone.qq.com</a>
                </div>
            </div>
            <div class="profile-info-row">
                <div class="profile-info-name">
                    <i class="fa fa-github-square" aria-hidden="true"></i>
                    GitHub
                </div>
                <div class="profile-info-value">
                    <a href="https://github.com/myloveGy" target="_blank">https://github.com/myloveGy</a>
                </div>
            </div>
        </div>
        <div class="hr hr16 dotted"></div>
    </div>
</div>

<div id="test">

</div>
<?php $this->beginBlock('javascript'); ?>
<script>
    var m = meTables({
        title: "导航栏目信息",
        url: {
            "search": "<?=\yii\helpers\Url::toRoute('china/search')?>"
        },
        params: {
            "pid": 0
        },
        table: {
            "aoColumns":[
                {"createdCell": function(td, data) {
                    $(td).html(data + '<b class="arrow fa fa-angle-down pull-right"></b>');
                }, "data": "id", "sName": "id", "class": "child-control", "title": "Id", "edit":{"type":"hidden"}, "search":{"type":"text"}, "defaultOrder": "desc"},
                {"data": "pid", "sName": "pid", "title": "上级分类"},
                {"data": "name", "sName": "name", "title":"名称", "edit":{"required":1, "rangelength":"[2, 50]"}, "search":{"type":"text"}, "bSortable": false}
            ]
        },
        bChildTables: true,
        childTables: {
            url: {
                "search": "<?=\yii\helpers\Url::toRoute('china/child')?>",
                "create": "<?=\yii\helpers\Url::toRoute('china/create')?>",
                "update": "<?=\yii\helpers\Url::toRoute('china/update')?>",
                "delete": "<?=\yii\helpers\Url::toRoute('china/delete')?>"
            },
            table: {
                "aoColumns":[
                    {"data": "id", "sName": "id", "title": "Id", "edit":{"type":"hidden"}, "search":{"type":"text"}, "defaultOrder": "desc"},
                    {"data": "pid", "sName": "pid", "title": "上级分类"},
                    {"data": "name", "sName": "name", "title":"名称", "edit":{"required":1, "rangelength":"[2, 50]"}, "search":{"type":"text"}, "bSortable": false},
                    meTables.fn.options.childTables.operations
                ]
            }
        }
    });

    $(function(){
        m.init();
    });
</script>
<?php $this->endBlock(); ?>
