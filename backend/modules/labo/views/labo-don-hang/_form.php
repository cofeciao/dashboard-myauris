<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\labo\models\LaboDonHang */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="labo-don-hang-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">
        <?= $form->field($model, 'bac_si_id')->textInput() ?>

        <?= $form->field($model, 'phong_kham_don_hang_id')->textInput() ?>

        <?= $form->field($model, 'ngay_nhan')->textInput() ?>

        <?= $form->field($model, 'ngay_giao')->textInput() ?>

        <?= $form->field($model, 'loai_phuc_hinh')->textInput() ?>

        <?= $form->field($model, 'loai_su')->textInput() ?>

        <?= $form->field($model, 'yeu_cau')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'trang_thai')->textInput() ?>

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

