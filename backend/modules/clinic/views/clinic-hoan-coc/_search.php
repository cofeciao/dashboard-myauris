<?php

use dosamigos\datepicker\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\clinic\models\search\PhongKhamDonHangWHoanCocSearch */
/* @var $form yii\widgets\ActiveForm */

$cls_col_1 = 'col-xxl-4 col-xl-4 col-md-6 col-12';
$cls_search_date_cre = 'hidden';
if ($model->type_search_create == 'range') {
    $cls_col_1 = 'col-xxl-5 col-xl-4 col-12 search-by-range';
}
if ($model->type_search_create == 'range') {
    $cls_search_date_cre = '';
}
?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'options' => [
        'data-pjax' => 1,
        'id' => 'form-search-hoan-coc',
    ],
]); ?>
<div class="form-search">
    <div class="row">
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
            <div class="form-group form-content row ml-0 mr-0">
                <div class="f-title control-label">
                    <label class="control-label">Từ khoá</label>
                </div>
                <div class="f-content">
                    <div class="search-code">
                        <?= $form->field($model, 'keyword', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-value"><span class="fa fa-times"></span></span></div>'])
                            ->textInput(['placeholder' => "Họ và tên, số điện thoại, mã khách hàng hoặc mã đơn hàng", 'title' => "Họ và tên, số điện thoại, mã khách hàng hoặc mã đơn hàng"])
                            ->label(false) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-search-col col-xl-7">
            <div class="row" style="padding-top:3px;padding-bottom:8px">
                <div class="form-search-col keyword col-md-4 col-12">
                    <div class="form-group form-content row ml-0 mr-0">
                        <div class="f-content">
                            <div class="search-code">
                                <?= $form->field($model, 'loai_thanh_toan', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])
                                    ->dropDownList(\yii\helpers\ArrayHelper::map(\backend\modules\clinic\models\PhongKhamLoaiThanhToan::getClinicLoaiThanhToan(), 'id', 'name'), ['class' => 'form-control ui dropdown', 'prompt' => 'Loại thanh toán'])->label(false) ?>
                            </div>
                        </div>
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
            <?= Html::submitButton('Tìm kiếm', ['class' => 'btn ' . $clsTimKiem, 'id' => 'customer-search', 'tabindex' => 1]) ?>
            <?= Html::submitButton('Mặc định', ['class' => 'btn ' . $clsMacDinh, 'id' => 'customer-search-default', 'tabindex' => 2]) ?>
            <?= Html::a('Hôm nay', ['index'], ['class' => 'btn ' . $clsHomNay]) ?>
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
            input.closest('.form-search-col').removeClass('col-xxl-4 col-xl-4 col-md-6 col-12').addClass('col-xxl-5 col-xl-4 col-12 search-by-range');
        } else {
            input.closest('.form-search-col').removeClass('col-xxl-5 col-xl-4 col-12 search-by-range').addClass('col-xxl-4 col-xl-4 col-md-6 col-12');
        }
    }
});
$('body').on('click', 'button[type=submit]', function(e) {
    console.log(e.target.id);
    if (e.target.id == 'customer-search-default') {
        $('#form-search-hoan-coc').find('input[type=text]').val('');
        $('#form-search-hoan-coc').find('select').children('option[value!=""]').prop('selected', false);
        $('#button-type').val(2);
    } else if (e.target.id == 'customer-search') {
        var type_search_create = $('body').find('input[data-type="creation"]:checked').val();
            
        if(type_search_create == 'date'){
            $('.creation_time_to').find('input').val('');
        }
        
        $('#button-type').val(1);
    }
});

$('body').on('click', '#refresh-data', function(){
    $.pjax.reload({url: window.location.href, method: 'POST', container: customPjax.options.pjaxId});
})
JS;
$this->registerJs($script, \yii\web\View::POS_END);
$this->registerCss('
.form-search-col.keyword .f-content{width:100%}
.form-search-col.mult .f-content{width:100%}
.form-search-col.co_so .f-content{width:100%}
');
?>
