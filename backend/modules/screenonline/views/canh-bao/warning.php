<?php

use backend\models\CanhBao;
use common\helpers\MyHelper;
use common\models\UserProfile;

if (isset($data)) {
    $green =[
        CanhBao::DIRECT_SALE_CHOT_THANH_CONG,
        CanhBao::TY_LE_CHOT_TREN_65,
        CanhBao::TY_LE_LICH_TUONG_TAC_TREN_12,
        CanhBao::DOI_ONLINE_TY_LE_TB_TREN_11,
        CanhBao::DIREC_SALE_CHOT_TB_TREM_65,
        CanhBao::TY_LE_DEN_LICH_HEN_TREN_70,
        CanhBao::GIA_TB_TUONG_TAC_TREN_200
    ];
    foreach ($data as $item) {
        $color = 'bg-red';
        if (in_array($item->type, $green)) {
            $color = 'bg-primary';
        }
        if (isset($item->parent_id)) {
            $customer = CanhBao::getCustomerInfo($item->parent_id);
        }
        if (isset($item->user_id)) {
            $userName = UserProfile::getFullName($item->user_id);
        }
        if (!empty($item->date)) {
            $date = date('d-m-Y', $item->date);
        } ?>
        <li>
            <div class="list-timeline-time" data-time="1564018249">
                <?= MyHelper::time_elapsed_string('@' . $item->date) ?>
            </div>
            <i class="fa fa-refresh list-timeline-icon <?= $color ?>"></i>
            <?php

            if ($item->type == CanhBao::KHACH_HANG_DANH_GIA) {
                ?>
                <div class="list-timeline-content text-danger">
                    Khách hàng <span class="yellow"> <?= $customer['name'] . ' - ' . $customer['phone'] ?> </span>
                    vừa đánh giá 1 sao
                </div>

                <?php
            }

        if ($item->type == CanhBao::GIA_TB_TUONG_TAC_TREN_200) {
            ?>
                <div class="list-timeline-content">
                    Cảnh báo giá 1 tương tác trên 200k <span class="yellow"> ngày <?= $date ?> </span>
                </div>
                <?php
        }
        if ($item->type == CanhBao::GIA_TB_TUONG_TAC_DUOI_120) {
            ?>
                <div class="list-timeline-content">
                    Giá 1 tương tác dưới 120k <span class="yellow"> ngày <?= $date ?> </span>
                </div>
                <?php
        }
        if ($item->type == CanhBao::DIRECT_SALE_CHOT_THANH_CONG) {
            ?>
                <div class="list-timeline-content">
                    Direct Sale <a href="#"><?= $userName ?></a> vừa chốt thành công liên
                    tục <a href="#"> 3
                        khách </a>
                </div>
                <?php
        }
        if ($item->type == CanhBao::DIRECT_SALE_CHOT_FAIL) {
            ?>
                <div class="list-timeline-content text-danger">
                    Direct Sale <a href="#"><?= $userName ?> </a> vừa chốt fail liên tục <a
                        href="#">2 khách</a>
                </div>
                <?php
        }
        if ($item->type == CanhBao::DIREC_SALE_CHOT_TB_DUOI_48) {
            ?>
                <div class="list-timeline-content text-danger">
                    <a href="#"> Đội Direct Sale </a> có tỉ lệ chốt TB dưới 48%
                    ngày <?= date('d-m-Y', $item->date) ?>
                </div>
                <?php
        }
        if ($item->type == CanhBao::DIREC_SALE_CHOT_TB_TREM_65) {
            ?>
                <div class="list-timeline-content text-danger">
                    <a href="#"> Đội Direct Sale </a> có tỉ lệ chốt TB trên 65% - <?= $item->description ?>
                    ngày <?= date('d-m-Y', $item->date) ?>
                </div>
                <?php
        }
        if ($item->type == CanhBao::TY_LE_LICH_TUONG_TAC_DƯƠI_10) {
            ?>
                <div class="list-timeline-content text-danger">
                    Online <a href="#"><?= $userName ?></a> có tỷ lệ lịch trên tương tác
                    dưới 10% ngày <?= date('d-m-Y', $item->date) ?>
                </div>
                <?php
        }
        if ($item->type == CanhBao::TY_LE_LICH_TUONG_TAC_TREN_12) {
            ?>
                <div class="list-timeline-content">
                    Online <a href="#"><?= $userName ?></a> có tỷ lệ lịch trên tương tác
                    trên 12% ngày <?= date('d-m-Y', $item->date) ?>
                </div>
                <?php
        }
        if ($item->type == CanhBao::TY_LE_DEN_LICH_HEN_DUOI_50) {
            ?>
                <div class="list-timeline-content warning">
                    Tỉ lệ khách đến / lịch hẹn dưới 50% ( <?= $item->description ?> ) ngày <?= date('d-m-Y', $item->date) ?>
                </div>
                <?php
        }
        if ($item->type == CanhBao::TY_LE_DEN_LICH_HEN_TREN_70) {
            ?>
                <div class="list-timeline-content">
                    Tỉ lệ khách đến / lịch hẹn trên 70% ( <?= $item->description ?> ) ngày <?= date('d-m-Y', $item->date) ?>

                </div>
                <?php
        }
        if ($item->type == CanhBao::DOI_ONLINE_TY_LE_TB_TREN_11) {
            ?>
                <div class="list-timeline-content">
                    <a href="#"> Đội Online </a>có tỉ lệ lịch / tương tác trên 11% ( <?= $item->description ?> ) ngày <?= date('d-m-Y', $item->date) ?>
                </div>
                <?php
        }
        if ($item->type == CanhBao::DOI_ONLINE_TY_LE_TB_DUOI_8) {
            ?>
                <div class="list-timeline-content text-danger">
                    <a href="#"> Đội Online </a> có tỉ lệ lịch / tương tác dưới 8%( <?= $item->description ?> ) ngày <?= date('d-m-Y', $item->date) ?>
                </div>
                <?php
        }
        if ($item->type == CanhBao::TY_LE_CHOT_DUOI_48) {
            ?>
                <div class="list-timeline-content text-danger">
                    <a href="#"> Đội Direcsale </a> có tỉ lệ chôt khách dưới 48% ( <?= $item->description ?> ) ngày <?= date('d-m-Y', $item->date) ?>
                </div>
                <?php
        }
        if ($item->type == CanhBao::TY_LE_CHOT_TREN_65) {
            ?>
                <div class="list-timeline-content">
                    <a href="#"> Đội Direcsale </a> có tỉ lệ chôt khách trên 65% ( <?= $item->description ?> ) ngày <?= date('d-m-Y', $item->date) ?>
                </div>
                <?php
        } ?>
        </li>
        <?php
    }
}
