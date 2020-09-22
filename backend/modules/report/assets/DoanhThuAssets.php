<?php
/**
 * Date: 11/6/19
 * Time: 11:05 AM
 */

namespace backend\modules\report\assets;

use backend\assets\AppAsset;
use backend\assets\ChartjsAsset;
use yii\web\AssetBundle;
use yii\web\View;

class DoanhThuAssets extends AssetBundle
{
    public $js = [
//        '/vendors/js/ui/jquery-ui.min.js',
        'modules/report/js/doanhthuJs.js',
    ];

    public $jsOptions = ['position' => View::POS_END];

    public $css = [
        '/modules/report/css/doanhthuCss.css'
    ];

    public $cssOptions;

    public $depends = [AppAsset::class, ChartjsAsset::class];
}
