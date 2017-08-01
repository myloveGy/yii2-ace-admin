<div class="hide">
    <div class="user-profile" id="user-profile-2">
        <div class="tabbable">
            <ul class="nav nav-tabs padding-18">
                <li class="active">
                    <a href="#home" data-toggle="tab">
                        <i class="green ace-icon fa fa-user bigger-120"></i>
                        我的信息
                    </a>
                </li>
                <li>
                    <a href="#pictures" data-toggle="tab">
                        <i class="pink ace-icon fa fa-picture-o bigger-120"></i>
                        我的图片
                    </a>
                </li>
            </ul>

            <div class="tab-content no-border padding-24">
                <div class="tab-pane in active" id="home">
                    <div class="row">
                        <div class="col-xs-12 col-sm-3 center">
							<span class="profile-picture">
								<img src="<?=$this->params['user']->face ? dirname($this->params['user']->face).'/thumb_'.basename($this->params['user']->face) : '/public/assets/avatars/profile-pic.jpg'?>" id="avatar2" alt="Alex's Avatar" class="editable img-responsive">
							</span>

                            <div class="space space-4"></div>

                            <a class="btn btn-sm btn-block btn-success" href="#">
                                <i class="ace-icon fa fa-plus-circle bigger-120"></i>
                                <span class="bigger-110">添加好友</span>
                            </a>

                            <a class="btn btn-sm btn-block btn-primary" href="#">
                                <i class="ace-icon fa fa-envelope-o bigger-110"></i>
                                <span class="bigger-110">发送好友</span>
                            </a>
                        </div><!-- /.col -->

                        <div class="col-xs-12 col-sm-9">
                            <h4 class="blue">
                                <span class="middle"><?=$this->params['user']->username?></span>
								<span class="label label-purple arrowed-in-right">
									<i class="ace-icon fa fa-circle smaller-80 align-middle"></i>
									在线
								</span>
                            </h4>

                            <div class="profile-user-info">
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> 姓名 </div>
                                    <div class="profile-info-value">
                                        <span><?=$this->params['user']->username?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> 国籍 </div>

                                    <div class="profile-info-value">
                                        <i class="fa fa-map-marker light-orange bigger-110"></i>
                                        <span>中国,<?=$this->params['user']->address?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> 年龄 </div>

                                    <div class="profile-info-value">
                                        <span>20</span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> 创建时间 </div>

                                    <div class="profile-info-value">
                                        <span><?=date('Y-m-d H:i:s', $this->params['user']->created_at)?></span>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-name"> 上次登录时间 </div>

                                    <div class="profile-info-value">
                                        <span>3小时前</span>
                                    </div>
                                </div>
                            </div>

                            <div class="hr hr-8 dotted"></div>

                            <div class="profile-user-info">
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> 个人主页 </div>
                                    <div class="profile-info-value">
                                        <a target="_blank" href="#">821901008@qq.com</a>
                                    </div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name">
                                        <i class="middle ace-icon fa fa-facebook-square bigger-150 blue"></i>
                                    </div>
                                    <div class="profile-info-value">
                                        <a href="#" target="_blank"></a>
                                    </div>
                                </div>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /.row -->

                    <div class="space-20"></div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <div class="widget-box transparent">
                                <div class="widget-header widget-header-small">
                                    <h4 class="widget-title smaller">
                                        <i class="ace-icon fa fa-check-square-o bigger-110"></i>
                                        小小的我
                                    </h4>
                                </div>

                                <div class="widget-body">
                                    <div class="widget-main">
                                        <p>一个普通的程序员，有着自己的梦想</p>
                                        <p>感谢访问我的个人资料</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6">
                            <div class="widget-box transparent">
                                <div class="widget-header widget-header-small header-color-blue2">
                                    <h4 class="widget-title smaller">
                                        <i class="ace-icon fa fa-lightbulb-o bigger-120"></i>
                                        我的技能
                                    </h4>
                                </div>

                                <div class="widget-body">
                                    <div class="widget-main padding-16">
                                        <div class="clearfix">
                                            <div class="grid3 center">
                                                <div data-color="#CA5952" data-percent="65" class="easy-pie-chart percentage" style="height: 72px; width: 72px; line-height: 71px; color: rgb(202, 89, 82);">
                                                    <span class="percent">65</span>%
                                                    <canvas height="72" width="72"></canvas></div>
                                                <div class="space-2"></div>
                                                Gopher Go程序员
                                            </div>

                                            <div class="grid3 center">
                                                <div data-color="#59A84B" data-percent="90" class="center easy-pie-chart percentage" style="height: 72px; width: 72px; line-height: 71px; color: rgb(89, 168, 75);">
                                                    <span class="percent">90</span>%
                                                    <canvas height="72" width="72"></canvas></div>
                                                <div class="space-2"></div>
                                                PHP
                                            </div>
                                            <div class="grid3 center">
                                                <div data-color="#9585BF" data-percent="80" class="center easy-pie-chart percentage" style="height: 72px; width: 72px; line-height: 71px; color: rgb(149, 133, 191);">
                                                    <span class="percent">80</span>%
                                                    <canvas height="72" width="72"></canvas></div>

                                                <div class="space-2"></div>
                                                Javascript/jQuery
                                            </div>
                                        </div>

                                        <div class="hr hr-16"></div>

                                        <div class="profile-skills">
                                            <div class="progress">
                                                <div style="width:90%" class="progress-bar progress-bar-purple">
                                                    <span class="pull-left">PHP &amp; MySQL</span>
                                                    <span class="pull-right">90%</span>
                                                </div>
                                            </div>
                                            <div class="progress">
                                                <div style="width:80%" class="progress-bar">
                                                    <span class="pull-left">HTML5 &amp; CSS3</span>
                                                    <span class="pull-right">80%</span>
                                                </div>
                                            </div>
                                            <div class="progress">
                                                <div style="width:85%" class="progress-bar progress-bar-success">
                                                    <span class="pull-left">Javascript &amp; jQuery</span>
                                                    <span class="pull-right">85%</span>
                                                </div>
                                            </div>

                                            <div class="progress">
                                                <div style="width:50%" class="progress-bar progress-bar-warning">
                                                    <span class="pull-left">Mongo &amp; Redis &amp; Memcache</span>
                                                    <span class="pull-right">60%</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /#home -->

                <div class="tab-pane" id="pictures">
                    <ul class="ace-thumbnails">
                        <?php for($i = 1; $i <= 5; $i++) : ?>
                        <li>
                            <a data-rel="colorbox" href="#">
                                <img src="/public/assets/images/gallery/thumb-<?=$i?>.jpg" alt="150x150">
                                <div class="text">
                                    <div class="inner">Sample Caption on Hover{{$v}}</div>
                                </div>
                            </a>

                            <div class="tools tools-bottom">
                                <a href="#">
                                    <i class="ace-icon fa fa-link"></i>
                                </a>

                                <a href="#">
                                    <i class="ace-icon fa fa-paperclip"></i>
                                </a>

                                <a href="#">
                                    <i class="ace-icon fa fa-pencil"></i>
                                </a>

                                <a href="#">
                                    <i class="ace-icon fa fa-times red"></i>
                                </a>
                            </div>
                        </li>
                        <?php endfor; ?>
                    </ul>
                    <ul class="pager pull-right">
                        <li class="previous disabled">
                            <a href="#">← 上一页</a>
                        </li>

                        <li class="next">
                            <a href="#">下一页 →</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>