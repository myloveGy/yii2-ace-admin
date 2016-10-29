<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl  = '@web/public/assets/';
    public static $assetsUrl = '@web/public/assets/';

    // 加载CSS
    public $css = [
        'css/bootstrap.min.css',
        'css/font-awesome.min.css',
//        'css/fullcalendar.css',
//        'css/jquery-ui.custom.min.css',
        'css/jquery.gritter.css',
//        'css/select2.css',
//        'css/datepicker.css',
//        'css/bootstrap-timepicker.css',
//        'css/daterangepicker.css',
//        'css/bootstrap-datetimepicker.css',
        'css/bootstrap-editable.css',
        'css/ace-fonts.css',
    ];

    // 加载的JavaScript
    public $js = [
        'js/ace-elements.min.js',
        'js/ace.min.js',
        'js/common/base.js',
        'js/common/dataTable.js',
//        'js/jquery-ui.custom.min.js',
//        'js/jquery.ui.touch-punch.min.js',
//        'js/date-time/moment.min.js',
//        'js/chosen.jquery.min.js',
//        'js/fuelux/fuelux.spinner.min.js',
//        'js/fuelux/fuelux.wizard.min.js',
//        'js/fullcalendar.min.js',
        'js/jquery.gritter.min.js',
        'js/bootbox.min.js',
//        'js/jquery.easypiechart.min.js',
//        'js/date-time/bootstrap-datepicker.min.js',
//        'js/date-time/bootstrap-timepicker.min.js',
//        'js/date-time/daterangepicker.min.js',
//        'js/date-time/bootstrap-datetimepicker.min.js',
//        'js/date-time/locales/bootstrap-datepicker.zh-CN.js',
//        'js/jquery.hotkeys.min.js',
//        'js/bootstrap-wysiwyg.min.js',
//        'js/select2.min.js',
        'js/x-editable/bootstrap-editable.min.js',
        'js/x-editable/ace-editable.min.js',
//        'js/jquery.maskedinput.min.js',
        'js/jquery.dataTables.min.js',
        'js/jquery.dataTables.bootstrap.js',
//        'js/colResizable.min.js',
//        'js/dataTables.colResize.js',
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

    /**
     * loadTimeJavascript() 加载时间的资源
     * @param $view
     * @param string $type
     * @param array $options
     */
    public static function loadTimeJavascript($view, $type = 'all', $options = ['depends' => 'backend\assets\AppAsset'])
    {
        // 按需加载的CSS
        $arrCss = [
            'date' => 'css/datepicker.css',
            'time' => 'css/bootstrap-timepicker.css',
            'range' => 'css/daterangepicker.css',
            'datetime' => 'css/bootstrap-datetimepicker.css',
        ];

        // 按需加载的JS
        $arrJavascript = [
            'main' => 'js/date-time/moment.min.js',
            'date' => 'js/date-time/bootstrap-datepicker.min.js',
            'date_CN' => 'js/date-time/locales/bootstrap-datepicker.zh-CN.js',
            'time' => 'js/date-time/bootstrap-timepicker.min.js',
            'range' => 'js/date-time/daterangepicker.min.js',
            'datetime' => 'js/date-time/bootstrap-datetimepicker.min.js',
        ];

        // 必须要加载的js
        switch ($type) {
            case 'date':
                $arrLoadCss = [$arrCss['date']];
                $arrLoad = [$arrJavascript['main'], $arrJavascript['date'], $arrJavascript['date_CN']];
                break;
            case 'time':
                $arrLoadCss = [$arrCss['time']];
                $arrLoad = [$arrJavascript['main'], $arrJavascript['time']];
                break;
            case 'range':
                $arrLoadCss = [$arrCss['range']];
                $arrLoad = [$arrJavascript['main'], $arrJavascript['range']];
                break;
            case 'datetime':
                unset($arrCss['range'], $arrJavascript['range']);
                $arrLoadCss = $arrCss;
                $arrLoad = $arrJavascript;
                break;
            default:
                $arrLoadCss = $arrCss;
                $arrLoad = $arrJavascript;
        }

        // 执行加载css资源
        foreach ($arrLoadCss as $value) {
            $view->registerCssFile(self::$assetsUrl.$value, $options);
        }

        // 执行加载javascript资源
        foreach ($arrLoad as $value) {
            $view->registerJsFile(self::$assetsUrl.$value, $options);
        }

    }
}
