<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\affiliate\models\search\AffiliateCustomerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="clinic-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'customer_code') ?>

    <?= $form->field($model, 'full_name') ?>

    <?= $form->field($model, 'forename') ?>

    <?= $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'avatar')?>

    <?php // echo $form->field($model, 'slug')?>

    <?php // echo $form->field($model, 'phone')?>

    <?php // echo $form->field($model, 'sex')?>

    <?php // echo $form->field($model, 'birthday')?>

    <?php // echo $form->field($model, 'status')?>

    <?php // echo $form->field($model, 'agency_id')?>

    <?php // echo $form->field($model, 'nguon_online')?>

    <?php // echo $form->field($model, 'address')?>

    <?php // echo $form->field($model, 'province')?>

    <?php // echo $form->field($model, 'district')?>

    <?php // echo $form->field($model, 'face_fanpage')?>

    <?php // echo $form->field($model, 'face_post_id')?>

    <?php // echo $form->field($model, 'face_customer')?>

    <?php // echo $form->field($model, 'note')?>

    <?php // echo $form->field($model, 'directsale')?>

    <?php // echo $form->field($model, 'note_direct')?>

    <?php // echo $form->field($model, 'permission_user')?>

    <?php // echo $form->field($model, 'per_inactivity')?>

    <?php // echo $form->field($model, 'permission_old')?>

    <?php // echo $form->field($model, 'tt_kh')?>

    <?php // echo $form->field($model, 'ngaythang')?>

    <?php // echo $form->field($model, 'date_lichhen')?>

    <?php // echo $form->field($model, 'time_lichhen')?>

    <?php // echo $form->field($model, 'co_so')?>

    <?php // echo $form->field($model, 'dat_hen')?>

    <?php // echo $form->field($model, 'customer_come')?>

    <?php // echo $form->field($model, 'customer_come_date')?>

    <?php // echo $form->field($model, 'customer_come_time_to')?>

    <?php // echo $form->field($model, 'customer_gen')?>

    <?php // echo $form->field($model, 'customer_mongmuon')?>

    <?php // echo $form->field($model, 'customer_thamkham')?>

    <?php // echo $form->field($model, 'customer_huong_dieu_tri')?>

    <?php // echo $form->field($model, 'customer_ghichu_bacsi')?>

    <?php // echo $form->field($model, 'status_fail')?>

    <?php // echo $form->field($model, 'is_customer_who')?>

    <?php // echo $form->field($model, 'is_affiliate_created')?>

    <?php // echo $form->field($model, 'customer_direct_sale_checkthammy')?>

    <?php // echo $form->field($model, 'customer_bacsi_check_final')?>

    <?php // echo $form->field($model, 'customer_old')?>

    <?php // echo $form->field($model, 'ngay_tao')?>

    <?php // echo $form->field($model, 'created_at')?>

    <?php // echo $form->field($model, 'updated_at')?>

    <?php // echo $form->field($model, 'created_by')?>

    <?php // echo $form->field($model, 'updated_by')?>

    <?php // echo $form->field($model, 'dat_hen_fail')?>

    <?php // echo $form->field($model, 'reason_reject')?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
