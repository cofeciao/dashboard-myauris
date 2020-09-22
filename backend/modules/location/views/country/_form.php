<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\location\models\Country */
/* @var $form yii\widgets\ActiveForm */
?>


    <?php $form = ActiveForm::begin(['id' => 'form-location-country']); ?>
    <div class="modal-body">
        <?= $form->field($model, 'CountryCode')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'CommonName')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'FormalName')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'CountryType')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'CountrySubType')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'Sovereignty')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'Capital')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'CurrencyCode')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'CurrencyName')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'TelephoneCode')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'CountryNumber')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'InternetCountryCode')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'Flags')->textInput(['maxlength' => true]) ?>

        <?php
        if (Yii::$app->controller->action->id == 'create') {
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

