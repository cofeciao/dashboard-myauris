<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\testab\models\search\AbCampaignSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ab-campaign-search">

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

    <?= $form->field($model, 'ky_thuat') ?>

    <?= $form->field($model, 'link_test') ?>

    <?php // echo $form->field($model, 'content')?>

    <?php // echo $form->field($model, 'chiphi_thucchay')?>

    <?php // echo $form->field($model, 'comment')?>

    <?php // echo $form->field($model, 'tin_nhan')?>

    <?php // echo $form->field($model, 'tong_tuong_tac')?>

    <?php // echo $form->field($model, 'hien_thi')?>

    <?php // echo $form->field($model, 'tiep_can')?>

    <?php // echo $form->field($model, 'nguoi_xem_1')?>

    <?php // echo $form->field($model, 'nguoi_xem_50')?>

    <?php // echo $form->field($model, 'tan_suat')?>

    <?php // echo $form->field($model, 'gia_tuong_tac')?>

    <?php // echo $form->field($model, 'gia_hien_thi')?>

    <?php // echo $form->field($model, 'gia_tiep_can')?>

    <?php // echo $form->field($model, 'gia_10s')?>

    <?php // echo $form->field($model, 'gia_50phantram')?>

    <?php // echo $form->field($model, 'status')?>

    <?php // echo $form->field($model, 'end_date')?>

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
