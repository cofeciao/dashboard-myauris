<?php

use backend\modules\customer\models\Dep365CustomerOnlineCome;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use backend\modules\setting\models\Dep365CoSo;
use yii\helpers\ArrayHelper;

$cls_col_1 = 'col-xxl-4 col-xl-4 col-md-6 col-12';
$cls_search_date_range = 'hidden';
if ($model->type_search_date == 'range') {
    $cls_col_1 = 'col-xxl-5 col-xl-4 col-12 search-by-range';
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
            <div class="form-search-col <?= $cls_col_1 ?>">
                <div class="form-group form-content row ml-0 mr-0">
                    <div class="f-title control-label">Khách hàng</div>
                    <div class="f-content">
                        <div class="search-code">
                            <?= $form->field($model, 'keyword', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-value"><span class="fa fa-times"></span></span></div>'])
                                ->textInput(['placeholder' => "Họ và tên, số điện thoại hoặc mã khách hàng"])
                                ->label(false) ?>
                        </div>
                    </div>
                </div>
                <div class="form-group form-content row ml-0 mr-0">
                    <div class="f-title">
                        <?= $form->field($model, 'type_search_date')->radioList([
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
                    <div class="f-content">
                        <div class="search-date date-date date-range">
                            <?= $form->field($model, 'come_time_from')->widget(DatePicker::class, [
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
                        <div class="date-range <?= $cls_search_date_range ?> search-date-text text-to">-</div>
                        <div class="search-date date-range <?= $cls_search_date_range ?>">
                            <?= $form->field($model, 'come_time_to')->widget(DatePicker::class, [
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
            </div>
            <div class="form-search-col col-lg-3 col-md-4 col-12">
                <?php if (Yii::$app->user->can(\common\models\User::USER_DEVELOP)) { ?>
                <div class="form-group row ml-0 mr-0">
                    <div class="f-content w-100">
                        <?php
                        echo $form->field($model, 'co_so', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])->dropDownList(ArrayHelper::map(Dep365CoSo::getCoSo(), 'id', 'name'), ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn cơ sở...'])->label(false);
                        ?>
                    </div>
                </div>
                <?php } ?>
                <div class="form-group form-content row ml-0 mr-0">
                    <div class="f-content w-100">
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
    if (name == 'type-search-date'){
        if(v == 'range'){
            input.closest('.form-search-col').removeClass('col-xxl-4 col-xl-4 col-md-6 col-12').addClass('col-xxl-5 col-xl-4 col-12 search-by-range');
        } else {
            input.closest('.form-search-col').removeClass('col-xxl-5 col-xl-4 col-12 search-by-range').addClass('col-xxl-4 col-xl-4 col-md-6 col-12');
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
    if (e.target.id == 'customer-search-default') {
        $('#form-search-clinic').find('input[type=text]').val('');
        $('#form-search-clinic').find('select').children('option[value!=""]').prop('selected', false);
        $('#button-type').val(2);
    } else if (e.target.id == 'customer-search') {
        var type_search_date = $('body').find('input[data-name="type-search-date"]:checked').val();
        if(type_search_date == 'date'){
            $('.search-date.date-range:not(.date-date)').find('input').val('');
        }        
        $('#button-type').val(1);
    }
});
$('body').on('click', '#refresh-data', function(){
    $.pjax.reload({url: window.location.href, method: 'POST', container: directSale.options.pjaxId});
})
JS;
$this->registerJs($script, \yii\web\View::POS_END);
