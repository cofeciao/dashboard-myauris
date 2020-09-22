<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\chi\models\Deadline */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deadline-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">
        <?= $form->field($model, 'id_de_xuat_chi')->textInput() ?>

        <?= $form->field($model, 'thoi_gian_bat_dau')->textInput() ?>

        <?= $form->field($model, 'thoi_gian_ket_thuc')->textInput() ?>

        <?= $form->field($model, 'danh_gia')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'created_at')->textInput() ?>

        <?= $form->field($model, 'created_by')->textInput() ?>

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

