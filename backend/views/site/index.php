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
<!--[if lte IE 8]>
<script src="/public/assets/js/excanvas.min.js"></script>
<![endif]-->
<?php $this->endBody() ?>
<script type="text/javascript">
    authHeight();
    var $windowDiv = $("#me-window"),
        $divContent = $("#page-content"),
        intSize = <?=Yii::$app->params['iframeNumberSize']?>;

    function authHeight() {
        $("#page-content").css("height", $(window).height() - $("#page-content").offset()["top"] - $(".footer").innerHeight() + "px")
    }

    function addDiv(strId, strTitle) {
        $windowDiv.find("div.active").removeClass("active");
        if ($windowDiv.find("div:not(div.hide)").size() >= intSize) {
            $windowDiv.find("div:not(div.hide):first").addClass("hide");
            $("#window-prev").removeClass("hide");
        }
        var html = '<div class="me-div active" data-id="' + strId + '"><span>' + strTitle + '</span><a href="javascript:;" class="me-window-close"><i class="ace-icon fa fa-times me-i-close"></i></a></div>';
        $windowDiv.append(html);
    }

    function addIframe(strId, strUrl, strTitle) {
        strId = "iframe-" + strId;
        $divContent.find("iframe.active").removeClass("active").addClass("hide");
        $windowDiv.find("div.active").removeClass("active");
        if ($divContent.find("#" + strId).size() > 0) {
            $divContent.find("#" + strId).addClass("active").removeClass("hide");
            $windowDiv.find("div[data-id=" + strId + "]").addClass("active");
        } else {
            var strIframe = '<iframe id="' + strId + '" name="' + strId + '" ' + 'width="100%" class="active iframe" height="100%" src="' + strUrl + '" frameborder="0"></iframe>';
            addDiv(strId, $.trim(strTitle));
            $("#page-content").append(strIframe);
        }
    }

    $(function () {
        $(window).resize(function () {
            authHeight()
        });
        $("#window-refresh").click(function (evt) {
            evt.preventDefault();
            var objActive = $("#page-content iframe.active").get(0);
            if (objActive) {
                objActive.contentWindow.location.reload()
            }
        });
        $(document).on("click", "#me-window span", function () {
            $("#me-window").find("div.active").removeClass("active");
            $("#page-content").find("iframe.active").removeClass("active").addClass("hide");
            $("#" + $(this).parent().addClass("active").attr("data-id")).removeClass("hide").addClass("active")
        });
        $(document).on("click", "a.me-window-close", function (evt) {
            evt.preventDefault();
            var $parent = $(this).parent("div"),
                isHasActive = $parent.hasClass("active"),
                $next = $windowDiv.find("div:not(div.hide):last").next("div");
            if ($next.size() > 0) {
                $next.removeClass("hide");
                if (isHasActive) {
                    $divContent.find("#" + $next.addClass("active").attr("data-id")).removeClass("hide").addClass("active")
                }
            } else {
                $windowDiv.find("div:not(div.hide):first").prev("div").removeClass("hide");
                if (isHasActive || $windowDiv.find("div.active").size() <= 0) {
                    $divContent.find("#" + $parent.prev("div").addClass("active").removeClass("hide").attr("data-id")).removeClass("hide").addClass("active")
                }
            }

            $parent.remove();
            $("#" + $parent.attr("data-id")).remove();
            var intShowDiv = $windowDiv.find("div:not(div.hide)").size();
            if ($windowDiv.find("div:not(div.hide):last").next("div").size() <= 0 || intShowDiv < intSize) {
                $("#window-next").addClass("hide")
            }

            if ($windowDiv.find("div:not(div.hide):first").prev("div").size() <= 0 || intShowDiv < intSize) {
                $("#window-prev").addClass("hide")
            }
        });
        $("#nav-list-main").find("a").click(function (e) {
            e.preventDefault();
            if ($(this).attr("href") != "#") {
                addIframe($(this).attr("data-id"), $(this).prop("href"), $(this).text());
                var $parent = $(this).closest("li").parent();
                if ($parent.hasClass("nav-list")) {
                    $parent.children("li").removeClass("active");
                    $parent.find("li.hsub ul.submenu").hide().removeClass("open active").find("li").removeClass("active")
                } else if ($parent.hasClass("submenu")) {
                    $parent.find("li.active").removeClass("active");
                    $parent.parent("li").siblings("li").removeClass("active")
                }
                $(this).closest("li").addClass("active")
            }
        });
        $("#window-prev").click(function () {
            if ($windowDiv.find("div:not(div.hide):first").prev("div").size() > 0) {
                $windowDiv.find("div:not(div.hide):first").prev("div").removeClass("hide");
                $windowDiv.find("div:not(div.hide):last").addClass("hide");
                $("#window-next").removeClass("hide");
                if ($windowDiv.find("div:not(div.hide):first").prev("div").size() <= 0) {
                    $(this).addClass("hide")
                }
            } else {
                if ($windowDiv.find("div.hide").size() > 0) {
                    $("#window-next").removeClass("hide")
                }
            }
        });

        $("#window-next").click(function () {
            if ($windowDiv.find("div:not(div.hide):last").next("div").size() >= 1) {
                $windowDiv.find("div:not(div.hide):last").next("div").removeClass("hide");
                $windowDiv.find("div:not(div.hide):first").addClass("hide");
                $("#window-prev").removeClass("hide");
                if ($windowDiv.find("div:not(div.hide):last").next("div").size() <= 0) {
                    $(this).addClass("hide")
                }
            } else {
                if ($windowDiv.find("div.hide").size() > 0) {
                    $("#window-prev").removeClass("hide")
                }
            }
        });

        $(".window-iframe").click(function (e) {
            e.preventDefault();
            if ($(this).attr("data-id")) {
                addIframe($(this).attr("data-id"), $(this).attr("data-url"), $(this).attr("title"))
            }
        })
    });
</script>
</body>
</html>
<?php $this->endPage() ?>

