<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use backend\modules\setting\models\Dep365CoSo;
use yii\helpers\ArrayHelper;

$cls_col_1 = 'col-xxl-6 col-xl-4 col-sm-6 col-12';
$cls_search_date_range = 'hidden';
$cls_search_code = 'hidden';
if ($model->type_search_date == 'range') {
    $cls_col_1 = 'col-lg-6 col-12 search-by-range';
    $cls_search_date_range = '';
}
?>
<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'options' => [
        'data-pjax' => 1,
        'id' => 'form-search-clinic',
    ],
]); ?>
    <div class="form-search">
        <div class="row">
            <div class="form-search-col col-xl-4 col-sm-6 col-12">
                <div class="form-group form-content row ml-0 mr-0">
                    <div class="f-title control-label">
                        Khách hàng
                    </div>
                    <div class="f-content">
                        <div class="search-code">
                            <?= $form->field($model, 'full_name', ['template' => '<div class="input-group">{input}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span></div>'])->textInput(['placeholder' => "Họ và tên hoặc số điện thoại"])->label(false) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 d-none d-md-block"></div>
            <div class="form-search-col <?= $cls_col_1 ?>">
                <div class="form-group form-content row ml-0 mr-0">
                    <div class="f-title">
                        <?= $form->field($model, 'type_search_date')->radioList([
                            'date' => 'Ngày',
                            'range' => 'Khoảng'
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
                    <div class="f-content">
                        <div class="search-date date-date date-range">
                            <?= $form->field($model, 'from')->widget(DatePicker::class, [
                                'template' => '{input}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>{addon}',
                                'clientOptions' => [
                                    'format' => 'dd-mm-yyyy',
                                    'autoclose' => true,
                                    'todayHighlight' => 'true'
                                ],
                                'clientEvents' => [],
                                'options' => [
                                    'placeholder' => "Ngày",
                                    'autocomplete' => 'off'
                                ]
                            ])->label(false) ?>
                        </div>
                        <div class="date-range <?= $cls_search_date_range ?> search-date-text text-to">-</div>
                        <div class="search-date date-range <?= $cls_search_date_range ?>">
                            <?= $form->field($model, 'to')->widget(DatePicker::class, [
                                'template' => '{input}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>{addon}',
                                'clientOptions' => [
                                    'format' => 'dd-mm-yyyy',
                                    'autoclose' => true,
                                    'todayHighlight' => 'true'
                                ],
                                'clientEvents' => [],
                                'options' => [
                                    'placeholder' => "Ngày",
                                    'autocomplete' => 'off'
                                ]
                            ])->label(false) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
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
            <div class="col-12">
                <?= Html::submitButton('Tìm kiếm', ['class' => 'btn '. $clsTimKiem, 'id' => 'customer-search', 'tabindex' => 1]) ?>
                <?= Html::submitButton('Mặc định', ['class' => 'btn '. $clsMacDinh, 'id' => 'customer-search-default', 'tabindex' => 2]) ?>
                <?= Html::a('7 ngày trước', ['index'], ['class' => 'btn ' . $clsHomNay]) ?>
                <?= Html::button('Refesh', ['class' => 'btn btn-default', 'id' => 'refresh-data']) ?>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
<?php
$script = <<< JS
$('.f-title').on('click', 'input', function(){
    var input = $(this),
        attr = input.attr('data-attr'),
        v = input.val(),
        name = input.attr('data-name');
    input.closest('.form-content').find('.f-content div[class*="'+ attr +'-"]').addClass('hidden');
    input.closest('.form-content').find('.f-content .'+ attr +'-'+ v).removeClass('hidden');
    if(name == 'type-search-date'){
        if(v == 'range'){
            input.closest('.form-search-col').removeClass('col-lg-4 col-md-6 col-12').addClass('col-lg-6 col-12 search-by-range');
        } else {
            input.closest('.form-search-col').removeClass('col-lg-6 col-12 search-by-range').addClass('col-lg-4 col-md-6 col-12');
        }
    }
});
$('#form-search-clinic').on('mouseout mouseleave', 'input', function(){
    var v = $(this).val();
    v = v.trim();
    v = v.replace(/\s+/g, ' ');
    $(this).val(v.trim());
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
    $.pjax.reload({url: window.location.href, method: 'POST', container: mygridview.options.pjaxId});
})
JS;
$this->registerJs($script, \yii\web\View::POS_END);
