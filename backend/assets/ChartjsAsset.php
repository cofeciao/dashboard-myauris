<?php
/**
 * Date: 11/8/19
 * Time: 9:32 AM
 */

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class ChartjsAsset extends AssetBundle
{
    public $js = ['/vendors/js/charts/chart.min.js','/vendors/js/charts/chartist-plugin-datalabels.js',];
    public $jsOptions = [];


    public $css = [];
    public $cssOptions;

    public $depends = [JqueryAsset::class];
}
