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
    $thanhToan = \backend\modules\clinic\models\PhongKhamDonHangWThanhToan::find()->where(['phong_kham_don_hang_id' => $model->id])->all(); ?>
    <table class="table">
        <thead>
        <tr>
            <th>STT</th>
            <th>Ngày thanh toán</th>
            <th>Loại thanh toán</th>
            <th>Hình thức</th>
            <th>Số tiền</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $totalMoney = 0;
    $i = 1;
    foreach ($thanhToan as $key => $item) {
        $totalMoney += str_replace('.', '', $item->tien_thanh_toan); ?>
            <tr>
                <th scope="row"><?= $i; ?></th>
                <td><?= date('d-m-Y', $item->created_at); ?></td>
                <td><?= PhongKhamLoaiThanhToan::getOneLTT($item->loai_thanh_toan)->name; ?></td>
                <td>
                    <?php
                    if ($item->tam_ung === null || !array_key_exists($item->tam_ung, \backend\models\doanhthu\ThanhToanModel::THANHTOAN_TYPE)) {
                        echo '-';
                    } else {
                        echo \backend\models\doanhthu\ThanhToanModel::THANHTOAN_TYPE[$item->tam_ung];
                    } ?>
                </td>
                <td><?= $item->tien_thanh_toan == null || $item->tien_thanh_toan == '' ? null : number_format($item->tien_thanh_toan, 0, '', '.'); ?>
                </td>
            </tr>
            <?php
            $i++;
    }
    if ($thanhToan == null) {
        ?>
            <tr>
                <th colspan="5" class="small">Trống</th>
            </tr>
            <?php
    } ?>
        <tfoot style="color:#0B6E50">
        <tr>
            <th scope="row" colspan="4">Total</th>
            <td><?= number_format($totalMoney, 0, ',', '.') ?></td>
        </tr>
        </tfoot>
    </table>
    <?php
}
