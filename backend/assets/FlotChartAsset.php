<?php
/**
 * Date: 11/6/19
 * Time: 11:52 AM
 */

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class FlotChartAsset extends AssetBundle
{
    public $js = [
        '/vendors/js/charts/flot/jquery.flot.min.js',
        '/vendors/js/charts/flot/jquery.flot.resize.js',
        '/vendors/js/charts/flot/jquery.flot.time.js',
        '/vendors/js/charts/flot/jquery.flot.selection.js',
        '/vendors/js/charts/flot/jquery.flot.symbol.js',
    ];

    public $jsOptions;

    public $css = [];

    public $cssOptions;

    public $depends = [JqueryAsset::class];

    public $publishOptions;
}
