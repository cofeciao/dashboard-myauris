<?php

use backend\modules\customer\models\Dep365CustomerOnline;
use dosamigos\datepicker\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->registerCss('
    .form-search-col .f-title {
        width: 70px;
    }
    .form-search .f-content {
        align-items: start;
    }
');
$this->title = 'Tìm kiếm và gửi sms';
?>
<?php $form = ActiveForm::begin([
    'options' => [
        'data-pjax' => 1,
        'id' => 'form-sms-customer-online',
    ],
    'enableAjaxValidation' => true,
    'validationUrl' => \yii\helpers\Url::toRoute('validate-sms'),
]); ?>
    <section id="dom">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content collapse show customer-index ">
                        <div class="card-body card-dashboard">
                            <div class="form-search">
                                <div class="row">
                                    <div class="form-search-col col-xl-5">
                                        <div class="form-group form-content row ml-0 mr-0">
                                            <div class="f-title control-label">
                                                Ngày tạo
                                            </div>
                                            <div class="f-content">
                                                <div class="search-date creation_time_from date-date date-range">
                                                    <?= $form->field($model, 'date_create_from')->widget(DatePicker::class, [
                                                        'template' => '{input}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>{addon}',
                                                        'clientOptions' => [
                                                            'format' => 'dd-mm-yyyy',
                                                            'autoclose' => true,
                                                        ],
                                                        'clientEvents' => [],
                                                        'options' => [
                                                            'placeholder' => "Từ ngày",
                                                            'autocomplete' => 'off'
                                                        ]
                                                    ])->label(false) ?>
                                                </div>
                                                <div class="date-range search-date-text text-to">-</div>
                                                <div class="search-date creation_time_to date-range">
                                                    <?= $form->field($model, 'date_create_to')->widget(DatePicker::class, [
                                                        'template' => '{input}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>{addon}',
                                                        'clientOptions' => [
                                                            'format' => 'dd-mm-yyyy',
                                                            'autoclose' => true,
                                                        ],
                                                        'clientEvents' => [],
                                                        'options' => [
                                                            'placeholder' => "Đến ngày",
                                                            'autocomplete' => 'off'
                                                        ]
                                                    ])->label(false) ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-content row ml-0 mr-0">
                                            <div class="f-title control-label">
                                                Lịch hẹn
                                            </div>
                                            <div class="f-content">
                                                <div class="search-date appointment_time_from date-date date-range">
                                                    <?= $form->field($model, 'date_dathen_from')->widget(DatePicker::class, [
                                                        'template' => '{input}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>{addon}',
                                                        'clientOptions' => [
                                                            'format' => 'dd-mm-yyyy',
                                                            'autoclose' => true,
                                                        ],
                                                        'clientEvents' => [],
                                                        'options' => [
                                                            'placeholder' => "Từ ngày",
                                                            'autocomplete' => 'off'
                                                        ]
                                                    ])->label(false) ?>
                                                </div>
                                                <div class="date-range search-date-text text-to">-</div>
                                                <div class="search-date appointment_time_to date-range">
                                                    <?= $form->field($model, 'date_dathen_to')->widget(DatePicker::class, [
                                                        'template' => '{input}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>{addon}',
                                                        'clientOptions' => [
                                                            'format' => 'dd-mm-yyyy',
                                                            'autoclose' => true,
                                                        ],
                                                        'clientEvents' => [],
                                                        'options' => [
                                                            'placeholder' => "Đến ngày",
                                                            'autocomplete' => 'off'
                                                        ]
                                                    ])->label(false) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-search-col col-xl-7">
                                        <div class="row" style="padding-top:0px;padding-bottom:2px">
                                            <div class="form-search-col co_so col-md-4 col-12">
                                                <div class="form-group row ml-0 mr-0">
                                                    <div class="f-content w-100">
                                                        <div class="search-code">
                                                            <?php
                                                            echo $form->field($model, 'status', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])
                                                                ->dropDownList(Dep365CustomerOnline::getStatusCustomerOnline(), ['id' => 'ui-drop', 'class' => 'ui dropdown form-control', 'prompt' => 'Chọn trạng thái..'])
                                                                ->label(false);
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-search-col mult col-md-4 col-12">
                                                <div class="form-group form-content row ml-0 mr-0">
                                                    <div class="f-content w-100">
                                                        <div class="search-code">
                                                            <?php
                                                            echo $form->field($model, 'den_or_khong_den', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])
                                                                ->dropDownList(Dep365CustomerOnline::getStatusDatHen(), ['class' => 'ui dropdown form-control', 'prompt' => 'Trạng thái đặt hẹn..'])
                                                                ->label(false);
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-search-col mult col-xl-4 col-md-4 col-sm-6 col-12">
                                                <div class="form-group form-content row ml-0 mr-0">
                                                    <div class="f-content w-100">
                                                        <div class="search-code">
                                                            <?php
                                                            echo $form->field($model, 'who_create', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])
                                                                ->dropDownList(Dep365CustomerOnline::getCustomerForWho(), ['id' => 'ui-drop', 'class' => 'ui dropdown form-control', 'prompt' => 'Chọn khách...'])
                                                                ->label(false);
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-search-col mult col-sm-6 col-12">
                                        <div class="form-group form-content row ml-0 mr-0">
                                            <div class="f-title control-label">
                                                Nội dung
                                            </div>
                                            <div class="f-content">
                                                <div class="f-content w-100">
                                                    <div class="search-code">
                                                        <?php
                                                        echo $form->field($model, 'content')
                                                            ->textarea(['rows' => 8])
                                                            ->label(false);
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-search-col mult col-sm-6 col-12">
                                        <div class="form-group form-content row ml-0 mr-0">
                                            <div class="f-title control-label">
                                            </div>
                                            <div class="f-content">
                                                <div class="f-content w-100">
                                                    <div class="search-code">
                                                        <?= Html::submitButton('Gửi Sms', ['class' => 'btn btn-primary', 'tabindex' => 1]) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-search-col mult col-sm-6 col-12">
                                        <div class="form-group form-content row ml-0 mr-0">
                                            <div class="f-title control-label">
                                            </div>
                                            <div class="f-content">
                                                <div class="f-content w-100">
                                                    <div class="search-code result">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php ActiveForm::end(); ?>

<?php
$url = \yii\helpers\Url::toRoute('send-sms');
$script = <<< JS
    $('body').on('beforeSubmit', 'form#form-sms-customer-online', function() {
        var formData = $('#form-sms-customer-online').serialize();
        c = confirm('Bạn có muốn gửi tin nhắn hàng loạt?');
        if(c) {
            $('body').myLoading({
                msg: 'Đang gửi tin...',
            });
            $.ajax({
                type: 'POST',
                dataType: 'json',
                url: '$url',
                data: formData,
            }).done(function(res) {
                if(res.status == 200) {
                    $('.result').html('Tổng số khách là: '+res.total+'. Đã gửi thành công: '+res.success+'. Gửi thất bại: ' +res.fail);
                }
                $('body').myUnloading();
            }).fail(function(err) {
                console.log(err);
                $('body').myUnloading();
            });
        }
        
        return false;
    });
JS;

$this->registerJs($script, \yii\web\View::POS_END);
