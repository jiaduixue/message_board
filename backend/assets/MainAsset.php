<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class MainAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [ 
        'css/bootstrap.min.css-v=3.3.5.css',
        'css/font-awesome.min.css-v=4.4.0.css',
        'css/animate.min.css',
        'css/style.min.css-v=4.0.0.css',
    ];
    public $js = [
        'js/jquery.min.js-v=2.1.4.js',
        'js/bootstrap.min.js-v=3.3.5.js',
        'js/plugins/metisMenu/jquery.metisMenu.js',
        'js/plugins/slimscroll/jquery.slimscroll.min.js',
        'js/plugins/layer/layer.min.js',
        'js/hplus.min.js-v=4.0.0.js',
        ['js/contabs.min.js', 'type' => 'text/javascript'],
        'js/plugins/pace/pace.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
