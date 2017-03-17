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
        'css/ace-fonts.css',
    ];

    // 加载的JavaScript
    public $js = [
        'js/ace-elements.min.js',
        'js/ace.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    /**
     * registerCommon() 注册主要的资源
     * @param $view
     */
    public static function registerCommon($view)
    {
        $options = ['depends' => 'backend\assets\AppAsset'];
//        $view->registerCssFile(self::$assetsUrl.'css/bootstrap-editable.css', $options);
        $arrJsResource = [
            'js/common/base.js',
            'js/common/dataTable.js',
            'js/common/meTables.js',
//            'js/x-editable/bootstrap-editable.min.js',
//            'js/x-editable/ace-editable.min.js',
            'js/jquery.dataTables.min.js',
            'js/jquery.dataTables.bootstrap.js',
            'js/jquery.validate.min.js',
            'js/validate.message.js',
            'js/layer/layer.js',
        ];

        foreach ($arrJsResource as $value) {
            $view->registerJsFile(self::$assetsUrl.$value, $options);
        }
    }

    /**
     * loadDataTables() 加载公共的dataTables 的css 和 javascript
     * @param $view
     * @param array $arrNoLoad
     * @param array $options
     */
    public static function loadDataTables($view, $arrNoLoad = [], $options = ['depends' => 'backend\assets\AppAsset'])
    {
        // 必须加载的JS
        $arrLoad = [
            // 主要的CSS 和 javascript
            'must' => [
                'css' => [

                ],

                'javascript' => [
                    'js/common/base.js',
                    'js/common/dataTable.js',
                    'js/jquery.dataTables.min.js',
                    'js/jquery.dataTables.bootstrap.js',
                    'js/layer/layer.js'
                ]
            ],

            // validate
            'validate' => [
                'css' => [],
                'javascript' => [
                    'js/jquery.validate.min.js',
                    'js/validate.message.js'
                ],
            ],

            // editable
            'editable' => [
                'css' => [
                    'css/bootstrap-editable.css'
                ],
                'javascript' => [
                    'js/x-editable/bootstrap-editable.min.js',
                    'js/x-editable/ace-editable.min.js'
                ]
            ],

            // gritter
            'gritter' => [
                'css' => [
                    'css/jquery.gritter.css',
                ],
                'javascript' => [
                    'js/jquery.gritter.min.js'
                ]
            ],

            // bootbox
            'bootbox' => [
                'css' => [

                ],
                'javascript' => [
                    'js/bootbox.min.js',
                ],
            ],
        ];

        // 处理不需要加载的css和javascript
        foreach ($arrNoLoad as $value) {
            unset($arrLoad[$value]);
        }

        // 执行加载
        foreach ($arrLoad as $value) {
            // 执行加载css资源
            foreach ($value['css'] as $css) {
                $view->registerCssFile(self::$assetsUrl.$css, $options);
            }

            // 执行加载javascript资源
            foreach ($value['javascript'] as $javascript) {
                $view->registerJsFile(self::$assetsUrl.$javascript, $options);
            }
        }

    }

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
