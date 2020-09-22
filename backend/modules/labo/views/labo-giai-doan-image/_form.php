<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\labo\models\LaboGiaiDoanImage */
/* @var $form yii\widgets\ActiveForm */

//echo Yii::getAlias('@backend/web').'/uploads/tmp/';
?>

<div class="labo-giai-doan-image-form">

    <?php $form = ActiveForm::begin([
            'action' => Url::toRoute('labo-giai-doan-image/create'),
    ]); // ['options' => ['enctype' => 'multipart/form-data']] ?>
    <div class="form-actions">
        <?= $form->field($model, 'labo_giai_doan_id')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'image')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'google_id')->hiddenInput()->label(false) ?>

        <?php
        echo $form->field($model, 'imageFile')->fileInput();
        ?>

        <?php if (Yii::$app->controller->action->id == 'create')
            $model->status = 1;
        ?>
        <?= $form->field($model, 'status')->hiddenInput()->label(false) ?>
    </div>
    <div class="form-actions">
        <?= Html::resetButton('<i class="ft-x"></i> Cancel', ['class' =>
            'btn btn-warning mr-1']) ?>
        <?= Html::submitButton('<i class="fa fa-check-square-o"></i> Save',
            ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

