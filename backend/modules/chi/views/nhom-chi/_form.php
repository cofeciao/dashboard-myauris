<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\chi\models\DanhMucChi;

/* @var $this yii\web\View */
/* @var $model backend\modules\chi\models\NhomChi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="nhom-chi-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'category', [
            'template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>{error}'
        ])->dropDownList(ArrayHelper::map(DanhMucChi::getDanhMucChi(), 'id', 'name'), [
            'class' => 'form-control ui dropdown search',
            'prompt' => $model->getAttributeLabel('category')
        ]) ?>

        <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textarea(['maxlength' => true, 'id' => 'desc']) ?>

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
