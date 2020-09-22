<?php

use backend\modules\clinic\models\PhongKhamLoaiThanhToan;

?>
<div id="deposit-template">
    <div class="deposit-wrap">
        <h1 class="text-center my-2">PHIẾU THANH TOÁN</h1>
        <div id="deposit-customer-details">
            <ul class="list-unstyled">
                <li>Tên khách hàng: <strong class="font-weight-bold"><?= $model->clinicHasOne->full_name == null ? $model->clinicHasOne->forename : $model->clinicHasOne->full_name; ?></strong>
                </li>
                <li>Địa chỉ: <?= $model->clinicHasOne->address == null ? '-' : $model->clinicHasOne->address; ?></li>
                <li>Số điện thoại: <?= $model->clinicHasOne->phone == null ? '-' : $model->clinicHasOne->phone; ?></li>
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
                            <th class="text-center">Ngày thanh toán</th>
                            <th class="text-center">Loại thanh toán</th>
                            <th class="text-center">Hình thức</th>
                            <th class="text-right">Số tiền</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $paymentTotal = 0;
                        $i = 1;
                        foreach ($payment as $key => $item) {
                            $paymentTotal += str_replace('.', '', $item->tien_thanh_toan); ?>
                            <tr>
                                <td class="text-center"><?= $i ?></td>
                                <td class="text-center"><?= date('d-m-Y', $item->created_at); ?></td>
                                <td class="text-center"><?= PhongKhamLoaiThanhToan::getOneLTT($item->loai_thanh_toan)->name; ?></td>
                                <td class="text-center">
                                    <?php
                                    if ($item->tam_ung === null || !array_key_exists($item->tam_ung, \backend\models\doanhthu\ThanhToanModel::THANHTOAN_TYPE)) {
                                        echo '-';
                                    } else {
                                        echo \backend\models\doanhthu\ThanhToanModel::THANHTOAN_TYPE[$item->tam_ung];
                                    } ?>
                                </td>
                                <td class="text-right"><?= $item->tien_thanh_toan == null || $item->tien_thanh_toan == '' ? null : number_format($item->tien_thanh_toan, 0, '', '.'); ?></td>
                            </tr>
                            <?php $i++;
                        } ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="4" class="text-right"><strong>Tổng cộng</strong></td>
                            <td class="text-right">
                                <?= number_format($paymentTotal, 0, '', '.');?>
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
                <li><strong class="font-weight-bold">Nha khoa thẩm mỹ công nghệ cao MY AURIS II</strong> chân thành cảm ơn quý khách đã tin tưởng và sử dụng dịch vụ của chúng tôi.</li>
                <?php if ($model->coSoHasOne != null) { ?>
                <li>Quý khách có thể đóng góp ý kiến về chất lượng và thái độ phục vụ của đội ngũ nhân viên chúng tôi qua hotline:
                    <strong class="font-weight-bold"><?= $model->coSoHasOne->hotline; ?></strong>
                    để chúng tôi ngày càng hoàn thiện dịch vụ một cách chuyên nghiệp nhất.
                </li>
                <?php } ?>
            </ul>
            <div class="row">
                <div class="col-8">

                </div>
                <div class="col-4">
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