<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\labo\models\search\SearchLaboDonHang */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="labo-don-hang-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'bac_si_id') ?>

    <?= $form->field($model, 'phong_kham_don_hang_id') ?>

    <?= $form->field($model, 'ngay_nhan') ?>

    <?= $form->field($model, 'ngay_giao') ?>

    <?php // echo $form->field($model, 'loai_phuc_hinh') ?>

    <?php // echo $form->field($model, 'loai_su') ?>

    <?php // echo $form->field($model, 'yeu_cau') ?>

    <?php // echo $form->field($model, 'trang_thai') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
