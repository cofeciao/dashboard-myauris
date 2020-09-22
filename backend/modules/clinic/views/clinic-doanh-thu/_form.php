<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin([
    'id' => 'form-clinic-payment',
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-clinic-payment', 'id' => $model->primaryKey]),
    'action' => Url::toRoute(['submit-clinic-payment', 'id' => $model->primaryKey]),
]); ?>
    <div class="modal-body">
        <div class="form-body">
            <?php
            if ($readOnly === true) {
                ?>
                <div class="form-group">
                    <div class="alert alert-danger">Đơn hàng đã hoàn cọc, bạn không thể chỉnh sửa thanh toán này
                    </div>
                </div>
            <?php
            } ?>
            <div class="form-group row">
                <div class="col-md-6 col-12">
                    <?= $form->field($model, 'customer_name')->textInput(['readOnly' => true]) ?>
                </div>
                <div class="col-md-6 col-12">
                    <?= $form->field($model, 'order_code')->textInput(['readOnly' => true]) ?>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 col-12">
                    <?php
                    if ($readOnly === true) {
                        ?>
                        <label class="control-label">Số tiền thanh toán</label>
                        <div class="form-control"
                             readonly="true"><?= $model->tien_thanh_toan != null ? number_format($model->tien_thanh_toan, 0, '', '.') : '-' ?></div>
                    <?php
                    } else { ?>
                        <?= $form->field($model, 'tien_thanh_toan')->textInput(['class' => 'form-control on-keyup', 'readOnly' => $readOnly]) ?>
                    <?php } ?>
                </div>
                <div class="col-md-4 col-12">
                    <?php if ($readOnly === true) {
                        $lttModel = new \backend\modules\clinic\models\PhongKhamLoaiThanhToan();
                        $loai_thanh_toan = $lttModel->getOneLTT($model->loai_thanh_toan); ?>
                        <label class="control-label">Loại thanh toán</label>
                        <div class="form-control" readonly="true">
                            <?php if ($loai_thanh_toan == null) {
                            echo '-';
                        } else {
                            echo $loai_thanh_toan->name;
                        } ?>
                        </div>
                    <?php
                    } else { ?>
                        <?= $form->field($model, 'loai_thanh_toan')->radioList(\yii\helpers\ArrayHelper::map(\backend\modules\clinic\models\PhongKhamLoaiThanhToan::getClinicLoaiThanhToan(), 'id', 'name'), ['readOnly' => $readOnly]) ?>
                    <?php } ?>
                </div>
                <div class="col-md-4 col-12">
                    <?php
                    if ($readOnly === true) {
                        ?>
                        <label class="control-label">Hình thức</label>
                        <div class="form-control"
                             readonly="true"><?= \backend\models\doanhthu\ThanhToanModel::THANHTOAN_TYPE[$model->tam_ung] ?></div>
                    <?php
                    } else { ?>
                        <?= $form->field($model, 'tam_ung')->dropDownList($listThanhToan, ['readOnly' => $readOnly]) ?>
                    <?php } ?>
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
$script = <<< JS
$('.on-keyup').on('change paste keyup', function(){
    var v = $(this).val() || '0';
    v = v.replace(/[^0-9]/gi, '');
    v = v.replace(/\./g, '');
    $(this).val(addCommas(v));
}).trigger('change');
JS;
$this->registerJs($script);
