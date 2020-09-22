<?php

use backend\modules\clinic\models\PhongKhamLichDieuTri;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\clinic\models\Clinic;
use backend\modules\clinic\models\ListChupHinh;

/* @var $this yii\web\View */
/* @var $model backend\modules\clinic\models\PhongKhamLichDieuTri */
/* @var $form yii\widgets\ActiveForm */

//if($model->primaryKey != null) $tr = date('d-m-Y h:i', $model->time_dieu_tri);
//var_dump($tr);

$this->registerCss('
.ui.multiple.dropdown>.text{margin:0;}
');
?>

<?php $form = ActiveForm::begin([
    'id' => 'clinicDieuTriAjax',
]); ?>
<div class="modal-body">
    <?php
    if ($customer) {
        $name = $customer->full_name == null ? $customer->forename : $customer->full_name;
        echo $form->field($model, 'customer_id')->hiddenInput(['value' => $customer->id])->label(false);
    }
    if ($model->time_dieu_tri != null) {
        $model->time_dieu_tri = date('d-m-Y H:i', $model->time_dieu_tri);
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
            <?= $form->field($model, 'order_code')->textInput(['readonly' => 'readonly']); ?>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
            <?= $form->field($model, 'ekip')->dropDownList(Clinic::getEkipbacsi($model->ekip), [
                'class' => 'ui dropdown search form-control',
                'prompt' => 'Chọn ekip bác sĩ...'
            ]); ?>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
            <?= $form->field($model, 'tro_thu')->dropDownList(Clinic::getTrothu($model->tro_thu), [
                'class' => 'ui dropdown form-control',
                'prompt' => 'Chọn ekip bác sĩ...',
                'style' => 'width: 100%',
                'multiple' => 'true'
            ]); ?>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
            <?= $form->field($model, 'id_list_chuphinh')->dropDownList(ArrayHelper::map(ListChupHinh::getListChupHinh(), 'id', 'name'), [
                'class' => 'ui dropdown form-control',
                'prompt' => 'Chọn loại chụp hình...',
                'style' => 'width: 100%',
            ]); ?>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
            <?= $form->field($model, 'room_id')->dropDownList(Clinic::getPhongKham($model->room_id), [
                'class' => 'dropdown ui form-control',
                'prompt' => 'Chọn phòng...',
                'style' => 'width: 100%',
            ]); ?>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
            <?php
            //            if ($model->time_dieu_tri != null) {
            //                echo $form->field($model, 'time_dieu_tri')->textInput(['readonly' => true, 'value' => Yii::$app->formatter->asDate($model->time_dieu_tri, 'dd-MM-Y h:i')]);
            //            } else {
            //                echo $form->field($model, 'time_dieu_tri')->widget(dosamigos\datetimepicker\DateTimePicker::class, [
            //                    'clientOptions' => [
            //                        'format' => 'dd-mm-yyyy hh:ii',
            //                        'autoclose' => true,
            //                    ],
            //                    'clientEvents' => [],
            //                ]);
            //            }

            echo $form->field($model, 'time_dieu_tri')->widget(\dosamigos\datetimepicker\DateTimePicker::class, [
                'clientOptions' => [
                    'format' => 'dd-mm-yyyy hh:ii',
                    'autoclose' => true,
                    'todayHighlight' => true
                ],
                'clientEvents' => [
                ],
                'options' => [
                    'readonly' => 'readonly',
                    'class' => 'form-control'
                ],
            ]);

            ?>
        </div>

        <!--        cap nhat thao tac cho khach-->
        <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
            <?= $form->field($model, 'thao_tac')->dropDownList(PhongKhamLichDieuTri::listThaoTac(), [
                'class' => 'dropdown ui form-control',
                'prompt' => 'Chọn thao tác...',
                'style' => 'width: 100%',
                'multiple' => 'true'
            ]); ?>
        </div>

        <?php /*<div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
            <?php
            if ($model->time_start != null) {
                echo $form->field($model, 'time_start')->textInput(['readonly' => true]);
            } else
                echo $form->field($model, 'time_start')->widget(\dosamigos\datetimepicker\DateTimePicker::class, [
                    'clientOptions' => [
                        'format' => 'dd-mm-yyyy hh:ii',
                        'autoclose' => true,
                    ],
                    'clientEvents' => [],
                ]) ?>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-xs-12 col-12">
            <?php
            if ($model->time_end != null) {
                echo $form->field($model, 'time_end')->textInput(['readonly' => true]);
            } else
                echo $form->field($model, 'time_end')->widget(\dosamigos\datetimepicker\DateTimePicker::class, [
                    'clientOptions' => [
                        'format' => 'dd-mm-yyyy hh:ii',
                        'autoclose' => true,
                    ],
                    'clientEvents' => [],
                ]) ?>
        </div>*/ ?>
    </div>
    <div class="row">
        <div class="col-md-6 col-12">
            <?= $form->field($model, 'huong_dieu_tri')->textarea(['rows' => 3]); ?>
        </div>
        <div class="col-md-6 col-12">
            <?= $form->field($model, 'note')->textarea(['rows' => 3]); ?>
        </div>
    </div>
    <?php if ($model->tai_kham == null) { ?>
        <div class="row">
            <div class="col-md-6 col-12">
                <?= $form->field($model, 'last_dieu_tri')->checkbox([]) ?>
            </div>
        </div>
    <?php } ?>
</div>
<div class="modal-footer">
    <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
        'btn btn-warning mr-1']) ?>
    <?= Html::submitButton(
        '<i class="fa fa-check-square-o"></i> Save',
        ['class' => 'btn btn-primary block-menu-left', 'data-pjax' => 0]
    ) ?>
</div>
<?php ActiveForm::end() ?>
