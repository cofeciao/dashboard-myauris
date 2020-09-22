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
.column-report{
    width: 20%;
}

CSS;
$this->registerCss($css);
?>
<div class="view m-1">
    <?php include "_search.php"; ?>
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
                <div class="table-wrap">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hovered">
                            <tr class="text-center">
                                <th class="custom-th" scope="col">#</th>
                                <th class="custom-th" scope="col">Tên DirectSale</th>
                                <th class="column-report custom-th" scope="col">Tương tác</th>
                                <th class="column-report custom-th" scope="col">Lịch mới</th>
                                <th class="column-report custom-th" scope="col">Lịch Hẹn</th>
                                <th class="column-report custom-th" scope="col">Khách Đến</th>
                                <th class="column-report custom-th" scope="col">Khách Làm</th>
                                <tbody id="table-report" class="text-center">

                                </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>


<?php
\backend\modules\report\assets\DiaLyAssets::register($this);
include "js_handle.php"; ?>