<?php

use backend\modules\setting\models\Dep365CoSo;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\modules\user\models\User;
use dosamigos\datepicker\DatePicker;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/*$cls_col_1 = 'col-sm-4 col-12';
$cls_search_date_range = 'hidden';
$cls_search_code = 'hidden';
if ($model->type_search_date == 'range') {
    $cls_col_1 = 'col-sm-6 col-12 search-by-range';
    $cls_search_date_range = '';
}*/

$cls_col_1 = 'col-xxl-4 col-xl-4 col-md-6 col-12';
$cls_search_date_apm = $cls_search_date_cc = 'hidden';
if ($model->type_search_lichhen == 'range' || $model->type_search_customer_come == 'range') {
    $cls_col_1 = 'col-xxl-5 col-xl-4 col-12 search-by-range';
}
if ($model->type_search_lichhen == 'range') {
    $cls_search_date_apm = '';
}
if ($model->type_search_customer_come == 'range') {
    $cls_search_date_cc = '';
}

?>
<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'options' => [
        'data-pjax' => 1,
        'id' => 'form-search-clinic',
    ],
]) ?>
    <div class="form-search">
        <div class="row">
            <div class="form-search-col <?= $cls_col_1 ?>">
                <div class="form-group form-content row ml-0 mr-0">
                    <!--f-title-->
                    <div class="f-title">
                        <?= $form->field($model, 'type_search_lichhen')->radioList([
                            'date' => 'Ngày hẹn',
                            'range' => 'Khoảng ngày'
                        ], [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                return '
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="' . $name . $index . '" name="' . $name . '" value="' . $value . '" data-name="type-search-date" data-attr="date" ' . ($checked == 1 ? 'checked' : '') . '>
                                    <label class="custom-control-label" for="' . $name . $index . '">' . ucfirst($label) . '</label>
                                </div>
                            ';
                            },
                        ])->label(false) ?>
                    </div>
                    <!--f-title-->
                    <!--f-content-->
                    <div class="f-content">
                        <div class="search-date date-date date-range">
                            <?= $form->field($model, 'time_lichhen_from')->widget(DatePicker::class, [
                                'template' => '{input}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>{addon}',
                                'clientOptions' => [
                                    'format' => 'dd-mm-yyyy',
                                    'autoclose' => true,
                                ],
                                'clientEvents' => [],
                                'options' => [
                                    'placeholder' => "Ngày",
                                    'autocomplete' => 'off'
                                ]
                            ])->label(false) ?>
                        </div>
                        <div class="date-range <?= $cls_search_date_apm ?> search-date-text text-to">-</div>
                        <div class="search-date date-range <?= $cls_search_date_apm ?>">
                            <?= $form->field($model, 'time_lichhen_to')->widget(DatePicker::class, [
                                'template' => '{input}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>{addon}',
                                'clientOptions' => [
                                    'format' => 'dd-mm-yyyy',
                                    'autoclose' => true,
                                ],
                                'clientEvents' => [],
                                'options' => [
                                    'placeholder' => "Ngày",
                                    'autocomplete' => 'off'
                                ]
                            ])->label(false) ?>
                        </div>
                    </div>
                    <!--f-content-->
                </div>

                <div class="form-group form-content row ml-0 mr-0">
                    <!--f-title-->
                    <div class="f-title">
                        <?= $form->field($model, 'type_search_customer_come')->radioList([
                            'date' => 'Ngày đến',
                            'range' => 'Khoảng ngày'
                        ], [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                return '
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="' . $name . $index . '" name="' . $name . '" value="' . $value . '" data-name="type-search-date" data-attr="date" ' . ($checked == 1 ? 'checked' : '') . '>
                                    <label class="custom-control-label" for="' . $name . $index . '">' . ucfirst($label) . '</label>
                                </div>
                            ';
                            },
                        ])->label(false) ?>
                    </div>
                    <!--f-title-->
                    <!--f-content-->
                    <div class="f-content">
                        <div class="search-date date-date date-range">
                            <?= $form->field($model, 'time_customer_come_from')->widget(DatePicker::class, [
                                'template' => '{input}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>{addon}',
                                'clientOptions' => [
                                    'format' => 'dd-mm-yyyy',
                                    'autoclose' => true,
                                ],
                                'clientEvents' => [],
                                'options' => [
                                    'placeholder' => "Ngày",
                                    'autocomplete' => 'off'
                                ]
                            ])->label(false) ?>
                        </div>
                        <div class="date-range <?= $cls_search_date_cc ?> search-date-text text-to">-</div>
                        <div class="search-date date-range <?= $cls_search_date_cc ?>">
                            <?= $form->field($model, 'time_customer_come_to')->widget(DatePicker::class, [
                                'template' => '{input}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>{addon}',
                                'clientOptions' => [
                                    'format' => 'dd-mm-yyyy',
                                    'autoclose' => true,
                                ],
                                'clientEvents' => [],
                                'options' => [
                                    'placeholder' => "Ngày",
                                    'autocomplete' => 'off'
                                ]
                            ])->label(false) ?>
                        </div>
                    </div>
                    <!--f-content-->
                </div>

                <div class="row mx-0">
                    <?= $form->field($model, 'button')->hiddenInput(['id' => 'button-type'])->label(false) ?>
                    <?php
                    $clsTimKiem = $clsMacDinh = $clsHomNay = 'btn-blue-grey';
                    if ($model->button == '') {
                        $clsHomNay = 'btn-primary';
                    }
                    if ($model->button == '1') {
                        $clsTimKiem = 'btn-primary';
                    }
                    if ($model->button == '2') {
                        $clsMacDinh = 'btn-primary';
                    }
                    ?>
                    <div class="col-12 p-0">
                        <?= Html::submitButton('Tìm kiếm', ['class' => 'btn ' . $clsTimKiem, 'id' => 'customer-search', 'tabindex' => 1]) ?>
                        <?= Html::submitButton('Mặc định', ['class' => 'btn ' . $clsMacDinh, 'id' => 'customer-search-default', 'tabindex' => 2]) ?>
                        <?= Html::a('Hôm nay', ['index'], ['class' => 'btn ' . $clsHomNay]) ?>
                        <?= Html::button('Refesh', ['class' => 'btn btn-default', 'id' => 'refresh-data']) ?>
                    </div>
                </div>
            </div>

            <div class="form-search-col col-xl-7">
                <div class="row" style="padding-bottom:8px">
                    <!--customer-->
                    <div class="form-search-col keyword col-md-4 col-12">
                        <div class="form-group form-content row ml-0 mr-0">
                            <div class="f-content">
                                <div class="search-code">
                                    <?= $form->field($model, 'keyword', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-value"><span class="fa fa-times"></span></span></div>'])
                                        ->textInput(['placeholder' => "Họ và tên, số điện thoại hoặc mã khách hàng"])
                                        ->label(false) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--customer-->

                    <!--get trang thai-->
                    <div class="form-search-col mult col-xl-4 col-md-4 col-sm-6 col-12">
                        <div class="form-group form-content row ml-0 mr-0">
                            <div class="f-content">
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
                    <!--get trang thai-->

                    <!--get trang thai dat hen-->
                    <div class="form-search-col mult col-xl-4 col-md-4 col-sm-6 col-12">
                        <div class="form-group form-content row ml-0 mr-0">
                            <div class="f-content">
                                <div class="search-code">
                                    <?php
                                    echo $form->field($model, 'dat_hen', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])
                                        ->dropDownList(Dep365CustomerOnline::getStatusDatHen(), ['class' => 'ui dropdown form-control', 'prompt' => 'Trạng thái đặt hẹn..'])
                                        ->label(false);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--get trang thai dat hen-->

                    <!--get co so-->
                    <div class="form-search-col co_so col-md-4 col-12">
                        <div class="form-group row ml-0 mr-0">
                            <div class="f-content">
                                <div class="search-code">
                                    <?php
                                    echo $form->field($model, 'co_so', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])
                                        ->dropDownList(ArrayHelper::map(Dep365CoSo::getCoSo(), 'id', 'name'), ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn cơ sở...'])
                                        ->label(false);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--get co so-->

                    <!--get trang thai khach den-->
                    <div class="form-search-col mult col-xl-4 col-md-4 col-sm-6 col-12">
                        <div class="form-group form-content row ml-0 mr-0">
                            <div class="f-content">
                                <div class="search-code">
                                    <?php
                                    echo $form->field($model, 'customer_come_time_to', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])
                                        ->dropDownList(ArrayHelper::map(Dep365CustomerOnlineCome::getCustomerOnlineCome(), 'id', 'name'), ['id' => 'ui-drop', 'class' => 'ui dropdown form-control', 'prompt' => 'Trạng thái khách đến..'])
                                        ->label(false);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--get trang thai khach den-->

                    <!--get nhan vien-->
                    <div class="form-search-col mult col-md-4 col-12">
                        <div class="form-group form-content row ml-0 mr-0">
                            <div class="f-content">
                                <div class="search-code">
                                    <?php
                                    echo $form->field($model, 'permission_user', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])
                                        ->dropDownList(User::getNhanVienIsActiveArray(), ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn nhân viên..'])
                                        ->label(false);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--get nhan vien-->

                    <!--get dich vu-->
                    <div class="form-search-col mult col-xl-4 col-md-4 col-sm-6 col-12">
                        <div class="form-group form-content row ml-0 mr-0">
                            <div class="f-content">
                                <div class="search-code">
                                    <?php
                                    echo $form->field($model, 'id_dich_vu', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])
                                        ->dropDownList(Dep365CustomerOnline::getDichVuOnline(), ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn dịch vụ..'])
                                        ->label(false);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--get dich vu-->

                    <!--get direct sale-->
                    <div class="form-search-col mult col-xl-4 col-md-4 col-sm-6 col-12">
                        <div class="form-group form-content row ml-0 mr-0">
                            <div class="f-content">
                                <div class="search-code">
                                    <?= $form->field($model, 'directsale', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])
                                        ->dropDownList(ArrayHelper::map(common\models\User::getNhanVienTuDirectSale(), 'id', 'fullname'), ['id' => 'ui-drop', 'class' => 'ui dropdown form-control', 'prompt' => 'Direct sale..'])
                                        ->label(false);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--get direct sale-->
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end() ?>
<?php
$script = <<< JS
$('.f-title').on('click', 'input', function(){
    var input = $(this),
        attr = input.attr('data-attr'),
        v = input.val(),
        name = input.attr('data-name');
    input.closest('.form-content').find('.f-content div[class*="' + attr + '-"]').addClass('hidden');
    input.closest('.form-content').find('.f-content .' + attr + '-' + v).removeClass('hidden');
    
    if(name == 'type-search-date'){
        if(v == 'range'){
            input.closest('.form-search-col').removeClass('col-xxl-4 col-xl-4 col-md-6 col-12').addClass('col-xxl-5 col-xl-4 col-12 search-by-range');
        } else {
            input.closest('.form-search-col').removeClass('col-xxl-5 col-xl-4 col-12 search-by-range').addClass('col-xxl-4 col-xl-4 col-md-6 col-12');
        }
    }
});
$('#form-search-clinic').on('change paste keyup', 'input', function(){
    $(this).val($(this).val().trim());
});
$('body').find('button[type=submit]').unbind('click').bind('click', function(e) {
    if (e.target.id == 'customer-search') {
        var type_search_date = $('body').find('input[data-name="type-search-date"]:checked').val(),
        type_search_code = $('body').find('input[data-name="type-search-code"]:checked').val();
        if(type_search_date == 'date'){
            $('.search-date.date-range:not(.date-date)').find('input').val('');
        }
        if(type_search_code == 'full_name'){
            $('.search-code.code-code').find('input').val('');
        } else {
            $('.search-code.code-full_name').find('input').val('');
        }
        $('#button-type').val(1);
    } else if (e.target.id == 'customer-search-default') {
        $('#form-search-clinic').find('input[type=text]').val('');
        $('#form-search-clinic').find('select').children('option[value!=""]').prop('selected', false);
        $('#button-type').val(2);
    }
});
$('body').on('click', '#refresh-data', function(){
    $.pjax.reload({url: window.location.href, method: 'POST', container: quanly.options.pjaxId});
})
JS;
$this->registerJs($script, \yii\web\View::POS_END);
$this->registerCss('
.form-search-col.keyword .f-content{width:100%}
.form-search-col.mult .f-content{width:100%}
.form-search-col.co_so .f-content{width:100%}
');
