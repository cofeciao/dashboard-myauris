<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\chi\models\search\DeXuatChiSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="de-xuat-chi-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nguoi_trien_khai') ?>

    <?= $form->field($model, 'so_tien_chi') ?>

    <?= $form->field($model, 'thoi_han_thanh_toan') ?>

    <?php // echo $form->field($model, 'status')?>

    <?php // echo $form->field($model, 'leader_accept')?>

    <?php // echo $form->field($model, 'leader_accept_at')?>

    <?php // echo $form->field($model, 'accountant_accept')?>

    <?php // echo $form->field($model, 'accountant_accept_at')?>

    <?php // echo $form->field($model, 'created_at')?>

    <?php // echo $form->field($model, 'created_by')?>

    <?php // echo $form->field($model, 'updated_by')?>

    <?php // echo $form->field($model, 'updated_at')?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
