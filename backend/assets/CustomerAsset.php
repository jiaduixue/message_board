<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class CustomerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap.min.css-v=3.3.5.css',
   
        'css/font-awesome.min.css-v=4.4.0.css',
        'css/plugins/bootstrap-table/bootstrap-table.min.css',
        'css/plugins/footable/footable.core.css',

        'css/plugins/iCheck/custom.css',
        'css/animate.min.css',
        'css/style.min.css-v=4.0.0.css',
        'css/plugins/toastr/toastr.min.css'
    ];
    public $js = [
        'js/jquery.min.js-v=2.1.4.js',
        'js/bootstrap.min.js-v=3.3.5.js',
        'js/plugins/peity/jquery.peity.min.js',
        'js/plugins/footable/footable.all.min.js',
        'js/content.min.js-v=1.0.0.js',
        'js/plugins/bootstrap-table/bootstrap-table.min.js',
        'js/plugins/bootstrap-table/bootstrap-table-mobile.min.js',
        'js/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.min.js',
        'js/demo/bootstrap-table-demo.min.js',
        'js/plugins/toastr/toastr.min.js',
        '../../../tajs.qq.com/stats-sId=9051096.js',
        'js/plugins/iCheck/icheck.min.js',
        'js/demo/peity-demo.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
}
