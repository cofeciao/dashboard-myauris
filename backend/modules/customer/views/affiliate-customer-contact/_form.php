<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

if ($model->customer_code != null && $model->customerHasOne != null) {
    $model->customer = $model->customerHasOne->full_name == null ? $model->customerHasOne->name : $model->customerHasOne->full_name;
}
?>

<div class="affiliate-customer-contact-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'note')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'customer')->textInput(['maxlength' => true, 'readonly' => true]) ?>

        <?php if (Yii::$app->controller->action->id == 'create') {
    $model->status = 1;
}
        ?>
        <?= $form->field($model, 'status')->checkbox() ?>
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

