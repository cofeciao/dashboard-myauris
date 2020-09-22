<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\recommend\models\RecommendOnlineSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="recommend-online-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'gioi_tinh') ?>

    <?= $form->field($model, 'nhom_tuoi') ?>

    <?= $form->field($model, 'tinh_trang_rang') ?>

    <?= $form->field($model, 'khach_quan_tam') ?>

    <?php // echo $form->field($model, 'san_pham') ?>

    <?php // echo $form->field($model, 'hinh_anh') ?>

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
