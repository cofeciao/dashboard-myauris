<?php
/**
 * Created by PhpStorm.
 * User: luken
 * Date: 6/18/2020
 * Time: 17:06
 */

use backend\modules\user\models\User;

$user = new User();
$roleName = $user->getRoleName(\Yii::$app->user->id);

if ($roleName == User::USER_QUANLY_PHONGKHAM) {
    foreach ($doanhThuThangTheoCoSo as $key => $item) {
        ?>
        <div class="card h-100">
            <div class="card-body">
                <span class="d-block font-11 font-weight-500 text-uppercase mb-10">Doanh thu tháng <?= date('m-Y') ?></span>
                <span class="d-block">
                    <span class="display-5 font-weight-400">
                        <?= number_format($item['doanhthutheothang'], 0); ?>
                        <sup><u>đ</u></sup>
                    </span>
                </span>
            </div>
        </div>
    <?php }
} else { ?>
    <div class="card mb-1">
        <div class="card-header">
            <h4 class="card-title font-weight-bold"><?= $title ?></h4>
        </div>
        <div class="card-body p-0">
            <div class="pt-1 pl-1 pr-1">
                <div id="revenue-month-data" class="hk-row">
                    <div class="col-lg-12">
                        <div class="d-flex align-items-center justify-content-start mb-1 pb-1"
                             style="border-bottom:1px solid #eee">
                            <div class="font-16 font-weight-500 mr-1">Tổng</div>
                            <div style="font-size:2rem;font-weight:300;line-height:1.2;color:#324148">
                                <?= number_format($totalDoanhThuThang, 0); ?>
                                <sup><u>đ</u></sup>
                            </div>
                        </div>
                    </div>

                    <?php
                    $bg_badge = ['bg-blue-grey', 'bg-danger bg-darken-4', 'bg-blue bg-accent-3', 'bg-purple bg-darken-1'];
                    foreach ($doanhThuThangTheoCoSo as $key => $item) {
                        $percent = $doanhThuThangTheoCoSo == 0 ? 0 : ($totalDoanhThuThang == 0 ? 0 : $item['doanhthutheothang'] / $totalDoanhThuThang * 100);
                        if ($percent <= 10) {
                            $text_color = 'text-danger';
                            $icon = '<i class="fa fa-arrow-down"></i>';
                        } elseif ($percent > 10 && $percent <= 50) {
                            $text_color = 'text-warning';
                            $icon = '<i class="fa fa-arrow-down"></i>';
                        } elseif ($percent > 50 && $percent <= 70) {
                            $text_color = 'blue';
                            $icon = '<i class="fa fa-arrow-up"></i>';
                        } elseif ($percent > 70) {
                            $text_color = 'text-primary';
                            $icon = '<i class="fa fa-arrow-up"></i>';
                        }
                        ?>
                        <div class="col-md-6 col-6 mb-1">
                            <span class="d-block text-capitalize" style="margin-bottom:.35rem;">
                                <span class="badge <?= array_key_exists($key, $bg_badge) ? $bg_badge[$key] : $bg_badge[0] ?>">Cơ sở <?= $key; ?></span>
                            </span>
                            <span class="d-block text-dark font-weight-500 font-20">
                                <?= number_format($item['doanhthutheothang'], 0); ?>
                                <sup><u>đ</u></sup>
                            </span>
                            <span class="d-block font-weight-500 font-13 <?= $text_color ?>">
                                <?= $icon ?>
                                <?= number_format($percent, 2) . '%' ?>
                            </span>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>