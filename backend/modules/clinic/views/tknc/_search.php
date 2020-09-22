<?php

use backend\modules\setting\models\Dep365CoSo;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use zhuravljov\yii\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\modules\clinic\models\search\PhongKhamChupHinhSearch */
/* @var $form yii\widgets\ActiveForm */

$cls_col_1 = 'col-sm-4 col-12';
$cls_search_date_range = 'hidden';
$cls_search_code = 'hidden';
if ($model->type_search_date == 'range') {
    $cls_col_1 = 'col-sm-6 col-12 search-by-range';
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
                    <div class="f-title">
                        <?= $form->field($model, 'type_search_code')->radioList([
                            'full_name' => 'Họ và tên',
                            'code' => 'Mã khách hàng'
                        ], [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                return '
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="' . $name . $index . '" name="' . $name . '" value="' . $value . '" data-name="type-search-code" data-attr="code" ' . ($checked == 1 ? 'checked' : '') . '>
                                    <label class="custom-control-label" for="' . $name . $index . '">' . ucfirst($label) . '</label>
                                </div>
                            ';
                            }
                        ])->label(false) ?>
                    </div>
                    <div class="f-content">
                        <div class="search-code code-full_name <?= $model->type_search_code == 'full_name' ? '' : 'hidden' ?>">
                            <?= $form->field($model, 'full_name')->textInput(['placeholder' => "Họ và tên"])->label(false) ?>
                        </div>
                        <div class="search-code code-code <?= $model->type_search_code == 'code' ? '' : 'hidden' ?>">
                            <?= $form->field($model, 'customer_code')->textInput(['placeholder' => "Mã khách hàng"])->label(false) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-search-col col-sm-4 col-12">
                <div class="form-group row ml-0 mr-0">
                    <div class="f-title w-50px">
                        <div>
                            <label class="control-label">Cơ sở</label>
                        </div>
                    </div>
                    <div class="f-content">
                        <?php
                        echo $form->field($model, 'co_so')->dropDownList(ArrayHelper::map(Dep365CoSo::getCoSo(), 'id', 'name'), ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn cơ sở...'])->label(false);
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
            if ($model->button == '2') {
                $clsMacDinh = 'btn-primary';
            }
            ?>
            <div class="col-12">
                <?= Html::submitButton('Tìm kiếm', ['class' => 'btn ' . $clsTimKiem, 'id' => 'customer-search', 'tabindex' => 1]) ?>
                <?= Html::submitButton('Mặc định', ['class' => 'btn ' . $clsMacDinh, 'id' => 'customer-search-default', 'tabindex' => 2]) ?>
                <?= Html::a('Hôm nay', ['index'], ['class' => 'btn ' . $clsHomNay]) ?>
                <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
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
            input.closest('.form-search-col').removeClass('col-sm-4 col-12').addClass('col-sm-6 col-12 search-by-range');
        } else {
            input.closest('.form-search-col').removeClass('col-sm-6 col-12 search-by-range').addClass('col-sm-4 col-12');
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
JS;
$this->registerJs($script, \yii\web\View::POS_END);
