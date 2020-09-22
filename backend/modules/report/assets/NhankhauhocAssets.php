<?php
/**
 * Date: 11/6/19
 * Time: 11:05 AM
 */

namespace backend\modules\report\assets;

use backend\assets\AppAsset;
use backend\assets\ChartjsAsset;
use backend\assets\DataTableAsset;
use yii\web\AssetBundle;
use yii\web\View;

class NhankhauhocAssets extends AssetBundle
{
    public $js = [
//        '/vendors/js/ui/jquery-ui.min.js',
        'modules/report/js/nhankhauhocJs.js',
    ];

    public $jsOptions = ['position' => View::POS_END];

    public $css = [
        '/modules/report/css/nhankhauhocCss.css'
    ];

    public $cssOptions;

    public $depends = [AppAsset::class, ChartjsAsset::class, DataTableAsset::class];
}
