<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class DepositTemplateAsset extends AssetBundle
{
    public $css = [
        'https://fonts.googleapis.com/css?family=Montserrat:400,400i,500,500i,600,600i,700,700i|Roboto:400,500,700',
        '/css/app.css',
        '/css/pages/print-deposit.css',
    ];

    public $js = [

    ];

    public $jsOptions = array(
        'position' => \yii\web\View::POS_HEAD
    );

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
