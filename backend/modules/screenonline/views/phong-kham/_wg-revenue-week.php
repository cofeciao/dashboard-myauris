<?php
/**
 * Created by PhpStorm.
 * User: luken
 * Date: 6/25/2020
 * Time: 09:01
 */

foreach ($doanhThuTuanTheoCoSo as $key => $value) {
?>
    <div class="card h-100">
        <div class="card-body">
            <span class="d-block font-11 font-weight-500 text-uppercase mb-10">Doanh thu tuần</span>
            <span class="d-block">
                    <span class="display-5 font-weight-400">
                        <?= number_format($value['doanhthutheotuan'], 0); ?>
                        <sup><u>đ</u></sup>
                    </span>
                </span>
        </div>
    </div>
<?php } ?>
