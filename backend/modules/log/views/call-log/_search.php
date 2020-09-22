<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\log\models\search\CallLogSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="call-log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'call_id') ?>

    <?= $form->field($model, 'call_den_di') ?>

    <?= $form->field($model, 'call_status') ?>

    <?= $form->field($model, 'call_time') ?>

    <?php // echo $form->field($model, 'call_time_start')?>

    <?php // echo $form->field($model, 'user_id')?>

    <?php // echo $form->field($model, 'phone')?>

    <?php // echo $form->field($model, 'customer_id')?>

    <?php // echo $form->field($model, 'call_source')?>

    <?php // echo $form->field($model, 'created_at')?>

    <?php // echo $form->field($model, 'updated_at')?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
