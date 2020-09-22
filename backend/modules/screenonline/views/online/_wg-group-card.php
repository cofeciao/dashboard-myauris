<?php
/**
 * Created by PhpStorm.
 * User: luken
 * Date: 6/24/2020
 * Time: 09:09
 */

use backend\modules\user\models\User;

$user = new User();
$roleName = $user->getRoleName(\Yii::$app->user->id);
?>
<style>
    .card-group > .card:not(:last-child) {
        border-right: 1px solid #eaecec;
    }

    .card-group > .card > .card-body > div:not(:last-child),
    .card-group > .card > .card-body span.counter-anim {
        margin-bottom: 5px;
    }
</style>
<?php
$color = ['blue-grey', 'primary', 'danger', 'warning', 'success'];
$i = 1;
foreach ($dataCoso as $key => $value) {
    ?>
    <div class="col-lg-12">
        <div class="card-group" style="padding:1rem 0">
            <div class="position-absolute" style="top:0;z-index:1">
                <span class="badge badge-<?= $color[$i]; ?> badge-pill float-left font-weight-bold">Cơ sở <?= $key; ?></span>
            </div>


            <div class="card card-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        <span class="d-block font-16 text-dark font-weight-500">Lịch hẹn hôm nay</span>
                    </div>
                    <div class="text-center">
                        <span class="d-block display-5 text-dark counter-anim"><?= $value['lich_hen']; ?></span>
                        <small class="d-block font-14">Tổng lịch hẹn T.<?= date('m') ?>:
                            <span class="font-weight-600"><?= $value['tong_lichhen_thang']; ?></span>
                        </small>
                    </div>
                </div>
            </div>


            <div class="card card-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="d-block font-16 text-dark font-weight-500">Khách đến</span>
                        </div>
                        <div>
                            <span class="text-success font-14 font-weight-500"><?= $value['phantram_khachden']; ?>%</span>
                        </div>
                    </div>
                    <div class="text-center">
                        <span class="d-block display-5 text-dark counter-anim"><?= $value['khach_den']; ?></span>
                        <small class="d-block font-14">Tổng khách đến T.<?= date('m') ?>:
                            <span class="font-weight-600"><?= $value['tong_khachden_thang']; ?></span>
                        </small>
                    </div>
                </div>
            </div>


            <?php
            if ($roleName == User::USER_QUANLY_PHONGKHAM) { ?>
                <div class="card card-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <span class="d-block font-16 text-dark font-weight-500">Khách làm</span>
                            </div>
                            <div>
                                <span class="text-success font-14 font-weight-500"><?= $value['phantram_khachlam']; ?>%</span>
                            </div>
                        </div>
                        <div class="text-center">
                            <span class="d-block display-5 text-dark counter-anim"><?= $value['khach_lam']; ?></span>
                            <small class="d-block font-14">Tổng khách làm T.<?= date('m') ?>:
                                <span class="font-weight-600"><?= $value['tong_khachlam_thang']; ?></span>
                            </small>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <?php
    $i++;
}
?>
<?php if ($roleName != User::USER_QUANLY_PHONGKHAM) { ?>
    <div class="col-lg-12">
        <div class="card-group" style="padding-top:1rem">
            <div class="position-absolute" style="top:0;z-index:1">
                <span class="badge bg-<?= $color[$i]; ?> badge-pill float-left font-weight-bold">Tổng</span>
            </div>
            <div class="card card-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        <span class="d-block font-16 text-dark font-weight-500">Lịch hẹn hôm nay</span>
                    </div>
                    <div class="text-center">
                        <span class="d-block display-5 text-dark counter-anim"><?= $dataTotal['tong_lichhen_ngay']; ?></span>
                        <small class="d-block font-13">Tổng lịch hẹn T.<?= date('m') ?>:
                            <span class="font-weight-600"><?= $dataTotal['tong_lichhen_thang']; ?></span>
                        </small>
                    </div>
                </div>
            </div>
            <div class="card card-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="d-block font-15 text-dark font-weight-500">Khách đến</span>
                        </div>
                        <div>
                            <?php
                            if ($dataTotal['tong_lichhen_ngay'] == 0) {
                                $phan_tram = 0;
                            } else {
                                $phan_tram = round(($dataTotal['tong_khachden_ngay'] / $dataTotal['tong_lichhen_ngay']) * 100, 2);
                            }
                            ?>
                            <span class="text-success font-14 font-weight-500"><?= $phan_tram; ?>%</span>
                        </div>
                    </div>
                    <div class="text-center">
                        <span class="d-block display-5 text-dark counter-anim"><?= $dataTotal['tong_khachden_ngay']; ?></span>
                        <small class="d-block font-13">Tổng khách đến T.<?= date('m') ?>:
                            <span class="font-weight-600"><?= $dataTotal['tong_khachden_thang']; ?></span>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
