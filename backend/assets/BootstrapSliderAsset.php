<?php
/**
 * Date: 11/8/19
 * Time: 9:32 AM
 */

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class BootstrapSliderAsset extends AssetBundle
{
    public $js = [
        'lib/bootstrap-slider/js/bootstrap-slider.min.js',
    ];
    public $jsOptions = [];


    public $css = [
        'lib/bootstrap-slider/css/bootstrap-slider.min.css',
    ];
    public $cssOptions;

    public $depends = [JqueryAsset::class];
}
