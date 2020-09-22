<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\directsale\models\DirectSaleRemindCall */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="direct-sale-remind-call-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">
        <?= $form->field($model, 'customer_id')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'status_fail')->textInput() ?>

    <?= $form->field($model, 'dat_hen')->textInput() ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'remind_call_time')->textInput() ?>

    <?= $form->field($model, 'permission_user')->textInput() ?>

    <?= $form->field($model, 'remind_call_status')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

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

