<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\clinic\models\search\PhongKhamKhuyenMaiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="phong-kham-khuyen-mai-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'price') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'date_start') ?>

    <?php // echo $form->field($model, 'date_end')?>

    <?php // echo $form->field($model, 'description')?>

    <?php // echo $form->field($model, 'status')?>

    <?php // echo $form->field($model, 'created_by')?>

    <?php // echo $form->field($model, 'updated_by')?>

    <?php // echo $form->field($model, 'created_at')?>

    <?php // echo $form->field($model, 'updated_at')?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
