<?php

use backend\modules\user\models\UserTimelineModel;
use yii\widgets\Pjax;

$this->registerCss('
    .content-header{display:none}
    .h-15{height:15%!important}
    .h-20{height:20%!important}
    .h-30{height:30%!important}
    .h-70{height:70%!important}
    .h-80{height:80%!important}
    .h-85{height:85%!important}
    .text-light{color:#f7f7f7!important}
    .text-green{color:#10c469!important}
    .text-blue{color:#188ae2!important}
    .text-pink{color:#f05050!important}
    .text-purple{color:#6f42c1!important}
    .badge-pink{background-color:#E91E63!important}
    .bg-blue,.badge-blue{background-color:#188ae2!important}
    .bg-purple,.badge-purple{background-color:#6f42c1!important}
    .bg-green,.badge-green{background-color:#10c469!important}
    .card{position:relative}
    .card.dark{background-color:#282e38}
    .card .col-left .row1 [class*=col-sm-] {height: 33.33%}
    .card .col-left .row2 {display:block;}
    .card .heading-elements{position:absolute;top:0;right:0;background:#fff;z-index:999}
    .dark .card-box{background-color:#323a46;padding:.5rem;-webkit-box-shadow:0 .75rem 6rem rgba(56,65,74,.03);box-shadow:0 .75rem 6rem rgba(56,65,74,.03);border-radius:.25rem;border-color:#282e38!important}
    .card-box .title {font-size:1rem!important}
    .header-title{font-size:1.5rem;font-weight:600;color:#f7f7f7}
    .sms-numb,.cmt-numb,.int-numb,.team-numb,.cs1-numb{min-width:70px;height:70px;margin:0 auto;display:flex;justify-content:center;align-items:center;font-size:2.5rem;font-weight:600;color:#f7f7f7;border:5px solid #fff;border-radius:50px}
    .card .col-left .header-title{font-size:1.2rem}
    .card .col-right .sms-numb,
    .card .col-right .cmt-numb,
    .card .col-right .int-numb,
    .card .col-right .team-numb,
    .card .col-right .cs1-numb {min-width:65px;height:65px}
    .sms-numb{border-color:#f9b9b9;border-left-color:#f05050;border-top-color:#f05050}
    .cmt-numb{border-color:#2DCEE3;border-left-color:#188ae2;border-top-color:#188ae2}
    .int-numb{border-color:#fee5b9;border-left-color:#ffbd4a;border-top-color:#ffbd4a}
    .team-numb{border-color:#91ffc8;border-left-color:#10c469;border-top-color:#10c469}
    .cs1-numb{border-color:#d0b7ff;border-left-color:#6f42c1;border-top-color:#6f42c1}
    .widget-detail h2{font-size:2.5rem;color:#F7F7F7}
    .xtotal {margin-top:10px;color:#fff;font-weight:700;font-size:1.3rem;position:absolute;right:10px;bottom:10px}
    .list{margin:0;padding:0;list-style:none}
    .list-timeline{position:relative;padding:15px 0;max-height:525px;overflow-y:scroll;overflow-x:hidden}
    .list > li{position:relative}
    .list-timeline > li{margin-bottom:10px}
    .list-timeline > li:last-child{margin-bottom:0}
    .list-timeline .list-timeline-time{margin:0 -20px;padding:10px 20px 10px 40px;min-height:40px;text-align:right;color:#fafafa;font-size:12px;font-style:italic;background-color:#f9f9f9;border-radius:2px}
    .list-timeline .list-timeline-icon{position:absolute;top:5px;left:10px;width:30px;height:30px;line-height:30px;color:#fff;text-align:center;border-radius:50%}
    .list-timeline .list-timeline-content{padding:2px 10px 1px;line-height: 1.3}
    .list-timeline .list-timeline-content a{color:#2dcee3;font-size:14px}
    @media screen and (min-width: 768px) {
        .list-timeline:before{position:absolute;top:0;left:100px;bottom:0;display:block;width:4px;content:"";background-color:#282e38;z-index:1}
        .list-timeline > li{min-height:40px;z-index:2}
        .list-timeline .list-timeline-time{position:absolute;top:0;left:0;margin:0;padding-right:0;padding-left:0;width:80px;background-color:transparent}
        .list-timeline .list-timeline-icon{top:3px;left:85px;width:34px;height:34px;line-height:34px;z-index:2!important}
        .list-timeline .list-timeline-content{padding-left:135px}
    }
    @media only screen and (min-width: 1200px) and (max-width: 1399px) {
        .card-box .title {
            height: 50px;
        }
    }
    @media screen and (max-width: 1600px) {
        .list-timeline > li:last-child{display:none}
    }
    @media screen and (max-width: 1349px) {
        .sms-numb, .cmt-numb, .int-numb, .team-numb, .cs1-numb,
        .card .col-right .sms-numb, .card .col-right .cmt-numb, 
        .card .col-right .int-numb, .card .col-right .team-numb, 
        .card .col-right .cs1-numb {
            min-width: 50px;
            height: 50px;
            font-size: 1.8rem;
        }
        .card .col-left .header-title {
            font-size: .9rem;
            height: 35%!important;
        }
        .card .col-left .row2 {
            overflow-y: hidden;
        }
        .list-timeline:before {
            left: 70px;
        }
        .list-timeline .list-timeline-time {
            width: 45px;
        }
        .list-timeline .list-timeline-icon {
            left: 55px;
        }
        .list-timeline .list-timeline-content {
            padding-left: 95px;
        }
        .list-timeline .list-timeline-content a,
        .list-timeline .list-timeline-content{
            font-size: .9rem;
        }
        .col-right .header-title {
            font-size: 1.2rem;
            margin: .5rem 0!important
        }
        .col-right .card-box .progress {
            margin-top: 10px!important;
        }
        .col-right .xtotal, .col-right .xtotal .badge {
            font-size: 1.2rem!important;
        }
    }
');

$this->title = "Screen Online";
Pjax::begin(['id' => 'screen-online-ajax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']])
?>
    <div class="card m-0 p-0 card-fullscreen dark">
        <div class="heading-elements">
            <ul class="list-inline mb-0">
                <li><a data-action="expand" style="display:block;padding:.5rem .7rem"><i class="ft-minimize"></i></a>
                </li>
            </ul>
        </div>
        <div class="card-content h-100">
            <div class="row m-0 h-100">
                <div class="col-left col-sm-6 col-12 d-flex flex-wrap h-100 p-0">
                    <div class="row row1 m-0 w-100 h-50 p-1">
                        <div class="col-sm-2 col-12 p-0">
                            <div class="card-box d-flex flex-wrap h-100 border">
                                <h4 class="header-title w-100 h-30 text-center mb-0">Lịch hẹn còn lại
                                    T.<?= date('m'); ?></h4>
                                <div class="d-flex justify-content-center align-items-center w-100 h-70">
                                    <span class="sms-numb"><?= $totalCustomerTodayToLastMonth; ?></span>
                                </div>
                            </div>
                        </div>
                        <?php
                        foreach ($dataLichHenWithCoSoToLastMonth as $key => $item) {
                            ?>
                            <div class="col-sm-2 col-12 p-0">
                                <div class="card-box d-flex flex-wrap h-100 border">
                                    <h4 class="header-title w-100 h-30 text-center mb-0">Lịch hẹn còn lại
                                        CS<?= $key; ?></h4>
                                    <div class="d-flex justify-content-center align-items-center w-100 h-70">
                                        <span class="cmt-numb"><?= $item['lichhen']; ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>

                        <div class="col-sm-2 col-12 p-0">
                            <div class="card-box d-flex flex-wrap h-100 border">
                                <h4 class="header-title w-100 h-30 text-center mb-0">Dự trù lịch hẹn còn lại
                                    T.<?= date('m'); ?></h4>
                                <div class="d-flex justify-content-center align-items-center w-100 h-70">
                                    <span class="int-numb"><?= $lichHenDutru; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2 col-12 p-0">
                            <div class="card-box d-flex flex-wrap h-100 border">
                                <h4 class="header-title w-100 h-30 text-center mb-0">Dự trù khách đến còn lại
                                    T.<?= date('m'); ?></h4>
                                <div class="d-flex justify-content-center align-items-center w-100 h-70">
                                    <span class="int-numb"><?= $uoctinhKhachDen; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3 col-12 p-0">
                            <div class="card-box d-flex flex-wrap h-100 border">
                                <h4 class="header-title w-100 h-20 text-center mb-0">Tổng lịch mới
                                    T.<?= date('m'); ?></h4>
                                <div class="d-flex justify-content-center align-items-center w-100 h-80">
                                    <span class="team-numb"><?= $totalLichMoiToiHienTai; ?></span>
                                </div>
                            </div>
                        </div>

                        <?php
                        foreach ($dataLichMoiTheoCoSo as $key => $item) {
                            ?>
                            <div class="col-sm-3 col-12 p-0">
                                <div class="card-box d-flex flex-wrap h-100 border">
                                    <h4 class="header-title w-100 h-20 text-center mb-0">Lịch mới T.<?= date('m'); ?>
                                        CS<?= $key; ?></h4>
                                    <div class="d-flex justify-content-center align-items-center w-100 h-80">
                                        <span class="team-numb"><?= $item['lichmoi']; ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <?php
                        foreach ($sanPham as $key => $value) {
                            ?>
                            <div class="col-sm-2 col-12 p-0">
                                <div class="card-box d-flex flex-wrap h-100 border">
                                    <h4 class="header-title w-100 h-25 text-center mb-0"><?= $value['name']; ?></h4>
                                    <div class="d-flex justify-content-center align-items-center w-100 h-75">
                                        <span class="sms-numb"><?= $value['sl']; ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="row row2 m-0 w-100 h-50">
                        <div class="col-5 float-left p-0 w-100">
                            <div class="row m-0 w-100 h-50 px-1 pb-1">
                                <div class="card-box w-100 py-0 text-light">
                                    <div id="timeline" class="timeline-wrapper h-100">
                                        <ul class="list list-timeline h-100">
                                            <?php
                                            foreach ($dataTimeline as $item) {
                                                if ($item != null) {
                                                    ?>
                                                    <li>
                                                        <div class="list-timeline-time"
                                                             data-time="<?= $item->created_at ?>"><?php echo \common\helpers\MyHelper::time_elapsed_string('@' . $item->created_at); ?></div>
                                                        <?php
                                                        $icon = '';
                                                        if ($item->action != null) {
                                                            if (is_array($item->action)) {
                                                                if (($item->action[0] == 1 || $item->action[0] == 2)) { // thao tac them - tao moi
                                                                    $icon = '<i class="fa fa-user-plus list-timeline-icon bg-blue"></i>';
                                                                    if ($item->action[1] == 6 || $item->action[1] == 10) {
                                                                        $icon = '<i class="fa fa-calendar-plus-o list-timeline-icon bg-green"></i>';
                                                                    } elseif ($item->action[1] == 9) {
                                                                        $icon = '<i class="fa fa-building-o list-timeline-icon bg-green"></i>';
                                                                    } elseif ($item->action[1] == 11) {
                                                                        $icon = '<i class="fa fa-credit-card list-timeline-icon bg-green"></i>';
                                                                    }
                                                                } elseif ($item->action[0] == 3) { // thao tac cap nhat
                                                                    $icon = '<i class="fa fa-refresh list-timeline-icon bg-primary"></i>';
                                                                }
                                                            } elseif ($item->action == 1 || $item->action == 2) {
                                                                $icon = '<i class="fa fa-user-plus list-timeline-icon bg-blue"></i>';
                                                            } elseif ($item->action == 3) {
                                                                $icon = '<i class="fa fa-refresh list-timeline-icon bg-primary"></i>';
                                                            } elseif ($item->action == 4) {
                                                                $icon = '<i class="fa fa-times list-timeline-icon bg-danger"></i>';
                                                            }
                                                            echo $icon;
                                                        } ?>
                                                        <div class="list-timeline-content">

                                                            <a href="#"><?= $item->nameUserHasOne == null ? "Không tồn tại" : $item->nameUserHasOne->fullname ?></a>
                                                            vừa
                                                            <a href="#">
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
                                                            </a>
                                                            khách hàng
                                                            <a href="#"><?= $item->nameCustomerHasOne == null ? "Không tồn tại" : $item->nameCustomerHasOne->name; ?></a>
                                                        </div>
                                                    </li>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-7 float-left p-0 w-100">
                            <div class="row m-0 w-100 h-100 pl-0 pr-1 pb-1">
                                <?php
                                foreach ($res as $key => $value) {
                                    ?>
                                    <div class="col-sm-3 p-0">
                                        <div class="card-box text-center text-light border p-1">
                                            <h4 class="title font-weight-bold mb-2"><?= $key; ?></h4>
                                            <h3 class="numb badge badge-blue badge-pill font-weight-bold m-0 font-medium-5"><?= $value; ?></h3>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-right col-sm-6 col-12 d-flex flex-wrap h-100 p-0">
                    <?php
                    $color = ['', 'purple', 'blue', 'green'];
                    $css = ['', 'cs1-numb', 'cmt-numb', 'team-numb'];
                    //                    var_dump($coSo);die;
                    $totalKhachHenTheoThangIndex = 0;
                    for ($i = 1; $i <= count($coSo); $i++) {
                        $totalKhachHenTheoThangIndex = $totalKhachHenTheoThangIndex + $coSo[$i]['lichhentheothang']; ?>
                        <div class="row m-0 p-1 w-100 h-25">
                            <div class="col-sm-6 col-12 p-0">
                                <div class="card-box h-100 border">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge badge-<?= $color[$i]; ?> badge-pill float-left font-weight-bold">Cơ sở <?= $coSo[$i]['name']; ?></span>
                                    </div>
                                    <div class="text-center">
                                        <h4 class="header-title w-100 h-20 text-center my-1">Lịch hẹn hôm nay</h4>
                                        <div class="d-flex w-100 h-80 justify-content-center align-items-center">
                                            <span class="<?= $css[$i]; ?>"><?= $coSo[$i]['lichhen']; ?></span>
                                        </div>
                                        <p class="xtotal">
                                            Tổng lịch hẹn T.<?= date('m') ?>:
                                            <span class="badge badge-<?= $color[$i] ?> badge-pill font-weight-bold font-medium-5"><?= $coSo[$i]['lichhentheothang']; ?></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-12 p-0">
                                <div class="card-box h-100 border">
                                    <div class="d-flex justify-content-between align-items-center hidden">
                                        <span class="badge badge-<?= $color[$i]; ?> badge-pill float-left font-weight-bold">Cơ sở <?= $coSo[$i]['name']; ?></span>
                                    </div>
                                    <h4 class="header-title w-100 h-20 text-center my-1">Khách đến</h4>
                                    <div class="d-flex w-100 h-30 justify-content-center align-items-center">
                                        <div class="widget-box w-100">
                                            <div class="widget-detail text-right">
                                        <span class="badge badge-<?= $color[$i]; ?> badge-pill float-left font-weight-bold font-medium-1"
                                              style="margin-top:10px"><?= $coSo[$i]['phantram']; ?>% <i
                                                    class="ft-trending-up"></i></span>
                                                <h2 class="font-weight-bold m-0"><?= $coSo[$i]['khachden']; ?></h2>
                                            </div>
                                            <div class="progress progress-bar-alt-purple progress-sm mb-0"
                                                 style="margin-top:18px;">
                                                <div class="progress-bar bg-<?= $color[$i]; ?>" role="progressbar"
                                                     aria-valuenow="50"
                                                     aria-valuemin="0" aria-valuemax="100"
                                                     style="width: <?= $coSo[$i]['phantram']; ?>%;">
                                                    <span class="sr-only"><?= $coSo[$i]['phantram']; ?>%</span>
                                                </div>
                                            </div>
                                            <p class="xtotal"">
                                            Tổng khách đến T.<?= date('m') ?>:
                                            <span class="badge badge-pink badge-pill font-weight-bold font-medium-5"><?= $coSo[$i]['khachdentheothang']; ?></span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>

                    <div class="row m-0 p-1 w-100 h-25">
                        <div class="col-sm-6 col-12 p-0">
                            <div class="card-box h-100 border">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge badge-pink badge-pill float-left font-weight-bold">Total</span>
                                </div>
                                <div class="text-center">
                                    <h4 class="header-title w-100 h-20 text-center my-1">Lịch hẹn hôm nay</h4>
                                    <div class="d-flex w-100 h-80 justify-content-center align-items-center">
                                        <span class="sms-numb"><?= $lichHenTotal; ?></span>
                                    </div>
                                    <p class="xtotal">
                                        Tổng lịch hẹn T.<?= date('m') ?>:
                                        <span class="badge badge-pink badge-pill font-weight-bold font-medium-5"><?=$totalKhachHenTheoThangIndex; ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12 p-0">
                            <div class="card-box h-100 border">
                                <div class="d-flex justify-content-between align-items-center hidden">
                                    <span class="badge badge-pink badge-pill float-left font-weight-bold">Total</span>
                                </div>
                                <h4 class="header-title w-100 h-20 text-center my-1">Khách đến</h4>
                                <div class="d-flex w-100 h-30 justify-content-center align-items-center">
                                    <div class="widget-box w-100">
                                        <?php
                                        if ($lichHenTotal == 0) {
                                            $pt = 0;
                                        } else {
                                            $pt = round(($customerGotoAurisTotal / $lichHenTotal) * 100, 2);
                                        }
                                        $trend = '';
                                        if ($pt != '0' && $pt != '100') {
                                            $trend = 'ft-trending-up';
                                        }
                                        ?>
                                        <div class="widget-detail text-right">
                                        <span class="badge badge-pink badge-pill float-left font-weight-bold font-medium-1"
                                              style="margin-top:10px"><?= $pt; ?>% <i class="<?= $trend; ?>"></i></span>
                                            <h2 class="font-weight-bold m-0"><?= $customerGotoAurisTotal; ?></h2>
                                        </div>
                                        <div class="progress progress-bar-alt-pink progress-sm mb-0"
                                             style="margin-top:18px;">
                                            <div class="progress-bar bg-pink" role="progressbar"
                                                 aria-valuenow="<?= $pt; ?>"
                                                 aria-valuemin="0" aria-valuemax="100" style="width: <?= $pt; ?>%;">
                                                <span class="sr-only"><?= $pt; ?>%</span>
                                            </div>
                                        </div>
                                        <p class="xtotal"">
                                        Tổng khách đến T.<?= date('m') ?>:
                                        <span class="badge badge-pink badge-pill font-weight-bold font-medium-5"><?= $customerGotoAurisWithMonthWithCoSoTotal; ?></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
<?php
Pjax::end();
?>
<?php
$url = \yii\helpers\Url::toRoute('get-data');
$datHenRingtone = '../uploads/temp/audio/airport_bell.mp3';
$datHenDenRingtone = '../uploads/temp/audio/cheering.mp3';
$utlDatHen = UserTimelineModel::ACTION_DAT_HEN;
$utlDatHenDen = UserTimelineModel::ACTION_THAM_KHAM;
$this->registerCssFile('https://semantic-ui.com/dist/semantic.min.css', ['depends' => \yii\bootstrap\BootstrapAsset::class]);
$script = <<< JS
    $(document).ready(function() {
	    var currentUrl = $(location).attr('href');
	    if(window.EventSource) {
	        source = new EventSource("/events/online/listener");
	        source.addEventListener("message", function(event) {
	            console.log(event.data);
	            setTime();
            
                if(JSON.parse(event.data) !== false) {
	                // var notifSound = '//translate.google.com/translate_tts?ie=UTF-8&q=' + JSON.parse(event.data).notification + '&tl=vi&client=tw-ob';
	                if (JSON.parse(event.data).status === '$utlDatHen')
	                    notificationPush('$datHenRingtone');
	                else if (JSON.parse(event.data).status === '$utlDatHenDen')
	                    notificationPush('$datHenDenRingtone');
	                $('body').myLoading({msg: 'Đang tải dữ liệu...'});
	                $.pjax.reload({url:currentUrl, method: 'POST', container: '#screen-online-ajax'});
	                // source.close();
	            }
	        });
	   } else {
	       alert("Trình duyệt không thích hợp, vui lòng chọn trình duyệt khác");
	   }
	});

    var defauts =
        {
            translate: {
                'years': '% năm trước',
                'months':'% tháng trước',
                'days': '% ngày trước',
                'hours': '% giờ trước',
                'minutes': '% phút trước',
                'seconds': '% giây trước',
                'error': 'không xác định',
            }
        };

    if (typeof options == 'undefined') {
        options = defauts;
    }

    function dateDiff(date1, date2) {
        var diff = {}
        var tmp = date2 - date1;
        tmp = Math.floor(tmp / 1000);
        diff.sec = tmp % 60;
        tmp = Math.floor((tmp - diff.sec) / 60);
        diff.min = tmp % 60;
        tmp = Math.floor((tmp - diff.min) / 60);
        diff.hour = tmp % 24;
        tmp = Math.floor((tmp - diff.hour) / 24);
        diff.day = tmp;
        diff.year = date2.getFullYear() - date1.getFullYear();
        diff.month = (date2.getMonth() + 1) - (date1.getMonth() + 1);
        return diff;
    }
    
    function getFormattedDateAgo(date) {
        var diff = dateDiff(date, new Date());
        if (diff.year >= 1) {
            return options.translate['years'].replace('%', diff.year);
        } else if (diff.month >= 1) {
            return options.translate['months'].replace('%', diff.month);
        } else if (diff.day >= 1) {
            return options.translate['days'].replace('%', diff.day);
        } else if (diff.hour >= 1) {
            return options.translate['hours'].replace('%', diff.hour);
        } else if (diff.min >= 1) {
            return options.translate['minutes'].replace('%', diff.min);
        } else if (diff.sec >= 1) {
            return options.translate['seconds'].replace('%', diff.sec);
        } else{
            return options.translate['error'];
        }
    }

    function setTime() {
        $('.list-timeline li').each(function () {
            var createdTime = $(this).find('.list-timeline-time').attr('data-time');
            var time = new Date(createdTime * 1000);
            $(this).find('.list-timeline-time').text(getFormattedDateAgo(time));
        });
    }

	function notificationPush(url_ringtone, url_mess = null) {
	    var audioElement = document.createElement('audio');
	    if (!url_ringtone) return false;
	    audioElement.setAttribute('src', url_ringtone);       
	    // audioElement.addEventListener('ended', function() {
	    //     audioElement.remove();
	    //     if (url_mess != null) {
	    //         notificationPush(url_mess);
	    //     }
	    // }, false);
	    audioElement.play();
	}
JS;

$this->registerJs($script, \yii\web\View::POS_END);
$this->registerCss('
@media screen and (min-width: 576px) {
    .col-sm-ct{
        flex: 0 0 20%;
        max-width: 20%;
    }
}
');
