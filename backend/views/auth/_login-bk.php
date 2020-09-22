<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<?php
$form = ActiveForm::begin([
    'id' => 'login-form',
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-login'])
]);
?>
    <fieldset class="position-relative has-icon-left mb-0 <?= $userLogin == null ? '' : 'hidden' ?>">
        <?php echo $form->field($model, 'username')->textInput(['class' => 'form-control form-control', 'placeholder' => 'E-mail', 'autofocus' => ''])->label(false) ?>
        <div class="form-control-position">
            <i class="ft-user"></i>
        </div>
    </fieldset>
<?php if ($userLogin != null) { ?>
    <div class="back-choose-user">
        <div class="current-user">
            <div class="user-avatar">
                <div class="avatar"><?= strtoupper(substr($userLogin['name'], 0, 1)) ?></div>
            </div>
            <div class="user-email"><?= $userLogin['email'] ?></div>
            <div class="user-arrow">
                <i class="fa fa-angle-down"></i>
            </div>
        </div>
    </div>
<?php } ?>
    <fieldset class="position-relative has-icon-left">
        <?php echo $form->field($model, 'password')->passwordInput(['class' => 'form-control form-control', 'placeholder' => 'Mật khẩu', 'required' => '', 'autocomplete' => 'off'])->label(false) ?>
        <div class="form-control-position">
            <i class="fa fa-key"></i>
        </div>
    </fieldset>
    <div class="row login-remmember">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-5">
            <?php echo $form->field($model, 'rememberMe')->checkbox([
                'template' => "{input}\n{label}\n{hint}\n{error}",
                'class' => 'chk-remember',
            ])->label('Ghi nhớ') ?>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-7 text-right">
            <?= Html::a(Yii::t('backend', 'Quên mật khẩu?'), ['/auth/request-password-reset'], ['class' => 'card-link']); ?>
        </div>
    </div>
    <fieldset class="position-relative text-center">
        <?= $form->field($model, 'reCaptcha')->widget(
                \himiklab\yii2\recaptcha\ReCaptcha2::class,
                ['siteKey' => RECAPTCHA_GOOGLE_SITEKEY]
            )->label(false) ?>
    </fieldset>

<?php echo Html::submitButton('<i class="ft-unlock"></i> ' . Yii::t('backend', \Yii::t('backend', 'Sign In')), [
    'class' => 'btn btn-primary btn-block block-page',
    'name' => 'login-button'
]) ?>
<?php ActiveForm::end(); ?>
<?php
$script = <<< JS
/*$('body').find('form#login-form').unbind('beforeSubmit').bind('beforeSubmit', function (event) {
    $.blockUI({
        message: '<div class="semibold"><span class="ft-refresh-cw icon-spin text-left"></span> <br>Loading...</div>',
        overlayCSS: {
            backgroundColor: '#FFF',
            opacity: 0.9,
            cursor: 'wait'
        },
        css: {
            border: 0,
            padding: 0,
            backgroundColor: 'transparent'
        }
    });
});*/
$('body').on('beforeSubmit', '#login-form', function(e) {
    e.preventDefault();
    console.log('abc');
    return false;
});
JS;
$this->registerJs($script);
