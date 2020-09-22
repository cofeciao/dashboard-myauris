<?php

use yii\helpers\Url;
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
if (isset($readonly) && $readonly == false) {
    $readOnly = false;
}
$id = $model->id ?: '';

?>
<?php $form = ActiveForm::begin([
    'id' => 'campaignAjax',
    'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    'validationUrl' => Url::toRoute(['check-validation-abcampaign', 'id' => $id])
]); ?>
<div class="modal-body">
    <?= $form->field($model, 'campaign_id')->hiddenInput(['value' => $chienDich->id])->label(false) ?>

    <?= $form->field($model, 'btn_form')->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'readonly' => $readOnly]) ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'ky_thuat')->dropDownList(ArrayHelper::map(AbAddKythuat::getKyThuat(), 'id', 'name'), ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn loại kỹ thuật...', 'readonly' => $readOnly]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'link_test')->textInput(['maxlength' => true, 'readonly' => $readOnly]) ?>
        </div>
    </div>

    <?= $form->field($model, 'content')->textarea(['rows' => 4, 'readonly' => $readOnly]) ?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'chiphi_thucchay')->textInput(['maxlength' => true, 'class' => 'form-control on-keyup', 'readonly' => $readOnly]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'comment')->textInput(['maxlength' => true, 'class' => 'form-control on-keyup', 'readonly' => $readOnly]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'tin_nhan')->textInput(['maxlength' => true, 'class' => 'form-control on-keyup', 'readonly' => $readOnly]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'hien_thi')->textInput(['maxlength' => true, 'class' => 'form-control on-keyup', 'readonly' => $readOnly]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'tiep_can')->textInput(['maxlength' => true, 'class' => 'form-control on-keyup', 'readonly' => $readOnly]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'nguoi_xem_1')->textInput(['maxlength' => true, 'class' => 'form-control on-keyup', 'readonly' => $readOnly]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'nguoi_xem_50')->textInput(['maxlength' => true, 'class' => 'form-control on-keyup', 'readonly' => $readOnly]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'tan_suat')->textInput(['maxlength' => true, 'class' => 'form-control tan-suat', 'readonly' => $readOnly]) ?>
        </div>
    </div>
    <?php
        /*if(Yii::$app->user->can(\backend\modules\user\models\User::USER_DEVELOP)) {
            */?><!--
            <?/*= $form->field($model, 'btn_end_date')->checkbox(['template' => '
                <label class="square-checkbox">
                    {input}
                    <span></span>
                </label>
            ']) */?>
            --><?php
/*        }*/
    ?>

    <p class="red mt-1">(*)Nếu kết thúc chiến dịch này, bạn sẽ không thể thay đổi thông tin đã nhập.</p>
    <hr class="m-0">
</div>
<div class="modal-footer">
    <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
        'btn btn-warning mr-1']) ?>

    <?= Html::submitButton(
            '<i class="fa fa-check-square-o"></i> Save',
            ['class' => 'btn btn-primary mr-1 submit-form', 'value' => 'save']
        ) ?>

    <?= Html::submitButton(
            '<i class="fa fa-thumb-tack"></i>  Kết thúc *',
            ['class' => 'btn btn-danger mr-1 submit-form', 'value' => 'end', 'text-confirm' => 'Bạn có muốn kết thúc chiến dịch này?']
        ) ?>
    <?php
    if (Yii::$app->user->can(\backend\modules\user\models\User::USER_DEVELOP)) {
        echo Html::submitButton(
            '<i class="fa fa-thumb-tack"></i>  Mở lại *',
            ['class' => 'btn btn-success mr-1 submit-form', 'value' => 'restart', 'text-confirm' => 'Bạn muốn mở lại chiến dịch này?']
        );
    }
    ?>
</div>
<?php ActiveForm::end(); ?>

<?php
$tit = Yii::t('backend', 'Notification');

$script = <<< JS
$("body").find('.on-keyup').unbind('keyup').bind('keyup', function () {
    var order_discount  = $(this).val().replace(/[^0-9]/gi, '');
    order_discount = order_discount.replace(/\./g, '');
    if(order_discount.trim() != '')
        $(this).val(addCommas(parseInt(order_discount)));
});

$('body').find('.submit-form').unbind('click').bind('click', function(e) {
    let btn = $(this).val(),
        text_confirm = $(this).attr('text-confirm') || null;
    if(text_confirm !== null){
        if(!confirm(text_confirm)) return false;
    }
    $('#abcampaign-btn_form').val(btn);
});

$('body').find('form#campaignAjax').unbind('beforeSubmit').bind('beforeSubmit', function(e) {
    e.preventDefault();
    var currentUrl = $(location).attr('href');
    var formData = $('#campaignAjax').serialize();
    
    $('#campaignAjax').myLoading({opacity: true});
    
    $.ajax({
        url: $('#campaignAjax').attr('action'),
        type: 'POST',
        data: formData,
        dataType: 'json',
    })
    .done(function(res) {
        if (res.status == 200) {
            $.when($.pjax.reload({url: currentUrl, method: 'POST', container:'#campaign-ajax'})).done(function(){
                $('.modal-header').find('.close').trigger('click');
                toastr.success(res.mess, '$tit');
            });
        } else {
            toastr.error(res.mess, '$tit');
        }
    });
    
    return false;
});

$(document).ready(function(){
    $('body').find('.on-keyup').trigger('keyup');
    $('body').on('keyup', '.tan-suat', function(){
        var v = $(this).val().trim().replace(/\./g, ',');
        $(this).val(v);
    });
});
JS;

$this->registerJs($script);
?>

