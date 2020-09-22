<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\directsale\models\DirectSaleModel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="direct-sale-model-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">
        <?= $form->field($model, 'customer_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'forename')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'avatar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'sex')->dropDownList([ 2 => '2', 1 => '1', 0 => '0', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'birthday')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'agency_id')->textInput() ?>

    <?= $form->field($model, 'nguon_online')->textInput() ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'province')->textInput() ?>

    <?= $form->field($model, 'district')->textInput() ?>

    <?= $form->field($model, 'face_fanpage')->textInput() ?>

    <?= $form->field($model, 'face_post_id')->textInput() ?>

    <?= $form->field($model, 'face_customer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'note')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'directsale')->textInput() ?>

    <?= $form->field($model, 'note_direct')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'permission_user')->textInput() ?>

    <?= $form->field($model, 'per_inactivity')->textInput() ?>

    <?= $form->field($model, 'permission_old')->textInput() ?>

    <?= $form->field($model, 'tt_kh')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'ngaythang')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date_lichhen')->textInput() ?>

    <?= $form->field($model, 'time_lichhen')->textInput() ?>

    <?= $form->field($model, 'co_so')->textInput() ?>

    <?= $form->field($model, 'dat_hen')->textInput() ?>

    <?= $form->field($model, 'customer_come')->textInput() ?>

    <?= $form->field($model, 'customer_come_date')->textInput() ?>

    <?= $form->field($model, 'customer_come_time_to')->textInput() ?>

    <?= $form->field($model, 'customer_gen')->textInput() ?>

    <?= $form->field($model, 'customer_mongmuon')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'customer_thamkham')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'customer_huong_dieu_tri')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'customer_ghichu_bacsi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status_fail')->textInput() ?>

    <?= $form->field($model, 'is_customer_who')->textInput() ?>

    <?= $form->field($model, 'customer_direct_sale_checkthammy')->textInput() ?>

    <?= $form->field($model, 'customer_bacsi_check_final')->textInput() ?>

    <?= $form->field($model, 'customer_old')->textInput() ?>

    <?= $form->field($model, 'ngay_tao')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput() ?>

    <?= $form->field($model, 'updated_by')->textInput() ?>

    <?php  if (Yii::$app->controller->action->id == 'create') {
    $model->status = 1;
}
    ?>
    <?=  $form->field($model, 'status')->checkbox() ?>
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

