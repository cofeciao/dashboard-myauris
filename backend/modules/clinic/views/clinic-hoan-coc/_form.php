<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\clinic\models\PhongKhamDonHangWThanhToan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="phong-kham-don-hang-wthanh-toan-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">
        <?= $form->field($model, 'customer_id')->textInput() ?>

    <?= $form->field($model, 'phong_kham_don_hang_id')->textInput() ?>

    <?= $form->field($model, 'tien_thanh_toan')->textInput() ?>

    <?= $form->field($model, 'loai_thanh_toan')->textInput() ?>

    <?= $form->field($model, 'tam_ung')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?php  if (Yii::$app->controller->action->id == 'create') {
    $model->status = 1;
}
    ?>
    <?=  $form->field($model, 'status')->checkbox() ?>
    </div>
    <div class="form-actions">
        <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
        'btn btn-warning mr-1']) ?>
        <?= Html::submitButton(
            '<i class="fa fa-check-square-o"></i> Save',
            ['class' => 'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

