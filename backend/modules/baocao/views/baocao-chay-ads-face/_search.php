<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\baocao\models\search\BaocaoChayAdsFaceSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="baocao-chay-ads-face-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'slug') ?>

    <?= $form->field($model, 'don_vi') ?>

    <?= $form->field($model, 'so_tien_chay') ?>

    <?php // echo $form->field($model, 'hien_thi')?>

    <?php // echo $form->field($model, 'tiep_can')?>

    <?php // echo $form->field($model, 'binh_luan')?>

    <?php // echo $form->field($model, 'tin_nhan')?>

    <?php // echo $form->field($model, 'page_chay')?>

    <?php // echo $form->field($model, 'tuong_tac')?>

    <?php // echo $form->field($model, 'so_dien_thoai')?>

    <?php // echo $form->field($model, 'goi_duoc')?>

    <?php // echo $form->field($model, 'lich_hen')?>

    <?php // echo $form->field($model, 'khach_den')?>

    <?php // echo $form->field($model, 'money_hienthi')?>

    <?php // echo $form->field($model, 'money_tiepcan')?>

    <?php // echo $form->field($model, 'money_binhluan')?>

    <?php // echo $form->field($model, 'money_tinnhan')?>

    <?php // echo $form->field($model, 'money_tuongtac')?>

    <?php // echo $form->field($model, 'money_sodienthoai')?>

    <?php // echo $form->field($model, 'money_goiduoc')?>

    <?php // echo $form->field($model, 'money_lichhen')?>

    <?php // echo $form->field($model, 'money_khachden')?>

    <?php // echo $form->field($model, 'status')?>

    <?php // echo $form->field($model, 'created_at')?>

    <?php // echo $form->field($model, 'updated_at')?>

    <?php // echo $form->field($model, 'created_by')?>

    <?php // echo $form->field($model, 'updated_by')?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
