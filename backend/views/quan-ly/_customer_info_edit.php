<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 17-04-2019
 * Time: 04:33 PM
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use dosamigos\datepicker\DatePicker;

?>
<?php
$form = ActiveForm::begin([
    'id' => 'form-customer-info-edit',
    'class' => 'form-edit'
]);
?>
    <div class="sub-panel">
        <div class="sp-title">Thông tin khách hàng</div>
        <div class="sp-content">
            <div class="row">
                <div class="col-4 c-col-1">
                    <div class="form-group c-group">
                        <label class="control-label c-label">Họ và tên:</label>
                        <div class="c-info">
                            <?= $form->field($customer, 'full_name')->textInput([])->label(false) ?>
                        </div>
                    </div>
                    <div class="form-group c-group">
                        <label class="control-label c-label">Tên:</label>
                        <div class="c-info">
                            <?= $form->field($customer, 'forename')->textInput([])->label(false) ?>
                        </div>
                    </div>
                    <div class="form-group c-group">
                        <label class="control-label c-label">Ngày sinh:</label>
                        <div class="c-info">
                            <?= $form->field($customer, 'birthday')->widget(DatePicker::class, [
                                'clientOptions' => [
                                    'format' => 'dd-mm-yyyy',
                                    'autoclose' => true,
                                ],
                                'clientEvents' => [],
                                'options' => [
                                    'placeholder' => "Ngày",
                                    'autocomplete' => 'off'
                                ]
                            ])->label(false) ?>
                        </div>
                    </div>
                    <div class="form-group c-group">
                        <label class="control-label c-label">Giới tính:</label>
                        <div class="c-info">
                            <?= $form->field($customer, 'sex')->dropDownList($customer->getSex(), [])->label(false) ?>
                        </div>
                    </div>
                </div>
                <div class="col-4 c-col-2">
                    <div class="form-group c-group">
                        <label class="control-label c-label">Thời gian khách đến:</label>
                        <div class="c-info">
                            <?= $form->field($customer, 'time_lichhen')->widget(DatePicker::class, [
                                'clientOptions' => [
                                    'format' => 'dd-mm-yyyy',
                                    'autoclose' => true,
                                ],
                                'clientEvents' => [],
                                'options' => [
                                    'placeholder' => "Ngày",
                                    'autocomplete' => 'off'
                                ]
                            ])->label(false) ?>
                        </div>
                    </div>
                    <div class="form-group c-group">
                        <label class="control-label c-label">Đặt hẹn:</label>
                        <div class="c-info">
                            <?= $form->field($customer, 'dat_hen')->dropDownList($customer->getStatusDatHen(), [])->label(false) ?>
                        </div>
                    </div>
                    <div class="form-group c-group">
                        <label class="control-label c-label">Direct sale:</label>
                        <div class="c-info"><?= $customer->directSaleHasOne != null ? $customer->directSaleHasOne->fullname : '-' ?></div>
                    </div>
                </div>
                <div class="col-4 c-col-3">
                    <div class="form-group c-group">
                        <label class="control-label c-label">Kết quả thăm khám:</label>
                        <div class="c-info">
                            <?php
                            if ($customer->dentalTagHasOne != null) {
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group c-group">
                        <label class="control-label c-label">Mong muốn khách hàng:</label>
                        <div class="c-info">
                            <?= $form->field($customer, 'customer_mongmuon')->textarea(['rows' => 4, 'style' => 'resize: none'])->label(false) ?>
                        </div>
                    </div>
                    <div class="form-group c-group">
                        <label class="control-label c-label">Ghi chú Direct:</label>
                        <div class="c-info"
                        <?= $form->field($customer, 'note_direct')->textarea(['rows' => 4, 'style' => 'resize: none'])->label(false) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="sub-panel">
        <div class="sp-title">Thông tin cá nhân</div>
        <div class="sp-content">
            <div class="row">
                <div class="col-4 c-col-1">
                    <div class="form-group c-group">
                        <label class="control-label c-label">Số điện thoại:</label>
                        <div class="c-info">
                            <?= $form->field($customer, 'phone')->textInput([])->label(false) ?>
                        </div>
                    </div>
                </div>
                <div class="col-4 c-col-1">
                    <div class="form-group c-group">
                        <label class="control-label c-label">Tỉnh thành:</label>
                        <div class="c-info"><?= $customer->provinceHasOne != null ? $customer->provinceHasOne->name : '-' ?></div>
                    </div>
                </div>
                <div class="col-4 c-col-1">
                    <div class="form-group c-group">
                        <label class="control-label c-label">Quận huyện:</label>
                        <div class="c-info"><?= $customer->districtHasOne != null ? $customer->districtHasOne->name : '-' ?></div>
                    </div>
                </div>
                <div class="col-12 c-col-1">
                    <div class="form-group c-group">
                        <label class="control-label c-label">Địa chỉ:</label>
                        <div class="c-info">
                            <?= $form->field($customer, 'address')->textInput([])->label(false) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sub-panel text-center">
        <?= Html::submitButton('Cập nhật', ['class' => 'btn btn-primary btn-submit-edit']) ?>
        <button type="button" class="btn btn-default btn-cancel-edit">Huỷ bỏ</button>
    </div>
<?php ActiveForm::end() ?>