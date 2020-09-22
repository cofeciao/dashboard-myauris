<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23-Jan-19
 * Time: 10:51 AM
 */

use backend\modules\clinic\models\PhongKhamLoaiThanhToan;

?>
<?php
if ($model) {
    $thanhToan = json_decode($model->thanh_toan); ?>
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Ngày thanh toán</th>
            <th>Số tiền</th>
            <th>Loại thanh toán</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $totalMoney = 0;
    foreach ($thanhToan as $key => $item) {
        $totalMoney += str_replace('.', '', $item->tien_thanh_toan); ?>
            <tr>
                <th scope="row">Thanh toán lần: <?= $key + 1; ?></th>
                <td><?= date('d-m-Y', $item->ngay_thanh_toan); ?></td>
                <td><?= $item->tien_thanh_toan; ?></td>
                <td><?= PhongKhamLoaiThanhToan::getOneLTT($item->loai_thanh_toan)->name; ?></td>
            </tr>
            <?php
    } ?>
        </tbody>
        <tfoot style="color:#0B6E50">
        <tr>
            <th scope="row" colspan="2">Total</th>
            <td><?=number_format($totalMoney, 0, ',', '.'); ?></td>
            <td></td>
        </tr>
        </tfoot>
    </table>
    <?php
}
