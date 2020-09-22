<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23-Jan-19
 * Time: 10:21 AM
 */

use backend\modules\clinic\models\PhongKhamSanPham;
use backend\modules\clinic\models\PhongKhamDichVu;

?>
<?php
if ($model) {
    $order = json_decode($model->customer_order); ?>
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Dịch vụ</th>
            <th>Sản phẩm</th>
            <th>Số lượng</th>
<!--            <th>Thành tiền</th>-->
        </tr>
        </thead>
        <tbody>
        <?php
        $totalSL = 0;
    $moneyTotal = 0;
    $i=1;
    foreach ($order as $key => $item) {
        $moneyTotal += str_replace('.', '', $item->thanh_tien);
        $totalSL += $item->so_luong; ?>
            <tr>
                <th scope="row">Dich vụ: <?= $i; ?></th>
                <td><?= PhongKhamDichVu::getOneDichVu($item->dich_vu)->name; ?></td>
                <td><?= PhongKhamSanPham::getOneSanPham($item->san_pham)->name; ?></td>
                <td><?= $item->so_luong; ?></td>
<!--                <td>--><?//= $item->thanh_tien; ?><!--</td>-->
            </tr>
            <?php
            $i++;
    } ?>
        </tbody>
        <tfoot style="color:#0B6E50">
        <tr>
            <th scope="row" colspan="4">Total</th>
            <td><?= $totalSL; ?></td>
<!--            <td>--><?php //echo number_format($moneyTotal, 0, ',', '.')?><!--</td>-->
        </tr>
        </tfoot>
    </table>
    <?php
}
