<?php
namespace backend\modules\report\assets;

use backend\assets\AppAsset;
use backend\assets\ChartjsAsset;
use yii\web\AssetBundle;
use yii\web\View;

class ProductAssets extends AssetBundle
{
    public $js = [
        '//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js',
    ];

    public $jsOptions = ['position' => View::POS_END];

    public $css = [
    ];

    public $cssOptions;

    public $depends = [AppAsset::class, ChartjsAsset::class];
}
