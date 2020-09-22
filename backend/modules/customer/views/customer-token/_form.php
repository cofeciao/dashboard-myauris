<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\customer\models\CustomerToken;

/* @var $this yii\web\View */
/* @var $model backend\modules\customer\models\CustomerToken */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-token-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">
        <?= $form->field($model, 'customer_id')->textInput() ?>

        <?= $form->field($model, 'token')->textInput(array_merge((Yii::$app->controller->action->id == 'update' ? [
            'readOnly' => true
        ] : []), ['maxlength' => true])) ?>

        <?= $form->field($model, 'type')->dropDownList(CustomerToken::TYPE, [
            'class' => 'dropdown ui search form-control'
        ]) ?>

        <?= $form->field($model, 'time')->input('number', [
            'min' => 0
        ]) ?>

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

