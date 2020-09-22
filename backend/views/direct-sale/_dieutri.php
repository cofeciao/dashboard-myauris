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
use backend\modules\clinic\models\Clinic;
use dosamigos\datepicker\DatePicker;
use dosamigos\datetimepicker\DateTimePicker;

?>
    <section id="form-control-repeater">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title" id="tel-repeater">Thêm lịch điều trị</h4>
            </div>
            <div class="card-content collapse show">
                <div class="vertical-scroll scroll-example">
                    <div class="card-body">
                        <?php $form = ActiveForm::begin([
                            'id' => 'form-dieu-tri',
                            'enableAjaxValidation' => true,
                            'enableClientValidation' => true,
                            'validationUrl' => ['validate-dieu-tri', 'id' => $customer->id],
                            'action' => Url::toRoute(['dieu-tri', 'id' => $customer->id]),
                        ]); ?>
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
                                <?= $form->field($model, 'ekip')->dropDownList(Clinic::getEkipbacsi(), ['prompt' => 'Chọn ekip bác sĩ...']); ?>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
                                <?= $form->field($model, 'time_dieu_tri')->widget(DatePicker::class, [
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
                                <?= $form->field($model, 'time_start')->widget(DateTimePicker::class, [
                                    'clientOptions' => [
                                        'format' => 'dd-mm-yyyy hh:ii',
                                        'autoclose' => true,
                                        'endDate' => '+1d'
                                    ],
                                    'clientEvents' => [],
                                    'options' => [
                                        'class' => 'form-control',
                                        'readonly' => 'readonly',
                                    ]
                                ]) ?>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
                                <?= $form->field($model, 'time_end')->widget(DateTimePicker::class, [
                                    'clientOptions' => [
                                        'format' => 'dd-mm-yyyy hh:ii',
                                        'autoclose' => true,
                                        'endDate' => '+1d'
                                    ],
                                    'clientEvents' => [],
                                    'options' => [
                                        'class' => 'form-control',
                                        'readonly' => 'readonly',
                                    ]
                                ]) ?>
                            </div>
                        </div>

                        <?= $form->field($model, 'huong_dieu_tri')->textarea(['rows' => 3]); ?>

                        <?= $form->field($model, 'note')->textarea(['rows' => 3]); ?>
                        <?= $form->field($model, 'last_dieu_tri')->checkbox(); ?>

                        <div class="form-actions center">
                            <?= Html::button('<i class="ft-x"></i> Close', ['class' =>
                                'btn btn-default mr-1 close-dieutri', 'data-pjax' => 0]) ?>
                            <?= Html::submitButton(
                                    '<i class="fa fa-check-square-o"></i> Save',
                                    ['class' => 'btn btn-primary', 'data-pjax' => 0]
                                ) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
$script = <<< JS
	$('.vertical-scroll').perfectScrollbar({
		suppressScrollX : true,
        theme: 'dark',
        wheelPropagation: true
	});

JS;
$this->registerJs($script, \yii\web\View::POS_END);
