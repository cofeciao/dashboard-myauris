<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\log\models\Dep365SendSms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="dep365-send-sms-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">
        <?= $form->field($model, 'customer_id')->textInput() ?>

    <?= $form->field($model, 'sms_uuid')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sms_text')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sms_to')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sms_time_send')->textInput() ?>

    <?= $form->field($model, 'sms_lanthu')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?php     if (Yii::$app->controller->action->id == 'create') {
    $model->status = 1;
}
    ?>
    <?=  $form->field($model, 'status')->checkbox() ?>
    </div>
    <div class="form-actions">
        <?= Html::resetButton('<i class="ft-x"></i> Reset', ['class' =>
        'btn btn-warning mr-1']) ?>
        <?= Html::submitButton(
            '<i class="fa fa-check-square-o"></i> Save',
            ['class' => 'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

