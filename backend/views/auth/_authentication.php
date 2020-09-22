<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php
$form = ActiveForm::begin([]);
?>
    <div class="back-choose-user">
        <div class="current-user">
            <a>
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
    <div class="alert alert-success">Một mã xác thực đã được gửi cho bạn qua <b>Email</b>/<b>Số điện thoại</b></div>
    <div class="resend-pin" style="display: none;">
        Không nhận được mã xác thực, <a href="<?= Url::toRoute(['resend-pin', 'user' => $params['user']]) ?>">gửi
            lại!</a>
    </div>
    <fieldset class="position-relative has-icon-left">
        <?php echo $form->field($params['model'], 'pin')->textInput(['class' => 'form-control form-control', 'placeholder' => 'Mã xác thực', 'autocomplete' => 'off'])->label(false) ?>
        <div class="form-control-position">
            <i class="fa fa-key"></i>
        </div>
    </fieldset>
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
<?php ActiveForm::end() ?>
<?php
$script = <<< JS
    function timeout(){
        setTimeout(function(){
            $('.alert').slideUp()
            $('.resend-pin').slideDown();
        }, 10000);
    }
    timeout();
    $('body').on('click', '.resend-pin > a', function(e) {
        e.preventDefault();
        $('.login-container').myLoading({
            opacity: true
        });
        var url_resend_pin = $(this).attr('href') || null;
        $('.resend-pin').slideUp();
        if(url_resend_pin !== null){
            $.ajax({
                type: 'POST',
                url: url_resend_pin,
                dataType: 'json',
            }).done(function(res){
                $('.login-container').myUnloading();
                if(res.code === 200){
                    $('.alert').slideDown()
                    $('.resend-pin').slideUp();
                    timeout();
                } else {
                    toastr.error(res.msg, 'Thông báo');
                    $('.resend-pin').slideDown();
                }
            }).fail(function(err){
                $('.login-container').myUnloading();
                console.log('resend pin error', err);
                toastr.error('Gửi mã xác thực thất bại!', 'Thông báo');
                $('.resend-pin').slideDown();
            });
        }
        return false;
    });
JS;
$this->registerJs($script);
