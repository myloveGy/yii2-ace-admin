<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AjaxAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl  = '@web/public/assets/';

    // 加载CSS
    public $css = [
        'css/bootstrap.min.css',
        'css/font-awesome.min.css',
        'css/ace-fonts.css'
    ];

    // 加载的JavaScript
    public $js = [
        'js/bootstrap.min.js',
        'js/ace-elements.min.js',
        'js/ace.min.js',
//        'js/common/base.js',
//        'js/common/dataTable.js',
//        'js/jquery-ui.custom.min.js',
//        'js/jquery.ui.touch-punch.min.js',
//        'js/date-time/moment.min.js',
//        'js/chosen.jquery.min.js',
//        'js/fuelux/fuelux.spinner.min.js',
//        'js/fuelux/fuelux.wizard.min.js',
//        'js/fullcalendar.min.js',
//        'js/jquery.gritter.min.js',
//        'js/bootbox.min.js',
//        'js/jquery.easypiechart.min.js',
//        'js/date-time/bootstrap-datepicker.min.js',
//        'js/date-time/bootstrap-timepicker.min.js',
//        'js/date-time/daterangepicker.min.js',
//        'js/date-time/bootstrap-datetimepicker.min.js',
//        'js/date-time/locales/bootstrap-datepicker.zh-CN.js',
//        'js/jquery.hotkeys.min.js',
//        'js/bootstrap-wysiwyg.min.js',
//        'js/select2.min.js',
//        'js/x-editable/bootstrap-editable.min.js',
//        'js/x-editable/ace-editable.min.js',
//        'js/jquery.maskedinput.min.js',
//        'js/jquery.dataTables.min.js',
//        'js/jquery.dataTables.bootstrap.js',
//        'js/colResizable.min.js',
//        'js/dataTables.colResize.js',
//        'js/jquery.validate.min.js',
//        'js/validate.message.js',
//        'js/layer/layer.js',
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
