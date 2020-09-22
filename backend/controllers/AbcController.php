<?php

namespace backend\controllers;


use backend\components\MyController;
use tpmanc\imagick\Imagick;
use yii\helpers\Url;

class AbcController extends MyController
{
    public function actionIndex()
    {
        return phpinfo();
        $i = Url::to('@backend/web/images/1.png');
        $img = Imagick::open($i);
        var_dump($img->getWidth());
    }
}
