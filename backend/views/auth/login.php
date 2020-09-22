<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('backend', 'Đăng Nhập');
?>
<div class="login-outer slideout">
    <div class="login-backdrop"></div>
    <div class="utilities-login utilities">
        <div class="utility-clock"></div>
        <div class="utility-calendar"></div>
    </div>
    <div class="login-content">
        <div class="login-cancel"><i class="fa fa-times"></i></div>
        <div class="user-avatar">
            <img src="" alt="" title="" class="avatar">
        </div>
        <div class="user-name"></div>
        <div class="login-form mt-1">
            <?php
            $form = ActiveForm::begin([
                'id' => 'login-form',
//                'enableAjaxValidation' => true,
//                'validationUrl' => Url::toRoute(['validate-login']),
                'action' => Url::toRoute(['submit-login']),
                'options' => [
                    'class' => 'form-horizontal form-simple',
                    'redirect-on-submit' => Url::toRoute(['/site/index']),
                ],
            ]);
            ?>
            <div class="form-group login-username">
                <?= $form->field($model, 'username')->textInput(['class' => 'form-control username', 'autocomplete' => 'off', 'placeHolder' => 'Username'])->label(false) ?>
            </div>
            <div class="form-group login-password">
                <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control password', 'autocomplete' => 'off', 'placeHolder' => 'Password'])->label(false) ?>
            </div>
            <div class="form-group">
                <?php echo $form->field($model, 'reCaptcha')->widget(
                \himiklab\yii2\recaptcha\ReCaptcha2::class,
                ['siteKey' => RECAPTCHA_GOOGLE_SITEKEY]
            )->label(false) ?>
            </div>
            <div class="form-group text-center">
                <?= Html::submitButton('Đăng nhập', ['class' => 'btn btn-primary submit-login mb-1']) ?>
                <div class="login-with-other-user-content">
                    <?= Html::a('Đăng nhập bằng tài khoản khác', 'javascript:;', ['class' => 'login-with-other-user']) ?>
                </div>
                <p class="text-center m-0 w-100">
                    <?= Html::a('Quên mật khẩu?', 'javascript:void(0)', ['class' => 'request-password-reset'])?>
                </p>
            </div>
            <?php ActiveForm::end() ?>
        </div>
        <div class="auth-form mt-1">
            <?php
            $form = ActiveForm::begin([
                'id' => 'auth-form',
//                'enableAjaxValidation' => true,
//                'validationUrl' => Url::toRoute(['validate-auth']),
                'action' => Url::toRoute(['submit-auth']),
                'options' => [
                    'class' => 'form-horizontal form-simple',
                    'redirect-on-submit' => Url::toRoute(['/site/index']),
                ],
            ]);
            ?>
            <?= $form->field($modelAuth, 'username')->hiddenInput(['class' => 'auth-username'])->label(false) ?>
            <div class="form-group">
                <?= $form->field($modelAuth, 'auth')->textInput(['class' => 'form-control auth text-center', 'placeHolder' => 'Mã xác thực'])->label(false) ?>
            </div>
            <div class="form-group text-center">
                <?= Html::submitButton('Xác thực', ['class' => 'btn btn-primary']) ?>
                <div class="resend-auth-content text-center mt-1">
                    Không nhận được mã? <?= Html::a('Gửi lại.', ['resend-pin', 'user' => ''], ['class' => 'resend-auth']) ?>
                </div>
            </div>
            <?php ActiveForm::end() ?>
        </div>
        <p class="text-center m-0 w-100" style="color:silver"><?= LOGIN_VERSION ?></p>
    </div>
    <div class="reset-content">
        <div class="card-title m-0">Lấy lại mật khẩu</div>
        <div class="reset-pass-form w-100 mt-1 overflow-hidden">
            <?php
            $form = ActiveForm::begin([
                'id' => 'request-password-reset-form',
                'action' => '/auth/request-password-reset',
                'options' => [
                    'class' => "form-horizontal",
                    'novalidate' => true,
                    'redirect-on-submit' => Url::toRoute(['/auth/login']),
                ]
            ])
            ?>
            <div class="msg-resp"></div>
            <div class="form-group email-login">
                <?php echo $form->field($modelResetPass, 'email', [
                    'options' => ['tag' => false]
                ])->textInput([
                    'class' => 'form-control',
                    'placeholder' => \Yii::t('backend', 'E-mail')
                ])->label(false); ?>
            </div>
            <div class="form-group">
                <?php echo $form->field($modelResetPass, 'reCaptcha')->widget(
                    \himiklab\yii2\recaptcha\ReCaptcha2::class,
                    ['siteKey' => RECAPTCHA_GOOGLE_SITEKEY]
                )->label(false) ?>
            </div>
            <div class="form-group text-center">
                <?= Html::submitButton('Chấp nhận', ['class' => 'btn btn-primary submit-resetpass']) ?>
            </div>
            <?php ActiveForm::end() ?>
            <p class="text-center m-0 mb-1 w-100">
                <?= Html::a('<i class="fa fa-arrow-left"></i> Đăng nhập', 'javascript:void(0)', ['class' => 'back-to-login'])?>
            </p>
        </div>
        <p class="text-center m-0 w-100" style="color:silver"><?= LOGIN_VERSION ?></p>
    </div>

    <span class="s1 d-block d-sm-none animated fadeIn"><i class="ft-plus"></i></span>

    <div class="login-list">
        <ul class="list-user-logged"></ul>
        <li class="user-logged user-tmp">
            <div class="user-logged-avatar">
                <img src="" alt="" title="">
            </div>
            <div class="user-logged-name"></div>
        </li>
    </div>
</div>
<div class="login-list-mb"></div>
<script>
    var folder = '';
</script>