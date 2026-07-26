<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class SystemAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [ 
        'css/bootstrap.min.css-v=3.3.5.css',
        'css/font-awesome.min.css-v=4.4.0.css',
        'css/plugins/morris/morris-0.4.3.min.css',
        'js/plugins/gritter/jquery.gritter.css',
        'css/animate.min.css',
        'css/style.min.css-v=4.0.0.css',
        'css/plugins/iCheck/custom.css',

        'css/plugins/toastr/toastr.min.css'
    ];
    public $js = [
        'js/jquery.min.js-v=2.1.4.js',
        'js/bootstrap.min.js-v=3.3.5.js',
        'js/plugins/bootstrap-table/bootstrap-table.min.js',
        'js/plugins/bootstrap-table/bootstrap-table-mobile.min.js',
        'js/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.min.js',
        'js/plugins/flot/jquery.flot.js',
        'js/plugins/flot/jquery.flot.tooltip.min.js',
        'js/plugins/flot/jquery.flot.spline.js',
        'js/plugins/flot/jquery.flot.resize.js',
        'js/plugins/flot/jquery.flot.pie.js',
        'js/plugins/flot/jquery.flot.symbol.js',
        'js/plugins/flot/curvedLines.js',
        'js/plugins/peity/jquery.peity.min.js',
        'js/demo/peity-demo.min.js',
        'js/content.min.js-v=1.0.0.js',
        'js/plugins/jquery-ui/jquery-ui.min.js',
        'js/plugins/gritter/jquery.gritter.min.js',
        'js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
        'js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',
        'js/plugins/slimscroll/jquery.slimscroll.min.js',
        'js/plugins/easypiechart/jquery.easypiechart.js',
        'js/plugins/sparkline/jquery.sparkline.min.js',
        'js/plugins/toastr/toastr.min.js',
        'js/demo/sparkline-demo.min.js',
        'js/plugins/iCheck/icheck.min.js',

        'js/plugins/layer/layer.min.js',
        'js/content.min.js',
        'js/welcome.min.js',
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
