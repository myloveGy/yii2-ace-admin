<?php
$this->title = 'Yii2 Admin 登录信息';
?>
<table class="table table-striped table-bordered table-hover" id="show-table"></table>
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
        title: 123,
        url: {
            "search": "<?=\yii\helpers\Url::toRoute('menu/search')?>"
        },
        params: {
            "type": 1,
            "love": "gongyan"
        },
        table: {
            "aoColumns":[
                {"data": "id", "sName":"id", "title": "Id", "edit":{"type":"hidden"}, "search":{"type":"text"}, "defaultOrder": "desc"},
                {"data": "pid", "sName":"pid", "title": "上级分类"},
                {"data": "menu_name", "sName":"menu_name", "title":"栏目名称", "edit":{"options":{"required":1, "rangelength":"[2, 50]"}}, "search":{"type":"text"}, "bSortable": false},
                {"data": "icons", "sName":"icons", "title":"图标", "edit":{"options":{"rangelength":"[2, 50]"}}, "bSortable": false}
            ]
        }
    });

    $(function(){
        m.init();
    });
//    console.info(mt, meTables);


    console.info(mt.inArray(1, [1, 2, 3, 4]));
</script>
<?php $this->endBlock(); ?>
