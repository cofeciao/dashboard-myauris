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
#overview-graph-lineChart{height:600px}
#overview-miniPie{width:600px;height:450px;}
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
        <div class="row m-1">
            <div class="col-6 ">
                <div id="overview-dimensionSummary">
                    <div id="overview-dimensionSummary-miniTable">
                        <table class="table">
                            <thead>
                            <tr class="text-center font-weight-bold">
                                <th class="font-weight-bold">Lý do</th>
                                <th class="font-weight-bold">Số lần hủy</th>
                                <th class="font-weight-bold">Tỷ lệ </th>
                            </tr>
                            </thead>
                            <tbody id="table-report-fail">

                            </tbody>
                        </table>
                    </div>
                    <!-- end #overview-dimensionSummary-miniTable -->
                </div>
                <!-- end #overview-dimensionSummary -->
            </div>
            <div class="col-6">
                <div id="overview-miniPie"></div>
            </div>
        </div>
    </section>
</div>

<?php include "js_handle.php"; ?>