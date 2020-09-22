<?php

use yii\helpers\Html;

if (!isset($currentPage)) {
    $currentPage = 1;
}
?>

<div class="pull-right mr-2">
    <?= Html::input('text', 'go-to-page', ($currentPage ?: 1), ['class' => 'go-to-page'])  ?> / <?= floor($totalPage) ?>
</div>


