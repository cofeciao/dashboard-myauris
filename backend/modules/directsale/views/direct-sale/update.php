<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 25-Mar-19
 * Time: 3:39 PM
 */

use backend\modules\user\models\User;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\phongkham\DirectSaleModel;
use dosamigos\datepicker\DatePicker;
use dosamigos\datetimepicker\DateTimePicker;

$user = new User();
$roleUser = $user->getRoleName(Yii::$app->user->id);

$name = $model->full_name == null ? $model->forename : $model->full_name;

$listStatusAccept = json_encode(array_keys($statusAccept));
if ($listStatusAccept == null) {
    $listStatusAccept = '{}';
}
?>
    <div class="modal-header bg-blue-grey bg-lighten-2 white">
        <h4 class="modal-title">Cập nhật thông tin khách hàng: <?= $name; ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php $form = ActiveForm::begin([
    'id' => 'form-update',
    'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    'validationUrl' => ['validate-update', 'id' => $model->id],
    'action' => Url::toRoute(['update-submit', 'id' => $model->id]),
]); ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
                <?= $form->field($model, 'full_name')->textInput(); ?>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
                <?= $form->field($model, 'birthday')->widget(DatePicker::class, [
                    'clientOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'autoclose' => true,
                    ],
                    'clientEvents' => [],
                    'options' => [
                        'class' => 'form-control',
                        'readonly' => 'readonly',
                    ]
                ]) ?>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
                <?= $form->field($model, 'customer_come')->widget(DateTimePicker::class, [
                    'clientOptions' => [
                        'format' => 'dd-mm-yyyy hh:ii',
                        'autoclose' => true,
                    ],
                    'clientEvents' => [],
                    'options' => [
                        'class' => 'form-control',
                        'readonly' => 'readonly',
                    ]
                ]) ?>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
                <?= $form->field($model, 'customer_come_time_to', [
                    'template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'
                ])->dropDownList(ArrayHelper::map(DirectSaleModel::getStatusCustomerGoToAuris(), 'id', 'name'), [
                    'class' => 'ui dropdown form-control',
                    'prompt' => 'Chọn trạng thái khách...',
                    'id' => 'dat-hen'
                ]); ?>
            </div>
            <div class="col-md-6 col-12" id="ly-do-khong-lam">
                <?= $form->field($model, 'ly_do_khong_lam')->dropDownList(Yii::$app->params['ly-do-khong-lam'], [
                    'class' => 'form-control dropdown ui search',
                    'prompt' => 'Lý do không làm...'
                ]) ?>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12" id="remind-call">
                <?php
                $clientOptions = [
                    'format' => 'dd-mm-yyyy hh:ii',
                    'autoclose' => true,
                    'todayHighlight' => true
                ];
                if ($roleUser != User::USER_DEVELOP && $roleUser != User::USER_ADMINISTRATOR) {
                    $clientOptions['startDate'] = '-0d';
                    $clientOptions['endDate'] = '+30d';
                }
                echo $form->field($model, 'remind_call_time')->widget(DateTimePicker::class, [
                    'clientOptions' => $clientOptions,
                    'clientEvents' => [],
                    'options' => [
                        'class' => 'form-control',
                        'readonly' => 'readonly',
                    ]
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-6 col-12">
                <?= $form->field($model, 'customer_mongmuon')->textarea(['rows' => 4]); ?>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-6 col-12">
                <?= $form->field($model, 'note_direct')->textarea(['rows' => 4])->label('Ghi chú'); ?>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-6 col-12">
                <?= $form->field($model, 'customer_huong_dieu_tri')->textarea(['rows' => 4]); ?>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-6 col-12" id="note">
                <?= $form->field($model, 'remind_call_note')->textarea(['rows' => 4]) ?>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
            'btn btn-default mr-1 close-update', 'data-pjax' => 0]) ?>
        <?= Html::submitButton(
                '<i class="fa fa-check-square-o"></i> Save',
                ['class' => 'btn btn-primary', 'data-pjax' => 0]
            ) ?>
    </div>
<?php ActiveForm::end(); ?>
<?php
$today = date('d-m-Y 00:00');
$script = <<< JS
var list_status_accept = $listStatusAccept,
    dat_hen = $('body').find('#dat-hen');
dat_hen.on('change', function(){
    var v = parseInt(($(this).val() || 0));
    if(v != 0 && list_status_accept.indexOf(v) === -1){
        $('#note, #ly-do-khong-lam').slideDown();
        $('#remind-call').slideDown().find('#directsalemodel-remind_call_time').val('$today');
    } else {
        $('#note, #remind-call').slideUp().find('input textarea').val('');
        $('#ly-do-khong-lam').slideUp();
    }
}).trigger('change');
JS;
$this->registerJs($script);
