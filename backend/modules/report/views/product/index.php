<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23-Apr-19
 * Time: 4:48 PM
 */

use yii\helpers\Html;
use yii\helpers\Url;

$css = <<< CSS
#overview-graph-lineChart{height:450px}
#overview-miniPie{width:600px;height:450px;}
CSS;
$this->registerCss($css);
?>

    <div class="view m-1">
        <?php include "_search.php";?>

        <section>
            <div class="row m-1">
                <div class="col-6 ">
                    <div id="overview-dimensionSummary">
                        <div id="overview-dimensionSummary-miniTable">
                            <table class="table">
                                <thead>
                                <tr class="text-center font-weight-bold">
                                    <th class="font-weight-bold">#</th>
                                    <th class="font-weight-bold">Sản Phẩm</th>
                                    <th class="font-weight-bold">Số lượng</th>
                                    <th class="font-weight-bold">Tổng thu </th>
                                </tr>
                                </thead>
                                <tbody id="table-report-sanpham">

                                </tbody>
                            </table>
                        </div>
                        <!-- end #overview-dimensionSummary-miniTable -->
                    </div>
                    <!-- end #overview-dimensionSummary -->
                </div>
                <div class="col-6">
                    <div id="overview-miniPie">

                    </div>
                </div>
            </div>
        </section>
    </div>

<?php
\backend\modules\report\assets\ProductAssets::register($this);
include "js_handle.php"; ?>