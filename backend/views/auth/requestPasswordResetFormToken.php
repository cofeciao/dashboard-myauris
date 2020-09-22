<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model \backend\modules\user\models\PasswordResetRequestForm */

$this->title = Yii::t('frontend', 'Request password reset');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            <section class="flexbox-container">
                <div class="col-12 d-flex align-items-center justify-content-center">
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-8 col-10 box-shadow-2 p-0">
                        <div class="card border-grey border-lighten-3 m-0 p-2">
                            <div class="card-header border-0">
                                <h6 class="card-subtitle line-on-side text-muted text-center font-small-3 pt-2">
                                    <span>Lấy lại mật khẩu</span>
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
                                    <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form', 'options' => ['class' => "form-horizontal", 'novalidate' => true]]); ?>
                                    <fieldset class="form-group position-relative has-icon-left">
                                        <?php echo $form->field($model, 'email', ['options' => ['tag' => false]])->textInput(['class' => 'form-control', 'placeholder' => \Yii::t('backend', 'E-mail')])->label(false); ?>
                                        <div class="form-control-position"><i class="ft-mail"></i></div>
                                    </fieldset>
                                    <fieldset class="position-relative text-center">
                                        <?= $form->field($model, 'reCaptcha')->widget(
                                        \himiklab\yii2\recaptcha\ReCaptcha2::class,
                                        ['siteKey' => RECAPTCHA_GOOGLE_SITEKEY]
                                    )->label(false) ?>
                                    </fieldset>
                                    <?php echo Html::submitButton('<i class="ft-unlock"></i> ' . \Yii::t('backend', 'Khôi phục mật khẩu'), ['class' => 'btn btn-sx btn-outline-primary btn-block block-page']) ?>
                                    <?php ActiveForm::end(); ?>
                                </div>
                            </div>
                            <div class="card-footer border-0 py-0">
                                <p class="float-sm-left text-center">
                                    <?= Html::a('<i class="ft-arrow-right"></i>'.Yii::t('backend', 'Sign In'), ['/auth/login'], [
                                        'class' => 'card-link',
                                        'name' => 'login-button',
                                    ]); ?>
                                </p>
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
    $('body').on('beforeSubmit', 'form#request-password-reset-form', function (event) {
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
/*$this->registerCss('
html body {
    height: 100%;
    background-color: #F5F7FA;
    direction: ltr;
}
html body .content {
    padding: 0;
    position: relative;
    transition: 300ms ease all;
    backface-visibility: hidden;
    min-height: 100%
}
.content-wrapper .flexbox-container {
    display: flex;
    align-items: center;
    height: 100vh;
}
.box-shadow-2 {
    -webkit-box-shadow: 0 5px 11px 0 rgba(0,0,0,.18), 0 4px 15px 0 rgba(0,0,0,.15);
    box-shadow: 0 5px 11px 0 rgba(0,0,0,.18), 0 4px 15px 0 rgba(0,0,0,.15);
    border: 0;
    -webkit-border-radius: .125rem;
    border-radius: .125rem;
}
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
');*/
?>
