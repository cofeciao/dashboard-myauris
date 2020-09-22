<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23-Apr-19
 * Time: 4:48 PM
 */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Report Khách Hàng Fail";

$css = <<< CSS

tr td:last-child {
    text-align: right;
}
td {
    height: 35px;
    vertical-align: middle !important;
}
.custom-th {
    text-align: center;
    height: 35px;
    vertical-align: middle !important;
    background-color: #404d67;
    color: white;
}
CSS;
$this->registerCss($css);
?>
<div class="view m-1">
    <?php include "_search.php";?>
    <section>
        <div class="row">
            <div class="col-12">
                <div id="overview-graph" class="pt-1">
                    <div id="overview-graph-lineChart"></div>
                </div>
                <!-- end #overview-graph -->
            </div>
        </div>
    </section>
    <section>
        <div class="row ">
            <div class="col-12">
                <table class="table table-striped table-bordered " >
                    <tr class="text-center">
                        <th class="custom-th" scope="col">#</th>
                        <th class="custom-th" scope="col">Tên tỉnh</th>
                        <th class="custom-th" scope="col" id="th-lichmoi" >Lịch hẹn</th>
                        <th class="custom-th" scope="col" data-name="KhachDen">Khách đến</th>
                        <th class="custom-th" scope="col">Không đến</th>
                        <th class="custom-th" scope="col">Thành công</th>
                        <th class="custom-th" scope="col">Chốt Fail</th>
                        <th class="custom-th" scope="col">Doanh thu</th>
                    <tbody id="table-report" class="text-center">

                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>


<?php
\backend\modules\report\assets\DiaLyAssets::register($this);
include "js_handle.php"; ?>