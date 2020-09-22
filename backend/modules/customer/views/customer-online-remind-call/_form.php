<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\CustomerModel;
use dosamigos\datepicker\DatePicker;
use dosamigos\datetimepicker\DateTimePicker;
use backend\modules\setting\models\Dep365CoSo;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineFailStatus;

if ($model->remind_call_time != null) {
    $model->remind_call_time = date('d-m-Y H:i', $model->remind_call_time);
}
$statusKBM = CustomerModel::STATUS_KBM;

$listStatusFail = Dep365CustomerOnlineFailStatus::getCustomerOnlineStatusFail();
$optionsStatusFail = [];
if (is_array($listStatusFail)) {
    foreach ($listStatusFail as $statusFail) {
        $optionsStatusFail[$statusFail->primaryKey] = [
            'date-nhaclich' => date('d-m-Y H:i', strtotime(date('d-m-Y') . ' +' . ($statusFail->time_nhaclich ?: 0) . 'days'))
        ];
    }
}
?>
<?php $form = ActiveForm::begin([
    'id' => 'form-customer-remind-call',
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validation-update', 'id' => $model->primaryKey]),
    'action' => Url::toRoute(['submit-update', 'id' => $model->primaryKey])
]); ?>
    <div class="modal-body">
        <?php if ($model->customerHasOne != null) { ?>
            <div class="customer-info">
                <div class="sub-panel">
                    <div class="sp-title">Thông tin khách hàng</div>
                    <div class="sp-content">
                        <div class="row">
                            <div class="col-6 c-col-1">
                                <div class="form-group c-group">
                                    <label class="control-label c-label">Tên:</label>
                                    <div class="c-info"><?= $model->customerHasOne->full_name != null ? $model->customerHasOne->full_name : $model->customerHasOne->name ?></div>
                                </div>
                                <div class="form-group c-group">
                                    <label class="control-label c-label">Ngày sinh:</label>
                                    <div class="c-info"><?= $model->customerHasOne->birthday != null ? $model->customerHasOne->birthday : '-' ?></div>
                                </div>
                                <div class="form-group c-group">
                                    <label class="control-label c-label">Trạng thái:</label>
                                    <div class="c-info"><?= $model->customerHasOne->statusCustomerHasOne != null ? $model->customerHasOne->statusCustomerHasOne->name : '-' ?></div>
                                </div>
                                <?php if ($model->customerHasOne->status == \backend\models\CustomerModel::STATUS_DH) { ?>
                                    <div class="form-group c-group">
                                        <label class="control-label c-label">Đặt hẹn:</label>
                                        <div class="c-info"><?= $model->customerHasOne->statusDatHenHasOne != null ? $model->customerHasOne->statusDatHenHasOne->name : '-' ?></div>
                                    </div>
                                <?php } ?>
                                <?php if ($model->customerHasOne->status == CustomerModel::STATUS_FAIL) { ?>
                                    <div class="form-group c-group">
                                        <label class="control-label c-label">Lý do fail:</label>
                                        <div class="c-info"><?= $model->customerHasOne->failStatusCustomerOnlineHasOne != null ? $model->customerHasOne->failStatusCustomerOnlineHasOne->name : '-' ?></div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-6 c-col-2">
                                <div class="form-group c-group">
                                    <label class="control-label c-label">Agency:</label>
                                    <div class="c-info"><?= $model->customerHasOne->agencyHasOne->name ?></div>
                                </div>
                                <div class="form-group c-group">
                                    <label class="control-label c-label">Nguồn trực tuyến:</label>
                                    <div class="c-info"><?= $model->customerHasOne->nguonCustomerOnlineHasOne->name ?></div>
                                </div>
                                <div class="form-group c-group">
                                    <label class="control-label c-label">Ghi chú nhắc lịch:</label>
                                    <div class="c-info"><?= $model->note != null ? trim($model->note) : '-' ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="change-remind-call">
            <?= $form->field($model, 'choose')->hiddenInput(['id' => 'choose'])->label(false) ?>
            <div class="sub-panel">
                <div class="sp-title">Cập nhật thông tin</div>
                <div class="sp-content">
                    <div class="remind-call-tab-choose">
                        <div class="mytabs mytabs-vertical col-12 p-0">
                            <ul class="mytab-title">
                                <li <?= $model->choose == 'doi-lich' ? 'class="active"' : '' ?>>
                                    <a href="#doi-lich" data-choose="doi-lich">Khách đặt hẹn lại</a>
                                </li>
                                <li <?= $model->choose == 'doi-nhac-lich' ? 'class="active"' : '' ?>>
                                    <a href="#doi-nhac-lich" data-choose="doi-nhac-lich">Dời nhắc lịch</a>
                                </li>
                                <li <?= $model->choose == 'tu-choi' ? 'class="active"' : '' ?>>
                                    <a href="#tu-choi" data-choose="tu-choi">Khách từ chối</a>
                                </li>
                            </ul>
                            <div class="mytab-content">
                                <div class="mytab-pane <?= $model->choose == 'doi-lich' ? 'active' : '' ?>"
                                     id="doi-lich">
                                    <div class="row">
                                        <div class="col-6">
                                            <?= $form->field($model, 'co_so')->dropDownList(ArrayHelper::map(Dep365CoSo::getCoSo(), 'id', 'name'), [
                                                'class' => 'form-control ui dropdown',
                                                'prompt' => 'Chọn cơ sở...'
                                            ]) ?>
                                        </div>
                                        <div class="col-6">
                                            <?= $form->field($model, 'time_lichhen')->widget(
                                                DateTimePicker::class,
                                                [
                                                    'clientOptions' => [
                                                        'autoclose' => true,
                                                        'todayHighlight' => true,
                                                        'format' => 'dd-mm-yyyy hh:ii',
                                                        'startDate' => '-0d'
                                                    ],
                                                    'options' => [
                                                        'autocomplete' => 'off',
                                                    ]
                                                ]
                                            ); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="mytab-pane <?= $model->choose == 'doi-nhac-lich' ? 'active' : '' ?>"
                                     id="doi-nhac-lich">
                                    <?php if ($model->status == CustomerModel::STATUS_KBM) { ?>
                                        <?= $form->field($model, 'status')->dropDownList([
                                            Dep365CustomerOnline::STATUS_KBM => 'KBM',
                                            Dep365CustomerOnline::STATUS_FAIL => 'Fail'
                                        ], [
                                            'class' => 'form-control dropdown ui',
                                            'prompt' => 'Chọn trạng thái...',
                                            'id' => 'field-status'
                                        ]) ?>
                                    <?php } ?>

                                    <?= $form->field($model, 'status_fail', [
                                        'template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>{error}'
                                    ])->dropDownList(ArrayHelper::map($listStatusFail, 'id', 'name'), [
                                        'class' => 'form-control dropdown ui',
                                        'prompt' => 'Lý do fail...',
                                        'id' => 'field-status-fail',
                                        'options' => $optionsStatusFail
                                    ]) ?>

                                    <?= $form->field($model, 'remind_call_time')->widget(
                                        DateTimePicker::class,
                                        [
                                            'clientOptions' => [
                                                'autoclose' => true,
                                                'todayHighlight' => true,
                                                'format' => 'dd-mm-yyyy hh:ii',
                                                'startDate' => '-0d'
                                            ],
                                            'options' => [
                                                'autocomplete' => 'off',
                                            ]
                                        ]
                                    ) ?>
                                    <?= $form->field($model, 'note')->textarea([]) ?>
                                </div>
                                <div class="mytab-pane <?= $model->choose == 'tu-choi' ? 'active' : '' ?>" id="tu-choi">
                                    <?= $form->field($model, 'reason_reject')->textarea(['rows' => 5]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
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
$today = date('d-m-Y H:i');
$script = <<< JS
var field_status = $('#field-status'),
    field_status_fail = $('#field-status-fail');
$('.mytab-title').on('click', 'a', function(e) {
    e.preventDefault();
    let choose = $(this).attr('data-choose') || 'doi-lich',
        reset = choose == 'doi-lich' ? 'tu-choi' : 'doi-lich';
    $('#choose').val(choose);
    $('#'+ reset).find('input, textarea').val('').closest('.form-group').removeClass('has-error').find('.help-block').text('');
    return true;
});
$('.ui.dropdown').dropdown();
field_status_fail.on('change', function(){
    var option = $(this).find('option:selected') || null,
        date_nhaclich = option == null || option.length <= 0 ? '$today' : option.attr('date-nhaclich');
    if([null, undefined].includes(date_nhaclich)) date_nhaclich = '$today';
    $('#customeronlineremindcall-remind_call_time').val(date_nhaclich);
});
field_status.on('change', function(){
    if($(this).val() == '$statusKBM'){
        $('.field-field-status-fail').slideUp();
    } else {
        $('.field-field-status-fail').slideDown();
    }
}).trigger('change');
JS;
$this->registerJs($script);
