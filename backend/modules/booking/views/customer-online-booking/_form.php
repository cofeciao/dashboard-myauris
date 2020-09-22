<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin([
    'id' => 'form-customer-booking'
]); ?>
<div class="modal-body">
    <div class="form-group">
        <label class="control-label">Khách hàng</label>
        <?= Html::textInput('', ($model->userRegisterHasOne != null ? $model->userRegisterHasOne->name : null), ['class' => 'form-control', 'disabled' => '', 'readonly' => '']) ?>
    </div>

    <div class="form-group">
        <label class="control-label">Loại khách hàng</label>
        <?= Html::textInput('', \backend\modules\booking\models\CustomerOnlineBooking::CUSTOMER_TYPE[$model->customer_type], ['class' => 'form-control', 'disabled' => '', 'readonly' => '']) ?>
    </div>

    <?= $form->field($model, 'time_id')->textInput() ?>

    <?= $form->field($model, 'coso_id')->textInput() ?>

    <?= $form->field($model, 'booking_date')->textInput() ?>

    <?php if (Yii::$app->controller->action->id == 'create') {
    $model->status = 1;
}
    ?>
    <?= $form->field($model, 'status')->checkbox() ?>
</div>
<div class="modal-footer">
    <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
        'btn btn-warning mr-1']) ?>
    <?= Html::submitButton(
            '<i class="fa fa-check-square-o"></i> Save',
            ['class' => 'btn btn-primary']
        ) ?>
</div>
<?php ActiveForm::end(); ?>
