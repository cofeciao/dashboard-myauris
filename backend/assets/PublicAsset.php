<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class PublicAsset extends AssetBundle
{
    public $css = [
        'https://fonts.googleapis.com/css?family=Muli:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i',
        '/vendors/css/extensions/bgvideo/video-js.min.css',
        '/vendors/css/extensions/bgvideo/bigvideo.css',
        '/css/app.css',
        '/css/core/menu/menu-types/vertical-menu.css',
        '/css/pages/coming-soon.css',
        '/vendors/css/extensions/toastr.css',
        '/css/plugins/extensions/toastr.css',
        'https://cdn.myauris.vn/assets/loading/myLoading.css',
        '/css/custom.css',
    ];
    public $js = [
        '/vendors/js/coming-soon/jquery.countdown.min.js',
        '/vendors/js/bgvideo/video.min.js',
        '/vendors/js/bgvideo/imagesloaded.pkgd.min.js',
        '/vendors/js/bgvideo/bigvideo.js',
        '/vendors/js/bgvideo/jquery.tubular.1.0.js',
        '/vendors/js/extensions/toastr.min.js',
        'https://cdn.myauris.vn/assets/loading/myLoading.js',
    ];

    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
