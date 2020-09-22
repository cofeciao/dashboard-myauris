<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\clinic\models\CheckcodeBaoHanh */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="checkcode-bao-hanh-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">
        <?= $form->field($model, 'customer_id')->textInput() ?>

        <?= $form->field($model, 'warranty_code')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'product_code')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'product_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'date_buy')->textInput() ?>

        <?= $form->field($model, 'warranty_time')->textInput() ?>

        <?= $form->field($model, 'co_so')->textInput() ?>

        <?= $form->field($model, 'co_so_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'status')->textInput() ?>

        <?= $form->field($model, 'created_at')->textInput() ?>

        <?= $form->field($model, 'created_by')->textInput() ?>

        <?= $form->field($model, 'updated_by')->textInput() ?>

        <?= $form->field($model, 'updated_at')->textInput() ?>

        <?php if (Yii::$app->controller->action->id == 'create')
            $model->status = 1;
        ?>
        <?= $form->field($model, 'status')->checkbox() ?>
    </div>
    <div class="form-actions">
        <?= Html::resetButton('<i class="ft-x"></i> Cancel', ['class' =>
            'btn btn-warning mr-1']) ?>
        <?= Html::submitButton('<i class="fa fa-check-square-o"></i> Save' ,
            ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

