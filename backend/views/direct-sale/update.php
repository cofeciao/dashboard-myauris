<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 25-Mar-19
 * Time: 3:39 PM
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\phongkham\DirectSaleModel;
use dosamigos\datepicker\DatePicker;
use dosamigos\datetimepicker\DateTimePicker;

$name = $model->full_name == null ? $model->forename : $model->full_name;

?>
<section id="form-control-repeater">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title" id="tel-repeater">Cập nhật thông tin khách hàng: <?= $name; ?></h4>
        </div>
        <div class="card-content collapse show">
            <div class="vertical-scroll scroll-example">
                <div class="card-body">
                    <?php $form = ActiveForm::begin([
                        'id' => 'form-update',
                        'enableAjaxValidation' => true,
                        'enableClientValidation' => true,
                        'validationUrl' => ['validate-update', 'id' => $model->id],
                        'action' => Url::toRoute(['update-submit', 'id' => $model->id]),
                    ]); ?>
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
                            <?= $form->field($model, 'customer_come_time_to')->dropDownList(ArrayHelper::map(DirectSaleModel::getStatusCustomerGoToAuris(), 'id', 'name'), ['prompt' => 'Chọn trạng thái khách...']); ?>
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
                    </div>
                    <div class="form-actions center">
                        <?= Html::button('<i class="ft-x"></i> Close', ['class' =>
                            'btn btn-default mr-1 close-update', 'data-pjax' => 0]) ?>
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
