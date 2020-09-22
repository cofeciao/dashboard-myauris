<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\booking\models\search\CustomerOnlineBookingSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-online-booking-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_register_id') ?>

    <?= $form->field($model, 'customer_type') ?>

    <?= $form->field($model, 'time_id') ?>

    <?= $form->field($model, 'coso_id') ?>

    <?php // echo $form->field($model, 'booking_date')?>

    <?php // echo $form->field($model, 'status')?>

    <?php // echo $form->field($model, 'created_by')?>

    <?php // echo $form->field($model, 'created_at')?>

    <?php // echo $form->field($model, 'updated_by')?>

    <?php // echo $form->field($model, 'updated_at')?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
