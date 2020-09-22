<?php

use backend\modules\clinic\models\PhongKhamLoaiThanhToan;

$this->registerCss('
body, body * {font-family: Time News Roman!important}
.deposit-wrap{position:relative;}
.title{margin-bottom:2rem;text-align:center}
.logo{position:absolute;top:-20px;left:0}
.logo img{max-height:72px;;opacity:.7}
');
$co_so = \backend\modules\setting\models\Dep365CoSo::getById(Yii::$app->user->identity->permission_coso);
$order_title = '';
$count_payment_title = 0;

$dh_thanh_tien = $model->dh_thanh_tien == null || $model->dh_thanh_tien == '' ? 0 : $model->dh_thanh_tien;
$chiet_khau = $model->chiet_khau == null || $model->chiet_khau == '' ? 0 : $model->chiet_khau;
$dat_coc = $model->dat_coc == null || $model->dat_coc == '' ? 0 : $model->dat_coc;
$thanh_toan = $model->thanh_toan == null || $model->thanh_toan == '' ? 0 : $model->thanh_toan;
$total = number_format($dh_thanh_tien - ($chiet_khau + $dat_coc + $thanh_toan), 0, '', '.');
if ($total < 0) {
    $total = 0;
}

foreach ($payment as $item) {
    if ($item->tam_ung == 0) {
        $count_payment_title += 1;
    }
}

$thanhtoan_type = $array_pop != null ? $array_pop->tam_ung : '';
if ($thanhtoan_type !== '' && array_key_exists($thanhtoan_type, \backend\models\doanhthu\ThanhToanModel::THANHTOAN_TYPE)) $order_title .= \backend\models\doanhthu\ThanhToanModel::THANHTOAN_TYPE[$thanhtoan_type];
if (isset($array_pop->tam_ung) && $array_pop->tam_ung == 0 && $total > 0) {
    $order_title .= ' LẦN ' . $count_payment_title;
}
?>
<div id="deposit-template">
    <div class="deposit-wrap">
        <div class="logo"><img src="<?= \yii\helpers\Url::to('@web/images/ico/favicon.png'); ?>" alt="logo"></div>
        <h1 class="title">PHIẾU <?= $order_title; ?></h1>
        <div id="deposit-customer-details">
            <ul class="list-unstyled">
                <li>Tên khách hàng: <strong
                            class="font-weight-bold"><?= $model->clinicHasOne == null || $model->clinicHasOne->full_name == null ? $model->clinicHasOne->forename : $model->clinicHasOne->full_name; ?></strong>
                </li>
                <li>Địa chỉ:
                    <?= $model->clinicHasOne->address == null ? '' : $model->clinicHasOne->address . ' '; ?>
                    <?= $model->clinicHasOne == null || $model->clinicHasOne->districtHasOne == null ? '' : 'Quận ' . $model->clinicHasOne->districtHasOne->name . ', '; ?>
                    <?= $model->clinicHasOne == null || $model->clinicHasOne->provinceHasOne == null ? '' : $model->clinicHasOne->provinceHasOne->name; ?>
                </li>
                <li>Số điện
                    thoại: <?= $model->clinicHasOne->phone == null ? '-' : preg_replace("/^(\d{4})(\d{3})(\d{3})$/", "$1 $2 $3", $model->clinicHasOne->phone); ?></li>
                <li>Mã đơn hàng: <strong><?= $model->order_code; ?></strong></li>
            </ul>
        </div>
        <div id="deposit-items-details">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 60px">STT</th>
                            <th class="text-left d-none">Tên dịch vụ</th>
                            <th class="text-left">Tên sản phẩm</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-right">Đơn giá</th>
                            <th class="text-right">Thành tiền</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $paymentTotal = 0;
                        $i = 1;
                        foreach ($order as $key => $item) {
                            $paymentTotal += str_replace('.', '', $item->thanh_tien); ?>
                            <tr>
                                <td class="text-center"><?= $i ?></td>
                                <td class="text-left d-none">
                                    <?php
                                    if ($item->dichVuHasOne !== null) {
                                        echo $item->dichVuHasOne->name;
                                    } else {
                                        echo '-';
                                    } ?>
                                </td>
                                <td class="text-left">
                                    <?php
                                    if ($item->sanPhamHasOne !== null) {
                                        echo $item->sanPhamHasOne->name;
                                    } else {
                                        echo '-';
                                    } ?>
                                </td>
                                <td class="text-center"><?= $item->so_luong; ?></td>
                                <td class="text-right">
                                    <?php
                                    if ($item->sanPhamHasOne !== null) {
                                        echo number_format($item->sanPhamHasOne->don_gia, 0, '', '.');
                                    } else {
                                        echo '-';
                                    } ?>
                                </td>
                                <td class="text-right">
                                    <?php
                                    echo number_format($item->thanh_tien, 0, '', '.'); ?>
                                </td>
                            </tr>
                            <?php $i++;
                        } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="4" class="text-left">
                                <strong>Chiết khấu</strong>
                                <p style="margin: 0;">
                                    <?php echo $model->getChiTietChietKhauN($model->id) ?>
                                </p>

                            </td>
                            <td class="text-right" style="vertical-align: middle">
                                <?php
                                if ($model->chiet_khau == null || $model->chiet_khau == '') {
                                    echo null;
                                }
                                $minus = '';
                                if ($model->chiet_khau !== 0) $minus = '-';
                                echo $minus . number_format($model->chiet_khau, 0, '', '.');
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-left"><strong>Tổng cộng</strong></td>
                            <td class="text-right"><?= number_format($paymentTotal - $model->chiet_khau, 0, '', '.') ?></td>
                        </tr>
                        <tr style="display: none">
                            <td colspan="4" class="text-left"><strong>Đặt cọc</strong></td>
                            <td class="text-right"><?= number_format($model->dat_coc, 0, '', '.') ?></td>
                        </tr>
                        <tr style="display: none">
                            <td colspan="4" class="text-left"><strong>Đã thanh toán</strong></td>
                            <td class="text-right">
                                <?php
                                if ($model->thanh_toan == null || $model->thanh_toan == '') {
                                    echo null;
                                }
                                echo number_format($model->thanh_toan, 0, '', '.');
                                ?>
                            </td>
                        </tr>
                        <?php
                        $count_payment = count($payment);
                        $paymentTotal -= $model->chiet_khau;
                        $tien_thanh_toan = 0;
                        $j = 1;
                        foreach ($payment as $key => $item) {
                            $tien_thanh_toan += str_replace('.', '', $item->tien_thanh_toan);
                            $con_lai = $paymentTotal - $tien_thanh_toan; ?>
                            <tr>
                                <td colspan="4" class="text-left">
                                    <strong>
                                        <?php
                                        if ($item->tam_ung === null || !array_key_exists($item->tam_ung, \backend\models\doanhthu\ThanhToanModel::THANHTOAN_TYPE)) {
                                            echo '-';
                                        } else {
                                            echo \backend\models\doanhthu\ThanhToanModel::THANHTOAN_TYPE[$item->tam_ung];
                                            if ($item->tam_ung == 0) {
                                                echo $count_payment == 1 && $con_lai > 0 ? ' lần 1' : ($con_lai > 0 ? ' lần ' . $j++ : '');
                                            }
                                        }

                                        ?>
                                        (<?= date('d/m/y', $item->ngay_tao); ?>
                                        - <?= PhongKhamLoaiThanhToan::getOneLTT($item->loai_thanh_toan)->name; ?>)
                                    </strong>
                                </td>
                                <td class="text-right"><?= $item->tien_thanh_toan == null || $item->tien_thanh_toan == '' ? null : number_format($item->tien_thanh_toan, 0, '', '.'); ?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="4" class="text-left"><strong>Còn lại</strong></td>
                            <td class="text-right">
                                <?php echo $total; ?>
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div id="deposit-footer">
            <p><strong class="font-weight-bold"><em>Ghi chú:</em></strong></p>
            <ul class="list-note list-unstyled">
                <li><strong class="font-weight-bold">Nha khoa thẩm mỹ công nghệ
                        cao <?= $co_so != null ? $co_so->title : ($model->coSoHasOne != null ? $model->coSoHasOne->title : 'MY AURIS') ?></strong>
                    chân
                    thành cảm ơn quý khách đã tin tưởng và sử dụng dịch vụ của chúng tôi.
                </li>
                <?php if ($co_so != null || $model->coSoHasOne != null) { ?>
                    <li>Quý khách có thể đóng góp ý kiến về chất lượng và thái độ phục vụ của đội ngũ nhân viên chúng
                        tôi qua hotline:
                        <strong class="font-weight-bold"><?= $co_so != null ? $co_so->hotline : $model->coSoHasOne->hotline; ?></strong>
                        để chúng tôi ngày càng hoàn thiện dịch vụ một cách chuyên nghiệp nhất.
                    </li>
                <?php } ?>
            </ul>
            <div class="row">
                <div class="col-7">

                </div>
                <div class="col-5">
                    <div class="text-right">
                        Ngày <?= date('d') ?> tháng <?= date('m') ?> năm <?= date('Y') ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="text-center">Quản lý phòng khám</div>
                </div>
                <div class="col-4">
                    <div class="text-center">Thu ngân</div>
                </div>
                <div class="col-4">
                    <div class="text-center">Khách hàng</div>
                </div>
            </div>
        </div>
    </div>
</div>
