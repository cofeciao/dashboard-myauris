<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\UserProfile;

$this->title = \Yii::t('backend', 'Edit profile');
?>
<div class="box-typical box-typical-padding">
    <h5 class="m-t-lg with-border"><?= \Yii::t('backend', 'Edit profile'); ?></h5>
    <div class="fixed-table-toolbar">
        <?php
        if (Yii::$app->session->hasFlash('alert')) {
            ?>
            <div class="alert <?= Yii::$app->session->getFlash('alert')['class']; ?> alert-icon alert-close alert-dismissible fade show"
                 role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <i class="font-icon font-icon-warning"></i>
                <?= Yii::$app->session->getFlash('alert')['body']; ?>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
    $form = ActiveForm::begin();
    ?>

    <?= $form->field($model, 'firstname')->textInput(); ?>

    <?= $form->field($model, 'middlename')->textInput(); ?>

    <?= $form->field($model, 'lastname')->textInput(); ?>

    <?= $form->field($model, 'phone')->textInput(); ?>

    <?= $form->field($model, 'facebook')->textInput(); ?>

    <?= $form->field($model, 'city')->textInput(); ?>

    <?= $form->field($model, 'address')->textInput(); ?>

    <?=$form->field($model, 'locale')->dropDownList(Yii::$app->params['availableLocales']); ?>

    <?= $form->field($model, 'gender')->dropDownList([
        UserProfile::GENDER_MALE => \Yii::t('frontend', 'Male'),
        UserProfile::GENDER_FEMALE => \Yii::t('frontend', 'Female'),
        UserProfile::GENDER_OTHER => \Yii::t('frontend', 'Undefined'),
    ]); ?>

    <?= $form->field($model, 'about')->textarea(['id' => 'desc']); ?>

    <section class="box-typical-section profile-settings-btns">
        <?= Html::submitButton(\Yii::t('backend', 'Save Changes'), ['class' => 'btn btn-rounded']); ?>
    </section>
    <?php
    ActiveForm::end();
    ?>


</div><!--.box-typical-->