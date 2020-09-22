<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\modules\setting\models\Dep365CoSo;
use backend\modules\customer\models\Dep365CustomerOnlineNguon;
use backend\modules\customer\models\Dep365CustomerOnlineStatus;
use backend\modules\location\models\Province;
use yii\helpers\ArrayHelper;
use dosamigos\datepicker\DatePicker;
use dosamigos\datetimepicker\DateTimePicker;
use backend\modules\customer\models\Dep365CustomerOnlineFailStatus;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\user\models\User;
use backend\modules\customer\models\Dep365Agency;
use yii\helpers\Url;
use backend\modules\customer\models\Dep365CustomerOnlineDichVu;

/* @var $model Dep365CustomerOnline */

if ($model->time_lichhen != null && Yii::$app->controller->action->id != 'create') {
    $model->time_lichhen = date('d-m-Y H:i', $model->time_lichhen);
}
if ($model->remind_call_time != null && Yii::$app->controller->action->id != 'create') {
    $model->remind_call_time = date('d-m-Y H:i', $model->remind_call_time);
}
if ($model->customer_come != null || $model->customer_come == '0') {
    if ($model->customer_come != null) {
        $model->customer_come = date('d-m-Y H:i', $model->customer_come);
    }
    if ($model->customer_come == '0') {
        $model->customer_come = null;
    }
}
$user = new User();
$roleUser = $user->getRoleName(Yii::$app->user->id);

$listStatusFail = Dep365CustomerOnlineFailStatus::getCustomerOnlineStatusFail();
$optionsStatusFail = [];
if (is_array($listStatusFail)) {
    foreach ($listStatusFail as $statusFail) {
        $optionsStatusFail[$statusFail->primaryKey] = [
            'date-nhaclich' => date('d-m-Y', strtotime(date('d-m-Y') . ' +' . ($statusFail->time_nhaclich ?: 0) . 'days'))
        ];
    }
}
?>
<?php $form = ActiveForm::begin([
    'id' => 'create-customer-online',
    'enableAjaxValidation' => true,
    'validationUrl' => Yii::$app->controller->action->id == 'create' ? Url::toRoute('/customer/customer-online/validate-online') : Url::toRoute(['/customer/customer-online/validate-online', 'id' => $model->id]),
]); ?>

<div class="modal-body">

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'forename')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'birthday', [
                'template' => '{label}{input}{error}'
            ])->widget(
                DatePicker::class,
                [
                    'template' => '{input}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>{addon}',
                    'clientOptions' => [
                        'autoclose' => true,
                        'todayHighlight' => true,
                        'format' => 'dd-mm-yyyy',
                    ],
                    'options' => [
                        'autocomplete' => 'off',
                    ]
                ]
            ); ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'sex')->dropDownList(Dep365CustomerOnline::getSex(), ['class' => 'ui dropdown search form-control', 'prompt' => 'Chọn giới tính...']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'phone')->textInput() ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'province', [
                'template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>{error}'])
                ->dropDownList(ArrayHelper::map(Province::getProvince(), 'id', 'name'), [
                    'class' => 'ui dropdown search form-control',
                    'prompt' => 'Chọn tỉnh thành...'])
            ?>
        </div>
        <div class="col-md-3">
            <?php
            if (isset(Yii::$app->controller->action->id) && Yii::$app->controller->action->id == 'create') {
                ?>
                <?= $form->field($model, 'district')
                    ->dropDownList(ArrayHelper::map(['empty' => 'Empty string'], 'id', 'name'), [
                        'class' => 'ui dropdown search form-control',
                        'autofocus' => '', 'prompt' => 'Chọn Quận/Huyện...'])
                ?>
                <?php
            }
            ?>
            <?php
            if (isset(Yii::$app->controller->action->id) && Yii::$app->controller->action->id == 'update') {
                ?>
                <?= $form->field($model, 'district', ['template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>{error}'])
                    ->dropDownList(ArrayHelper::map($district, 'id', 'name'), [
                        'name' => 'Dep365CustomerOnline[district]',
                        'class' => 'ui dropdown search form-control',
                        'autofocus' => '', 'prompt' => 'Chọn Quận/Huyện...'])
                ?>
                <?php
            }
            ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'id_dich_vu')
                ->dropDownList(ArrayHelper::map(Dep365CustomerOnlineDichVu::find()->published()->all(), 'id', 'name'), [
                    'class' => 'ui dropdown search form-control',
                    'autofocus' => '', 'prompt' => 'Chọn dịch vụ...'])
            ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'agency_id', ['template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>{error}'])
                ->dropDownList(ArrayHelper::map(Dep365Agency::getAgency(), 'id', 'name'), [
                    'class' => 'ui dropdown search form-control',
                    'prompt' => 'Chọn Agency...'])
            ?>
        </div>
        <div class="col-md-4">
            <?php
            if (isset(Yii::$app->controller->action->id) && Yii::$app->controller->action->id == 'create') {
                ?>
                <?= $form->field($model, 'nguon_online', ['template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>{error}'])
                    ->dropDownList([], [
                        'class' => 'ui dropdown search form-control',
                        'prompt' => 'Chọn nguồn trực tuyến...',
                        'disabled' => true])
                ?>
                <?php
            }
            ?>
            <?php
            if (isset(Yii::$app->controller->action->id) && Yii::$app->controller->action->id == 'update') {
                ?>
                <?= $form->field($model, 'nguon_online', ['template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>{error}'])
                    ->dropDownList(ArrayHelper::map($agencyUpdate, 'id', 'name'), [
                        'class' => 'ui dropdown search form-control',
                        'autofocus' => '', 'prompt' => 'Chọn nguồn trực tuyến...'])
                ?>
                <?php
            }
            ?>
        </div>
    </div>

    <div class="nguon-online-slidedown">
        <div class="row">

            <div class="col-md-8">
                <?= $form->field($model, 'face_fanpage', ['template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>{error}'])
                    ->dropDownList(Dep365CustomerOnline::getFanpageFacebook(), [
                        'class' => 'ui dropdown search form-control',
                        'prompt' => 'Chọn page facebook...'])
                ?>
                <?= $form->field($model, 'face_post_id')->textInput(['maxlength' => true])->hiddenInput()->label(false) ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($model, 'face_customer')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'status', ['template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>{error}'])
                ->dropDownList(ArrayHelper::map(Dep365CustomerOnlineStatus::getStatusCustomerOnline(), 'id', 'name'), [
                    'class' => 'ui dropdown search form-control',
                    'prompt' => 'Chọn trạng thái khách hàng...'])
            ?>
        </div>
        <?php
        if (in_array($roleUser, [User::USER_DATHEN, User::USER_DEVELOP, User::USER_ADMINISTRATOR])) {
            ?>
            <div class="col-md-4" id="dat-hen">
                <?= $form->field($model, 'dat_hen', ['template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>{error}'])
                    ->dropDownList(Dep365CustomerOnline::getStatusDatHen(), [
                        'class' => 'ui dropdown search form-control',
                        'prompt' => 'Trạng thái khách đến...'])
                ?>
            </div>
            <div class="col-md-4" id="customer-come">
                <?php
                $clientOptions = [
                    'autoclose' => true,
                    'todayHighlight' => true,
                    'format' => 'dd-mm-yyyy hh:ii',
                ];
                if (!in_array($roleUser, [User::USER_DEVELOP, User::USER_ADMINISTRATOR])) {
                    $clientOptions['startDate'] = '-0d';
                    $clientOptions['endDate'] = '+4d';
                }
                echo $form->field($model, 'customer_come')->widget(
                    DateTimePicker::class,
                    [
                        'clientOptions' => $clientOptions,
                        'options' => [
                            'autocomplete' => 'off',
                        ]
                    ]
                ); ?>
            </div>
            <?php
        } ?>
        <div class="col-md-4" id="status-fail">
            <?= $form->field($model, 'status_fail', ['template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>{error}'])
                ->dropDownList(ArrayHelper::map($listStatusFail, 'id', 'name'), [
                    'class' => 'ui dropdown search form-control',
                    'prompt' => 'Chọn lý do fail...',
                    'options' => $optionsStatusFail
                ])
            ?>
        </div>
        <?php
        $_role_sales_online_dat_hen_khong_den = in_array($roleUser, [User::USER_NHANVIEN_ONLINE, User::USER_MANAGER_ONLINE]) && $model->primaryKey != null && $model->status == Dep365CustomerOnline::STATUS_DH && $model->dat_hen == Dep365CustomerOnline::DAT_HEN_KHONG_DEN;
        if (in_array($roleUser, [User::USER_DATHEN, User::USER_DEVELOP, User::USER_ADMINISTRATOR]) ||
            ($_role_sales_online_dat_hen_khong_den)) {
            ?>
            <?php if ($_role_sales_online_dat_hen_khong_den) { ?>
                <div class="col-md-4">
                    <?= Html::label($model->getAttributeLabel('dat_hen'), '', [
                        'class' => 'control-label'
                    ]) ?>
                    <?= Html::textInput('', $model->statusDatHenHasOne->name, [
                        'class' => 'form-control',
                        'disabled' => 'disabled',
                        'readonly' => 'readonly'
                    ]) ?>
                </div>
            <?php } ?>
            <div class="col-md-4" id="dat-hen-fail"
                 style="display: <?= $_role_sales_online_dat_hen_khong_den ? 'block' : 'none' ?>">
                <?= $form->field($model, 'dat_hen_fail', ['template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>{error}'])
                    ->dropDownList(ArrayHelper::map(\backend\modules\customer\models\Dep365CustomerOnlineFailDathen::getCustomerOnlineDatHenFail(), 'id', 'name'), [
                        'class' => 'ui dropdown search form-control',
                        'prompt' => 'Chọn lý do không đến...'
                    ])
                ?>
            </div>
            <?php
        }
        ?>
        <div class="col-md-4" id="co-so">
            <?= $form->field($model, 'co_so', ['template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>{error}'])
                ->dropDownList(ArrayHelper::map(Dep365CoSo::find()->published()->all(), 'id', 'name'), [
                    'class' => 'ui dropdown search form-control',
                    'prompt' => 'Chọn cơ sở...'
                ])
            ?>
        </div>
        <div class="col-md-4" id="time-lichhen">
            <?php
            $clientOptions = [
                'autoclose' => true,
                'todayHighlight' => true,
                'format' => 'dd-mm-yyyy hh:ii',
            ];
            if (!in_array($roleUser, [User::USER_DEVELOP, User::USER_ADMINISTRATOR])) {
                $clientOptions['startDate'] = '-0d';
            }
            echo $form->field($model, 'time_lichhen')->widget(
                DateTimePicker::class,
                [
                    'clientOptions' => $clientOptions,
                    'options' => [
                        'autocomplete' => 'off',
                    ]
                ]
            ); ?>
        </div>
        <div class="col-md-4" id="remind-call">
            <?php
            $clientOptions = [
                'autoclose' => true,
                'todayHighlight' => true,
                'format' => 'dd-mm-yyyy hh:ii',
            ];
            if (!in_array($roleUser, [User::USER_DEVELOP, User::USER_ADMINISTRATOR])) {
                $clientOptions['startDate'] = '-0d';
                $clientOptions['endDate'] = '+30d';
            }
            echo $form->field($model, 'remind_call_time')->widget(
                DateTimePicker::class,
                [
                    'template' => '{input}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>{addon}',
                    'clientOptions' => $clientOptions,
                    'options' => [
                        'autocomplete' => 'off',
                        'class' => 'ipt-remind-call',
                    ]
                ]
            ); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'nguoi_gioi_thieu')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-6" id="note-remind-call">
            <?= $form->field($model, 'note_remind_call')->textarea(['rows' => '4']) ?>
        </div>
        <div class="col-6" id="tt-kh">
            <?= $form->field($model, 'tt_kh')->textarea(['rows' => '4']) ?>
        </div>
        <?php /*<div class="col-6" id="not_tinh_trang_kh">
            <?= $form->field($model, 'note_tinh_trang_kh')->textarea(['rows' => 4]) ?>
        </div>
        <div class="col-6" id="note_mong_muon_kh">
            <?= $form->field($model, 'note_mong_muon_kh')->textarea(['rows' => 4]) ?>
        </div>*/ ?>
        <div class="col-6" id="note">
            <?= $form->field($model, 'note')->textarea(['rows' => 4]) ?>
        </div>
        <?php /*<div class="col-6" id="note_direct_sale_ho_tro">
            <?= $form->field($model, 'note_direct_sale_ho_tro')->textarea(['rows' => 4]) ?>
        </div>*/ ?>
    </div>
    <div class="row">
        <div class="col-12">
            <?= $form->field($model, 'phoneConfirm')->checkbox(['name' => 'Dep365CustomerOnline[phoneConfirm]', 'id' => 'dep365customeronline-phoneConfirm']); ?>
        </div>
    </div>
    <?php if (in_array($roleUser, [User::USER_DEVELOP, User::USER_ADMINISTRATOR]) && $model->primaryKey != null) { ?>
        <div class="row">
            <div class="col-6">
                <?= $form->field($model, 'permission_user')->dropDownList(User::getNhanVienOnline(), [
                    'class' => 'form-control ui dropdown search'
                ]) ?>
            </div>
        </div>
    <?php } ?>
</div>
<div class="modal-footer">
    <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
        'btn btn-warning mr-1']) ?>
    <?= Html::submitButton(
        '<i class="fa fa-check-square-o"></i> Save',
        ['class' => 'btn btn-primary btn-submit']
    ) ?>
</div>

<?php ActiveForm::end(); ?>
<?php
$this->registerCss('
#dat-hen, #customer-come, #status-fail, #dat-hen-fail, #co-so, #time-lichhen, #remind-call, #note-remind-call, #tt-kh {
    display: none;
}
');
$urlChangeProvince = Url::toRoute(['/customer/customer-online/get-district']);
$urlChangeAgency = Url::toRoute(['/customer/customer-online/get-nguon-online']);
$statusKBM = \backend\models\CustomerModel::STATUS_KBM;
$statusDH = \backend\models\CustomerModel::STATUS_DH;
$statusFail = \backend\models\CustomerModel::STATUS_FAIL;
$dat_hen_den = Dep365CustomerOnline::DAT_HEN_DEN;
$dat_hen_khong_den = Dep365CustomerOnline::DAT_HEN_KHONG_DEN;
$today = date('d-m-Y');
$script = <<< JS
$('.ui.dropdown').dropdown();

var statusKBM = '$statusKBM',
    statusDH = '$statusDH',
    statusFail = '$statusFail',
    dat_hen_den = '$dat_hen_den',
    dat_hen_khong_den = '$dat_hen_khong_den',
    agency = $('#dep365customeronline-agency_id').val(),
    idTinhThanh = $('#dep365customeronline-province').val(),
    idFanpage = $('#dep365customeronline-nguon_online').val(),
    form = $('body').find('#create-customer-online');
function setVisible(onchange = true){
    let status = $('#dep365customeronline-status').val() || null,
        dat_hen = $('#dep365customeronline-dat_hen').val() || null;
    if(status == statusDH){
        $('#status-fail, #remind-call, #note-remind-call').hide();
        $('#dat-hen, #co-so, #time-lichhen, #not_tinh_trang_kh, #note_mong_muon_kh, #note, #note_direct_sale_ho_tro').show();
        if(dat_hen != null){
            if(dat_hen == dat_hen_den){
                $('#dat-hen-fail, #remind-call, #note-remind-call').hide();
                $('#customer-come, #tt-kh').show();
            } else if(dat_hen == dat_hen_khong_den) {
                $('#customer-come').hide();
                $('#dat-hen-fail, #remind-call, #note-remind-call').show();
            } else {
                $('#status-fail').hide();
                $('#dat-hen-fail, #remind-call, #note-remind-call').hide();
            }
        }
    } else if(status == statusFail) {
        $('#dat-hen, #dat-hen-fail, #co-so, #customer-come, #time-lichhen, #not_tinh_trang_kh, #note_mong_muon_kh, #note_direct_sale_ho_tro').hide();
        // $('#dep365customeronline-note_tinh_trang_kh, #dep365customeronline-note_mong_muon_kh, #dep365customeronline-note_direct_sale_ho_tro').val('');
        $('#status-fail, #remind-call, #note, #note-remind-call').show();
    } else if(status == statusKBM) {
        $('#dat-hen, #dat-hen-fail, #co-so, #customer-come, #time-lichhen, #status-fail, #note-remind-call, #not_tinh_trang_kh, #note_mong_muon_kh, #note, #note_direct_sale_ho_tro').hide();
        // $('#dep365customeronline-note_tinh_trang_kh, #dep365customeronline-note_mong_muon_kh, #dep365customeronline-note, #dep365customeronline-note_direct_sale_ho_tro').val('');
        $('#remind-call, #note-remind-call').show();
        if(onchange == true) $('#dep365customeronline-remind_call_time').val('$today 00:00');
    } else {
        $('#customer-come, #time-lichhen, #dat-hen, #dat-hen-fail, #status-fail, #co-so, #remind-call, #note-remind-call').hide();
    }
}
setVisible(false);
    if(agency == '') {
        $("#dep365customeronline-nguon_online").attr('disabled','disabled');
    }
    if(idFanpage == 1) {
        $('.nguon-online-slidedown').slideDown();
    }
    if(idTinhThanh.length == 0) {
        $("#dep365customeronline-district").attr('disabled','disabled');
    };
    
    $('#dep365customeronline-agency_id').on('change', function(){
        $('.nguon-online-slidedown').slideUp();
        var idAgency = $(this).val();
        $.ajax({
            url: '$urlChangeAgency',
            method: "POST",
            dataType: "json",
            data:{"idAgency": idAgency},
            success: function (data) {
                // console.log(data);
                $("#dep365customeronline-nguon_online").empty();
                $("#dep365customeronline-nguon_online").append('<option>Chọn nguồn khách hàng...</option>');
                $.each(data, function (i, val) {
                    var opt  = "<option value=\'" + val.id + "\' >" + val.name + "</option>";
                    $("#dep365customeronline-nguon_online").append(opt);
                });
                $("#dep365customeronline-nguon_online").removeAttr('disabled').closest('.ui.dropdown').removeClass('disabled');
            }
        });
    });
    
    $('#dep365customeronline-province').on('change', function(){
        var id = $(this).val() || null;

        if(id == null || id.length == 0 || id == 97) {
            $("#dep365customeronline-district").empty();
            $("#dep365customeronline-district").append('<option>Chọn Quận/Huyện...</option>');
            $("#dep365customeronline-district").attr('disabled','disabled');
            $(".field-dep365customeronline-district .help-block").empty();
            $(".field-dep365customeronline-phone .help-block").empty();
        }
        else {
            $.ajax({
                url: '$urlChangeProvince',
                method: "POST",
                dataType: "text",
                data:{"id": id},
                success: function (data) {
                    try{
                        data = JSON.parse(data);
                    } catch (e) {
                        data = {};
                    }
                    var options = '<option>Chọn Quận/Huyện...</option>' + data.map(function(obj){
                        return '<option value="' + obj.id + '">' + obj.name + '</option>';
                    }).join("");
                    $("#dep365customeronline-district").html(options);
                    $("#dep365customeronline-district").removeAttr('disabled').closest('.ui.dropdown').removeClass('disabled');
                }
            });
        }
    });
    $('#dep365customeronline-nguon_online').on('change', function(){
        var id = $(this).val();
        if(id == '1') {
            $('.nguon-online-slidedown').slideDown();
        } else {
            $('.nguon-online-slidedown').slideUp();
        }
    });
    $('#dep365customeronline-status, #dep365customeronline-dat_hen').on('change', function() {
        setVisible();
    });
    $('#dep365customeronline-status_fail').on('change', function(){
        var option = $(this).find('option:selected') || null,
            date_nhaclich = option == null || option.length <= 0 ? '$today' : option.attr('date-nhaclich');
        if([null, undefined].includes(date_nhaclich)) date_nhaclich = '$today';
        $('#dep365customeronline-remind_call_time').val(date_nhaclich + ' 00:00');
    });
    form.on('click', '.btn-submit:not(.disabled)', function(){
        $(this).addClass('disabled').attr('disabled', 'disabled');
        $('#create-customer-online').submit();
    });
    form.on('afterValidate', function(event, messages, errorAttributes){
        if(typeof errorAttributes === 'object' && errorAttributes.length > 0) form.find('.btn-submit').removeClass('disabled').removeAttr('disabled');
    });
    form.on('beforeSubmit', function (e) {
        e.preventDefault();
        var form = $(this),
            formData = form.serialize();
        form.myLoading({
            fixed: true,
            opacity: true
        });
    
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            dataType: 'json',
            data: formData,
        }).done(function (res) {
            if (res.status == 200) {
                var pjaxId = typeof customerOnline == 'undefined' || [null, undefined].includes(typeof customerOnline.options.pjaxId) || $(customerOnline.options.pjaxId).length <= 0 ? null : customerOnline.options.pjaxId;
                if (pjaxId !== null) {
                    $.when($.pjax.reload({
                        url: window.location.href,
                        method: 'POST',
                        container: pjaxId
                    })).done(function () {
                        toastr.success(res.mess, 'Thông báo');
                        form.myUnloading();
                        $('.modal-header').find('.close').trigger('click');
                        $('.modal-content').html('');
                    });
                } else {
                    toastr.success(res.mess, 'Thông báo');
                    form.myUnloading();
                    $('.modal-header').find('.close').trigger('click');
                }
            } else {
                form.myUnloading();
                toastr.error(res.mess, 'Thông báo');
                form.find('.btn-submit').removeClass('disabled').removeAttr('disabled');
            }
        }).fail(function (err) {
            form.myUnloading();
            console.log('error submit form #create-customer-online', err);
            toastr.error('Có lỗi xảy ra!', 'Thông báo');
            form.find('.btn-submit').removeClass('disabled').removeAttr('disabled');
        });
    
        return false;
    });
JS;

$this->registerJs($script);
?>

<script>
    // Nghia 13-1-2019 , them vao trong bị loi \n
    /*$('#dep365customeronline-note, #dep365customeronline-note_tinh_trang_kh, #dep365customeronline-note_mong_muon_kh, #dep365customeronline-note_direct_sale_ho_tro').keyup(function (e) {
        var code = e.which;
        var temp = this.value;
        var count = (temp.match(/\n/g) || []).length;
        if (code == 13) {
            count++;
            this.value += count + ". ";
        }
        if (count == 0 && temp.length == 1) {
            this.value = "1. " + this.value;
        }
    });*/
</script>
