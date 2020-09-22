<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model backend\modules\testab\models\AbLocation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ab-location-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'mota')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'list_province')->dropDownList(ArrayHelper::map(\backend\modules\location\models\Province::getProvince(), 'id', 'name'), ['class' => 'form-control select2', 'multiple' => true, 'placeholder' => 'Chọn tỉnh trong khu vực']) ?>

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

