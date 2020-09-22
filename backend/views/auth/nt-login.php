<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('backend', 'Đăng Nhập');
?>
    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-body">
                <section class="flexbox-container">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-8 col-10 box-shadow-2 p-0">
                            <div class="card border-grey border-lighten-3 m-0">
                                <div class="card-header border-0">
                                    <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
                                        <span>Đăng nhập vào <?= Html::a('myauris.vn', Url::to(SITE_ADMIN), ['target' => '_blank']); ?></span>
                                    </h6>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <?php
                                        if (Yii::$app->session->hasFlash('alert')) {
                                            ?>
                                            <div class="alert <?= Yii::$app->session->getFlash('alert')['class']; ?> alert-dismissible"
                                                 role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                                <?= Yii::$app->session->getFlash('alert')['body']; ?>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['class' => 'form-horizontal form-simple', 'novalidate' => true]]); ?>
                                        <fieldset class="position-relative has-icon-left mb-0">
                                            <?php echo $form->field($model, 'username')->textInput(['class' => 'form-control form-control', 'placeholder' => 'E-mail', 'autofocus' => ''])->label(false) ?>
                                            <div class="form-control-position">
                                                <i class="ft-user"></i>
                                            </div>
                                        </fieldset>
                                        <fieldset class="position-relative has-icon-left">
                                            <?php echo $form->field($model, 'password')->passwordInput(['class' => 'form-control form-control', 'placeholder' => 'Mật khẩu', 'required' => ''])->label(false) ?>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
<?php
$script = <<< JS
    $('body').on('beforeSubmit', 'form#login-form', function (event) {
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
    });
JS;
$this->registerJs($script, \yii\web\View::POS_END);
?>