<?php

use backend\modules\setting\models\Dep365CoSo;
use common\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\clinic\models\PhongKhamDirectSale;
use backend\modules\clinic\models\Clinic;
use dosamigos\datepicker\DatePicker;

/*$cls_search_date_range = 'hidden';
$cls_search_code = 'hidden';
if ($model->type_search_date == 'range') {
    $cls_col_1 = 'col-xxl-5 col-xl-4 col-12 search-by-range';
    $cls_search_date_range = '';
}*/

$cls_col_1 = 'col-xxl-4 col-xl-4 col-md-6 col-12';
$cls_search_date_cre = $cls_search_date_pay = 'hidden';
if ($model->type_search_create == 'range' || $model->type_search_payment == 'range') {
    $cls_col_1 = 'col-xxl-5 col-xl-4 col-12 search-by-range';
}
if ($model->type_search_create == 'range') {
    $cls_search_date_cre = '';
}
if ($model->type_search_payment == 'range') {
    $cls_search_date_pay = '';
}
?>
<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'options' => [
        'data-pjax' => 1,
        'id' => 'form-search-order',
    ],
]); ?>
<div class="form-search clinic-order">
    <div class="row search-option">
        <div class="form-search-col <?= $cls_col_1 ?>">
            <div class="form-group form-content row ml-0 mr-0">
                <div class="f-title control-label">
                    <?= $form->field($model, 'type_search_create')->radioList([
                        'date' => 'Ngày tạo',
                        'range' => 'Khoảng ngày'
                    ], [
                        'item' => function ($index, $label, $name, $checked, $value) {
                            return '
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="' . $name . $index . '" name="' . $name . '" value="' . $value . '" data-name="type-search-date" data-attr="date" data-type="creation" ' . ($checked == 1 ? 'checked' : '') . '>
                                            <label class="custom-control-label" for="' . $name . $index . '">' . ucfirst($label) . '</label>
                                        </div>
                                    ';
                        },
                    ])->label(false) ?>
                </div>
                <div class="f-content">
                    <div class="search-date creation_time_from date-date date-range">
                        <?= $form->field($model, 'creation_time_from')->widget(DatePicker::class, [
                            'template' => '{input}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>{addon}',
                            'clientOptions' => [
                                'format' => 'dd-mm-yyyy',
                                'autoclose' => true,
                                'todayHighlight' => true
                            ],
                            'clientEvents' => [],
                            'options' => [
                                'placeholder' => "Ngày",
                                'autocomplete' => 'off'
                            ]
                        ])->label(false) ?>
                    </div>
                    <div class="date-range <?= $cls_search_date_cre ?> search-date-text text-to">-</div>
                    <div class="search-date creation_time_to date-range <?= $cls_search_date_cre ?>">
                        <?= $form->field($model, 'creation_time_to')->widget(DatePicker::class, [
                            'template' => '{input}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>{addon}',
                            'clientOptions' => [
                                'format' => 'dd-mm-yyyy',
                                'autoclose' => true,
                                'todayHighlight' => true
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

        <div class="form-search-col col-xl-4">

            <div class="form-search-col keyword ">
                <div class="form-group form-content row ml-0 mr-0">
                    <div class="f-content">
                        <div class="search-code">
                            <?= $form->field($model, 'keyword', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-value"><span class="fa fa-times"></span></span></div>'])
                                ->textInput([
                                    'data-toggle' => 'tooltip',
                                    'data-placement' => 'top',
                                    'data-original-title' => 'Họ và tên, số điện thoại, mã khách hàng, mã đơn hàng',
                                    'placeholder' => 'Họ và tên, số điện thoại, mã khách hàng, mã đơn hàng'
                                ])
                                ->label(false) ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="form-search-col col-xl-4">

            <div class="form-group row ml-0 mr-0">
                <div class="f-content w-100">
                    <?php
                    echo $form->field($model, 'co_so', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])
                        ->dropDownList(ArrayHelper::map(Dep365CoSo::getCoSo(), 'id', 'name'), ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn cơ sở...'])
                        ->label(false);
                    ?>
                </div>
            </div>

        </div>

        <div class="form-search-col col-xl-2">
            <div class="form-group form-content row ml-0 mr-0">
                <div class="f-content  w-100">
                    <?= $form->field($model, 'trang_thai', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])
                        ->dropDownList(\yii\helpers\ArrayHelper::toArray(\backend\modules\clinic\models\PhongKhamDonHang::getListTrangThaiDonDichVu()), ['class' => 'form-control ui dropdown', 'prompt' => 'Trạng thái DV'])->label(false) ?>
                </div>
            </div>
        </div>

        

    </div>
    <div class="row">
        <?= $form->field($model, 'button')->hiddenInput(['id' => 'button-type'])->label(false) ?>
        <?php
        $orderToday = $orderMacDinh = $orderYesterday = $orderSearch = 'btn-blue-grey';
        if ($model->button == '1') {
            $orderSearch = 'btn-primary';
        }
        if ($model->button == '') {
            $orderMacDinh = 'btn-primary';
        }
        if ($model->button == '2') {
            $orderYesterday = 'btn-primary';
        }
        if ($model->button == '3') {
            $orderToday = 'btn-primary';
        }
        ?>
        <div class="form-group col-xl-6 col-lg-6 col-12">
            <?= Html::submitButton('Tìm kiếm', ['class' => 'btn ' . $orderSearch, 'id' => 'order-search', 'tabindex' => 1]) ?>
            <?= Html::a('Mặc định', ['index'], ['class' => 'btn ' . $orderMacDinh]) ?>
            <?= Html::submitButton('Hôm qua', ['class' => 'btn ' . $orderYesterday, 'id' => 'order-yesterday', 'tabindex' => 2]) ?>
            <?= Html::submitButton('Hôm nay', ['class' => 'btn ' . $orderToday, 'id' => 'order-today', 'tabindex' => 3]) ?>
            <?= Html::button('Refesh', ['class' => 'btn btn-default', 'id' => 'refresh-data']) ?>
            <?= Html::button('Ẩn thanh tìm kiếm >>', ['class' => 'btn btn-blue', 'id' => 'search-more']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php
$date = date('d-m-Y');
$yesterday = date('d-m-Y', time() - 86400);
$script = <<< JS
$('#form-search-order').on('mouseout mouseleave', 'input', function(){
    var v = $(this).val();
    v = v.trim();
    v = v.replace(/\s+/g, ' ');
    $(this).val(v.trim());
});

$(".search-option").hide();

$("#search-more").click(function(){
    if($(".search-option").is(":visible")){
        $("#search-more").text("Hiện thanh tìm kiếm >>");
    } else{
        $("#search-more").text("Ẩn thanh tìm kiếm <<");
    }
    $(".search-option").toggle();
});

$('body').find('button[type=submit]').unbind('click').bind('click', function(e) {
    if (e.target.id == 'order-today') {
        $('#phongkhamdonhangsearch-created_at').val('$date');
        $('#button-type').val(3);
    } else if (e.target.id == 'order-yesterday') {
        $('#phongkhamdonhangsearch-created_at').val('$yesterday');
        $('#button-type').val(2);
    } else if (e.target.id == 'order-search') {
        $('#button-type').val(1);
    } else {
        $('#form-search-order').find('input[type=text]').val('');
        $('#form-search-order').find('select').children('option[value!=""]').prop('selected', false);
    }
});
$('.f-title').on('click', 'input', function(){
    var input = $(this),
        attr = input.attr('data-attr'),
        v = input.val(),
        name = input.attr('data-name');
    
    input.closest('.form-content').find('.f-content div[class*="'+ attr +'-"]').addClass('hidden');
    input.closest('.form-content').find('.f-content .'+ attr +'-'+ v).removeClass('hidden');
    if(name == 'type-search-date'){
        if(v == 'range'){
            input.closest('.form-search-col').removeClass('col-xxl-4 col-xl-4 col-md-6 col-12').addClass('col-xxl-5 col-xl-4 col-12 search-by-range');
        } else {
            input.closest('.form-search-col').removeClass('col-xxl-5 col-xl-4 col-12 search-by-range').addClass('col-xxl-4 col-xl-4 col-md-6 col-12');
        }
    }
});
$('body').on('click', '#refresh-data', function(){
    $.pjax.reload({url: window.location.href, method: 'POST', container: clinic.options.pjaxId});
})

JS;
$this->registerJs($script, \yii\web\View::POS_END);
$this->registerCss('
.form-search-col.keyword .f-content{width:100%}
.form-search-col.mult .f-content{width:100%}
.form-search-col.co_so .f-content{width:100%}
');
