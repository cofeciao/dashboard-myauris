<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AuthAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/css/lib/ion-range-slider/ion.rangeSlider.css',
        '/css/lib/ion-range-slider/ion.rangeSlider.skinHTML5.css',
        '/css/separate/elements/player.min.css',
        '/css/separate/vendor/fancybox.min.css',
        '/css/separate/pages/profile-2.min.css',
        '/css/custom.css',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
