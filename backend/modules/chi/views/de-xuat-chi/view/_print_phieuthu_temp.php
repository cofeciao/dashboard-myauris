<?php

$this->registerCss('
body, body * {font-family: Time News Roman!important}
');
?>
<div id="deposit-template">
    <div class="deposit-wrap">
        <h1 class="text-center my-2">PHIẾU </h1>
        <div id="deposit-customer-details">
            <ul class="list-unstyled">
                <li>Tên khách hàng:
                    <strong class="font-weight-bold">test</strong>
                </li>
                <li>Địa chỉ: test</li>
                <li>Số điện thoại: test</li>
                <li>Mã đơn hàng: <strong>test</strong></li>
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

                        <tr>
                            <td class="text-center">test</td>
                            <td class="text-left d-none">

                            </td>
                            <td class="text-left">
                                test
                            </td>
                            <td class="text-center">4523</td>
                            <td class="text-right">
                                test
                            </td>
                            <td class="text-right">
                                test
                            </td>
                        </tr>

                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="4" class="text-left">
                                <strong>Chiết khấu</strong>
                                <p style="margin: 0;">
                                    test
                                </p>

                            </td>
                            <td class="text-right" style="vertical-align: middle">
                                test
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-left"><strong>Tổng cộng</strong></td>
                            <td class="text-right">test</td>
                        </tr>
                        <tr style="display: none">
                            <td colspan="4" class="text-left"><strong>Đặt cọc</strong></td>
                            <td class="text-right">test</td>
                        </tr>
                        <tr style="display: none">
                            <td colspan="4" class="text-left"><strong>Đã thanh toán</strong></td>
                            <td class="text-right">
                                test
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4" class="text-left">
                                <strong>
                                    test
                                </strong>
                            </td>
                            <td class="text-right">
                                test
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-left"><strong>Còn lại</strong></td>
                            <td class="text-right">
                                test
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
                        cao test</strong>
                    chân
                    thành cảm ơn quý khách đã tin tưởng và sử dụng dịch vụ của chúng tôi.
                </li>

                <li>Quý khách có thể đóng góp ý kiến về chất lượng và thái độ phục vụ của đội ngũ nhân viên chúng
                    tôi qua hotline:
                    <strong class="font-weight-bold">test</strong>
                    để chúng tôi ngày càng hoàn thiện dịch vụ một cách chuyên nghiệp nhất.
                </li>
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
