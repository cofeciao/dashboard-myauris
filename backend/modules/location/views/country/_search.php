<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\location\models\search\CountrySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="country-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'CountryCode') ?>

    <?= $form->field($model, 'CommonName') ?>

    <?= $form->field($model, 'FormalName') ?>

    <?= $form->field($model, 'CountryType') ?>

    <?php // echo $form->field($model, 'CountrySubType')?>

    <?php // echo $form->field($model, 'Sovereignty')?>

    <?php // echo $form->field($model, 'Capital')?>

    <?php // echo $form->field($model, 'CurrencyCode')?>

    <?php // echo $form->field($model, 'CurrencyName')?>

    <?php // echo $form->field($model, 'TelephoneCode')?>

    <?php // echo $form->field($model, 'CountryCode3')?>

    <?php // echo $form->field($model, 'CountryNumber')?>

    <?php // echo $form->field($model, 'InternetCountryCode')?>

    <?php // echo $form->field($model, 'SortOrder')?>

    <?php // echo $form->field($model, 'status')?>

    <?php // echo $form->field($model, 'Flags')?>

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
