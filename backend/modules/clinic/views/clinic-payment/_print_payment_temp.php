<?php

use backend\models\doanhthu\ThanhToanModel;
use backend\modules\clinic\models\PhongKhamLoaiThanhToan;

$this->registerCss('
body, body * {font-family: Time News Roman!important}
');
?>
<div id="deposit-template">
    <div class="deposit-wrap">
        <h1 class="text-center my-2">
            <?php
            if ($payment->tam_ung == null) {
                $payment->tam_ung = ThanhToanModel::THANH_TOAN;
            }
            echo 'Phiếu ' . ThanhToanModel::THANHTOAN_TYPE[$payment->tam_ung];
            ?>
        </h1>
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
                    <h5><strong>Thông tin đơn hàng</strong></h5>
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 60px">STT</th>
                            <th class="text-left">Tên dịch vụ</th>
                            <th class="text-left">Tên sản phẩm</th>
                            <th class="text-center">Số lượng<br>răng</th>
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
                                <td class="text-left">
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
                            <td colspan="5" class="text-right"><strong>Tổng cộng</strong></td>
                            <td class="text-right"><?= number_format($paymentTotal, 0, '', '.') ?></td>
                        </tr>
                        <?php if ($payment->tam_ung == 1) { ?>
                            <tr>
                                <td colspan="5" class="text-right"><strong>Đặt cọc</strong></td>
                                <td class="text-right"><?= number_format($model->dat_coc, 0, '', '.') ?></td>
                            </tr>
                        <?php } ?>
                        </tfoot>
                    </table>
                    <?php if ($payment->tam_ung != 1) { ?>
                        <h5><strong>Thông tin thanh toán</strong></h5>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center" style="width: 60px">STT</th>
                                <th class="text-center">Ngày thanh toán</th>
                                <th class="text-center">Loại thanh toán</th>
                                <th class="text-center">Hình thức</th>
                                <th class="text-right">Số tiền</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td class="text-center">1</td>
                                <td class="text-center"><?= date('d-m-Y', $payment->created_at); ?></td>
                                <td class="text-center"><?= PhongKhamLoaiThanhToan::getOneLTT($payment->loai_thanh_toan)->name; ?></td>
                                <td class="text-center">
                                    <?php
                                    if ($payment->tam_ung === null || !array_key_exists($payment->tam_ung, \backend\models\doanhthu\ThanhToanModel::THANHTOAN_TYPE)) {
                                        echo '-';
                                    } else {
                                        echo \backend\models\doanhthu\ThanhToanModel::THANHTOAN_TYPE[$payment->tam_ung];
                                    }
                                    ?>
                                </td>
                                <td class="text-right"><?= $payment->tien_thanh_toan == null || $payment->tien_thanh_toan == '' ? '0' : number_format($payment->tien_thanh_toan, 0, '', '.'); ?></td>
                            </tr>
                            </tbody>
                            <tfoot>

                            </tfoot>
                        </table>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div id="deposit-footer">
            <p><strong class="font-weight-bold"><em>Ghi chú:</em></strong></p>
            <ul class="list-note list-unstyled">
                <li><strong class="font-weight-bold">Nha khoa thẩm mỹ công nghệ
                        cao <?= $model->coSoHasOne != null ? $model->coSoHasOne->title : 'MY AURIS' ?></strong> chân
                    thành cảm ơn quý khách đã tin tưởng và sử dụng dịch vụ của chúng tôi.
                </li>
                <?php if ($model->coSoHasOne != null) { ?>
                    <li>Quý khách có thể đóng góp ý kiến về chất lượng và thái độ phục vụ của đội ngũ nhân viên chúng
                        tôi qua hotline:
                        <strong class="font-weight-bold"><?= $model->coSoHasOne->hotline; ?></strong>
                        để chúng tôi ngày càng hoàn thiện dịch vụ một cách chuyên nghiệp nhất.
                    </li>
                <?php } ?>
            </ul>
            <div class="row">
                <div class="col-7">

                </div>
                <div class="col-5">
                    <div class="text-center">
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
