<?php

use yii\helpers\Url;
use yii\helpers\Html;

echo Html::img(Url::toRoute(['handle-image', 'w' => 320, 'h' => 480]), []);
