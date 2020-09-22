<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\testab\models\AbAddKythuat;

/* @var $this yii\web\View */
/* @var $model backend\modules\testab\models\AbCampaign */
/* @var $form yii\widgets\ActiveForm */
$readOnly = false;
if ($model->end_date != null) {
    $readOnly = true;
}
?>

<div class="ab-campaign-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'readonly' => $readOnly]) ?>

        <?= $form->field($model, 'ky_thuat')->dropDownList(ArrayHelper::map(AbAddKythuat::getKyThuat(), 'id', 'name'), ['prompt' => 'Chọn loại kỹ thuật...', 'readonly' => $readOnly]) ?>

        <?= $form->field($model, 'link_test')->textInput(['maxlength' => true, 'readonly' => $readOnly]) ?>

        <?= $form->field($model, 'content')->textarea(['rows' => 4, 'readonly' => $readOnly]) ?>

        <?= $form->field($model, 'chiphi_thucchay')->textInput(['maxlength' => true, 'class' => 'form-control on-keyup', 'readonly' => $readOnly]) ?>

        <?= $form->field($model, 'comment')->textInput(['maxlength' => true, 'class' => 'form-control on-keyup', 'readonly' => $readOnly]) ?>

        <?= $form->field($model, 'tin_nhan')->textInput(['maxlength' => true, 'readonly' => $readOnly]) ?>

        <?= $form->field($model, 'btn_form')->hiddenInput()->label(false) ?>

        <p class="red">(*)Nếu kết thúc chiến dịch này, bạn sẽ không thể thay đổi thông tin đã nhập.</p>
    </div>
    <div class="form-actions">
        <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
            'btn btn-warning mr-1']) ?>

        <?= Html::submitButton(
                '<i class="fa fa-check-square-o"></i> Save',
                ['class' => 'btn btn-primary mr-1 submit-form', 'value' => 'save']
            ) ?>

        <?= Html::submitButton(
                '<i class="fa fa-thumb-tack"></i>  Kết thúc *',
                ['class' => 'btn btn-danger submit-form', 'value' => 'end']
            ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$script = <<< JS
$("body").on('keyup', '.on-keyup', function () {
    var order_discount  = $(this).val().replace(/[^0-9]/gi, '');
    order_discount = order_discount.replace(/\./g, '');
    if(order_discount.trim() != '')
        $(this).val(addCommas(parseInt(order_discount)));
});

$('body').on('click', '.submit-form', function() {
    var btn = $(this).val();
    if(btn == 'end') {
        alert('Bạn có muốn kết thúc chiến dịch này?');
    }
    $('#abcampaign-btn_form').val(btn);
});

JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>

