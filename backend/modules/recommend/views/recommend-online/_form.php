<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\recommend\models\RecommendOnline */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recommend-online-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">
        <div class="row">
            <div class="col-3">
                <?php echo $form->field($model, 'gioi_tinh')->dropDownList($model::getListGioiTinh(), [
                    'class' => 'dropdown ui search form-control',
                    'prompt' => 'Giới tính',
                    'style' => 'width: 100%',
                    'multiple' => 'true'
                ]); ?>
            </div>
            <div class="col-3">
                <?php
                echo $form->field($model, 'nhom_tuoi')->dropDownList($model::getListNhomTuoi(), [
                    'class' => 'dropdown ui search form-control',
                    'prompt' => 'Nhóm tuổi...',
                    'style' => 'width: 100%',
                    'multiple' => 'true'
                ]);
                ?>
            </div>
            <div class="col-6">
                <?php
                echo $form->field($model, 'tinh_trang_rang')->dropDownList($model::getListTinhTrangRang(), [
                    'class' => 'dropdown ui search form-control',
                    'prompt' => 'Tình trạng răng...',
                    'style' => 'width: 100%',
                    'multiple' => 'true'
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <?php
                echo $form->field($model, 'khach_quan_tam')->dropDownList($model::getListKhachQuanTam(), [
                    'class' => 'dropdown ui search form-control',
                    'prompt' => 'Khách quan tâm ...',
                    'style' => 'width: 100%',
                    'multiple' => 'true'
                ]);
                ?>
            </div>
            <div class="col-6">
                <?php
                echo $form->field($model, 'san_pham')->dropDownList($model::getListSanPham(), [
                    'class' => 'dropdown ui search form-control',
                    'prompt' => 'Nhóm Sản phẩm...',
                    'style' => 'width: 100%',
                    'multiple' => 'true'
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <?= $form->field($model, 'tin_nhan')->textarea(['id' => 'content']) ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'hinh_anh')->textarea(['id' => 'content']) ?>
            </div>
        </div>

    </div>
    <div class="form-actions">
        <?= Html::resetButton('<i class="ft-x"></i> Cancel', ['class' =>
        'btn btn-warning mr-1']) ?>
        <?= Html::submitButton(
            '<i class="fa fa-check-square-o"></i> Save',
            ['class' => 'btn btn-primary']
        ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>