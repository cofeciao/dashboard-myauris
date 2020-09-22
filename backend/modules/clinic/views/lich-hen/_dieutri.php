<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23-Jan-19
 * Time: 2:14 PM
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\clinic\models\Clinic;

?>
<div class="modal-header bg-blue-grey bg-lighten-2 white">
    <h4 class="modal-title">Thêm lịch điều trị</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php $form = ActiveForm::begin([
    'id' => 'form-dieu-tri',
    'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    'validationUrl' => ['validate-dieu-tri', 'id' => $customer->id],
    'action' => Url::toRoute(['dieu-tri', 'id' => $customer->id]),
]); ?>
    <div class="modal-body">
        <?php
        if ($customer) {
            $name = $customer->full_name == null ? $customer->forename : $customer->full_name;
            echo $form->field($model, 'customer_id')->hiddenInput(['value' => $customer->id])->label(false);
        }
        if ($model->time_dieu_tri != null) {
            $model->time_dieu_tri = date('d-m-Y', $model->time_dieu_tri);
        }
        if ($model->time_start != null) {
            $model->time_start = date('d-m-Y H:i', $model->time_start);
        }
        if ($model->time_end != null) {
            $model->time_end = date('d-m-Y H:i', $model->time_end);
        }
        ?>
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
                <?= $form->field($model, 'order_code')->textInput(['value' => $order->order_code, 'readonly' => 'readonly']); ?>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
                <?= $form->field($model, 'ekip')->dropDownList(Clinic::getEkipbacsi(), ['class' => 'ui dropdown search form-control', 'prompt' => 'Chọn ekip bác sĩ...', 'style' => 'width: 100%']); ?>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
                <?= $form->field($model, 'time_dieu_tri')->widget(\dosamigos\datepicker\DatePicker::class, [
                    'clientOptions' => [
                        'format' => 'dd-mm-yyyy',
                        'autoclose' => true,
                        'todayHighlight' => true,
                    ],
                    'clientEvents' => [],
                    'options' => [
                        'autocomplete' => 'off'
                    ]
                ]) ?>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
                <?= $form->field($model, 'time_start')->widget(\dosamigos\datetimepicker\DateTimePicker::class, [
                    'clientOptions' => [
                        'format' => 'dd-mm-yyyy hh:ii',
                        'autoclose' => true,
                        'endDate' => '+1d',
                        'autocomplete' => 'off',
                    ],
                    'clientEvents' => [],
                    'options' => [
                        'autocomplete' => 'off'
                    ]
                ]) ?>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
                <?= $form->field($model, 'time_end')->widget(\dosamigos\datetimepicker\DateTimePicker::class, [
                    'clientOptions' => [
                        'format' => 'dd-mm-yyyy hh:ii',
                        'autoclose' => true,
                        'endDate' => '+1d',
                        'autocomplete' => 'off',
                    ],
                    'clientEvents' => [],
                    'options' => [
                        'autocomplete' => 'off'
                    ]
                ]) ?>
            </div>
        </div>

        <?= $form->field($model, 'huong_dieu_tri')->textarea(['rows' => 3]); ?>

        <?= $form->field($model, 'note')->textarea(['rows' => 3]); ?>
        <?= $form->field($model, 'last_dieu_tri')->checkbox(); ?>
    </div>
    <div class="modal-footer">
        <?= Html::resetButton('<i class="ft-x"></i> Reset', ['class' =>
            'btn btn-warning mr-1']) ?>
        <?= Html::submitButton(
                '<i class="fa fa-check-square-o"></i> Save',
                ['class' => 'btn btn-primary']
            ) ?>
    </div>
<?php ActiveForm::end() ?>
<?php
$script = <<< JS
    $('.ui.dropdown').dropdown({
        forceSelection: false,
    });

	$('.vertical-scroll').perfectScrollbar({
		suppressScrollX : true,
        theme: 'dark',
        wheelPropagation: true
	});

JS;
$this->registerJs($script, \yii\web\View::POS_END);
