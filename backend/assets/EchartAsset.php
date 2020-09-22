<?php
/**
 * Date: 11/6/19
 * Time: 1:47 PM
 */

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;
use yii\web\View;

class EchartAsset extends AssetBundle
{
    public $js = ['/vendors/js/charts/echarts/echarts.js'];

    public $jsOptions = ['position' => View::POS_END];

    public $depends = [AppAsset::class, JqueryAsset::class];
}
