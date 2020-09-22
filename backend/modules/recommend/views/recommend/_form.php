<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\recommend\models\Recommend */
/* @var $form yii\widgets\ActiveForm */

$this->registerCss('
label.control-label {
    color: #000;
}
');
?>

<div class="btn-add-campaign clearfix" style="margin-top:0px;position:relative">
    <?= Html::a('<i class="fa fa-plus"> Master Data</i>', ['index'], ['title' => 'Thêm mới', 'data-pjax' => 0, 'class' => 'btn btn-default pull-left']) ?>
</div>
<div class="recommend-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">
        <!--        --><?php //echo $form->field($model, 'gioi_tinh')->textInput(['maxlength' => true]); ?>

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
                    'prompt' => 'Nhom tuoi...',
                    'style' => 'width: 100%',
                    'multiple' => 'true'
                ]);
                ?>
            </div>

            <div class="col-3">
                <?php
                echo $form->field($model, 'bo_cuc')->dropDownList($model::getListBoCuc(), [
                    'class' => 'dropdown ui search form-control',
                    'prompt' => 'Bo cuc...',
                    'style' => 'width: 100%',
                    'multiple' => 'true'
                ]);
                ?>
            </div>

            <div class="col-3">
                <?php
                echo $form->field($model, 'phong_cach')->dropDownList($model::getListPhongCach(), [
                    'class' => 'dropdown ui search form-control',
                    'prompt' => 'Phong cach...',
                    'style' => 'width: 100%',
                    'multiple' => 'true'
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <?php
                echo $form->field($model, 'tinh_trang_rang')->dropDownList($model::getListTinhTrangRang(), [
                    'class' => 'dropdown ui search form-control',
                    'prompt' => 'Tinh trang rang...',
                    'style' => 'width: 100%',
                    'multiple' => 'true'
                ]);
                ?>
            </div>
            <div class="col-3">
                <?php
                echo $form->field($model, 'mong_muon')->dropDownList($model::getListMongMuon(), [
                    'class' => 'dropdown ui search form-control',
                    'prompt' => 'Mong muốn...',
                    'style' => 'width: 100%',
                    'multiple' => 'true'
                ]);
                ?>
            </div>
            <div class="col-3">
                <?php
                // echo $form->field($model, 'giai_phap')->dropDownList($model::getListGiaiPhap(), [
                //     'class' => 'dropdown ui search form-control',
                //     'prompt' => 'Giai phap...',
                //     'style' => 'width: 100%',
                //     'multiple' => 'true'
                // ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <?php
                // echo $form->field($model, 'benh_ly')->dropDownList($model::getListBenhLy(), [
                //     'class' => 'dropdown ui search form-control',
                //     'prompt' => 'Bệnh lý...',
                //     'style' => 'width: 100%',
                //     'multiple' => 'true'
                // ]);
                ?>
            </div>
        </div>

        <h4 style=" color: #ff365c; font-size: 25px; ">  Gợi ý khách hàng  </h4>

        <div class="row">
            <div class="col-3">
                <?php
                echo $form->field($model, 'tieu_de')->textInput();
                ?>
            </div>
            <div class="col-9">
                <?php
                echo $form->field($model, 'mo_ta')->textInput();
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-3">
                <?php
                echo $form->field($model, 'phan_loai')->dropDownList($model::getListPhanLoai(), [
                    'class' => 'dropdown ui search form-control',
                    'prompt' => 'Phân loại ...',
                    'style' => 'width: 100%',
//                    'multiple' => 'true'
                ]);
                ?>
            </div>
            <div class="col-9">
                <?php
                echo $form->field($model, 'san_pham')->dropDownList($model::getListSanPham(), [
                    'class' => 'dropdown ui search form-control',
                    'prompt' => 'San pham...',
                    'style' => 'width: 100%',
                    'multiple' => 'true'
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <!-- <div class="col-3">
                <?php
                //echo $form->field($model, 'vat_lieu')->textInput();
                ?>
            </div> -->
            <div class="col-12">
                <?php
                echo $form->field($model, 'video')->textInput();
                ?>
            </div>
            
        </div>


    </div>
    <div class="form-actions">
        <?= Html::resetButton('<i class="ft-x"></i> Cancel', ['class' =>
            'btn btn-warning mr-1']) ?>
        <?= Html::submitButton('<i class="fa fa-check-square-o"></i> Save',
            ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

