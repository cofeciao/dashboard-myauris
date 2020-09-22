<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\location\models\search\WardSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ward-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'Type') ?>

    <?= $form->field($model, 'LatiLongTude') ?>

    <?= $form->field($model, 'DistrictID') ?>

    <?php // echo $form->field($model, 'SortOrder')?>

    <?php // echo $form->field($model, 'status')?>

    <?php // echo $form->field($model, 'IsDeleted')?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
