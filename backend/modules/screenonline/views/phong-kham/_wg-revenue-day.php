<?php
/**
 * Created by PhpStorm.
 * User: luken
 * Date: 6/18/2020
 * Time: 15:35
 */

use backend\modules\user\models\User;

$user = new User();
$roleName = $user->getRoleName(\Yii::$app->user->id);

if ($roleName == User::USER_QUANLY_PHONGKHAM) {
    foreach ($doanhThuNgayTheoCoSo as $key => $value) {
        ?>
        <div class="card h-100">
            <div class="card-body">
                <span class="d-block font-11 font-weight-500 text-uppercase mb-10">Doanh thu ngày</span>
                <span class="d-block">
                    <span class="display-5 font-weight-400">
                        <?= number_format($value['doanhthu'], 0); ?>
                        <sup><u>đ</u></sup>
                    </span>
                </span>
            </div>
        </div>
    <?php }
} else {
    $bg_color = ['bg-blue-grey', 'bg-danger bg-darken-4', 'bg-blue bg-accent-3'];
    $col = '';
    switch (count($doanhThuNgayTheoCoSo)) :
        case 1:
            $col = 'col-lg-12 col-md-6';
            break;
        default:
            $col = 'col-lg-6 col-md-6';
            break;
    endswitch;
    foreach ($doanhThuNgayTheoCoSo as $key => $value) {
        ?>
        <div class="<?= $col ?>">
            <div class="card text-white <?= array_key_exists($key, $bg_color) ? $bg_color[$key] : $bg_color[0] ?> mb-1">
                <div class="card-body">
                    <span class="d-block font-11 font-weight-500 text-uppercase mb-10">Doanh thu cơ sở <?= $key ?></span>
                    <span class="d-block">
                    <span class="display-6 font-weight-400">
                        <?= number_format($value['doanhthu'], 0); ?>
                        <sup><u>đ</u></sup>
                    </span>
                </span>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="col-lg-12 col-md-6">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <span class="d-block font-11 font-weight-500 text-uppercase mb-10">Tổng doanh thu hôm nay</span>
                <span class="d-block">
                <span class="display-5 font-weight-400">
                    <?= number_format($totalDoanhThuNgay, 0); ?>
                    <sup><u>đ</u></sup>
                </span>
            </span>
            </div>
        </div>
    </div>
<?php } ?>