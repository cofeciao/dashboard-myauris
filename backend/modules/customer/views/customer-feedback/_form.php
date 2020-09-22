<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\customer\models\CustomerFeedback */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-feedback-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">
        <?= $form->field($model, 'customer_id')->textInput([]) ?>

        <?= $form->field($model, 'feedback')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'created_at')->textInput() ?>

        <?php if (Yii::$app->controller->action->id == 'create') {
    $model->status = 1;
}
        ?>
        <?= $form->field($model, 'status')->checkbox() ?>
    </div>
    <div class="form-actions">
        <?= Html::resetButton('<i class="ft-x"></i> Cancel', ['class' =>
            'btn btn-warning mr-1']) ?>
        <?= Html::submitButton(
                '<i class="fa fa-check-square-o"></i> Save',
                ['class' => 'btn btn-primary']
            ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

