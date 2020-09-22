<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use backend\modules\clinic\models\Clinic;
use yii\helpers\Url;
use backend\modules\user\models\User;
use yii\helpers\ArrayHelper;

$css = <<< CSS

CSS;
$this->registerCss($css);
$listNhanVienCSKH =   ArrayHelper::map(User::getUsersByRoles([User::USER_DATHEN]), 'id', 'fullname');

?>
<div class="modal-header bg-blue-grey bg-lighten-2 white">
    <h4 class="modal-title"> Chăm sóc khách hàng <span> </span></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<?php $form = ActiveForm::begin([
    'id' => 'cham-soc',
    'class' => 'form form-horizontal',
    'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    'validationUrl' =>  Url::toRoute(['/clinic/clinic/validate-cham-soc']),
    'action' =>  Url::toRoute(['/clinic/clinic/cham-soc', 'id' => $model->customer_id]),
]); ?>

<div class="modal-body">
    <div class="form-body">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-6">
                <?= $form->field($model, 'user_id', [
                    'template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span>{error}</div>'
                ])->dropDownList($listNhanVienCSKH, [
                    'class' => 'form-control dropdown ui search',
                    'prompt' => 'Chọn nhân viên'
                ]) ?>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-6">
                <?= $form->field($model, 'status', [
                    'template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span>{error}</div>'
                ])->dropDownList($model::getListStatusCSKH(), [
                    'class' => 'form-control dropdown ui search',
                    'prompt' => 'Trạng thái'
                ]) ?>
            </div>

            <?= $form->field($model, 'customer_id')->hiddenInput()->label(false) ?>

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
<?php ActiveForm::end(); ?>
<?php

$script = <<< JS
var form_cham_soc = $('#cham-soc');
form_cham_soc.on('beforeSubmit', function(e) {
        e.preventDefault();
        var formData = form_cham_soc.serialize();
        
        form_cham_soc.myLoading({opacity: true});
        
        $.ajax({
            url: form_cham_soc.attr('action'),
            type: 'POST',
            dataType: 'json',
            data: formData,
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
                    form_cham_soc.myUnloading();
                    $('.modal-header').find('.close').trigger('click');
                }
            } else {
                form_cham_soc.myUnloading();
                toastr.error(res.result, 'Thông báo');
            }
        }).fail(function(err) {
            form_cham_soc.myUnloading();
            console.log('create fail', err);
        });
        return false;
    });
JS;
$this->registerJs($script);
