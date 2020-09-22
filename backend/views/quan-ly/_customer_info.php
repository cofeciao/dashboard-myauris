<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 17-04-2019
 * Time: 04:33 PM
 */
?>
<div class="sub-panel">
    <div class="sp-title">Thông tin khách hàng</div>
    <div class="sp-content">
        <div class="row">
            <div class="col-4 c-col-1">
                <div class="form-group c-group">
                    <label class="control-label c-label">Họ và tên:</label>
                    <div class="c-info"><?= $customer->full_name ?: '-' ?></div>
                </div>
                <div class="form-group c-group">
                    <label class="control-label c-label">Tên:</label>
                    <div class="c-info"><?= $customer->forename ?: '-' ?></div>
                </div>
                <div class="form-group c-group">
                    <label class="control-label c-label">Ngày sinh:</label>
                    <div class="c-info"><?= $customer->birthday ?: '-' ?></div>
                </div>
                <div class="form-group c-group">
                    <label class="control-label c-label">Giới tính:</label>
                    <div class="c-info"><?= $customer->getSex()[$customer->sex] ?></div>
                </div>
            </div>
            <div class="col-4 c-col-2">
                <div class="form-group c-group">
                    <label class="control-label c-label">Thời gian đến:</label>
                    <div class="c-info"><?= $customer->time_lichhen != null ? date('d-m-Y', $customer->time_lichhen) : '-' ?></div>
                </div>
                <div class="form-group c-group">
                    <label class="control-label c-label">Đặt hẹn:</label>
                    <div class="c-info font-weight-bold"><?= $customer->statusDatHenHasOne != null ? $customer->statusDatHenHasOne->name : '-' ?></div>
                </div>
                <div class="form-group c-group">
                    <label class="control-label c-label">Direct sale:</label>
                    <div class="c-info"><?= $customer->directSaleHasOne != null ? $customer->directSaleHasOne->fullname : '-' ?></div>
                </div>
            </div>
            <div class="col-4 c-col-3">
                <div class="form-group c-group">
                    <label class="control-label c-label">Kết quả thăm khám:</label>
                    <div class="c-info">
                        <?php
                        if ($customer->dentalTagHasOne != null) {
                        }
                        ?>
                    </div>
                </div>
                <div class="form-group c-group">
                    <label class="control-label c-label">Mong muốn KH:</label>
                    <div class="c-info"><?= $customer->customer_mongmuon ?: '-' ?></div>
                </div>
                <div class="form-group c-group">
                    <label class="control-label c-label">Ghi chú Direct:</label>
                    <div class="c-info"><?= $customer->note_direct ?: '-' ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="sub-panel">
    <div class="sp-title">Thông tin cá nhân</div>
    <div class="sp-content">
        <div class="row">
            <div class="col-4 c-col-1">
                <div class="form-group c-group">
                    <label class="control-label c-label">Số điện thoại:</label>
                    <div class="c-info"><?= $customer->phone ?: '-' ?></div>
                </div>
            </div>
            <div class="col-4 c-col-1">
                <div class="form-group c-group">
                    <label class="control-label c-label">Tỉnh thành:</label>
                    <div class="c-info"><?= $customer->provinceHasOne != null ? $customer->provinceHasOne->name : '-' ?></div>
                </div>
            </div>
            <div class="col-4 c-col-1">
                <div class="form-group c-group">
                    <label class="control-label c-label">Quận huyện:</label>
                    <div class="c-info"><?= $customer->districtHasOne != null ? $customer->districtHasOne->name : '-' ?></div>
                </div>
            </div>
            <div class="col-12 c-col-1">
                <div class="form-group c-group">
                    <label class="control-label c-label">Địa chỉ:</label>
                    <div class="c-info"><?= $customer->address ?: '-' ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
