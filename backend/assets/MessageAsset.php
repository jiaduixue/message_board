<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class MessageAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [ 
        'css/bootstrap.min.css-v=3.3.5.css',
        'css/font-awesome.min.css-v=4.4.0.css',
        'css/plugins/morris/morris-0.4.3.min.css',
        'css/plugins/iCheck/custom.css',
        'js/plugins/gritter/jquery.gritter.css',
        'css/plugins/summernote/summernote.css',
        'css/plugins/summernote/summernote-bs3.css',
        'css/animate.min.css',
        'css/style.min.css-v=4.0.0.css',
    ];
    public $js = [
        'js/jquery.min.js-v=2.1.4.js',
        'js/bootstrap.min.js-v=3.3.5.js',
        'js/content.min.js-v=1.0.0.js',
        'js/plugins/iCheck/icheck.min.js',
        'js/plugins/summernote/summernote.min.js',
        'js/plugins/summernote/summernote-zh-CN.js'
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
