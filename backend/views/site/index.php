<?php
$this->title = 'Yii2 Admin 登录信息';
$this->params['breadcrumbs'][] = $this->title;
?>
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

    <div class="col-xs-12 col-sm-12">
        <h4 class="red">
            <span class="middle"><i class="fa fa-exclamation-triangle light-red bigger-110" aria-hidden="true"></i></span>
            警告信息
        </h4>
        <div class="profile-user-info">
            <div class="profile-info-row">
                <div class="profile-info-value red">
                    <span>禁止把源码给予（或出售）第三方，会失去售后和永久更新; 如果您的网站出现问题，请记住您的操作，方便技术处理; 有任何技术或运营问题，请及时联系在线客服或是售后服务</span>
                </div>
            </div>
        </div>
    </div>
</div>
