<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\chi\models\search\DeadlineSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="deadline-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_de_xuat_chi') ?>

    <?= $form->field($model, 'thoi_gian_bat_dau') ?>

    <?= $form->field($model, 'thoi_gian_ket_thuc') ?>

    <?= $form->field($model, 'danh_gia') ?>

    <?php // echo $form->field($model, 'created_at')?>

    <?php // echo $form->field($model, 'created_by')?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('backend', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('backend', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
