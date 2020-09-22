<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\location\models\search\ProvinceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="province-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'Name') ?>

    <?= $form->field($model, 'Type') ?>

    <?= $form->field($model, 'TelephoneCode') ?>

    <?= $form->field($model, 'ZipCode') ?>

    <?php // echo $form->field($model, 'CountryId')?>

    <?php // echo $form->field($model, 'CountryCode')?>

    <?php // echo $form->field($model, 'SortOrder')?>

    <?php // echo $form->field($model, 'IsPublished')?>

    <?php // echo $form->field($model, 'IsDeleted')?>

    <?php // echo $form->field($model, 'created_at')?>

    <?php // echo $form->field($model, 'updated_at')?>

    <?php // echo $form->field($model, 'created_by')?>

    <?php // echo $form->field($model, 'updated_by')?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
