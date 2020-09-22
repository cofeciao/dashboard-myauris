<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php $form = ActiveForm::begin([
    'id' => 'create-customer-facebook',
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['/customer/customer-online/validate-customer-facebook', 'id' => $model->id]),
]); ?>

    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'face_customer')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'face_fanpage')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($model, 'face_post_id')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
            'btn btn-warning mr-1']) ?>
        <?= Html::submitButton(
                '<i class="fa fa-check-square-o"></i> Save',
                ['class' => 'btn btn-primary']
            ) ?>
    </div>
<?php ActiveForm::end() ?>
<?php
$script = <<< JS
$('body').find('#create-customer-facebook').unbind('beforeSubmit').bind('beforeSubmit', function(e) {
    e.preventDefault();
    var form = $(this),
        form_data = new FormData(form[0]);
    form.myLoading();
    $.ajax({
        type: 'POST',
        url: form.attr('action'),
        dataType: 'json',
        data: form_data,
        cache: false,
        processData: false,
        contentType: false
    }).done(function(res){
        form.myUnloading();
        if(res.code === 200){
            toastr.success(res.msg, 'Thông báo');
            $('#custom-modal .modal-header').find('button[data-dismiss=modal]').trigger('click');
        } else {
            console.log('save error', res);
            toastr.error(res.msg, 'Thông báo');
        }
    }).fail(function(err) {
        form.myUnloading();
        console.log('save fail', err);
        toastr.error('Lưu thất bại', 'Thông báo');
    });
    return false;
});
JS;
$this->registerJs($script);
