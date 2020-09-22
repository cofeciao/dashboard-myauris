<?php
/**
 * Created by PhpStorm.
 * User: abc
 * Date: 12/21/2019
 * Time: 2:02 PM
 */

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class DataTableAsset extends AssetBundle
{
    public $js = ['/dataTables/datatables.min.js'];
    public $jsOptions = [];


    public $css = ['/dataTables/datatables.min.css'];
    public $cssOptions;

    public $depends = [JqueryAsset::class];
}
