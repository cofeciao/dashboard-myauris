<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Cấu hình";
?>
    <section id="dom">
        <div class="row">
            <div class="col-12">
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
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><?= $this->title ?></h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <?php
                            $form = ActiveForm::begin([
                                'id' => 'form-setting',
                                'enableAjaxValidation' => true,
                                'validationUrl' => Url::toRoute(['validate-setting']),
                            ]);
                            ?>
                            <div class="cc-block">
                                <div class="ccb-header">
                                    <div class="ccbh-title">Bảo mật tài khoản</div>
                                </div>
                                <div class="ccb-content">
                                    <div class="list-settings row">
                                        <div class="col-md-4 col-sm-4 col-xs-6">
                                            <label class="setting <?= ($model->auth_message == 1 || $model->auth_mail == 1) ? 'active' : '' ?>">
                                                <div class="setting-label">
                                                    <label>Xác thực 2 bước</label>
                                                </div>
                                                <div class="setting-button">
                                                    <div class="rounded-checkbox">
                                                        <input type="checkbox"
                                                               onchange="$(this).is(':checked')?$('.auth-child').removeClass('hide'):$('.auth-child').addClass('hide').find('input[type=checkbox]').prop('checked',false)" <?= ($model->auth_message == 1 || $model->auth_mail == 1) ? 'checked' : '' ?>>
                                                        <span></span>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-6 auth-child <?= $model->auth_message == 1 || $model->auth_mail == 1 ? '' : 'hide' ?>">
                                            <?= $form->field($model, 'auth_message')->checkbox([
                                                'template' => "
                                                    <label class='setting " . ($model->auth_message == 1 ? 'active' : '') . "'>
                                                        <div class='setting-label'>{label}</div>
                                                        <div class='setting-button'>
                                                            <div class='rounded-checkbox'>
                                                                {input}
                                                                <span></span>
                                                            </div>
                                                        </div>
                                                        {error}
                                                    </label>
                                                "
                                            ]) ?>
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-6 auth-child <?= $model->auth_message == 1 || $model->auth_mail == 1 ? '' : 'hide' ?>">
                                            <?= $form->field($model, 'auth_mail')->checkbox([
                                                'template' => "
                                                    <label class='setting " . ($model->auth_mail == 1 ? 'active' : '') . "'>
                                                        <div class='setting-label'>{label}</div>
                                                        <div class='setting-button'>
                                                            <div class='rounded-checkbox'>
                                                                {input}
                                                                <span></span>
                                                            </div>
                                                        </div>
                                                        {error}
                                                    </label>
                                                "
                                            ]) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <?= Html::submitButton('<i class="fa fa-check-square-o"></i> Save', ['class' => 'btn btn-primary btn-submit']) ?>
                            </div>
                            <?php ActiveForm::end() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
$script = <<< JS
$('body').on('change', '.rounded-checkbox > input', function(){
    if($(this).is(':checked')){
        $(this).closest('.setting').addClass('active');
    } else {
        $(this).closest('.setting').removeClass('active');
    }
});
$('#form-setting').on('afterValidateAttribute', function (event, fields, errors) {
    if (errors.length > 0) {
        $('#'+ fields.id).prop('checked', false).closest('.setting').removeClass('active');
    }
});
JS;
$this->registerJs($script, \yii\web\View::POS_END);
