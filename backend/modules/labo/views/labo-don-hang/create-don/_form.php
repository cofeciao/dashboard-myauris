<?php

use backend\modules\clinic\models\Clinic;
use backend\modules\labo\models\LaboDonHang;
use backend\modules\labo\models\LaboGiaiDoan;
use dosamigos\datepicker\DatePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\modules\labo\models\LaboDonHang */
/* @var $form yii\widgets\ActiveForm */

$array_vi_tri_rang = ($model->vi_tri_rang) ? $model->vi_tri_rang : [];
?>

<div class="labo-don-hang-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">

        <div class="row">

            <div class="col-3">
                <?= $form->field($model, 'bac_si_id')->dropDownList(Clinic::getEkipbacsi(), ['class' => 'ui dropdown search form-control', 'prompt' => 'Chọn bác sĩ...', 'style' => 'width: 100%']); ?>
            </div>

            <div class="col-3">
                <?= $form->field($model, 'user_labo')->dropDownList(LaboDonHang::getListUserLabo(), ['class' => 'ui dropdown search form-control', 'prompt' => 'Chọn Labo...', 'style' => 'width: 100%']); ?>
            </div>

            <?php
            //echo $form->field($model, 'phong_kham_don_hang_id')->textInput();
            ?>
            <div class="col-3">
                <?= $form->field($model, 'ngay_nhan')->widget(DatePicker::class, [
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

            <div class="col-3">
                <?= $form->field($model, 'ngay_giao')->widget(DatePicker::class, [
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

        </div>

        <div class="row">
            <div class="col-6">
                <?= $form->field($model, 'loai_phuc_hinh')->radioList($model::getListLoaiPhucHinh()) ?>
            </div>
            <div class="col-6">
                <?php echo $form->field($model, 'so_luong')->textInput(['type' => 'number']); ?>
            </div>
        </div>


        <?= $form->field($model, 'yeu_cau')->textarea(['rows' => 3]) ?>

        <?php
        echo $form->field($model, 'imageFile')->fileInput();
        ?>

        <?= $form->field($model, 'trang_thai')->radioList($model::getListTrangThai()) ?>

        <?php if (false) : ?>
            <h2>Vị trị răng</h2>
            <div class="row">
                <div class="col-1"></div>
                <div class="col-10" style="overflow-x:auto;">
                    <table class="table ">
                        <tbody>
                            <tr>
                                <?php
                                $listTren = LaboDonHang::getListRangTren();
                                foreach ($listTren as $key => $value) :
                                    $checked = isset($array_vi_tri_rang[$key]) ? "checked" : " ";
                                ?>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="LaboDonHang[vi_tri_rang][<?= $key ?>]" id="checkbox<?= $key ?>" <?= $checked ?>>
                                            <label class="custom-control-label" for="checkbox<?= $key ?>"><?= $value ?></label>
                                        </div>
                                    </td>
                                <?php
                                endforeach;
                                ?>
                            </tr>

                            <tr>
                                <?php
                                $listDuoi = LaboDonHang::getListRangDuoi();
                                foreach ($listDuoi as $key => $value) :
                                    $checked = isset($array_vi_tri_rang[$key]) ? "checked" : " ";
                                ?>
                                    <td>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="LaboDonHang[vi_tri_rang][<?= $key ?>]" id="checkbox<?= $key ?>" <?= $checked ?>>
                                            <label class="custom-control-label" for="checkbox<?= $key ?>"><?= $value ?></label>
                                        </div>
                                    </td>
                                <?php
                                endforeach;
                                ?>
                            </tr>

                        </tbody>
                    </table>

                </div>
                <div class="col-1"></div>
            </div>
        <?php endif; ?>

    </div>
    <div class="form-actions">
        <?= Html::resetButton('<i class="ft-x"></i> Cancel', ['class' =>
        'btn btn-warning mr-1']) ?>
        <?= Html::submitButton(
            '<i class="fa fa-check-square-o"></i> Save',
            ['class' => 'btn btn-primary mr-1']
        ) ?>

        <?php
        if (false) : //(!$model->isNewRecord) :
            $mGiaiDoan = LaboGiaiDoan::getByDonHangGiaiDoan($model->id, LaboGiaiDoan::GIAI_DOAN_TIEP_NHAN);
        ?>
            <?= Html::a(
                '<i class="fa fa-check-square-o"></i> Cập nhật hình phiếu Labo',
                Url::toRoute(['/labo/labo-don-hang/update-giai-doan', 'id' => $mGiaiDoan->id]),
                ['class' => 'btn btn-primary']
            ) ?>
        <?php
        endif;
        ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>