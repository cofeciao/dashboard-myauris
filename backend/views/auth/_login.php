<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'login-form',
]);
?>
    <fieldset class="position-relative has-icon-left mb-0 <?= $params['userLogin'] == null ? '' : 'hidden' ?>">
        <?php echo $form->field($params['model'], 'username')->textInput(['class' => 'form-control form-control', 'placeholder' => 'E-mail', 'autofocus' => ''])->label(false) ?>
        <div class="form-control-position">
            <i class="ft-user"></i>
        </div>
    </fieldset>
<?php if ($params['userLogin'] != null) { ?>
    <div class="back-choose-user">
        <div class="current-user">
            <a href="<?= Url::toRoute(['login']) ?>">
                <div class="user-avatar">
                    <div class="avatar"><?= strtoupper(substr($params['userLogin']['name'], 0, 1)) ?></div>
                </div>
                <div class="user-email"><?= $params['userLogin']['email'] ?></div>
                <div class="user-arrow">
                    <i class="fa fa-angle-down"></i>
                </div>
            </a>
        </div>
    </div>
<?php } ?>
    <fieldset class="position-relative has-icon-left">
        <?php echo $form->field($params['model'], 'password')->passwordInput(['class' => 'form-control form-control', 'placeholder' => 'Mật khẩu', 'required' => '', 'autocomplete' => 'off'])->label(false) ?>
        <div class="form-control-position">
            <i class="fa fa-key"></i>
        </div>
    </fieldset>
    <div class="row login-remmember">
        <!--<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-5">
            <?php /*echo $form->field($params['model'], 'rememberMe')->checkbox([
                'template' => "{input}\n{label}\n{hint}\n{error}",
                'class' => 'chk-remember',
            ])->label('Ghi nhớ') */?>
        </div>-->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-right">
            <?= Html::a(Yii::t('backend', 'Quên mật khẩu?'), ['/auth/request-password-reset'], ['class' => 'card-link']); ?>
        </div>
    </div>
    <fieldset class="position-relative text-center">
        <?= $form->field($params['model'], 'reCaptcha')->widget(
    \himiklab\yii2\recaptcha\ReCaptcha2::class,
    ['siteKey' => RECAPTCHA_GOOGLE_SITEKEY]
)->label(false) ?>
    </fieldset>

<?php echo Html::submitButton('<i class="ft-unlock"></i> Sign In', [
    'class' => 'btn btn-primary btn-block block-page',
    'name' => 'login-button'
]) ?>
<?php ActiveForm::end(); ?>