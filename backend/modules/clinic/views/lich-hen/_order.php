<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 19-Jan-19
 * Time: 10:03 AM
 */

use backend\modules\clinic\models\PhongKhamKhuyenMai;
use unclead\multipleinput\MultipleInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\modules\clinic\models\PhongKhamDichVu;
use backend\modules\clinic\models\PhongKhamSanPham;

$dayOfWeek = [
    0 => 'Chủ Nhật',
    1 => 'Thứ Hai',
    2 => 'Thứ Ba',
    3 => 'Thứ Tư',
    4 => 'Thứ Năm',
    5 => 'Thứ Sáu',
    6 => 'Thứ Bảy',
];

$css = <<< CSS
#custom-modal .modal-dialog.modal-lg {max-width:1200px}
td.list-cell__san_pham .select2-container {width: 100% !important}
CSS;
$this->registerCss($css);

$form = ActiveForm::begin([
    'id' => 'form-don-hang',
    'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    'validationUrl' => ['validate-order', 'id' => $customer->primaryKey],
    'action' => Url::toRoute(['order-customer', 'id' => $customer->primaryKey]),
    'options' => [
        'class' => 'form form-horizontal',
        'redirect-on-submit' => Url::toRoute(['/clinic/clinic-order', 'customer_id' => $customer->primaryKey]),
    ]
]); ?>
    <button type="button" class="close hide" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <div class="modal-body no-header">
        <div class="order-info row">
            <div class="customer-info col-md-6 col-sm-4 col-4">
                <div class="ci-row">Khách hàng: <span class="font-weight-bold"><?= $customer->name ?></span></div>
                <div class="ci-row">Mã khách hàng: <span class="font-weight-bold"><?= $customer->customer_code ?></span>
                </div>
            </div>
            <div class="date col-md-6 col-sm-8 col-8 text-right">
                <?= $dayOfWeek[date('w')] ?>, Ngày <?= date('d') ?> Tháng <?= date('m') ?> Năm <?= date('Y') ?>
            </div>
        </div>
        <div class="order-title text-center">Đơn hàng</div>
        <div class="order-content order-list customer-status">
            <?php
            if ($customer) {
                echo $form->field($model, 'customer_id')->hiddenInput(['value' => $customer->id])->label(false);
            }
            ?>

            <?= $form->field($model, 'dich_vu', [
                'template' => '{input}',
            ])->hiddenInput()->label(false); ?>

            <?php
            $model->customer_order = $orderData;

            //            $arrSP = ArrayHelper::map(PhongKhamSanPham::getSanPham(), 'id', 'name');
            //            $arrSP[0] = 'Chọn sản phẩm...';
            //            ksort($arrSP);

            $arrDV = ArrayHelper::map(PhongKhamDichVu::getDichVu(), 'id', 'name');
            //            $arrDV[0] = 'Chọn sản phẩm...';
            ksort($arrDV);

            ?>
            <?= $form->field($model, 'customer_order', [
                'template' => '{input}'
            ])->widget(MultipleInput::class, [
                'max' => 5,
                'min' => 1,
                'allowEmptyList' => false,
                'enableGuessTitle' => true,
                'cloneButton' => false,
                'columns' => [
                    [
                        'name' => 'id',
                        'type' => 'hiddenInput',
                        'value' => function ($data) {
                            if ($data == null || !isset($data['id'])) {
                                return null;
                            }
//                                                $data = json_decode(json_encode($data), true);
                            $result = $data['id'];
                            return $result;
                        },
                        'options' => [
                            'class' => 'id_order',
                        ]
                    ],
                    [
                        'name' => 'dich_vu',
//                        'type' => 'dropDownList',
                        'title' => 'Dịch vụ',
                        'defaultValue' => '',
                        'value' => function ($data) {
                            return null;
                        },
//                        'items' => $arrDV,
                        'options' => [
                            'class' => 'dich-vu text-left',
                            'prompt' => 'Chọn dịch vụ...',
                            'readOnly' => true
                        ],
                        'headerOptions' => [
                            'width' => 190,
                        ]
                    ],
                    [
                        'name' => 'san_pham',
                        'type' => 'dropDownList',
                        'title' => 'Sản phẩm',
                        'defaultValue' => 0,
                        'value' => function ($data) {
                            if ($data == null || !isset($data['san_pham'])) {
                                return null;
                            }
//                                                $data = json_decode(json_encode($data), true);
                            return $data['san_pham'];
                        },
                        'items' => function ($data) {
                            return ArrayHelper::map(PhongKhamSanPham::getArraySanPhamByDichVu(), 'id', 'name');
                        },
                        'options' => [
                            'class' => 'sl-sp san-pham-clinic select2',
                            'prompt' => 'Chọn sản phẩm...'
                        ],
                        'headerOptions' => [
                            'width' => 190,
                        ]
                    ],
                    [
                        'name' => 'so_luong',
                        'title' => 'Số lượng',
                        'defaultValue' => 1,
                        'value' => function ($data) {
                            if ($data == null || !isset($data['so_luong'])) {
                                return 1;
                            }
//                                                $data = json_decode(json_encode($data), true);
                            $result = $data['so_luong'] == null ? 1 : $data['so_luong'];
                            return $result;
                        },
                        'options' => [
                            'type' => 'number',
                            'class' => 'input-priority sl-sp so-luong-clinic',
                        ],
                        'headerOptions' => [
                            'width' => '70px',
                        ]
                    ],
                    [
                        'name' => 'thanh_tien',
                        'title' => 'Thành tiền',
                        'defaultValue' => 0,
                        'value' => function ($data) {
//                        $data = json_decode(json_encode($data), true);
                            if ($data == null || !isset($data['thanh_tien'])) {
                                return null;
                            }
                            return number_format($data['thanh_tien'], 0, '', '.');
                        },
                        'options' => [
                            'class' => 'thanh-tien',
                            'readonly' => 'readonly',
                        ],
                        'headerOptions' => [
                            'width' => '150px',
                        ]
                    ],
                    [
                        'name' => 'chiet_khau_order',
                        'title' => 'Chiết khấu',
                        'defaultValue' => 0,
                        'value' => function ($data) {
                            if ($data == null || !isset($data['chiet_khau_order'])) {
                                return null;
                            }
                            return number_format($data['chiet_khau_order'], 0, '', '.');
                        },
                        'options' => function ($data) {
                            return [
                                'class' => 'on-keyup chiet-khau-order',
                                'data-content' => ($data == null || !isset($data['ly_do_chiet_khau'])) ? null : $data['ly_do_chiet_khau']
                            ];
                        },
                        'headerOptions' => [
                            'width' => '150px',
                        ]
                    ],
                    [
                        'name' => 'chiet_khau_theo_order',
                        'type' => 'dropDownList',
                        'title' => 'Chiết khấu theo',
                        'defaultValue' => null,
                        'value' => function ($data) {
                            if ($data == null || !isset($data['chiet_khau_theo_order'])) {
                                return null;
                            }
                            return $data['chiet_khau_theo_order'];
                        },
                        'items' => function ($data) {
                            return PhongKhamKhuyenMai::TYPE;
                        },
                        'options' => [
                            'class' => 'select2 text-left chiet-khau-theo-order',
                        ],
                        'headerOptions' => [
                            'width' => '70px',
                        ]
                    ],
                ],
                'iconMap' => [
                    'myFt' => [
                        'drag-handle' => 'ft-file-plus',
                        'remove' => 'ft-delete my-remove',
                        'add' => 'ft-plus',
                        'clone' => 'ft-file-plus my-clone',
                    ],
                ],
                'iconSource' => 'myFt',
            ])->label(false);
            ?>
            <div class="form-group row order-row text-right">
                <div class="col-10">Tổng:</div>
                <div class="col-2">
                    <span id="tong">-</span>
                </div>
            </div>
            <div class="form-group row order-row text-right">
                <div class="col-10">Chiết khấu:</div>
                <div class="col-2">
                    <span id="chiet-khau"><?= $model->chiet_khau != null ? number_format($model->chiet_khau, 0, '', '.') : 0 ?></span>
                </div>
            </div>
            <div class="form-group row order-row text-right">
                <div class="col-10">Tạm tính:</div>
                <div class="col-2">
                    <span id="tam-tinh">-</span>
                </div>
            </div>
            <?php if (isset($listKhuyenMai) && is_array($listKhuyenMai)) { ?>
                <div class="form-group row order-row text-right">
                    <div class="col-10">Chương trình khuyến mãi:</div>
                    <div class="col-2 khuyen-mai-content">
                        <?= $form->field($model, 'khuyen_mai')->dropDownList(ArrayHelper::map($listKhuyenMai, 'id', 'name'), ['prompt' => 'Chọn chương trình...', 'class' => 'form-control khuyen-mai'])->label(false) ?>
                        <span class="khuyen-mai-info"></span>
                        <?= Html::hiddenInput('', (array_key_exists($model->khuyen_mai, $listKhuyenMai) ? $listKhuyenMai[$model->khuyen_mai]['price'] : ''), ['class' => 'khuyenmai_price']) ?>
                        <?= Html::hiddenInput('', (array_key_exists($model->khuyen_mai, $listKhuyenMai) ? $listKhuyenMai[$model->khuyen_mai]['type'] : ''), ['class' => 'khuyenmai_type']) ?>
                    </div>
                </div>
            <?php } ?>
            <div class="form-group row">
                <div class="col-10"></div>
                <div class="col-2">
                    <hr class="m-0"/>
                </div>
            </div>
            <div class="form-group row order-row text-right">
                <div class="col-10">Thành tiền:</div>
                <div class="col-2">
                    <span id="thanh-tien">-</span>
                    <?= $form->field($model, 'chiet_khau')->hiddenInput(['class' => 'on-keyup'])->label(false) ?>
                </div>
            </div>

            <?= $form->errorSummary($model); ?>
            <?= Html::hiddenInput('', 1, ['id' => 'button-handle']) ?>
        </div>
    </div>
    <div class="modal-footer">
        <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
            'btn btn-warning mr-1']) ?>
        <?= Html::submitButton('<i class="fa fa-print"></i> Save & Print', ['class' => 'btn btn-success', 'id' => 'btn-print']) ?>
        <?= Html::submitButton(
                '<i class="fa fa-check-square-o"></i> Save',
                ['class' => 'btn btn-primary', 'id' => 'btn-submit']
            ) ?>
    </div>
<?php ActiveForm::end(); ?>

<?php
$order = <<< JS
$('.vertical-scroll').perfectScrollbar({
    suppressScrollX : true,
    theme: 'dark',
    wheelPropagation: true
});
jQuery('.multiple-input').on('afterInit', function(){
    addLyDoChietKhau();
}).on('afterAddRow', function(e, row){
    row.find('.id_order').val('0');
    row.find('.select2').select2();
    addLyDoChietKhau();
    handleChietKhau();
}).on('afterDeleteRow', function(){
    addLyDoChietKhau();
    handleChietKhau();
});

$('.on-keyup').each(function() {
    var order_discount = $(this).val().replace(/\./g, '');
    $(this).val(addCommas(order_discount));
});
handleChietKhau();
$('#btn-submit').on('click', function(e) {
    $('#button-handle').val(1);
});
$('#btn-print').on('click', function(){
    $('#button-handle').val(2);
});
JS;
$this->registerJs($order, \yii\web\View::POS_END);
$this->registerCss('
.text-left .order-content{text-align:left!important}
.select2-container{width:100%!important}
');
