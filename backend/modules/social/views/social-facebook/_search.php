<?php

use kartik\export\ExportMenu;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;

$cls_col_1 = 'col-xxl-4 col-xl-4 col-md-6 col-12';
$cls_search_date_range = 'hidden';
$cls_search_code = 'hidden';
if ($model->type_search_date == 'range') {
    $cls_col_1 = 'col-xxl-5 col-xl-4 col-12 search-by-range';
    $cls_search_date_range = '';
}
?>
<?php
$form = ActiveForm::begin([
    'action' => ['index'],
    'method' => 'get',
    'id' => 'form-search-clinic',
    'options' => [
        'data-pjax' => true
    ],
]); ?>
    <div class="form-search">
        <div class="row">
            <div class="form-search-col <?= $cls_col_1 ?>">
                <div class="form-group form-content row ml-0 mr-0">
                    <div class="f-title">
                        <?= $form->field($model, 'type_search_date')->radioList([
                            'date' => 'Ngày tạo',
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
                            <?= $form->field($model, 'from')->widget(DatePicker::class, [
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
                            <?= $form->field($model, 'to')->widget(DatePicker::class, [
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
            <div class="form-search-col col-xl-3 col-md-4 col-12">
                <div class="form-group form-content row ml-0 mr-0">
                    <div class="select-group w-100">
                        <?php
                        echo $form->field($model, 'khach_cu', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])
                            ->dropDownList(['Khách mới','Khách cũ'], ['class' => 'ui dropdown form-control', 'prompt' => 'Loại khách hàng...'])
                            ->label(false);
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-search-col col-lg-3 col-md-4">
                <div class="form-group form-content row ml-0 mr-0">
                    <div class="select-group w-100">
                        <?php
                        echo $form->field($model, 'status', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])
                            ->dropDownList($filter['status'], ['class' => 'ui dropdown form-control', 'prompt' => 'Trạng thái khách...'])
                            ->label(false);
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-search-col col-lg-3 col-md-4">
                <div class="form-group form-content row ml-0 mr-0">
                    <div class="select-group w-100">
                        <?php
                        echo $form->field($model, 'dat_hen', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])
                            ->dropDownList($filter['dat_hen'], ['class' => 'ui dropdown form-control', 'prompt' => 'Trạng thái đặt hẹn...'])
                            ->label(false);
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-search-col col-lg-3 col-md-4">
                <div class="form-group form-content row ml-0 mr-0">
                    <div class="select-group w-100">
                        <?php
                        echo $form->field($model, 'da_den', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])
                            ->dropDownList($filter['come'], ['class' => 'ui dropdown form-control', 'prompt' => 'Trạng thái khách đến...'])
                            ->label(false);
                        ?>
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
            ?>
            <div class="col-sm-6 pull-left">
                <?= Html::submitButton('Lọc kết quả', ['class' => 'btn btn-md ' . $clsTimKiem, 'id' => 'customer-search', 'tabindex' => 1]) ?>
                <?= Html::a('Mặc định', ['index'], ['class' => 'btn ' . $clsHomNay]) ?>
                <?= Html::button('Refesh', ['class' => 'btn btn-default', 'id' => 'refresh-data']) ?>
            </div>
            <div class="col-sm-6 pull-right">
                <div class="export-all col-xl-12">
                    <?= ExportMenu::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => $gridColumns,
                        'filename' => 'Customers-export',
                        'fontAwesome' => true,
                        'target' => ExportMenu::TARGET_SELF,
                        'showConfirmAlert' => false,
                        'clearBuffers' => true,
                        'initProvider' => true,
                        'container' => ['class' => 'btn-social-fb pull-right', 'role' => 'group'],
                        'dropdownOptions' => [
                            'label' => 'Export All',
                            'class' => 'btn btn-secondary'
                        ],
                        'columnBatchToggleSettings' => [
                            'label' => 'Chọn tất cả',
                        ],
                        'columnSelectorOptions' => [
                            'class' => 'btn btn-secondary'
                        ]
                    ]); ?>
                </div>
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
    }else if (e.target.id == 'customer-search-reset') {
        $('#form-search-clinic').find('input[type=text]').val('');
        $('#form-search-clinic').find('select').children('option[value!=""]').prop('selected', false);
    }
});
$('body').on('click', '#refresh-data', function(){
    $.pjax.reload({url: window.location.href, method: 'POST', container: social.options.pjaxId});
})
JS;
$this->registerJs($script, \yii\web\View::POS_END);
