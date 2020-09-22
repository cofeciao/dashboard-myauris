<?php

use yii\widgets\ActiveForm;
use dosamigos\datetimepicker\DateTimePicker;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\modules\clinic\models\PhongKhamDonHang */

// $this->title = $model->clinicHasOne->full_name == null ? $model->clinicHasOne->forename : $model->clinicHasOne->full_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Phòng khám'), 'url' => ['/clinic/clinic']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Đơn hàng'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="modal-header bg-blue-grey bg-lighten-2 white">
    <h4 class="modal-title"> Chăm sóc khách hàng <span> </span></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<?php $form = ActiveForm::begin([
    'id' => 'checkcode-bao-hanh',
    'class' => 'form form-horizontal',
    'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    // 'validationUrl' =>  Url::toRoute(['/clinic/clinic/validate-cham-soc']),
    'action' =>  Url::toRoute(['create-code-bao-hanh', 'id' => $model->customer_id]),
]); ?>
<div class="modal-body view-order-customer ">

    <div class="form-actions">
        <?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
        <?= $form->field($model, 'customer_id')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'warranty_code')->textInput(['maxlength' => true, 'disabled' => true]) ?>

        <?= $form->field($model, 'product_code')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'product_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'date_buy')->textInput() ?>

        <?= $form->field($model, 'warranty_time')->textInput() ?>

        <?= $form->field($model, 'co_so')->textInput() ?>

        <?= $form->field($model, 'co_so_name')->textInput(['maxlength' => true]) ?>

    </div>

    <div class="form-actions">
        <?= Html::resetButton('<i class="ft-x"></i> Cancel', ['class' =>
        'btn btn-warning mr-1']) ?>
        <?= Html::submitButton(
            '<i class="fa fa-check-square-o"></i> Save',
            ['class' => 'btn btn-primary']
        ) ?>
    </div>


</div>
<?php ActiveForm::end(); ?>
<div class="modal-footer p-0"></div>
</div>
<?php
$js = <<< JS
var form = $('#checkcode-bao-hanh');
form.on('beforeSubmit', function(e){
    e.preventDefault();
    var form_data = form.serialize(),
        url = form.attr('action');
        form.myLoading({opacity: true});
   
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form_data,
        dataType: 'json',
    }).done(function(res) {
        if (res.status == 1) {
            let pjaxId = typeof clinic !== 'undefined' && typeof clinic.options.pjaxId != "undefined" && $(clinic.options.pjaxId).length > 0 ? clinic.options.pjaxId : null;
            if(pjaxId !== null){
                $.when($.pjax.reload({url: window.location.href, method: 'POST', container: pjaxId})).done(function(){
                    $('.modal-header').find('.close').trigger('click');
                    toastr.success(res.result, 'Thông báo');
                });
            } else {
                toastr.success(res.result, 'Thông báo');
                form.myUnloading();
                $('.modal-header').find('.close').trigger('click');
            }
        } else {
            form.myUnloading();
            toastr.error(res.result, 'Thông báo');
        }
    })
    .fail(function(err){
        form.myUnloading();
            console.log('create fail', err);
    });
   
    return false;
})
JS;
$this->registerJs($js);
