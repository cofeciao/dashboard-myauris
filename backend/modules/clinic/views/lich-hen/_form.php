<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 14-Jan-19
 * Time: 2:56 PM
 */

use backend\modules\user\models\User;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use backend\modules\clinic\models\Clinic;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use dosamigos\datetimepicker\DateTimePicker;

$idForm = 'clinic-update';
$isLetan = null;

if (Yii::$app->controller->action->id == 'create') {
    $varClass = 'create-customer';
    $idForm = 'clinic-create';
    $isLetan = 2;
}
if (Yii::$app->controller->action->id == 'create') {
    $model->customer_come = date('d-m-Y H:i', time());
}

if (Yii::$app->controller->action->id == 'render-and-update') {
    $model->customer_come = ($model->customer_come !== null && $model->customer_come !== 0) ? date('d-m-Y H:i', $model->customer_come) : '';
}
$user = new User();
$roleUser = $user->getRoleName(Yii::$app->user->id);
?>
<?php $form = ActiveForm::begin([
    'id' => $idForm,
    'class' => 'form form-horizontal',
    'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    'validationUrl' => Yii::$app->controller->action->id == 'create' ? Url::toRoute(['validate-create']) : Url::toRoute(['validate-render-and-update', 'id' => $model->getAttribute('id')]),
    'action' => Yii::$app->controller->action->id == 'create' ? Url::toRoute('create') : Url::toRoute(['render-and-update', 'id' => $model->getAttribute('id')]),
]); ?>
    <div class="modal-body">
        <div class="form-body">
            <h4 class="form-section"><i class="fa fa-eye"></i> Thông tin cá nhân</h4>
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-3 col-xs-6 col-6">
                    <?= $form->field($model, 'full_name')->textInput(['autocomplete' => 'off']); ?>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-xs-6 col-6">
                    <?= $form->field($model, 'forename')->textInput(['autocomplete' => 'off']); ?>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-xs-6 col-6">
                    <?= $form->field($model, 'phone')->textInput(['autocomplete' => 'off']); ?>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-xs-6 col-6">
                    <?= $form->field($model, 'sex')->dropDownList(Clinic::getSex(), ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn giới tính...']) ?>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-xs-6 col-6">
                    <label>Tỉnh thành</label>
                    <?= $form->field($model, 'province', [
                        'template' => '{input} {hint}{error}',
                    ])->dropDownList(Clinic::getProvince(), ['class' => 'ui dropdown search form-control', 'prompt' => 'Chọn tỉnh thành...']); ?>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-xs-6 col-6">
                    <label>Quận huyện</label>
                    <?= $form->field($model, 'district', [
                        'template' => '{input}
                                            {hint}{error}',
                    ])->dropDownList([], ['class' => 'ui dropdown search form-control', 'prompt' => 'Chọn Quận/Huyện...']); ?>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-xs-6 col-12">
                    <?= $form->field($model, 'address')->textInput(); ?>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-xs-6 col-6">
                    <?= $form->field($model, 'birthday')->widget(DatePicker::class, [
                        'clientOptions' => [
                            'format' => 'dd-mm-yyyy',
                            'autoclose' => true,
                            'endDate' => "+0d",
                        ],
                        'clientEvents' => [],
                    ]) ?>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-xs-6 col-6 dich-vu-content">
                    <?= $form->field($model, 'id_dich_vu', [
                        'template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span>{error}</div>'
                    ])->dropDownList(\backend\modules\customer\models\Dep365CustomerOnlineDichVu::getSanPhamDichVuArray(), [
                        'class' => 'form-control dropdown ui search',
                        'prompt' => 'Chọn dịch vụ...',
                        'id' => 'select-dich-vu'
                    ]) ?>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-xs-6 col-6">
                    <?php
                    $clientOptions = [
                        'format' => 'dd-mm-yyyy hh:ii',
                        'autoclose' => true,
                        'todayHighlight' => true
                    ];
                    if ($roleUser != User::USER_DEVELOP && $roleUser != User::USER_ADMINISTRATOR) {
                        $clientOptions['startDate'] = '-0d';
                        $clientOptions['endDate'] = '+4d';
                    }
                    echo $form->field($model, 'customer_come')->widget(DateTimePicker::class, [
                        'clientOptions' => $clientOptions,
                        'clientEvents' => [
                        ],
                        'options' => [
                            'readonly' => 'readonly',
                            'class' => 'form-control'
                        ]
                    ]) ?>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-xs-6 col-6">
                    <?= $form->field($model, 'dat_hen')->dropDownList(Clinic::getStatusDatHen(), ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn đặt hẹn...']); ?>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-xs-6 col-6">
                    <?php
                    $dr = \common\models\User::getNhanVienTuDirectSale($model->directsale);
                    $directsale = [];
                    if ($dr != null) {
                        foreach ($dr as $key => $item) {
                            $directsale[$item->id] = $item->userProfile->fullname;
                        }
                    }
                    echo $form->field($model, 'directsale')->dropDownList($directsale, ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn direct sale...']); ?>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-4 col-xs-6 col-6 customer-to-auris">
                    <?= $form->field($model, 'customer_come_time_to')->dropDownList(ArrayHelper::map(Clinic::getStatusCustomerGoToAuris($isLetan), 'id', 'name'), [
                        'class' => 'ui dropdown form-control',
                        'prompt' => 'Chọn trạng thái khách...',
                        'id' => 'customer-come-time-to'
                    ]); ?>
                </div>
                <div class="col-md-4 col-6 ly-do-khong-lam">
                    <?= $form->field($model, 'ly_do_khong_lam', [
                        'template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span>{error}</div>'
                    ])->dropDownList(Yii::$app->params['ly-do-khong-lam'], [
                        'class' => 'form-control dropdown ui search',
                        'prompt' => 'Lý do khách không làm...'
                    ]) ?>
                </div>
                <?php if (in_array($roleUser, [User::USER_DEVELOP, User::USER_ADMINISTRATOR])) { ?>
                    <div class="col-md-4 col-6 ngay-dong-y-lam">
                        <?= $form->field($model, 'ngay_dong_y_lam')->widget(DatePicker::class, [
                            'clientOptions' => [
                                'format' => 'dd-mm-yyyy',
                                'autoclose' => true,
                                'endDate' => "+0d",
                            ],
                            'clientEvents' => [],
                            'options' => [
                                'readonly' => 'readonly',
                                'class' => 'form-control'
                            ]
                        ]) ?>
                    </div>
                <?php } ?>
            </div>

            <h4 class="form-section"><i class="ft-user"></i> Khách hàng tới</h4>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-xs-6 col-12">
                    <?= $form->field($model, 'customer_thamkham')->textarea(['rows' => 4]); ?>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-xs-6 col-12">
                    <?= $form->field($model, 'customer_mongmuon')->textarea(['rows' => 4]); ?>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-xs-6 col-12">
                    <?= $form->field($model, 'note_direct')->textarea(['rows' => 4]); ?>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-6 col-xs-6 col-12">
                    <?= $form->field($model, 'customer_huong_dieu_tri')->textarea(['rows' => 4]); ?>
                </div>
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
$urlChangeProvince = Url::toRoute(['/clinic/clinic/get-district']);

$objAccept = json_encode($listAccept);
if ($objAccept == null) {
    $objAccept = '{}';
}

$currentDate = date('d-m-Y 00:00');

if (isset($model)) {
    $selected = $model->getAttribute('district');
} else {
    $selected = '';
}
$dathenCreate = Yii::$app->controller->action->id;
$dateBirthday = $model->getAttribute('birthday');
$tit = Yii::t('backend', 'Notification');
$script = <<< JS
    $('.ui.dropdown').dropdown({'forceSelection': false});
    
    var idDHStatus = $('#clinic-dat_hen').val(),
        idTinhThanh = $('#clinic-province').val(),
        listAccept = $objAccept,
        form_update = $('#clinic-update'),
        form_create = $('#clinic-create');
    if('$dathenCreate' == 'create') {
        $("#clinic-dat_hen").attr('disabled','disabled');
   }
    if(idTinhThanh.length == 0 || idTinhThanh.length == '97') {
            $("#clinic-district").attr('disabled','disabled');
    } else {
        $.ajax({
            url: '$urlChangeProvince',
            method: "POST",
            dataType: "text",
            data:{"id": idTinhThanh},
            success: function (data) {
                $("#clinic-district").empty();
                $("#clinic-district").append('<option>Chọn Quận/Huyện...</option>');
                $.each(JSON.parse(data), function (i, val) {
                    var opt  = "<option "+ (val.id == '$selected' ? 'selected' : '')+" value=\'" + val.id + "\' >" + val.name + "</option>";
                    $("#clinic-district").append(opt);
                });
                $("#clinic-district").removeAttr('disabled');
            }
        });
    };
    
    if(idDHStatus != '1') {
        $('.customer-to-auris').hide();
    }
    
    $('#clinic-dat_hen').on('change', function() {
        idDH = $(this).val();
        if(idDH == 1) {
            $('.customer-to-auris').slideDown(300);
        } else if(idDH == 2) {
            $('.customer-to-auris').slideUp(300);
        }
    });
    
    $('#clinic-province').on('change', function(){
        var id = $(this).val();

        if(id.length == 0 || id == 97) {
            $("#clinic-district").empty().append('<option>Chọn Quận/Huyện...</option>');
            $("#clinic-district").attr('disabled','disabled');
            $(".field-clinic-district .help-block").empty();
        }
        else {
            $.ajax({
                url: '$urlChangeProvince',
                method: "POST",
                dataType: "text",
                data:{"id": id},
                success: function (data) {
                    $("#clinic-district").empty();
                    $("#clinic-district").append('<option>Chọn Quận/Huyện...</option>');
                    $.each(JSON.parse(data), function (i, val) {
                        var opt  = "<option value=\'" + val.id + "\' >" + val.name + "</option>";
                        $("#clinic-district").append(opt);
                    });
                    $("#clinic-district").removeAttr('disabled');
                }
            });
        }
    });
    
    $('#clinic-customer_come_time_to').on('change', function(){
        var val = $(this).val() || null,
            idDH = $('#clinic-dat_hen').val() || null;
        if(Object.keys(listAccept).includes(val) || idDH == '2'){
            $('.ly-do-khong-lam').slideUp().find('#clinic-ly_do_khong_lam').children('option').prop('selected', false);
            $('.remind-call-time').slideUp().find('#clinic-remind_call_time').val('');
        } else {
            $('.ly-do-khong-lam, .remind-call-time').slideDown();
            $('.remind-call-time').find('#clinic-remind_call_time').val('$currentDate');
        }
    }).trigger('change');
    
    /*form_update.unbind('submit').bind('submit', function(e) {
        // Cập nhật khách hàng trong clinic
        e.preventDefault();
        var currentUrl = $(location).attr('href');
        var formData = form_update.serialize();
        
        form_update.myLoading({opacity: true});
        
        $.ajax({
            url: form_update.attr('action'),
            type: 'POST',
            dataType: 'json',
            data: formData,
        }).done(function(res) {
            if (res.status == 1) {
                $.when($.pjax.reload({url: currentUrl, method: 'POST', container: clinic.options.pjaxId})).done(function(){
                    $('.modal-header').find('.close').trigger('click');
                    toastr.success(res.result, '$tit');
                });
            } else {
                form_update.myUnloading();
                toastr.error(res.result, '$tit');
            }
        }).fail(function(err) {
            form_update.myUnloading();
            console.log('update fail', err);
        });
        return false;
    });*/
    
    form_create.unbind('submit').bind('submit', function(e) {
        e.preventDefault();
        var formData = form_create.serialize();
        
        form_create.myLoading({opacity: true});
        
        $.ajax({
            url: form_create.attr('action'),
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
                    form_create.myUnloading();
                    $('.modal-header').find('.close').trigger('click');
                }
            } else {
                form_create.myUnloading();
                toastr.error(res.result, 'Thông báo');
            }
        }).fail(function(err) {
            form_create.myUnloading();
            console.log('create fail', err);
        });
        return false;
    });
JS;
$this->registerJs($script, \yii\web\View::POS_END);
