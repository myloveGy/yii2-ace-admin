<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl  = '@web/public/';

    // 加载CSS
    public $css = [
        'assets/css/bootstrap.min.css',
        'assets/css/font-awesome.min.css',
        'assets/css/jquery-ui.custom.min.css',
        'assets/css/jquery.gritter.css',
        'assets/css/select2.css',
        'assets/css/datepicker.css',
        'assets/css/bootstrap-timepicker.css',
        'assets/css/daterangepicker.css',
        'assets/css/bootstrap-datetimepicker.css',
        'assets/css/bootstrap-editable.css',
        'assets/css/ace-fonts.css',
    ];

    // 加载的JavaScript
    public $js = [
        'assets/js/jquery-ui.custom.min.js',
        'assets/js/jquery.ui.touch-punch.min.js',
        'assets/js/chosen.jquery.min.js',
        'assets/js/fuelux/fuelux.spinner.min.js',
        'assets/js/fuelux/fuelux.wizard.min.js',
        'assets/js/jquery.gritter.min.js',
        'assets/js/bootbox.min.js',
        'assets/js/jquery.easypiechart.min.js',
        'assets/js/date-time/bootstrap-datepicker.min.js',
        'assets/js/date-time/bootstrap-timepicker.min.js',
        'assets/js/date-time/moment.min.js',
        'assets/js/date-time/daterangepicker.min.js',
        'assets/js/date-time/bootstrap-datetimepicker.min.js',
        'assets/js/date-time/locales/bootstrap-datepicker.zh-CN.js',
        'assets/js/jquery.hotkeys.min.js',
        'assets/js/bootstrap-wysiwyg.min.js',
        'assets/js/select2.min.js',
        'assets/js/x-editable/bootstrap-editable.min.js',
        'assets/js/x-editable/ace-editable.min.js',
        'assets/js/jquery.maskedinput.min.js',
        'js/jquery.dataTables.min.js',
        'assets/js/jquery.dataTables.bootstrap.js',
        'assets/js/colResizable.min.js',
        'assets/js/dataTables.colResize.js',
        'js/jquery.validate.min.js',
        'js/validate.message.js',
        'js/layer/layer.js',
    ];

    // 加载选项
    public $jsOptions = [
//        'position' => View::POS_HEAD,
    ];


    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
