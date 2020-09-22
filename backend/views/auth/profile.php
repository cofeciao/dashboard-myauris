<?php

use common\models\UserProfile;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datetimepicker\DateTimePicker;

$this->title = 'Hồ sơ cá nhân';
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
                    <h4 class="card-title" id="basic-layout-colored-form-control">Thông tin cá nhân</h4>
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
                        <div class="card-text">
                            <p>Bạn luôn có thể thay đổi thông tin cá nhân của bạn và bạn chịu trách nhiệm về thông
                                tin của bạn với nhà quản lý.</p>
                        </div>
                        <?php $form = ActiveForm::begin(['id' => 'form-update-profile']); ?>
                        <div class="form-body">
                            <h4 class="form-section"><i class="fa fa-eye"></i> Giới thiệu về bạn</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Họ & tên</label>
                                        <?= $form->field($model, 'fullname')->textInput(['class' => 'form-control border-primary'])->label(false); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($model, 'bithday')->widget(
                DatePicker::class,
                [
                                        'template' => '{input}{addon}',
                                        'clientOptions' => [
                                            'autoclose' => true,
                                            'format' => 'dd-mm-yyyy',
                                        ]
                                    ]
            ); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Biệt danh</label>
                                        <?= $form->field($model, 'nickname')->textInput(['class' => 'form-control border-primary'])->label(false); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Địa chỉ</label>
                                        <?= $form->field($model, 'address')->textInput(['class' => 'form-control border-primary'])->label(false); ?>
                                    </div>
                                </div>
                            </div>
                            <h4 class="form-section"><i class="ft-mail"></i> Thông tin liên hệ</h4>
                            <div class="form-group">
                                <label for="userinput">Email</label>
                                <input type="text" id="userinput" disabled="disabled"
                                       class="form-control border-primary"
                                       value="<?= Yii::$app->user->identity->email; ?>">
                            </div>
                            <div class="form-group">
                                <div class="phone">
                                    <label for="">Số điện thoại</label>
                                    <?= $form->field($model, 'phone')->textInput(['class' => 'form-control border-primary'])->label(false); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">ID Pancake</label>
                                <?= $form->field($model, 'id_pancake')->textInput(['class' => 'form-control border-primary'])->label(false); ?>
                            </div>
                            <div class="form-group">
                                <label for="">Facebook</label>
                                <?= $form->field($model, 'facebook')->textInput(['class' => 'form-control border-primary'])->label(false); ?>
                            </div>
                            <div class="form-group">
                                <label for="">Ghi chú</label>
                                <?= $form->field($model, 'about')->textarea(['class' => 'form-control border-primary', 'rows' => 4])->label(false); ?>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="">Ảnh đại diện</label>
                                        <?= $form->field($model, 'avatar')->fileInput([])->label(false) ?>
                                    </div>
                                    <img src="<?= UserProfile::getAvatar('200x200') ?>" style="max-width:200px;max-height:200px;">
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="">Hình nền đăng nhập</label>
                                        <?= $form->field($model, 'cover')->fileInput([])->label(false) ?>
                                    </div>
                                    <img src="<?= UserProfile::getCover() ?>" style="max-width:400px;max-height:300px;">
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-warning mr-1">
                                <i class="ft-x"></i> Close
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
$this->registerCssFile('https://cdn.myauris.vn/plugins/myCss/myPlugins.css');

$this->registerCss('
.form-group.row-phone {
    display: flex;
}
.form-group.row-phone .phone {
    width: 100%;
}
.row-phone .authenticate {
    width: 130px;
    margin-left: 10px;
    text-align: right;
}
');

$script = <<< JS
    $('body').on('beforeSubmit', 'form#form-update-profile', function (event) {
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
