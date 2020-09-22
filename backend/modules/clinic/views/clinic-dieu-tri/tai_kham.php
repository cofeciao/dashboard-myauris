<?php
/* @var $customer backend\modules\clinic\models\Clinic */
/* @var $lichDieuTri backend\modules\clinic\models\PhongKhamLichDieuTri */

/* @var $name string */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

if(is_numeric($model->time_dieu_tri)) $model->time_dieu_tri = date('d-m-Y H:i', $model->time_dieu_tri);
?>
    <div class="modal-header bg-blue-grey bg-lighten-2 white">
        <h4 class="modal-title">Lịch tái khám: <?= $name; ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php $form = ActiveForm::begin([
    'id' => 'form-tai-kham',
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-tai-kham', 'customer_id' => $customer->id, 'lich_dieu_tri_id' => $lichDieuTri->id]),
]) ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
                <div class="form-group">
                    <label class="control-label">Khách hàng</label>
                    <?= Html::textInput('PhongKhamLichDieuTri[name]', $name, ['class' => 'form-control', 'readonly' => 'readonly']); ?>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
                <div class="form-group">
                    <label class="control-label">Số điện thoại</label>
                    <?= Html::textInput('PhongKhamLichDieuTri[phone]', $customer->phone, ['class' => 'form-control', 'readonly' => 'readonly']); ?>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
                <?= $form->field($model, 'customer_code')->textInput(['value' => $customer->customer_code, 'readonly' => 'readonly']); ?>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
                <?= $form->field($model, 'order_code')->textInput(['readonly' => 'readonly']); ?>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
                <?= $form->field($model, 'time_dieu_tri')->widget(\dosamigos\datetimepicker\DateTimePicker::class, [
                    'clientOptions' => [
                        'format' => 'dd-mm-yyyy hh:ii',
                        'autoclose' => true,
                        'todayHighlight' => true
                    ],
                    'clientEvents' => [
                    ],
                    'options' => [
                        'readonly' => 'readonly',
                        'class' => 'form-control'
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
            'btn btn-warning mr-1']) ?>
        <?= Html::submitButton(
            '<i class="fa fa-check-square-o"></i> Save',
            ['class' => 'btn btn-primary block-menu-left', 'data-pjax' => 0]
        ) ?>
    </div>
<?php ActiveForm::end() ?>
<?php
$script = <<< JS
var form = $('#form-tai-kham');
form.on('beforeSubmit', function(e){
    e.preventDefault();
    form.myLoading({opacity: true});
    var form_data = new FormData(form[0]),
        url = form.attr('action');
    $.ajax({
        type: 'POST',
        url: url,
        dataType: 'json',
        data: form_data,
        cache: false,
        processData: false,
        contentType: false
    }).done(res => {
        if(res.code === 200){
            toastr.success(res.msg);
            $.when($.pjax.reload({url: window.location.href, method: 'POST', container: clinic.options.pjaxId})).done(function(){
                $('#custom-modal .close').trigger('click');
            });
        } else {
            toastr.warning(res.msg);
            form.myUnloading();
        }
    }).fail(f => {
        console.log('error', f);
        toastr.error('Có lỗi xảy ra');
        form.myUnloading();
    });
    return false;
});
JS;
$this->registerJs($script);
