<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\toothstatus\models\LuaChonLoaiKyThuat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lua-chon-loai-ky-thuat-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

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

