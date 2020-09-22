<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use dosamigos\datepicker\DatePicker;
use backend\modules\clinic\models\PhongKhamKhuyenMai;

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
    'id' => 'form-payment',
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-payment']),
    'action' => Url::toRoute(['submit-add-payment', 'id' => $order->primaryKey])
]);
?>
    <button type="button" class="close hide" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <div class="modal-body no-header">
        <div class="order-info row">
            <div class="customer-info col-md-6 col-sm-4 col-4">
                <div class="ci-row">Khách hàng: <span
                            class="font-weight-bold"><?= $customer->full_name != null ? $customer->full_name : $customer->name ?></span>
                </div>
                <div class="ci-row">Mã khách hàng: <span class="font-weight-bold"><?= $customer->customer_code ?></span>
                </div>
            </div>
            <div class="date col-md-6 col-sm-8 col-8 text-right">
                <?= $dayOfWeek[date('w')] ?>, Ngày <?= date('d') ?> Tháng <?= date('m') ?> Năm <?= date('Y') ?>
            </div>
        </div>
        <div class="order-title text-center">Đơn hàng <span class="font-weight-bold"><?= $order->order_code ?></span>
        </div>
        <?= $form->field($model, 'phong_kham_don_hang_id')->hiddenInput()->label(false); ?>
        <?= $form->field($model, 'customer_id')->hiddenInput()->label(false) ?>
        <div class="order-content customer-status">
            <div class="table-responsive table-order max-height-150">
                <table class="table table-hover table-striped">
                    <thead>
                    <tr>
                        <th>STT</th>
                        <th>Dịch vụ</th>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Tổng</th>
                        <th>Chiết khấu</th>
                        <th>Thành tiền</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $tongtien = 0;
                    $tongchietkhau = 0;
                    if ($listOrder != null) {
                        $i = 1;
                        foreach ($listOrder as $o) {
                            $tongtien += $o->thanh_tien; ?>
                            <tr>
                                <td><?= $i ?></td>
                                <td><?= $o->dichVuHasOne != null ? $o->dichVuHasOne->name : '-' ?></td>
                                <td><?= $o->sanPhamHasOne != null ? $o->sanPhamHasOne->name : '-' ?></td>
                                <td><?= $o->so_luong ?></td>
                                <td><?= number_format($o->thanh_tien, 0, '', '.') ?></td>
                                <td><?php
                                    $chietkhau = 0;
                            if (!in_array($o->chiet_khau_order, [null, 0])) {
                                if ($o->chiet_khau_theo_order == \backend\modules\clinic\models\PhongKhamKhuyenMai::TYPE_CURENCY) {
                                    $chietkhau = $o->chiet_khau_order;
                                    echo number_format($chietkhau, 0, '', '.');
                                } else {
                                    $chietkhau = $o->thanh_tien * $o->chiet_khau_order / 100;
                                    echo number_format($chietkhau, 0, '', '.') . ' (' . $o->chiet_khau_order . '%)';
                                }
                            } ?></td>
                                <td><?= number_format($o->thanh_tien - $chietkhau, 0, '', '.') ?></td>
                            </tr>

                            <?php
                            $tongchietkhau += $chietkhau;
                            $i++;
                        }
                    }
                    if ($order->khuyenMaiHasOne != null) {
                        if ($tongchietkhau > $tongtien) {
                            $tongchietkhau = $tongtien;
                        }
                        $khuyenmai = 0;
                        $conlai = $tongtien - $tongchietkhau;
                        if ($order->khuyenMaiHasOne->price != null) {
                            if ($order->khuyenMaiHasOne->type == PhongKhamKhuyenMai::TYPE_PERCENT) {
                                $khuyenmai = $conlai * $order->khuyenMaiHasOne->price / 100;
                            } else {
                                $khuyenmai = $order->khuyenMaiHasOne->price;
                            }
                        } ?>
                        <tr>
                            <td><?= $i ?></td>
                            <td><?= $order->khuyenMaiHasOne->name != null ? $order->khuyenMaiHasOne->name : '' ?></td>
                            <td>Khuyến mãi</td>
                            <td>1</td>
                            <td>-</td>
                            <td><?= $khuyenmai != 0 ? number_format($khuyenmai, 0, '', '.') : '-' ?></td>
                            <td>-<?= $khuyenmai != 0 ? number_format($khuyenmai, 0, '', '.') : '-' ?></td>
                        </tr>
                    <?php
                    } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="detail-order customer-status">
            <aside class="txtR flr">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                        <tr>
                            <td colspan="4">Tổng cộng:</td>
                            <td>
                                <span class="tong-cong"><?= $totalPrice != 0 ? number_format($totalPrice, 0, '', '.') : '-' ?></span>
                                <?= Html::hiddenInput('', ($totalPrice != null ? (is_numeric($totalPrice) && $totalPrice > 0 ? $totalPrice : 0) : 0), ['id' => 'tong-cong']) ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">Đặt cọc:</td>
                            <td>
                                <span class="dat-coc"><?= $datCoc != null ? number_format($datCoc->tien_thanh_toan, 0, '', '.') : '-' ?></span>
                                <?= Html::hiddenInput('', ($datCoc != null ? $datCoc->tien_thanh_toan : 0), ['id' => 'dat-coc']) ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">Chiết khấu:</td>
                            <td>
                                <span class="chiet-khau"><?= $order->chiet_khau != null && $order->chiet_khau != 0 ? number_format($order->chiet_khau, 0, '', '.') : '-' ?></span>
                                <?= Html::hiddenInput('', ($order->chiet_khau != null ? $order->chiet_khau : 0), ['id' => 'chiet-khau']) ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">Đã thanh toán:</td>
                            <td>
                                <span class="da-thanh-toan"><?= is_numeric($totalThanhToan) && $totalThanhToan > 0 ? number_format($totalThanhToan, 0, '', '.') : '-' ?></span>
                                <?= Html::hiddenInput('', (is_numeric($totalThanhToan) && $totalThanhToan > 0 ? $totalThanhToan : 0), ['id' => 'da-thanh-toan']) ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">Còn lại:</td>
                            <td>
                                <span class="con-lai"><?= is_numeric($price) && $price > 0 ? number_format($price, 0, '', '.') : '-' ?></span>
                                <?= Html::hiddenInput('', (is_numeric($price) && $price > 0 ? $price : 0), ['id' => 'con-lai']) ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </aside>
        </div>
        <div class="order-content payment">
            <div class="row">
                <div class="col-sm-3 col-xs-6 col-12">
                    <?= $form->field($model, 'tien_thanh_toan')->textInput([]) ?>
                </div>
                <div class="col-sm-2 col-xs-6 col-12">
                    <?= $form->field($model, 'loai_thanh_toan')->radioList(\yii\helpers\ArrayHelper::map(\backend\modules\clinic\models\PhongKhamLoaiThanhToan::getClinicLoaiThanhToan(), 'id', 'name'), []) ?>
                </div>
                <div class="col-sm-3 col-xs-6 col-12">
                    <?= $form->field($model, 'tam_ung')->dropDownList($thanhToanType, []) ?>
                </div>
                <div class="col-sm-4 col-xs-6 col-12">
                    <?= $form->field($model, 'ngay_tao')->widget(DatePicker::class, [
                        'clientOptions' => [
                            'format' => 'dd-mm-yyyy',
                            'autoclose' => true,
                            'endDate' => "+0d",
                        ],
                        'clientEvents' => [],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
            'btn btn-warning']) ?>
        <?= Html::submitButton('<i class="fa fa-print"></i> Save & Print', ['class' => 'btn btn-success', 'id' => 'btn-print']) ?>
        <?= Html::submitButton(
                '<i class="fa fa-check-square-o"></i> Save',
                ['class' => 'btn btn-primary', 'id' => 'btn-submit']
            ) ?>
    </div>
<?php ActiveForm::end() ?>
<?php
$script = <<< JS
function hoadon(){
    var tmp = {
            1: 'dat-coc',
            3: 'chiet-khau'
        },
        /* Gán giá trị variable mặc định */
        variable = {
            'dat-coc': parseInt($('#dat-coc').val() || '0'),
            'chiet-khau': parseInt($('#chiet-khau').val() || '0'),
            'da-thanh-toan': parseInt($('#da-thanh-toan').val() || '0'),
            'con-lai': parseInt($('#con-lai').val() || '0'),
        },
        thanhtoan = $('#phongkhamdonhangwthanhtoan-tien_thanh_toan').val() || '0',
        tamung = parseInt($('#phongkhamdonhangwthanhtoan-tam_ung').val()),
        tongcong = parseInt($('#tong-cong').val() || '0'),
        conlai = tamung === 2 ? variable['dat-coc'] + variable['da-thanh-toan'] : tongcong,
        max = tamung === 2 ? variable['dat-coc'] + variable['da-thanh-toan'] : tongcong - (variable['dat-coc'] + variable['da-thanh-toan'] + variable['chiet-khau']);
        
    thanhtoan = parseInt(thanhtoan.replace(/\./g, ''));
    if(thanhtoan > max) thanhtoan = max;
    if(thanhtoan < 0) thanhtoan = 0;
    $('#phongkhamdonhangwthanhtoan-tien_thanh_toan').val(addCommas(thanhtoan));
    if(tamung === 2){
        /* Hoàn cọc */
        /*$.each(variable, function(k, v){
            $('.'+ k).html(v);
        });*/
    } else {
        conlai -= thanhtoan;
        /* Lặp qua danh sách variable mặc định */
        $.each(variable, function(k, v){
            /* Nếu variable != giá trị còn lại mặc định */
            if(k !== 'con-lai') conlai -= v;
            var val;
            if(tamung === 0 && k === 'da-thanh-toan'){
                val = variable['da-thanh-toan'] + thanhtoan;
                val = val === 0 ? '-' : addCommas(val);
                $('.da-thanh-toan').html(val);
                return;
            } else {
                if(tmp[tamung] === k) val = thanhtoan === 0 ? '-' : addCommas(thanhtoan);
                else val = v === 0 ? '-' : addCommas(v);
                $('.'+ k).html(val);
            }
        });
        if(conlai < 0) conlai = 0;
        conlai = conlai === 0 ? '-' : addCommas(conlai);
        $('.con-lai').html(conlai);
    }
}
// $('.detail').on('click', function(){
//     $('.table-order-detail').slideToggle();
// });
$('#phongkhamdonhangwthanhtoan-tien_thanh_toan').on('change paste keyup', function(){
    hoadon();
});
$('#phongkhamdonhangwthanhtoan-tam_ung').on('change', function(){
    hoadon();
});
JS;
$this->registerJs($script);
