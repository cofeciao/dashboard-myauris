<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i%7COpen+Sans:300,300i,400,400i,600,600i,700,700i',
        'https://cdn.myauris.vn/assets/loading/myLoading.css',
        'https://cdn.myauris.vn/assets/login/login.css',
        '/vendors/css/extensions/toastr.css',
        '/css/plugins/extensions/toastr.css'
    ];
    public $js = [
        'https://cdn.myauris.vn/assets/loading/myLoading.js',
        'https://cdn.myauris.vn/assets/login/utilities.js',
        'https://cdn.myauris.vn/assets/login/login.js',
        '/vendors/js/extensions/toastr.min.js',
    ];
    public $jsOptions = array(
        'position' => \yii\web\View::POS_END
    );
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
