<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use backend\modules\customer\models\Dep365CustomerOnline;

/* @var $this yii\web\View */
/* @var $model backend\modules\customer\models\Dep365CustomerOnline */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Khách hàng trực tuyến', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$user = new Dep365CustomerOnline();
$users = $user->getUserCreatedBy($model->getAttribute('created_by'));

if ($users == false) {
    $userCreatedInTimeLine = false;
} else {
    $userCreatedInTimeLine = $users->fullname;
}

if ($model->time_lichhen) {
    $model->time_lichhen = date('d-m-Y', $model->time_lichhen);
}

?>

    <div class="modal-header bg-blue-grey bg-lighten-2 white">
        <h4 class="modal-title">Gửi SMS: <?= $this->title; ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="modal-body">
        <div class="send-sms-view">
            <div class="row">
                <div class="col-12">
                    <?php
                    if ($model->getAttribute('province') == 97) {
                        echo '<h4 style="color: red">Bạn không thể gửi tin cho khách hàng là người nước ngoài.</h4>';
                    } else {
                        ?>
                        <?php
                        $form = ActiveForm::begin([
                            'id' => 'send-sms-form',
//                                'action' => 'view',
//                                'enableAjaxValidation' => true,
//                                'validationUrl' => 'validate-sms',
                        ]); ?>

                        <div class="form-group">
                            <label for="sms-text" class="control-label">Nội dung tin nhắn</label>
                            <label style="float: right; color: red" class="len-char"></label>
                            <?= Html::textarea('', $sms_text, [
                                'class' => 'form-control',
                                'id' => 'sms-text',
                                'readonly' => 'readonly',
                                'rows' => 8,
                                'onkeyup' => "countChar(this)"
                            ]) ?>
                        </div>
                        <?= Html::hiddenInput('', $model->getAttribute('id'), [
                            'id' => 'formsendsms-customer_id'
                    ]) ?>

                        <?php /*<?= $form->field($sendSmsForm, 'sms_text', [
                            'template' => '{label}<label style="float: right; color: red" class="len-char"></label>{input}{error}'
                        ])->textarea([
                            'rows' => 8,
                            'onkeyup' => 'countChar(this)'
                        ])->label('Nội dung tin nhắn'); ?>

                        <?= $form->field($sendSmsForm, 'customer_id')->hiddenInput(['value' => $model->getAttribute('id')])->label(false); ?>

                        <?= $form->field($sendSmsForm, 'sms_to')->hiddenInput(['value' => $model->getAttribute('phone')])->label(false); ?>
                        <div class="row">
                            <div class="col-lg-6 col-xl-6 col-md-6 col-6">
                                <?= $form->field($sendSmsForm, 'sms_lanthu')->dropDownList(
                            \backend\modules\customer\models\FormSendSms::getLanSms(),
                            [
                                        'class' => 'ui dropdown',
                                        'prompt' => 'Chọn loại tin nhắn ...'
                                    ]
                        )->label(false); ?>

                            </div>
                        </div>*/ ?>
                        <div class="form-actions">
                            <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
                                'btn btn-warning mr-1']) ?>
                            <?= Html::submitButton(
                                '<i class="fa fa-check-square-o"></i> Send',
                                ['class' => 'btn btn-primary submit-form-send-sms']
                            ) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                        <?php
                    }
                    ?>
                </div>
                <div class="col-md-6 col-12 d-none">
                    <h3 class="page-title text-center" style="line-height: 1.5;">
                        Timeline
                    </h3>
                    <section id="timeline" class="timeline-center timeline-wrapper">
                        <ul class="timeline">
                            <li class="timeline-line"></li>
                            <li class="timeline-line"></li>
                            <li class="timeline-item">
                                <div class="timeline-badge">
                                <span class="bg-teal bg-lighten-1"
                                      data-toggle="tooltip"
                                      data-placement="left"
                                      title="Nullam facilisis fermentum"><i
                                            class="ft-corner-up-right"></i></span>
                                </div>
                                <div class="timeline-card card border-grey border-lighten-2">
                                    <div class="card-header">
                                        <h4 class="card-title"><a href="#">Ngày hẹn</a></h4>

                                        <span class="font-small-3"><?= $model->getAttribute('time_lichhen'); ?> </span>
                                        <a class="heading-elements-toggle"><i
                                                    class="fa fa-ellipsis-v font-medium-3"></i></a>
                                        </br>
                                        <?php
                                        if ($model->getAttribute('dat_hen') == 1) {
                                            ?>
                                            <div class="badge badge-danger"> Đã đến</div>
                                            <?php
                                        } elseif ($model->getAttribute('dat_hen') == 2) {
                                            ?>
                                            <div class="badge"
                                                 style="background-image: linear-gradient(45deg,rgba(142,114,136,0.47),#8e7288); color: #fff;">
                                                Không đến
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="badge badge-light"> Chưa cập nhật</div>
                                            <?php
                                        } ?>
                                    </div>
                                </div>
                            </li>
                        </ul>

                        <?php
                        if ($smsSended) {
                            ?>
                            <?php
                            foreach ($smsSended as $key => $item) {
                                $smsLanthu = '';
                                if ($item->sms_lanthu == 1) {
                                    $smsLanthu = 'Trước 1 ngày';
                                    $color = 'blue';
                                }

                                if ($item->sms_lanthu == 3) {
                                    $smsLanthu = 'Trước 3 ngày';
                                    $color = 'cyan ';
                                }

                                if ($item->sms_lanthu == 7) {
                                    $color = 'purple ';
                                    $smsLanthu = 'Trước 7 ngày';
                                }

                                if ($item->sms_lanthu == 0) {
                                    $color = 'red';
                                    $smsLanthu = 'Khác';
                                } ?>
                                <ul class="timeline">
                                    <li class="timeline-line"></li>
                                    <li class="timeline-line"></li>
                                    <li class="timeline-item">
                                        <div class="timeline-badge">
                                                        <span class="<?php if ($item->status == 0) {
                                                            echo 'bg-teal ';
                                                        } else {
                                                            echo 'bg-red ';
                                                        } ?> ?>bg-lighten-1"
                                                              data-toggle="tooltip"
                                                              data-placement="left"
                                                              title="Nullam facilisis fermentum"><i
                                                                    class="ft-mail"></i></span>
                                        </div>
                                        <div class="timeline-card card border-grey border-lighten-2">
                                            <div class="card-header">
                                                <h4 class="card-title"><a
                                                            href="#"><?= $smsLanthu; ?></a></h4>

                                                <span class="font-small-3"><?= date('d-m-Y H:i', $item->created_at); ?> </span><br>
                                                <?php
                                                if ($item->status == 0) {
                                                    ?>
                                                    <div class="badge badge-primary">
                                                        Thành công
                                                    </div>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <div class="badge badge-danger">
                                                        Không thành công. Mã
                                                        lỗi: <?= $item->status; ?>
                                                    </div>
                                                    <?php
                                                } ?>
                                                <a class="heading-elements-toggle"><i
                                                            class="fa fa-ellipsis-v font-medium-3"></i></a>
                                            </div>
                                            <div class="card-content">
                                                <div class="card-body">
                                                    <p><a href="#"
                                                          class="click-show-content-sms"
                                                          data-sms="<?= $item->id; ?>">...</a>
                                                    </p>
                                                    <p class="card-text sms-content-<?= $item->id; ?>"
                                                       style="display: none"><?= $item->sms_text; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <?php
                            }
                        }
                        ?>
                        <ul class="timeline">
                            <li class="timeline-line"></li>
                            <li class="timeline-line"></li>
                            <li class="timeline-item">
                                <div class="timeline-badge">
                                        <span class="bg-teal bg-lighten-1"
                                              data-toggle="tooltip"
                                              data-placement="left"
                                              title="Nullam facilisis fermentum"><i
                                                    class="ft-plus"></i></span>
                                </div>
                                <div class="timeline-card card border-grey border-lighten-2">
                                    <div class="card-header">
                                        <h4 class="card-title"><a
                                                    href="#">Ngày tạo</a></h4>

                                        <span class="font-small-3"><?= date('d-m-Y H:i', $model->getAttribute('created_at')); ?> </span>
                                        <a class="heading-elements-toggle"><i
                                                    class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    </div>
                                    <div class="card-content">
                                        <div class="card-body">
                                            <p class="card-text">
                                                by: <?= $userCreatedInTimeLine ?></p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer p-0"></div>
<?php

//$this->registerCssFile('/css/pages/timeline.css');
$url = \yii\helpers\Url::toRoute(['/customer/customer-online/send-sms']);
$urlChangeSms = \yii\helpers\Url::toRoute(['/customer/customer-online/change-sms-customer']);
$idCustomer = $model->getAttribute('id');
$script = <<< JS
    $('.ui.dropdown').dropdown();

    $('.click-show-content-sms').on('click', function () {
        var dataSms = $(this).attr('data-sms');
        $(this).hide();
        $('.sms-content-' + dataSms).slideDown();
    });
    
    $('#formsendsms-sms_lanthu').on('change', function () {
        var id = $(this).val();
        if (id == '' || id == 0) {
            $('#formsendsms-sms_text').val('').empty();
            $('.len-char').html('');
            return false;
        }
    
        $('#send-sms-form').block({
            message: '<div class="semibold"><span class="ft-refresh-cw icon-spin text-left"></span> <br>Loading...</div>',
            overlayCSS: {
                backgroundColor: '#FFF',
                opacity: 1,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
        $('#formsendsms-sms_text').val('').empty();
    
        $.ajax({
                url: '$urlChangeSms',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id,
                    idCustomer: '$idCustomer'
                }
            })
            .done(function (data) {
                if (data.status == 1) {
                    $('#formsendsms-sms_text').val(data.text);                    
                } else {
                    toastr.error(data.text, 'Thông báo');
                }
                $('.len-char').html(data.text.length);
                $('#send-sms-form').unblock();
            })
            .fail(function (data) {
                console.log(data);
                // $('#formsendsms-sms_text').val('Chưa có dữ liệu.');
                toastr.error('Chưa có dữ liệu', 'Thông báo');
                $('#send-sms-form').unblock();
            });
    });
    
    function countChar(val) {
        var len = val.value.length;
        $('.len-char').html(len);
    }
    countChar($('#sms-text')[0]);
    $('body').find("form#send-sms-form").unbind("beforeSubmit").bind("beforeSubmit", function (e) {
        e.preventDefault();
        var currentUrl = $(location).attr('href');
        
        $('.send-sms-view').block({
            message: '<div class="semibold"><span class="ft-refresh-cw icon-spin text-left"></span> <br>Loading...</div>',
            overlayCSS: {
                backgroundColor: '#FFF',
                opacity: 1,
                cursor: 'wait',
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
    
        var form = $(this);
        if (form.find('.has-error').length) {
            return false;
        }
    
        var sms_text = form.find('#formsendsms-sms_text').val();
        var customer_id = form.find('#formsendsms-customer_id').val();
        var phone = form.find('#formsendsms-sms_to').val();
        var sms_lanthu = form.find('#formsendsms-sms_lanthu').val();
        
        // submit form
        $.ajax({
            url: '$url',
            type: 'POST',
            dataType: "json",
            data: {
                sms_text: sms_text,
                customer_id: customer_id,
                phone: phone,
                sms_lanthu: sms_lanthu,
            }
        }).done(function(response){
            // if (sms_lanthu == 1 || sms_lanthu == 3 || sms_lanthu == 7) {
            //     var smsLanthu = 'Trước ' + sms_lanthu + ' ngày';
            // }
            // if (sms_lanthu == 0) {
            //     var smsLanthu = 'Khác';
            // }
            // var d = new Date();
            // var month = d.getMonth() + 1;
            //
            // var toDay = d.getDate() + '-' + month + '-' + d.getFullYear() + ' ' + d.getHours() + ':' + d.getMinutes();
            // var result = '<ul class="timeline"><li class="timeline-line"></li><li class="timeline-line"></li><li class="timeline-item"><div class="timeline-badge"><span class="bg-teal bg-lighten-1" data-toggle="tooltip" data-placement="left">';
            // result += '<i class="ft-mail"></i></span></div><div class="timeline-card card border-grey border-lighten-2"><div class="card-header">';
            // result += '<h4 class="card-title"><a href="#">' + smsLanthu + '</a></h4>';
            // result += '<p class="card-subtitle text-muted mb-0 pt-1"><span class="font-small-3">' + toDay + '</span></p><a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a></div>';
            // result += '<div class="card-content"><div class="card-content"><div class="card-body"><p class="card-text">' + sms_text + '</p></div></div></div></div></li></ul>';
            //
            // $(result).prependTo('section#timeline');
            if (response.status == '1') {
                $.when($.pjax.reload({url: currentUrl, method: 'POST', container: '#customer-online-ajax'})).done(function(){
                    $('.modal-header').find('.close').trigger('click');
                    toastr.success(response.text, 'Thông báo');
                });
            } else {
                toastr.error(response.text, 'Thông báo');                    
            }
            $('.send-sms-view').unblock();
        }).fail(function(error){
            console.log(error);
            toastr.warning('Lỗi hệ thống, liên hệ nhân viên kỹ thuật', 'Thông báo');
            $('.send-sms-view').unblock();
        });
        
        form.trigger("reset");
        $('.len-char').empty();
        return false;
    });
JS;

$this->registerJs($script, \yii\web\View::POS_END);
$this->registerCSS('
#timeline.timeline-center .timeline-line{left:0}
#timeline.timeline-center .timeline-item{width:100%}
');
?>