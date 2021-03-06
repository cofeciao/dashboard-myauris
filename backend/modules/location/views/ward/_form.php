<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\location\models\District;
use backend\modules\location\models\Ward;

/* @var $this yii\web\View */
/* @var $model backend\modules\location\models\Ward */
/* @var $form yii\widgets\ActiveForm */
?>

    <?php $form = ActiveForm::begin(['id' => 'form-location-ward']); ?>
    <div class="modal-body">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'Type')->dropDownList(Ward::getWardType()) ?>

        <?= $form->field($model, 'DistrictID')->dropDownList(ArrayHelper::map(District::find()->all(), 'id', 'name'), ['class' => 'select2 form-control']) ?>

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

