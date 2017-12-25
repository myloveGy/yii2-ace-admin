<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Url;
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="<?= Yii::$app->charset ?>"/>
    <title><?=Yii::$app->name.Html::encode($this->title) ?></title>
    <meta name="description" content="3 styles with inline editable feature" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <?= Html::csrfMetaTags() ?>
    <?php $this->head(); ?>
    <!-- ace styles -->
    <link rel="stylesheet" href="/public/assets/css/ace.min.css" id="main-ace-style" />
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="/public/assets/css/ace-part2.min.css" />
    <![endif]-->
    <!--[if lte IE 9]>
    <link rel="stylesheet" href="/public/assets/css/ace-ie.min.css" />
    <![endif]-->
    <!-- inline styles related to this page -->
    <!-- ace settings handler -->
    <script src="/public/assets/js/ace-extra.min.js"></script>
    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
    <!--[if lte IE 8]>
    <script src="/public/assets/js/html5shiv.min.js"></script>
    <script src="/public/assets/js/respond.min.js"></script>
    <![endif]-->
    <style>
        body {overflow: hidden}
        .me-breadcrumb{display:block;max-width:100%;max-height:41px;overflow:hidden}
        .me-breadcrumb div{color:#585858;float:left;height:100%;display:inline-block;padding-left:15px;padding-right:15px;border-right:1px solid #e2e2e2}
        .me-breadcrumb div.me-window{padding:0;border-right:none}
        .me-breadcrumb div.me-window div{padding-right:8px}
        .me-breadcrumb div.me-window a{margin-left:5px}
        .me-breadcrumb div.active,.me-breadcrumb div.options:hover{color:#428bca;font-weight:700;background-color:#fff}
        .me-breadcrumb div a{color:red}
        .me-breadcrumb div.options a{color:#428bca;font-size:14px}
        .me-breadcrumb div span{cursor:pointer}
        #nav-search span a#window-refresh{font-size:20px}
        .iframe{-webkit-transition:all.3s ease-out 0s;transition:all.3s ease-out 0s}
        .breadcrumbs-fixed+.page-content{padding-top:41px}
        #page-content{overflow-y:hidden;padding-right:0;padding-bottom:0;padding-left:0}
    </style>
</head>
<body class="no-skin">
<?php $this->beginBody() ?>
<!-- #section:basics/navbar.layout -->
<div id="navbar" class="navbar navbar-default navbar-fixed-top">
    <script type="text/javascript">
        try { ace.settings.check('navbar' , 'fixed')}catch(e){}
    </script>

    <div class="navbar-container" id="navbar-container">
        <!-- #section:basics/sidebar.mobile.toggle -->
        <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler">
            <span class="sr-only">Toggle sidebar</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <div class="navbar-header pull-left">
            <a href="/" class="navbar-brand">
                <small><?=Yii::$app->params['projectName']?></small>
            </a>
        </div>

        <div class="navbar-buttons navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">
                <!-- 用户信息显示 -->
                <li class="light-blue">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        <img class="nav-user-photo" src="<?=$this->params['user']->face ? $this->params['user']->face : '/public/assets/avatars/avatar.jpg'?>" alt="Jason's Photo" />
                        <span class="user-info">
                                <small>欢迎登录</small><?=$this->params['user']->username?>
                            </span>
                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>

                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        <li>
                            <a class="window-iframe" data-id="index" title="登录信息" data-url="<?=Url::toRoute(['site/system'])?>" href="<?=Url::toRoute(['site/system'])?>">
                                <i class="ace-icon fa fa-desktop"></i>登录信息
                            </a>
                        </li>
                        <li>
                            <a class="window-iframe" data-id="my-info" title="个人信息" data-url="<?=Url::toRoute(['admin/view'])?>" href="<?=Url::toRoute(['admin/view'])?>">
                                <i class="ace-icon fa fa-user"></i>个人信息
                            </a>
                        </li>
                        <li>
                            <a class="window-iframe" data-id="my-arrange" title="我的日程" data-url="<?=Url::toRoute(['arrange/calendar'])?>" href="<?=Url::toRoute(['arrange/calendar'])?>">
                                <i class="ace-icon fa fa-calendar"></i>我的日程
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <?=Html::beginForm(['/site/logout'], 'post'); ?>
                            <?=Html::submitButton(
                                '<i class="ace-icon fa fa-power-off"></i> 退出登录 ',
                                ['class' => 'btn btn-link logout']
                            )?>
                            <?=Html::endForm(); ?>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</div>

<!-- /section:basics/navbar.layout -->
<div class="main-container main-container-fixed" id="main-container">
    <script type="text/javascript">
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
    </script>

    <!-- #section:basics/sidebar -->
    <div id="sidebar" class="sidebar responsive sidebar-fixed">
        <script type="text/javascript">
            try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
        </script>

        <div class="sidebar-shortcuts" id="sidebar-shortcuts">
            <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                <button class="btn btn-success window-iframe"  title="我的日程信息" data-id="my-arrange" data-url="<?=Url::toRoute(['arrange/calendar'])?>">
                    <i class="ace-icon fa fa-calendar"></i>
                </button>
                <button class="btn btn-info">
                    <i class="ace-icon fa fa-pencil"></i>
                </button>
                <button class="btn btn-warning window-iframe" title="个人信息" data-id="my-info" data-url="<?=Url::toRoute(['admin/view'])?>">
                    <i class="ace-icon glyphicon glyphicon-user"></i>
                </button>
                <button class="btn btn-danger window-iframe" title="登录信息" data-id="index" data-url="<?=Url::toRoute(['site/system'])?>">
                    <i class="ace-icon fa fa-cogs"></i>
                </button>
            </div>
            <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                <span class="btn btn-success"></span>
                <span class="btn btn-info"></span>
                <span class="btn btn-warning"></span>
                <span class="btn btn-danger"></span>
            </div>
        </div>
        <!--左侧导航栏信息-->
        <?php
            try {
                echo \backend\widgets\Nav::widget([
                    'options' => [
                        'id' => 'nav-list-main',
                        'class' => 'nav nav-list',
                    ],
                    'labelName' => 'menu_name',
                    'items' =>  $this->params['menus'],
                    'itemsName' => 'child'
                ]);
            } catch (\Exception $e) {

            }
        ?>
        <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
            <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
        </div>

        <script type="text/javascript">
            try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
        </script>
    </div>

    <!--主要内容信息-->
    <div class="main-content">

        <!--头部可固定导航信息-->
        <div class="breadcrumbs breadcrumbs-fixed" id="breadcrumbs">
            <script type="text/javascript">
                try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>
            <div class="me-breadcrumb pull-left">
                <div class="prev options hide" id="window-prev">
                    <a href="#"><i class="ace-icon fa fa-backward"></i></a>
                </div>
                <div class="me-window" id="me-window">
                    <div class="me-div active" data-id="iframe-index">
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <span>首页</span>
                        <a href="#" class="me-window-close">
                            <i class="ace-icon fa fa-times "></i>
                        </a>
                    </div>
                </div>
                <div class="next options hide" id="window-next">
                    <a href="#"><i class="ace-icon fa fa-forward"></i></a>
                </div>
            </div>

            <!--搜索-->
            <div class="nav-search" id="nav-search">
                <span class="input-icon">
                    <a id="window-refresh" href="#">
                        <i class="ace-icon fa fa-refresh  bigger-110 icon-only"></i>
                    </a>
                </span>
            </div>
        </div>

        <div class="page-content" id="page-content">
            <iframe class="active iframe" name="iframe-index" id="iframe-index" width="100%" height="100%" src="<?=Url::toRoute(['site/system'])?>" frameborder="0"></iframe>
        </div>
    </div>
</div>
<!-- 公共的JS文件 -->
<!-- basic scripts -->
<!--[if !IE]> -->
<script type="text/javascript">
    window.jQuery || document.write("<script src='/public/assets/js/jquery.min.js'>"+"<"+"/script>");
</script>
<!-- <![endif]-->
<!--[if IE]>
<script type="text/javascript">
    window.jQuery || document.write("<script src='/public/assets/js/jquery1x.min.js'>" + "<"+"/script>");
</script>
<![endif]-->
<script type="text/javascript">
    if('ontouchstart' in document.documentElement) document.write("<script src='/public/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<script src="/public/assets/js/bootstrap.min.js"></script>
<!-- page specific plugin scripts -->
<!--[if lte IE 8]>
<script src="/public/assets/js/excanvas.min.js"></script>
<![endif]-->
<?php $this->endBody() ?>
<script src="/public/assets/js/common/iframe.js"></script>
<script type="text/javascript">
    authHeight();
    var $windowDiv = $("#me-window");
    var $divContent = $("#page-content");
</script>
</body>
</html>
<?php $this->endPage() ?>

