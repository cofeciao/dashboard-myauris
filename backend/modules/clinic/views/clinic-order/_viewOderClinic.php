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
    $order = \backend\modules\clinic\models\PhongKhamDonHangWOrder::find()->where(['phong_kham_don_hang_id' => $model->id])->all();
//    var_dump($order);die;
//    $order = json_decode($model->customer_order); ?>
    <table class="table">
        <thead>
        <tr>
            <th>STT</th>
            <th>Dịch vụ</th>
            <th>Sản phẩm</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $totalSL = 0;
    $moneyTotal = 0;
    $i = 1;
    foreach ($order as $key => $item) {
        $moneyTotal += str_replace('.', '', $item->thanh_tien);
        $totalSL += $item->so_luong; ?>
            <tr>
                <th scope="row"><?= $i; ?></th>
                <td>
                    <?php
                    $dichvu = PhongKhamDichVu::getOneDichVu($item->dich_vu);
        if ($dichvu !== null) {
            echo $dichvu->name;
        } else {
            echo '-';
        } ?>
                </td>
                <td>
                    <?php
                    $sanpham = PhongKhamSanPham::getOneSanPham($item->san_pham);
        if ($sanpham !== null) {
            echo $sanpham->name;
        } else {
            echo '-';
        } ?>
                </td>
                <td><?= $item->so_luong; ?></td>
                <td><?= number_format($item->thanh_tien, 0, '', '.') ?></td>
            </tr>
            <?php
            $i++;
    }
    if ($order == null) {
        ?>
            <tr>
                <th colspan="5" class="small">Trống</th>
            </tr>
            <?php
    } ?>

        </tbody>
        <tfoot style="color:#0B6E50">
        <tr>
            <th scope="row" colspan="3">Total</th>
            <td><?= $totalSL; ?></td>
            <td><?= number_format($moneyTotal, 0, ',', '.') ?></td>
        </tr>
        </tfoot>
    </table>
    <?php
}
