<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
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
    <link rel="stylesheet" href="/public/assets/css/ace-skins.min.css" />
    <link rel="stylesheet" href="/public/assets/css/ace-rtl.min.css" />
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


    <!-- 公共的JS文件 -->
    <!-- basic scripts -->
    <!--[if !IE]> -->
    <script type="text/javascript">
        window.jQuery || document.write("<script src='/public/assets/js/jquery.min.js'>"+"<"+"/script>");
    </script>
    <!-- <![endif]-->
    <!--[if IE]>
    <script type="text/javascript">
        window.jQuery || document.write("<script src='/public/assets/js/jquery1x.min.js'>"+"<"+"/script>");
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
    <script src="/public/assets/js/ace-elements.min.js"></script>
    <script src="/public/assets/js/ace.min.js"></script>
    <script src="/public/js/base.js"></script>
    <script src="/public/js/dataTable.js"></script>
</head>
<body class="no-skin">
<?php $this->beginBody() ?>
<!-- #section:basics/navbar.layout -->
<div id="navbar" class="navbar navbar-default">
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
            <a href="/site" class="navbar-brand">
                <small>Yii2 Admin 后台管理</small>
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
                            <a href="<?=Url::toRoute(['site/index'])?>"><i class="ace-icon fa fa-desktop"></i>登录信息</a>
                        </li>
                        <li>
                            <a href="<?=Url::toRoute(['admin/view'])?>"><i class="ace-icon fa fa-user"></i>个人信息</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="<?=Url::toRoute(['site/logout'])?>" id="user-logout"><i class="ace-icon fa fa-power-off"></i>退出</a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</div>

<!-- /section:basics/navbar.layout -->
<div class="main-container" id="main-container">
    <script type="text/javascript">
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
    </script>

    <!-- #section:basics/sidebar -->
    <div id="sidebar" class="sidebar responsive">
        <script type="text/javascript">
            try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
        </script>

        <div class="sidebar-shortcuts" id="sidebar-shortcuts">
            <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                <button class="btn btn-success">
                    <i class="ace-icon fa fa-signal"></i>
                </button>

                <button class="btn btn-info">
                    <i class="ace-icon fa fa-pencil"></i>
                </button>

                <button class="btn btn-warning me-user" id="bt-me-user">
                    <i class="ace-icon glyphicon glyphicon-user"></i>
                </button>

                <button class="btn btn-danger me-set" id="bt-me-set">
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
        <ul class="nav nav-list">
            <?php foreach ($this->params['menus'] as $value) : ?>
                <li>
                    <a <?php if ($value['pid'] == 0 && ! empty($value['child'])) : ?> class="dropdown-toggle" <?php endif; ?> href="<?php echo !empty($value['url']) ? Url::to([$value['url']]) : '#'; ?>">
                        <i class="menu-icon fa <?=$value['icons']?>"></i>
                        <span class="menu-text"> <?=$value['menu_name']?> </span>
                        <?php if ($value['pid'] == 0 && ! empty($value['child'])) : ?><b class="arrow fa fa-angle-down"></b><?php endif;?>
                    </a>
                    <?php if ($value['pid'] == 0 && ! empty($value['child'])) : ?>
                        <ul class="submenu">
                            <?php foreach ($value['child'] as $val) : ?>
                                <li>
                                    <a href="<?=Url::toRoute([$val['url']])?>">
                                        <i class="menu-icon fa fa-caret-right"></i>
                                        <?=$val['menu_name']?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

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
        <div class="breadcrumbs" id="breadcrumbs">
            <script type="text/javascript">
                try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>

            <!--面包屑信息-->
            <ul class="breadcrumb">
                <?= Breadcrumbs::widget(
                    [
                        'homeLink' => [
                            'label' => '<i class="ace-icon fa fa-home home-icon"></i> 首页',
                            'url' => ['/']
                        ],
                        'encodeLabels' => false,
                        'tag' => 'ol',
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []
                    ]
                ); ?>
            </ul>

            <!--搜索-->
            <div class="nav-search" id="nav-search">
                <form class="form-search">
                    <span class="input-icon">
                        <input type="text" placeholder="搜索信息" class="nav-search-input" id="nav-search-input" autocomplete="off" />
                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                </form>
            </div>
        </div>

        <div class="page-content">

            <!--样式设置信息-->
            <div class="ace-settings-container" id="ace-settings-container">
                <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
                    <i class="ace-icon fa fa-cog bigger-150"></i>
                </div>


                <div class="ace-settings-box clearfix" id="ace-settings-box">
                    <div class="pull-left width-50">
                        <div class="ace-settings-item">
                            <div class="pull-left">
                                <select id="skin-colorpicker" class="hide">
                                    <option data-skin="no-skin" value="#438EB9">#438EB9</option>
                                    <option data-skin="skin-1" value="#222A2D">#222A2D</option>
                                    <option data-skin="skin-2" value="#C6487E">#C6487E</option>
                                    <option data-skin="skin-3" value="#D0D0D0">#D0D0D0</option>
                                </select>
                            </div>
                            <span>&nbsp; 选择皮肤 </span>
                        </div>

                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
                            <label class="lbl" for="ace-settings-navbar"> 固定导航栏 </label>
                        </div>

                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
                            <label class="lbl" for="ace-settings-sidebar"> 固定侧边栏 </label>
                        </div>

                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-breadcrumbs" />
                            <label class="lbl" for="ace-settings-breadcrumbs"> 固定的面包屑导航</label>
                        </div>

                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-rtl" />
                            <label class="lbl" for="ace-settings-rtl"> 从右到左（替换）</label>
                        </div>

                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-add-container" />
                            <label class="lbl" for="ace-settings-add-container">
                                缩小显示
                            </label>
                        </div>
                    </div>

                    <div class="pull-left width-50">
                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-hover" />
                            <label class="lbl" for="ace-settings-hover"> 菜单收缩</label>
                        </div>

                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-compact" />
                            <label class="lbl" for="ace-settings-compact"> 简单菜单</label>
                        </div>

                        <div class="ace-settings-item">
                            <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-highlight" />
                            <label class="lbl" for="ace-settings-highlight"> 当前菜单标记变换</label>
                        </div>
                    </div>
                </div>
            </div>

            <!--主要内容信息-->
            <div class="page-content-area">
                <div class="page-header">
                    <h1> <?=$this->title;?>
<!--                        <small>-->
<!--                            <i class="ace-icon fa fa-angle-double-right"></i>-->
<!--                            编辑我的信息-->
<!--                        </small>-->
                    </h1>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <?= $content ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!--尾部信息-->
    <div class="footer">
        <div class="footer-inner">
            <div class="footer-content">
                <span class="bigger-120">
                    <span class="blue bolder"> Liujinxing </span>
                    Yii2 Admin 项目 &copy; 2016-2018
                </span>
            </div>
        </div>
    </div>
    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
    </a>
</div>
<?php $this->endBody() ?>
<script type="text/javascript">
    $(function(){
        // 导航栏样式装换
        var select = 'ul.nav-list a[href=' + window.location.pathname.replace(/\//g, '\\/') +']';
        $(select).closest('li').addClass('active').parentsUntil('ul.nav-list').addClass('active open');
        // 隐藏和显示
        $('.me-hide').click(function(evt){
            evt.preventDefault();
            var sDataHide = $(this).attr('data-hide'),
                $parent   = empty(sDataHide) ? $(this).parent().parent().fadeOut() : $(sDataHide).fadeOut();
            $(select).append('<span class="badge badge-primary tooltip-error" title="显示">显示</span>').bind('click', function (e) {
                e.preventDefault();
                $parent.fadeIn();
                $(this).unbind('click').find('span:last').remove();
                return false;
            });
        });

        // 用户退出
        $('#user-logout').click(function(e){
            e.preventDefault();
            $.post($(this).attr('href'), function(json){
                window.location.reload();
            }, 'json');
        });

        // 用户页面
        $('#bt-me-user').click(function(){window.location.href="<?=Url::toRoute(['admin/view'])?>"});
        $('#bt-me-set').click(function(){window.location.href="<?=Url::toRoute(['site/index'])?>"});
    })
</script>
</body>
</html>
<?php $this->endPage() ?>

