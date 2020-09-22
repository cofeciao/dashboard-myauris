<?php
/**
 * Created by PhpStorm.
 * User: luken
 * Date: 6/6/2020
 * Time: 09:35
 */

use backend\modules\user\models\UserTimelineModel;
use common\helpers\MyHelper;
use yii\widgets\Pjax;

$this->title = "Screen Online";
Pjax::begin(['id' => 'screen-online-ajax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]);
$this->registerCssFile(\yii\helpers\Url::to('@web/modules') . '/screenonline/screenonline.css', ['depends' => [\backend\assets\AppAsset::class]]);
?>
    <div class="heading-element">
        <ul class="list-inline m-0">
            <li><a href="/"><i class="fa fa-home"></i></a></li>
        </ul>
    </div>
    <div class="db-full-screen">
        <div class="hk-row m-0">
            <div class="col-xl-6 col-lg-12">
                <div class="hk-row">
                    <?php
                    switch (count($dataLichMoiTheoCoSo)) {
                        case 1:
                            $col = 'col-lg-6 col-md-6';
                            break;
                        case 2:
                            $col = 'col-lg-4 col-md-6';
                            break;
                        case 3:
                            $col = 'col-lg-3 col-md-6';
                            break;
                        default:
                            $col = 'col-lg-4 col-md-6';
                            break;
                    }
                    ?>
                    <div class="<?= $col ?>">
                        <div class="card card-sm mb-1">
                            <div class="card-body">
                                <div class="box-content">
                                    <span class="box-text font-16">Tổng lịch mới tháng <?= date('m') ?></span>
                                    <div class="d-flex justify-content-between align-items-end">
                                        <div class="box-number counter-anim"><?= $totalLichMoiToiHienTai ?></div>
                                        <span class="progress-description">100%</span>
                                    </div>
                                    <div class="progress progress-sm my-1">
                                        <div class="progress-bar bg-primary" style="width: 100%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    foreach ($dataLichMoiTheoCoSo as $key => $value) {
                        $percent = $totalLichMoiToiHienTai == 0 ? 0 : $value['lichmoi'] / $totalLichMoiToiHienTai * 100;
                        if ($percent <= 10) $bg_color = 'bg-danger';
                        elseif ($percent > 10 && $percent <= 50) $bg_color = 'bg-warning';
                        elseif ($percent > 50 && $percent <= 70) $bg_color = 'blue';
                        elseif ($percent > 70) $bg_color = 'bg-success';
                        ?>
                        <div class="<?= $col ?>">
                            <div class="card card-sm mb-1">
                                <div class="card-body">
                                    <div class="box-content">
                                        <span class="box-text font-16">Lịch mới CS.<?= $key ?> tháng <?= date('m') ?></span>
                                        <div class="d-flex justify-content-between align-items-end">
                                            <div class="box-number counter-anim"><?= $value['lichmoi']; ?></div>
                                            <span class="progress-description"><?= number_format($percent, 2) . '%' ?></span>
                                        </div>
                                        <div class="progress progress-sm my-1">
                                            <div class="progress-bar <?= $bg_color ?>"
                                                 style="width: <?= number_format($percent, 2) . '%' ?>"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="hk-row">
                    <?php
                    switch (count($dataLichHenWithCoSoToLastMonth)) {
                        case 1:
                            $col = 'col-lg-6 col-md-6';
                            break;
                        case 2:
                            $col = 'col-lg-4 col-md-6';
                            break;
                        case 3:
                            $col = 'col-lg-3 col-md-6';
                            break;
                        default:
                            $col = 'col-lg-4 col-md-6';
                            break;
                    }
                    ?>
                    <div class="<?= $col ?>">
                        <div class="card card-sm mb-1">
                            <div class="card-body">
                                <div class="box-content d-flex justify-content-between align-items-center">
                                    <span class="box-text font-16" style="width:50%">Lịch hẹn còn lại<br> tháng <?= date('m') ?></span>
                                    <div class="text-center" style="width:50%">
                                        <div class="box-number counter-anim mt-0"><?= $totalCustomerTodayToLastMonth; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    foreach ($dataLichHenWithCoSoToLastMonth as $key => $value) {
                        ?>
                        <div class="<?= $col ?>">
                            <div class="card card-sm mb-1">
                                <div class="card-body">
                                    <div class="box-content d-flex justify-content-between align-items-center">
                                        <span class="box-text font-16"
                                              style="width:50%">Lịch hẹn còn lại<br> CS.<?= $key; ?></span>
                                        <div class="text-center" style="width:50%">
                                            <div class="box-number counter-anim mt-0"><?= $value['lichhen']; ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="hk-row">
                    <div class="col-lg-8">
                        <div class="hk-row">
                            <div class="col-md-6">
                                <div class="card card-sm mb-1">
                                    <div class="card-body">
                                        <div class="box-content d-flex justify-content-between align-items-center">
                                    <span class="box-text font-16"
                                          style="width:60%">Dự trù lịch hẹn<br> còn lại tháng <?= date('m') ?></span>
                                            <div class="text-center" style="width:40%">
                                                <div class="box-number counter-anim mt-0"><?= $lichHenDutru; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-sm mb-1">
                                    <div class="card-body">
                                        <div class="box-content d-flex justify-content-between align-items-center">
                                    <span class="box-text font-16"
                                          style="width:60%">Dự trù khách đến<br> còn lại tháng <?= date('m') ?></span>
                                            <div class="text-center" style="width:40%">
                                                <div class="box-number counter-anim mt-0"><?= $uoctinhKhachDen; ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="rankings">
                            <div class="card mb-1">
                                <div class="card-body p-0">
                                    <div class="ranking-header">
                                        <div class="d-flex">
                                            <div class="col-2">#</div>
                                            <div class="col-5">Tên nhân viên</div>
                                            <div class="col-5 text-center">Lịch hẹn mới</div>
                                        </div>
                                    </div>
                                    <div class="ranking-body">
                                        <?php
                                        $i = 1;
                                        $bg_color = '';
                                        $txt_color = '';
                                        arsort($res);
                                        foreach ($res as $key => $value) {
                                            if ($i == 1) :
                                                $bg_color = 'bg-amber';
                                                $txt_color = 'text-dark';
                                            elseif ($i == 2) :
                                                $bg_color = 'bg-amber bg-lighten-2';
                                                $txt_color = 'text-dark';
                                            elseif ($i == 3) :
                                                $bg_color = 'bg-yellow bg-lighten-2';
                                                $txt_color = 'text-dark';
                                            else :
                                                $bg_color = '';
                                                $txt_color = '';
                                            endif;
                                            ?>
                                            <div class="d-flex <?= $bg_color ?> <?= $txt_color ?>">
                                                <div class="col-2"><span class="font-16"><?= $i ?></span></div>
                                                <div class="col-5"><span class="font-16"><?= $key ?></span></div>
                                                <div class="col-5 text-center"><span class="font-16"><?= $value ?></span></div>
                                            </div>
                                            <?php $i++;
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div id="product" style="min-height: 250px"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 col-12">
                <div id="group-card" class="hk-row mb-1 justify-content-center">
                </div>
            </div>
            <div class="col-xl-3 col-md-6 col-12">
                <div id="time-line">
                    <div class="card">
                        <div class="card-body">
                            <div class="user-activity user-activity-sm">
                                <?php
                                foreach ($dataTimeline as $item) {
                                    if ($item != null) {
                                        ?>
                                        <div class="media">
                                            <div class="media-img-wrap">
                                                <div class="avatar avatar-sm">
                                                <span class="avatar-text avatar-text-info rounded-circle">
                                                    <span class="initial-wrap">
                                                        <?php
                                                        $icon = '';
                                                        if ($item->action != null) {
                                                            if (is_array($item->action)) {
                                                                if (($item->action[0] == 1 || $item->action[0] == 2)) { // thao tac them - tao moi
                                                                    $icon = '<span class="fa fa-user-plus list-timeline-icon"></span>';
                                                                    if ($item->action[1] == 6 || $item->action[1] == 10) {
                                                                        $icon = '<span class="fa fa-calendar-plus-o list-timeline-icon"></span>';
                                                                    } elseif ($item->action[1] == 9) {
                                                                        $icon = '<span class="fa fa-building-o list-timeline-icon"></span>';
                                                                    } elseif ($item->action[1] == 11) {
                                                                        $icon = '<span class="fa fa-credit-card list-timeline-icon"></span>';
                                                                    }
                                                                } elseif ($item->action[0] == 3) { // thao tac cap nhat
                                                                    $icon = '<span class="fa fa-refresh list-timeline-icon"></span>';
                                                                }
                                                            } elseif ($item->action == 1 || $item->action == 2) {
                                                                $icon = '<span class="fa fa-user-plus list-timeline-icon"></span>';
                                                            } elseif ($item->action == 3) {
                                                                $icon = '<span class="fa fa-refresh list-timeline-icon"></span>';
                                                            } elseif ($item->action == 4) {
                                                                $icon = '<span class="fa fa-times list-timeline-icon"></span>';
                                                            }
                                                            echo $icon;
                                                        } ?>
                                                    </span>
                                                </span>
                                                </div>
                                            </div>
                                            <div class="media-body">
                                                <div>
                                                    <span class="d-block" style="margin-bottom:5px;">
                                                        <span class="font-weight-500 text-dark text-capitalize"><?= $item->nameUserHasOne == null ? "Không tồn tại" : $item->nameUserHasOne->fullname ?></span>
                                                        <span class="">vừa</span>
                                                        <span class="font-weight-500 text-dark">
                                                            <?php
                                                            if ($item->action != null) {
                                                                $str = '';
                                                                if (is_array($item->action)) {
                                                                    foreach ($item->action as $action) {
                                                                        $str .= UserTimelineModel::LIST[$action] . ' ';
                                                                    }
                                                                } else {
                                                                    $str .= UserTimelineModel::LIST[$item->action] . ' ';
                                                                }
                                                                echo $str;
                                                            } ?>
                                                        </span>
                                                        <span class="">khách hàng</span>
                                                        <span class="font-weight-500 text-dark text-capitalize">
                                                            <?= $item->nameCustomerHasOne == null ? "Không tồn tại" : $item->nameCustomerHasOne->name; ?>
                                                        </span>
                                                    </span>
                                                    <span class="d-block font-13"><?php echo MyHelper::time_elapsed_string('@' . $item->created_at); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
$urlGetCardGroupApmt = \yii\helpers\Url::toRoute('get-data-card-group-apmt');
$urlGetTotalApmtMonth = \yii\helpers\Url::toRoute('get-data-total-apmt-month');
$urlProductToday = \yii\helpers\Url::toRoute('get-data-product-today');
$script = <<< JS
var d = new Date(),
    day = d.getDate(),
    month = d.getMonth(),
    year = d.getFullYear(),
    last_DoM = new Date(year, month + 1, 0).getDate();
var startDateReport = '1-' + (month + 1) + '-' + year,
    endDateReport = day + '-' + (month +1) + '-'+ year,
    lastDateReport = last_DoM + '-' + (month +1) + '-' + year;
var csrf = $('meta[name=csrf-token]').attr('content') || null;

/*Counter Animation*/
var counterAnim = $('.counter-anim');
if( counterAnim.length > 0 ){
    counterAnim.counterUp({ delay: 10,
    time: 1000});
}

$(document).ajaxStart(function () {    
    $('#product').myLoading({msg: 'Đang tải dữ liệu...', size: 'sm'});
}).ajaxStop(function () {    
    $('#product').myUnloading();
});

$.post('$urlProductToday', { _csrf: csrf, startDateReport: startDateReport, endDateReport: endDateReport }, function(data) {
    $('#product').html(data);
});

$.post('$urlGetCardGroupApmt', { _csrf: csrf, startDateReport: startDateReport, endDateReport: endDateReport, lastDateReport: lastDateReport }, function(data) {
    $('#group-card').html(data);
});

JS;

$this->registerJsFile('/vendors/js/charts/echarts/echarts.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('/vendors/js/waypoints/lib/jquery.waypoints.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('/vendors/js/jquery.counterup/jquery.counterup.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJS($script, \yii\web\View::POS_END);
?>