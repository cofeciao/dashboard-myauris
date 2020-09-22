<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ChangePasswordForm */
/* @var $form ActiveForm */

$this->title = 'Thay đổi mật khẩu';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="content-body">
    <?= $this->render('layout/header'); ?>
    <section id="timeline" class="timeline-center timeline-wrapper">
        <div class="col-md-12">
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
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <?php $form = ActiveForm::begin(['id' => 'form-change-pass']); ?>
                        <div class="form-body">
                            <h4 class="form-section"><i class="ft-lock"></i> Cập nhật mật khẩu</h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <?= $form->field($model, 'old_password')->passwordInput(['placeholder' => "Mật khẩu cũ"]) ?>
                                </div>
                                <div class="col-md-12">
                                    <?= $form->field($model, 'password')->passwordInput(['placeholder' => "Mật khẩu mới"]) ?>
                                </div>
                                <div class="col-md-12">
                                    <?= $form->field($model, 'confirm_password')->passwordInput(['placeholder' => "Nhập lại mật khẩu mới"]) ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-warning mr-1">
                                <i class="ft-x"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-check-square-o"></i> Save
                            </button>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php
$this->registerCssFile('/css/pages/users.css');
$this->registerCssFile('/css/pages/timeline.css');

$script = <<< JS
    $('body').on('beforeSubmit', 'form#form-change-pass', function (event) {
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
