<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \backend\modules\user\models\ResetPasswordForm */

$this->title = Yii::t('frontend', 'Reset password');
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
.form-control-position {
    position: absolute;
    top: 4px;
    right: 0;
    z-index: 2;
    display: block;
    width: 2.5rem;
    height: 2.5rem;
    line-height: 2.5rem;
    text-align: center;
}
.has-icon-left .form-control {
    padding-right: 1rem;
    padding-left: calc(2.75rem + 2px);
}
.has-icon-left .form-control-position {
    right: auto;
    left: inherit;
}
.position-relative .form-control.form-control-lg ~ .form-control-position {
    top: 10px;
}
');
?>
<div class="app-content content h-100">
    <div class="content-wrapper h-100">
        <div class="content-body h-100">
            <section class="flexbox-container d-flex align-items-center h-100">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-8 col-10 box-shadow-2 p-0">
                        <div class="card border-grey border-lighten-3 m-0">
                            <div class="card-header border-0">
                                <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
                                    <span>Cấp lại mật khẩu <?= Html::a('myauris.vn', Url::to(SITE_ADMIN), ['target' => '_blank']); ?></span>
                                </h6>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <?php
                                    if (Yii::$app->session->hasFlash('alert')) {
                                        ?>
                                        <div class="alert <?= Yii::$app->session->getFlash('alert')['class']; ?> alert-dismissible"
                                             role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                            <?= Yii::$app->session->getFlash('alert')['body']; ?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <?php $form = ActiveForm::begin(['id' => 'reset-password-form', 'options' => ['class' => 'form-horizontal form-simple', 'novalidate' => true]]); ?>
                                    <fieldset class="position-relative has-icon-left">
                                        <?php echo $form->field($model, 'password')->passwordInput(['class' => 'form-control form-control', 'placeholder' => 'Mật khẩu', 'required' => ''])->label(false) ?>
                                        <div class="form-control-position">
                                            <i class="fa fa-key"></i>
                                        </div>
                                    </fieldset>

                                    <fieldset class="position-relative has-icon-left">
                                        <?php echo $form->field($model, 'confirm_password')->passwordInput(['class' => 'form-control form-control', 'placeholder' => 'Xác nhận mật khẩu', 'required' => ''])->label(false) ?>
                                        <div class="form-control-position">
                                            <i class="fa fa-key"></i>
                                        </div>
                                    </fieldset>

                                    <fieldset class="position-relative text-center">
                                        <?= $form->field($model, 'reCaptcha')->widget(
                                        \himiklab\yii2\recaptcha\ReCaptcha2::class,
                                        ['siteKey' => RECAPTCHA_GOOGLE_SITEKEY]
                                    )->label(false) ?>
                                    </fieldset>

                                    <?php echo Html::submitButton('<i class="ft-unlock"></i> ' . Yii::t('backend', \Yii::t('backend', 'Đặt lại mật khẩu')), [
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
    $('body').on('beforeSubmit', 'form#reset-password-form', function (event) {
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