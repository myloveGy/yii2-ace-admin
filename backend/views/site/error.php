<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
?>
<div class="error-container">
    <div class="well">
        <h1 class="grey lighter smaller">
            <span class="blue bigger-125">
                <i class="ace-icon fa fa-sitemap"></i>
                <?= Html::encode($this->title) ?>
            </span>
            <?= nl2br(Html::encode($message)) ?>
        </h1>
        <hr />
        <div class="alert alert-danger">
            <?= nl2br(Html::encode($message)) ?>
        </div>
        <h3 class="lighter smaller"><?= nl2br(Html::encode($message)) ?></h3>
        <div>


            <div class="space"></div>
            <h4 class="smaller">尝试下列方法：</h4>

            <ul class="list-unstyled spaced inline bigger-110 margin-15">
                <li>
                    <i class="ace-icon fa fa-hand-o-right blue"></i>
                    告诉管理员添加权限操作
                </li>
                <li>
                    <i class="ace-icon fa fa-hand-o-right blue"></i>
                    阅读常见问题
                </li>
                <li>
                    <i class="ace-icon fa fa-hand-o-right blue"></i>
                    告诉我们
                </li>
            </ul>
        </div>

        <hr />
        <div class="space"></div>
        <div class="center">
            <a href="javascript:history.back()" class="btn btn-grey">
                <i class="ace-icon fa fa-arrow-left"></i>
                回去
            </a>

            <a href="javascript: window.parent ? window.parent.location.reload() : window.location.href = '<?=Url::to(['site/system'])?>'" class="btn btn-primary">
                <i class="ace-icon fa fa-desktop"></i>
                首页
            </a>
        </div>
    </div>
</div>
