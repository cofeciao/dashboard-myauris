<?php

$this->registerCss('
    .content-header{display:none}
    .h-15{height:15%!important}
    .h-20{height:20%!important}
    .h-30{height:30%!important}
    .h-40{height:40%!important}
    .h-60{height:60%!important}
    .h-70{height:70%!important}
    .h-80{height:80%!important}
    .h-85{height:85%!important}
    .h-90{height:90%!important}
    .text-light{color:#f7f7f7!important}
    .text-green{color:#10c469!important}
    .text-blue{color:#188ae2!important}
    .text-pink{color:#f05050!important}
    .text-purple{color:#6f42c1!important}
    .badge-pink{background-color:#E91E63!important}
    .bg-blue,.badge-blue{background-color:#188ae2!important}
    .bg-purple,.badge-purple{background-color:#6f42c1!important}
    .bg-green,.badge-green{background-color:#10c469!important}
    .large{margin-top:-10px}
    .small{position:absolute;bottom:10px;font-size:11px;color:#10c469}
    .card{position:relative}
    .card.dark{background-color:#282e38}
    .card .heading-elements{position:absolute;top:0;right:0;background:#fff;z-index:999}
    .dark .card-box{position:relative;background-color:#323a46;padding:.3rem;-webkit-box-shadow:0 .75rem 6rem rgba(56,65,74,.03);box-shadow:0 .75rem 6rem rgba(56,65,74,.03);border-radius:.25rem;border-color:#282e38!important}
    .header-title{font-size:1.1rem;font-weight:600;color:#f7f7f7;margin-bottom:5px}
    .card .col-left .header-title{font-size:1.1rem}
    .sms-numb,.cmt-numb,.int-numb,.team-numb,.cs1-numb{min-width:60px;height:60px;margin:0 auto;display:flex;justify-content:center;align-items:center;font-size:2rem;font-weight:600;color:#f7f7f7;border:5px solid #fff;border-radius:50px}
    .card .col-right .sms-numb,
    .card .col-right .cmt-numb,
    .card .col-right .int-numb,
    .card .col-right .team-numb,
    .card .col-right .cs1-numb {min-width:60px;height:60px}
    .sms-numb{border-color:#f9b9b9;border-left-color:#f05050;border-top-color:#f05050}
    .cmt-numb{border-color:#2DCEE3;border-left-color:#188ae2;border-top-color:#188ae2}
    .int-numb{border-color:#fee5b9;border-left-color:#ffbd4a;border-top-color:#ffbd4a}
    .team-numb{border-color:#91ffc8;border-left-color:#10c469;border-top-color:#10c469}
    .cs1-numb{border-color:#d0b7ff;border-left-color:#6f42c1;border-top-color:#6f42c1}
    .col-left .row.bot .col-sm-1 .card-box{justify-content:center;align-items:center;color:#fff;font-weight:700;font-size:1.5rem}
    .col-left .row.bot .col-sm-1 .card-box.bg-blue{background-color:#4283f2}
    .col-left .row.bot .number{position:absolute;left:1px;bottom:0;border-radius:0 10px 0 4px;padding:3px 8px;background-color:#4285f4;color:#fff;font-weight:600;font-size:1.2rem}
    .col-left .row.bot .xclass{display:inline-block;padding:5px;background:#4284f3;color:#fff;border-radius:4px;font-weight:700}
    .col-left .row.bot .xclass span{color:#00ff7e}
    .col-right .badge {font-size:11px}
    .col-right .mid .row1 .card-box .header-title,
    .col-right .bot .row1 .card-box .header-title{font-size:2rem;line-height:2;margin-bottom:0}
    .col-right .mid .row2 .card-box .header-title,
    .col-right .bot .row2 .card-box .header-title{font-size:1.5rem}
    @media screen and (min-width: 576px) {
        .col-sm-ct{flex:0 0 20%;max-width:20%;}
    }
    @media screen and (min-width: 768px) {
    
    }
    @media screen and (min-width: 1400px) {
        .sms-numb, .cmt-numb, .int-numb, .team-numb, .cs1-numb {min-width:65px;height:65px;font-size:1.6rem}
    }
    @media screen and (max-width: 1600px) {
    
    }
');
$this->title = 'Screen Clinic';
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
            <div class="col-left col-sm-6 col-12 d-flex flex-wrap h-100">
                <div class="row top m-0 w-100 h-60 pl-1">
                    <div class="row1 w-100 d-flex clearfix">
                        <div class="col-sm-3 col-12 p-0">
                            <div class="card-box d-flex flex-wrap h-100 border">
                                <h4 class="header-title w-100 h-30 text-center mb-0">Tổng lịch hẹn
                                    <br/>tháng <?= date('m'); ?>
                                </h4>
                                <div class="d-flex justify-content-center align-items-center w-100 h-70">
                                    <span class="team-numb"><?= $lichHenTotalThang; ?></span>
                                </div>
                            </div>
                        </div>
                        <?php
                        foreach ($dataCot1Trai as $key => $value) { ?>
                            <div class="col-sm-3 col-12 p-0">
                                <div class="card-box d-flex flex-wrap h-100 border">
                                    <h4 class="header-title w-100 h-30 text-center mb-0">Lịch hẹn
                                        <br/>CS.0<?= $value['name']; ?>
                                        tháng <?= date('m'); ?>
                                    </h4>
                                    <div class="d-flex justify-content-center align-items-center w-100 h-70">
                                        <span class="team-numb"><?= $value['lichHenTheoThang']; ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="row2 w-100 d-flex clearfix">
                        <div class="col-sm-ct col-12 p-0">
                            <div class="card-box d-flex flex-wrap h-100 border">
                                <h4 class="header-title w-100 h-50 text-center mb-0">Lịch hẹn còn lại
                                    <br/>T.<?= date('m'); ?></h4>
                                <div class="d-flex justify-content-center align-items-end w-100 h-50">
                                    <span class="sms-numb"><?= $lichhenConLaiTrongThang; ?></span>
                                </div>
                            </div>
                        </div>
                        <?php
                        foreach ($dataCot1TraiDong2 as $key => $item) {
                            ?>
                            <div class="col-sm-ct col-12 p-0">
                                <div class="card-box d-flex flex-wrap h-100 border">
                                    <h4 class="header-title w-100 h-50 text-center mb-0">Lịch hẹn còn lại
                                        <br/>CS.0<?= $key; ?></h4>
                                    <div class="d-flex justify-content-center align-items-end w-100 h-50">
                                        <span class="sms-numb"><?= $item['lichhenConLaiTrongThang']; ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="col-sm-ct col-12 p-0">
                            <div class="card-box d-flex flex-wrap h-100 border">
                                <h4 class="header-title w-100 h-50 text-center mb-0">Dự trù lịch hẹn tổng
                                    <br/>T.<?= date('m'); ?></h4>
                                <div class="d-flex justify-content-center align-items-end w-100 h-50">
                                    <span class="sms-numb"><?= $lichHenDutruTrongThang + $lichHenTotalThang; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row3 w-100 d-flex clearfix">
                        <div class="col-sm-ct col-12 p-0">
                            <div class="card-box d-flex flex-wrap h-100 border">
                                <h4 class="header-title w-100 h-25 text-center mb-0">Tổng khách online đến
                                    T.<?= date('m'); ?>
                                </h4>
                                <div class="d-flex justify-content-center align-items-end w-100 h-75">
                                    <div class="team-numb flex-wrap">
                                        <span class="large w-100 text-center"><?= $khachDenTotal; ?></span>
                                        <span class="small"><?= $phanTramKhachDenSoLichHen; ?>%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php foreach ($khachdenTheoThang as $key => $item) { ?>
                            <div class="col-sm-ct col-12 p-0">
                                <div class="card-box d-flex flex-wrap h-100 border">
                                    <h4 class="header-title w-100 h-25 text-center mb-0">Khách online đến
                                        CS.0<?= $key; ?>
                                    </h4>
                                    <div class="d-flex justify-content-center align-items-end w-100 h-75">
                                        <div class="team-numb flex-wrap">
                                            <span class="large w-100 text-center"><?= $item['khachdenTheoThang']; ?></span>
                                            <span class="small"><?= $item['phantram']; ?>%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-sm-ct col-12 p-0">
                            <div class="card-box d-flex flex-wrap h-100 border">
                                <h4 class="header-title w-100 h-25 text-center mb-0">Dự trù tổng khách online đến
                                    T.<?= date('m'); ?></h4>
                                <div class="d-flex justify-content-center align-items-end w-100 h-75">
                                    <div class="team-numb flex-wrap">
                                        <span class="large w-100 text-center"><?= $duTruKhachDenTrongThang; ?></span>
                                        <span class="small"><?= $duTruKhachDenTrongThangPhanTram; ?>%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row4 w-100 d-flex clearfix">
                        <div class="col-sm-ct col-12 p-0">
                            <div class="card-box d-flex flex-wrap h-100 border">
                                <h4 class="header-title w-100 h-25 text-center mb-0">Tổng khách vãng lai
                                    <br/>T.<?= date('m'); ?></h4>
                                <div class="d-flex justify-content-center align-items-end w-100 h-75">
                                <span class="cmt-numb">
                                    <?= $khacVangLaiResultTotal; ?>
                                </span>
                                </div>
                            </div>
                        </div>
                        <?php foreach ($khacVangLaiResult as $key => $value) { ?>
                            <div class="col-sm-ct col-12 p-0">
                                <div class="card-box d-flex flex-wrap h-100 border">
                                    <h4 class="header-title w-100 h-25 text-center mb-0">Khách vãng lai
                                        <br/>CS.0<?= $key; ?></h4>
                                    <div class="d-flex justify-content-center align-items-end w-100 h-75">
                                        <span class="cmt-numb"><?= $value['khachVangLaiTheoCoSo']; ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-sm-ct col-12 p-0">
                            <div class="card-box d-flex flex-wrap h-100 border">
                                <h4 class="header-title w-100 h-25 text-center mb-0">Dự trù khách vãng lai
                                    <br/>T.<?= date('m'); ?></h4>
                                <div class="d-flex justify-content-center align-items-end w-100 h-75">
                                <span class="cmt-numb">
                                    <?= $duTruKhachVangLai; ?>
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row5 w-100 d-flex clearfix">
                        <div class="col-sm-ct col-12 p-0">
                            <div class="card-box d-flex flex-wrap h-100 border">
                                <h4 class="header-title w-100 h-25 text-center mb-0">Khách chốt <br/>T.<?= date('m'); ?>
                                </h4>
                                <div class="d-flex justify-content-center align-items-end w-100 h-75">
                                    <div class="team-numb flex-wrap">
                                        <span class="large w-100 text-center"><?= $khachChotTrongThangResultTotal; ?></span>
                                        <?php
                                        $totalKhacDen = $khacVangLaiResultTotal + $khachDenTotal;
                                        $phanTram = $totalKhacDen == 0 ? 0 : round(($khachChotTrongThangResultTotal / $totalKhacDen) * 100, 2);
                                        ?>
                                        <span class="small"><?= $phanTram; ?>%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php foreach ($khachChotTrongThangResult as $key => $item) { ?>
                            <div class="col-sm-ct col-12 p-0">
                                <div class="card-box d-flex flex-wrap h-100 border">
                                    <h4 class="header-title w-100 h-25 text-center mb-0">Khách chốt
                                        <br/>CS.0<?= $key; ?>
                                    </h4>
                                    <div class="d-flex justify-content-center align-items-end w-100 h-75">
                                        <div class="team-numb flex-wrap">
                                            <span class="large w-100 text-center"><?= $item['khachChotTrongThang']; ?></span>
                                            <span class="small"><?= $item['phantram']; ?>%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-sm-ct col-12 p-0">
                            <div class="card-box d-flex flex-wrap h-100 border">
                                <h4 class="header-title w-100 h-25 text-center mb-0">Dự trù tổng khách chốt
                                    <br/>T.<?= date('m'); ?></h4>
                                <div class="d-flex justify-content-center align-items-end w-100 h-75">
                                    <div class="team-numb flex-wrap">
                                        <span class="large w-100 text-center"><?= $duTruKhachChotTrongThang; ?></span>
                                        <span class="small"><?= $duTruKhachChotTrongThangPhanTram; ?>%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row bot m-0 w-100 h-40 pl-1">
                    <div class="row1 w-100 h-50 d-flex clearfix">
                        <div class="col-sm-1 p-0">
                            <div class="card-box bg-blue d-flex flex-wrap h-100 border">
                                <div class="title">
                                    Ngày
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-11 p-0">
                            <div class="row d-flex flex-wrap m-0 w-100 h-100">
                                <?php
                                $i = 1;
                                foreach ($khachTuVanDirectSaleNgayResultFinal as $key => $item) {
                                    ?>
                                    <div class="col-sm-ct h-50 p-0">
                                        <div class="card-box border h-100 text-center">
                                            <span class="number bg-blue"><?= $i ?></span>
                                            <h4 class="header-title my-1"><?= $item['name']; ?></h4>
                                            <div class="xclass bg-blue"><?= $item['khachDirectSakeChot'] . '/' . $item['khachDirectSakeTuVan'] . ' - ' . '<span class="">' . $item['phantram'] . '%</span>'; ?></div>
                                        </div>
                                    </div>
                                    <?php
                                    $i++;
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="row1 w-100 h-50 d-flex clearfix">
                        <div class="col-sm-1 p-0">
                            <div class="card-box bg-red d-flex flex-wrap h-100 border">
                                <div class="title">
                                    Tháng
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-11 p-0">
                            <div class="row d-flex flex-wrap m-0 w-100 h-100">
                                <?php
                                $i = 1;
                                foreach ($khachTuVanDirectSaleThangResultFinal as $key => $item) {
                                    ?>
                                    <div class="col-sm-ct h-50 p-0">
                                        <div class="card-box border h-100 text-center">
                                            <span class="number bg-red"><?= $i ?></span>
                                            <h4 class="header-title my-1"><?= $item['name']; ?></h4>
                                            <div class="xclass bg-red"><?= $item['khachDirectSakeChot'] . '/' . $item['khachDirectSakeTuVan'] . ' - ' . '<span class="">' . $item['phantram'] . '%</span>'; ?></div>
                                        </div>
                                    </div>
                                    <?php
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-right col-sm-6 col-12 d-flex flex-wrap h-100">
                <div class="row top m-0 w-100 h-50">
                    <?php
                    $color = ['', 'badge-green', 'badge-blue', 'badge-purple'];
                    $num = ['', 'team-numb', 'cmt-numb', 'cs1-numb'];
                    $text = ['', 'text-green', 'text-blue', 'text-purple'];
                    $count = count($cot1benphai);
                    for ($i = 1;
                         $i <= $count;
                         $i++) {
                        ?>
                        <div class="row1 w-100 d-flex clearfix">
                            <div class="col-sm-3 col-12 p-0">
                                <div class="card-box h-100 border">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge <?= $color[$i]; ?> badge-pill float-left font-weight-bold">Cơ sở <?= $i; ?></span>
                                    </div>
                                    <div class="text-center h-80">
                                        <h4 class="header-title w-100 h-20 text-center">Lịch hẹn hôm nay</h4>
                                        <div class="d-flex w-100 h-80 justify-content-center align-items-center">
                                            <span class="<?= $num[$i]; ?>"><?= $cot1benphai[$i]['lichhen']; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 col-12 p-0">
                                <div class="card-box h-100 border">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge <?= $color[$i]; ?> badge-pill float-left font-weight-bold">Cơ sở <?= $i; ?></span>
                                    </div>
                                    <div class="text-center h-80">
                                        <h4 class="header-title w-100 h-20 text-center">Khách đến thực tế</h4>
                                        <div class="d-flex w-100 h-80 justify-content-center align-items-center">
                                            <span class="<?= $num[$i]; ?>"><?= $cot1benphai[$i]['khachden']; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 col-12 p-0">
                                <div class="card-box h-100 border">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge <?= $color[$i]; ?> badge-pill float-left font-weight-bold">Cơ sở <?= $i; ?></span>
                                    </div>
                                    <div class="text-center h-80">
                                        <h4 class="header-title w-100 h-20 text-center">Khách chốt</h4>
                                        <div class="d-flex w-100 h-80 justify-content-center align-items-center">
                                            <span class="<?= $num[$i]; ?>"><?= $cot1benphai[$i]['khachchot']; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 col-12 p-0">
                                <div class="card-box h-100 border">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge <?= $color[$i]; ?> badge-pill float-left font-weight-bold">Cơ sở <?= $i; ?></span>
                                    </div>
                                    <div class="text-center h-80">
                                        <h4 class="header-title w-100 h-20 text-center">Doanh thu hôm nay</h4>
                                        <div class="d-flex w-100 h-80 justify-content-center align-items-center">
                                            <span class="my-1 font-weight-bold font-large-1 <?= $text[$i]; ?>"><?= number_format($cot1benphai[$i]['doanhthu'], 0, ',', '.'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="row4 w-100 d-flex clearfix">
                        <div class="col-sm-3 col-12 p-0">
                            <div class="card-box h-100 border">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge badge-pink badge-pill float-left font-weight-bold">Tổng</span>
                                </div>
                                <div class="text-center h-80">
                                    <h4 class="header-title w-100 h-20 text-center">Lịch hẹn hôm nay</h4>
                                    <div class="d-flex w-100 h-80 justify-content-center align-items-center">
                                        <span class="sms-numb"><?= $lichhenTotalNgay; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 col-12 p-0">
                            <div class="card-box h-100 border">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge badge-pink badge-pill float-left font-weight-bold">Tổng</span>
                                </div>
                                <div class="text-center h-80">
                                    <h4 class="header-title w-100 h-20 text-center">Khách đến thực tế</h4>
                                    <div class="d-flex w-100 h-80 justify-content-center align-items-center">
                                        <span class="sms-numb"><?= $khachdenTotalNgay; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 col-12 p-0">
                            <div class="card-box h-100 border">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge badge-pink badge-pill float-left font-weight-bold">Tổng</span>
                                </div>
                                <div class="text-center h-80">
                                    <h4 class="header-title w-100 h-20 text-center">Khách chốt</h4>
                                    <div class="d-flex w-100 h-80 justify-content-center align-items-center">
                                        <span class="sms-numb"><?= $khachChotTotalNgay; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 col-12 p-0">
                            <div class="card-box h-100 border">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge badge-pink badge-pill float-left font-weight-bold">Tổng</span>
                                </div>
                                <div class="text-center h-80">
                                    <h4 class="header-title w-100 h-20 text-center">Doanh thu hôm nay</h4>
                                    <div class="d-flex w-100 h-80 justify-content-center align-items-center">
                                        <span class="my-1 font-weight-bold font-large-1 text-pink"><?= number_format($doanhThuTotalNgay, 0, ',', '.'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mid m-0 w-100 h-25">
                    <div class="row1 w-100 h-25 d-flex clearfix">
                        <div class="card-box border w-100">
                            <div class="header-title text-center w-100 h-100">Doanh thu tháng <?= date('m'); ?></div>
                        </div>
                    </div>
                    <div class="row2 w-100 h-75 d-flex clearfix">
                        <?php
                        $num = ['', 'team-numb', 'cmt-numb', 'cs1-numb'];
                        foreach ($doanhThuTheoThang as $key => $item) {
                            ?>
                            <div class="col-sm-3 col-12 p-0">
                                <div class="card-box d-flex align-items-center border h-100">
                                    <div class="w-100">
                                        <h4 class="header-title w-100 text-center mb-0">Cơ sở <?= $key; ?></h4>
                                        <span class="<?= $num[$key]; ?>"><?= number_format($item['doanhthutheothang'], 0, ',', '.'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="col-sm-3 col-12 p-0">
                            <div class="card-box d-flex align-items-center border h-100">
                                <div class="w-100">
                                    <h4 class="header-title w-100 text-center mb-0">Tổng</h4>
                                    <span class="sms-numb"><?= number_format($doanhThuTotalThangTotal, 0, ',', '.'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row bot m-0 w-100 h-25">
                    <div class="row1 w-100 h-25 d-flex clearfix">
                        <div class="card-box border w-100">
                            <div class="header-title text-center w-100 h-100">Doanh thu theo dịch vụ
                                tháng <?= date('m'); ?></div>
                        </div>
                    </div>
                    <div class="row2 w-100 h-75 d-flex clearfix">
                        <?php
                        foreach ($doanhThuTheoDichVu as $key => $item) {
                            ?>
                            <div class="col-sm-ct col-12 p-0">
                                <div class="card-box d-flex align-items-center border h-100">
                                    <div class="w-100">
                                        <h4 class="header-title w-100 text-center mb-0"><?= $item['name']; ?></h4>
                                        <span class="sms-numb"><?= number_format($item['doanhthutheodichvu'], 0, ',', '.'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



