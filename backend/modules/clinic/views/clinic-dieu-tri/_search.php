<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\clinic\models\Clinic;
use zhuravljov\yii\widgets\DatePicker;
use backend\modules\user\models\User;

$user = new User();
$roleName = $user->getRoleName(Yii::$app->user->id);


$cls_col_1 = 'col-xxl-4 col-xl-4 col-sm-6 col-12';
$cls_search_date_range = 'hidden';
$cls_search_code = 'hidden';
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
        'id' => 'form-search-dieutri'
    ],
]); ?>
    <div class="form-search">
        <div class="row search-option">
            <div class="form-search-col <?= $cls_col_1 ?>">
                <div class="form-group form-content row ml-0 mr-0">
                    <div class="f-title control-label">
                        Khách hàng
                    </div>
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
                            <?= $form->field($model, 'time_start')->widget(\dosamigos\datepicker\DatePicker::class, [
                                'template' => '{input}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>{addon}',
                                'clientOptions' => [
                                    'format' => 'dd-mm-yyyy',
                                    'autoclose' => true,
                                ],
                                'clientEvents' => [],
                                'options' => [
                                    'placeholder' => "Ngày bắt đầu",
                                    'autocomplete' => 'off'
                                ]
                            ])->label(false) ?>
                        </div>
                        <div class="date-range <?= $cls_search_date_range ?> search-date-text text-to">-</div>
                        <div class="search-date date-range <?= $cls_search_date_range ?>">
                            <?= $form->field($model, 'time_end')->widget(\dosamigos\datepicker\DatePicker::class, [
                                'template' => '{input}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>{addon}',
                                'clientOptions' => [
                                    'format' => 'dd-mm-yyyy',
                                    'autoclose' => true,
                                ],
                                'clientEvents' => [],
                                'options' => [
                                    'placeholder' => "Ngày kết thúc",
                                    'autocomplete' => 'off'
                                ]
                            ])->label(false) ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            if (in_array($roleName, [
                \common\models\User::USER_DEVELOP,
                \common\models\User::USER_ADMINISTRATOR,
                \common\models\User::USER_MANAGER_LE_TAN,
                \common\models\User::USER_LE_TAN,
            ])) :
                ?>
                <div class="form-search-col col-sm-4 col-12">
                    <div class="form-group row ml-0 mr-0" style="padding-bottom:2px">
                        <div class="f-content w-100">
                            <?php
                            $dr = \common\models\User::getNhanVienTuDirectSale();
                            $directsale = [];
                            if ($dr != null) {
                                foreach ($dr as $key => $item) {
                                    $directsale[$item->id] = $item->userProfile->fullname;
                                }
                            }
                            echo $form->field($model, 'direct_sale', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])->dropDownList($directsale, ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn direct sale...'])->label(false) ?>
                        </div>
                    </div>
                    <div class="form-group row ml-0 mr-0">
                        <div class="f-content w-100">
                            <?= $form->field($model, 'ekip', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])->dropDownList(Clinic::getEkipbacsi(), ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn ekip bác sĩ...'])->label(false) ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="form-search-col col-sm-4 col-12">
                <?php
                if (in_array($roleName, [
                    \common\models\User::USER_DEVELOP,
                    \common\models\User::USER_ADMINISTRATOR,
                    \common\models\User::USER_MANAGER_LE_TAN,
                    \common\models\User::USER_LE_TAN,
                ])) :
                    ?>
                    <div class="form-group row ml-0 mr-0">
                        <div class="f-content w-100">
                            <?= $form->field($model, 'co_so', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])->dropDownList($listCoSo, ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn cơ sở...'])->label(false) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="form-group row ml-0 mr-0">
                    <div class="f-content w-100">
                        <?= $form->field($model, 'tro_thu', ['template' => '<div class="input-group">{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])->dropDownList(Clinic::getTrothu(), ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn trợ thủ...'])->label(false) ?>
                    </div>
                </div>

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
            $dieuTriSearch = 'btn-blue-grey';
            $dieuTriMacDinh = 'btn-blue-grey';
            if ($model->button == '') {
                $dieuTriMacDinh = 'btn-primary';
            }
            if ($model->button == '1') {
                $dieuTriSearch = 'btn-primary';
            }
            ?>
            <div class="col-12">
                <?= Html::submitButton('Tìm kiếm', ['class' => 'btn ' . $dieuTriSearch, 'id' => 'dieutri-search']) ?>
                <?= Html::a('Mặc định', ['index'], ['class' => 'btn ' . $dieuTriMacDinh]) ?>
                <?= Html::button('Refesh', ['class' => 'btn btn-default', 'id' => 'refresh-data']) ?>
                <?= Html::button('Hiện thanh tìm kiếm >>', ['class' => 'btn btn-blue', 'id' => 'search-more']) ?>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>
<?php
$date = date('d-m-Y');
$tommorow = date('d-m-Y', time() + 86400);
$yesterday = date('d-m-Y', time() - 86400);
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
            input.closest('.form-search-col').removeClass('col-xxl-4 col-xl-4 col-sm-6 col-12').addClass('col-xxl-5 col-xl-4 col-sm-6 col-12 search-by-range');
        } else {
            input.closest('.form-search-col').removeClass('col-xxl-5 col-xl-4 col-12 search-by-range').addClass('col-xxl-4 col-xl-4 col-sm-6 col-12');
        }
    }
});

$(".search-option").hide();

$("#search-more").click(function(){
    if($(".search-option").is(":visible")){
        $("#search-more").text("Hiện thanh tìm kiếm >>");
    } else{
        $("#search-more").text("Ẩn thanh tìm kiếm <<");
    }
    $.when($(".search-option").toggle()).done(function(){
        clinic.setHeightContent();
    });
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
        if(type_search_code == 'keyword'){
            $('.search-code.code-code').find('input').val('');
        } else {
            $('.search-code.code-keyword').find('input').val('');
        }
        $('#button-type').val(1);
    } else if (e.target.id == 'customer-search-default') {
        $('#form-search-clinic').find('input[type=text]').val('');
        $('#form-search-clinic').find('select').children('option[value!=""]').prop('selected', false);
        $('#button-type').val(2);
    }
});
$('body').on('click', '#refresh-data', function(){
    $.pjax.reload({url: window.location.href, method: 'POST', container: clinic.options.pjaxId});
})
JS;
$this->registerJs($script, \yii\web\View::POS_END);
