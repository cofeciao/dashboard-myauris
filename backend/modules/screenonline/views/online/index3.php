<?php
$this->registerCss('
    .content-header{display:none}
    .h-10{height:10%!important}
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
    .text-yellow{color:#ff0!important}
    .bg-light{background-color:#f7f7f7!important}
    .bg-pink,.badge-pink{background-color:#E91E63!important}
    .bg-blue,.badge-blue{background-color:#188ae2!important}
    .bg-purple,.badge-purple{background-color:#6f42c1!important}
    .bg-green,.badge-green{background-color:#10c469!important}
    .large{margin-top:-10px;font-size:52px}
    .small{position:absolute;bottom:10px;font-size:24px}
    .sticker{display:block;position:absolute;left:0;bottom:0;padding:5px 15px;background:#f90;border-radius:0 10px;color:#fff}
    .card{position:relative}
    .card.dark{background-color:#282e38}
    .card .heading-elements{position:absolute;top:0;right:0;background:#fff;z-index:999}
    .dark .card-box{background-color:#323a46;padding:.3rem;-webkit-box-shadow:0 .75rem 6rem rgba(56,65,74,.03);box-shadow:0 .75rem 6rem rgba(56,65,74,.03);border-radius:.25rem;border-color:#282e38!important}
    .header-title{font-size:1.1rem;font-weight:600;color:#f7f7f7;margin-bottom:5px}
    .card .col-left .header-title{font-size:1.1rem}
    .card .col-left .row2 .header-title{font-size:1.6rem}
    .sms-numb,.cmt-numb,.int-numb,.team-numb,.cs1-numb{position:relative;min-width:130px;height:130px;margin:0 auto;display:flex;justify-content:center;align-items:center;font-size:2rem;font-weight:600;color:#f7f7f7;border:10px solid #fff;border-radius:100%}
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
    .sticker.bg-light select{background:#f7f7f7;border:none;outline:none}
    .list{margin:0;padding:0;list-style:none}
    .list-timeline{position:relative;padding-top:20px}
    .list > li{position:relative}
    .list-timeline > li{margin-bottom:10px}
    .list-timeline > li:last-child{margin-bottom:0}
    .list-timeline .list-timeline-time{margin:0 -20px;padding:10px 20px 10px 40px;min-height:40px;text-align:right;color:#fafafa;font-size:14px;font-style:italic;background-color:#f9f9f9;border-radius:2px}
    .list-timeline .list-timeline-icon{position:absolute;top:5px;left:10px;width:30px;height:30px;line-height:30px;color:#fff;text-align:center;border-radius:50%}
    .list-timeline .list-timeline-content{padding:10px 10px 1px}
    .list-timeline .list-timeline-content a{color:#2dcee3;font-size:16px}
    .row.bot .col-left .row1 .slb-time{right:0;bottom:0;padding:5px 10px;background:#525861;border-radius:4px 0 10px 4px}
    .row.bot .col-left .row1 .slb-time #time{color: #fff;border:none;background:none;outline:none}
    @media screen and (min-width: 576px) {
        .col-sm-ct{flex:0 0 20%;max-width:20%;}
    }
    @media screen and (min-width: 768px) {
        .list-timeline:before{position:absolute;top:0;left:120px;bottom:0;display:block;width:4px;content:"";background-color:#282e38;z-index:1}
        .list-timeline > li{min-height:40px;z-index:2}
        .list-timeline .list-timeline-time{position:absolute;top:0;left:0;margin:0;padding-right:0;padding-left:0;width:90px;background-color:transparent}
        .list-timeline .list-timeline-icon{top:3px;left:105px;width:34px;height:34px;line-height:34px;z-index:2!important}
        .list-timeline .list-timeline-content{padding-left:160px}
    }
    @media screen and (min-width: 1400px) {
        /*.sms-numb, .cmt-numb, .int-numb, .team-numb, .cs1-numb {min-width:65px;height:65px;font-size:2rem}*/
    }
    @media screen and (max-width: 1600px) {
        
    }
');
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
                <div class="row top m-0 w-100 h-50 p-0">
                    <div class="row1 w-100 d-flex clearfix">
                        <div class="col-sm-6 col-12 p-0">
                            <div class="card-box d-flex flex-wrap h-100 border">
                                <h4 class="header-title w-100 h-20 text-center mb-0">Thời gian đặt hẹn đến quyết định
                                    làm</h4>
                                <div class="d-flex justify-content-center align-items-center w-100 h-75">
                                    <div class="sms-numb flex-wrap">
                                        <span class="large w-100 text-center">58</span>
                                        <span class="small">ngày</span>
                                    </div>
                                </div>
                                <span class="sticker bg-pink"><?= date('Y') ?></span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12 p-0">
                            <div class="card-box d-flex flex-wrap h-100 border">
                                <h4 class="header-title w-100 h-20 text-center mb-0">
                                    Đánh giá hài lòng khác hàng thực tế<br/>
                                    <em class="font-weight-normal">(1 khách hàng)</em>
                                </h4>
                                <div class="d-flex justify-content-center align-items-center w-100 h-75">
                                    <div class="int-numb flex-wrap">
                                        <span class="large w-100 text-center">5.74</span>
                                        <span class="small text-yellow"><i class="fa fa-star"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row2 w-100 d-flex clearfix">
                        <div class="col-sm-6 col-12 p-0">
                            <div class="card-box d-flex flex-wrap h-100 border">
                                <h4 class="header-title w-100 h-20 text-center mb-0">Trung bình thời gian làm thực tế
                                    của một khách hàng</h4>
                                <div class="d-flex justify-content-center align-items-center w-100 h-75">
                                    <div class="team-numb flex-wrap">
                                        <span class="large w-100 text-center">24</span>
                                        <span class="small">giờ</span>
                                    </div>
                                </div>
                                <span class="sticker bg-green">Hệ thống</span>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12 p-0">
                            <div class="card-box d-flex flex-wrap h-100 border">
                                <h4 class="header-title w-100 h-20 text-center mb-0">Trung bình số lần điều trị trên
                                    khách</h4>
                                <div class="d-flex justify-content-center align-items-center w-100 h-75">
                                    <div class="team-numb flex-wrap">
                                        <span class="large w-100 text-center">2,7</span>
                                        <span class="small">lần</span>
                                    </div>
                                </div>
                                <span class="sticker bg-light">
                                    <select name="" id="">
                                        <option value="2019">2019</option>
                                    </select>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row bot m-0 w-100 h-50 p-0">
                    <div class="col-left col-sm-6 col-12 p-0">
                        <div class="row1 w-100 h-15 d-flex clearfix">
                            <div class="card-box border w-100 position-relative">
                                <div class="header-title text-center font-weight-normal w-100 h-100 m-0"
                                     style="font-size: 20px">Đánh giá thăm khám
                                </div>
                                <div class="slb-time position-absolute" style="right:0;bottom:0;">
                                    <select name="time" id="time">
                                        <option value="0719">Tháng 7, 2019</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row2 w-100 h-85 d-flex clearfix">
                                <div class="col-sm-6 col-12 p-0">
                                    <div class="card-box border text-white w-100 h-100 px-1">
                                        <div class="text-center w-100" style="font-size:24px;margin:10px 0 20px">Sales</div>
                                        <div class="list-person">
                                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                <div class="d-flex justify-content-between" style="font-size:16px">
                                                    <div class="name"><?= $i ?>. B. Vũ</div>
                                                    <div class="rate text-yellow">* 2,7</div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12 p-0">
                                    <div class="card-box border text-white w-100 h-100 px-1">
                                        <div class="text-center w-100" style="font-size:24px;margin:10px 0 20px">Bác sĩ
                                        </div>
                                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                                            <div class="d-flex justify-content-between" style="font-size:16px">
                                                <div class="name"><?= $i ?>. B. Vũ</div>
                                                <div class="rate text-yellow">* 2,7</div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="col-right col-sm-6 col-12 p-0">
                        <div class="row1 w-100 h-15 d-flex clearfix">
                            <div class="card-box border w-100">
                                <div class="header-title w-100 h-100 m-0 d-flex justify-content-center align-items-center font-weight-normal"
                                     style="font-size: 18px;">Đánh giá hài lòng trung bình
                                </div>
                            </div>
                        </div>
                        <div class="row2 w-100 h-85 d-flex clearfix">
                                <div class="col-sm-6 col-12 p-0">
                                    <div class="card-box border text-white w-100 h-100 px-1">
                                        <div class="text-center w-100" style="font-size:24px;margin:10px 0 20px">Sales</div>
                                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                                            <div class="d-flex justify-content-between" style="font-size:16px">
                                                <div class="name"><?= $i ?>. B. Vũ</div>
                                                <div class="rate text-yellow">* 2,7</div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-12 p-0">
                                    <div class="card-box border text-white w-100 h-100 px-1">
                                        <div class="text-center w-100" style="font-size:24px;margin:10px 0 20px">Bác sĩ
                                        </div>
                                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                                            <div class="d-flex justify-content-between" style="font-size:16px">
                                                <div class="name"><?= $i ?>. B. Vũ</div>
                                                <div class="rate text-yellow">* 2,7</div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

            <div class="col-right col-sm-6 col-12 h-100">
                <div class="w-100 p-3 text-center text-white" style="font-size:36px;">Hệ thống kiểm soát tự động</div>
                <div class="card-box w-100 py-0 text-light">
                    <div id="timeline" class="timeline-wrapper h-90">
                        <ul class="list list-timeline h-100">
                            <?php //for ($i = 1; $i <= 15; $i++) {?>
                            <li>
                                <div class="list-timeline-time" data-time="1564018249">1 phút trước</div>
                                <i class="fa fa-refresh list-timeline-icon bg-red"></i>
                                <div class="list-timeline-content text-danger">
                                    Khách hàng Đào Văn Mong (0123456789) vừa đánh giá 1 sao
                                </div>
                            </li>
                            <li>
                                <div class="list-timeline-time" data-time="1564018249">3 phút trước</div>
                                <i class="fa fa-refresh list-timeline-icon bg-red"></i>
                                <div class="list-timeline-content text-danger">
                                    Direct Sale Đào Thị Tú vừa chốt fail liên tục 2 khách
                                </div>
                            </li>
                            <li>
                                <div class="list-timeline-time" data-time="1564018249">5 phút trước</div>
                                <i class="fa fa-refresh list-timeline-icon bg-primary"></i>
                                <div class="list-timeline-content">
                                    Direct Sale <a href="#">Đặng Tình tứ</a> vừa chốt thành công liên tục <a href="#"> 3 khách </a>
                                </div>
                            </li>
                            <li>
                                <div class="list-timeline-time" data-time="1564018249">7 phút trước</div>
                                <i class="fa fa-refresh list-timeline-icon bg-red"></i>
                                <div class="list-timeline-content text-danger">
                                    Online Đào Văn Mong có tỷ lệ lịch trên tương tác dưới 10% ngày 23-07-2019
                                </div>
                            </li>
                            <li>
                                <div class="list-timeline-time" data-time="1564018249">9 phút trước</div>
                                <i class="fa fa-refresh list-timeline-icon bg-red"></i>
                                <div class="list-timeline-content text-danger">
                                    Khách hàng Đào Văn Mong (0123456789) vừa đánh giá 1 sao
                                </div>
                            </li>
                            <li>
                                <div class="list-timeline-time" data-time="1564018249">12 phút trước</div>
                                <i class="fa fa-refresh list-timeline-icon bg-red"></i>
                                <div class="list-timeline-content text-danger">
                                    Direct Sale Đào Thị Tú vừa chốt fail liên tục 2 khách
                                </div>
                            </li>
                            <li>
                                <div class="list-timeline-time" data-time="1564018249">15 phút trước</div>
                                <i class="fa fa-refresh list-timeline-icon bg-primary"></i>
                                <div class="list-timeline-content">
                                    Direct Sale <a href="#">Đặng Tình tứ</a> vừa chốt thành công liên tục <a href="#"> 3 khách </a>
                                </div>
                            </li>
                            <li>
                                <div class="list-timeline-time" data-time="1564018249">17 phút trước</div>
                                <i class="fa fa-refresh list-timeline-icon bg-red"></i>
                                <div class="list-timeline-content text-danger">
                                    Online Đào Văn Mong có tỷ lệ lịch trên tương tác dưới 10% ngày 23-07-2019
                                </div>
                            </li>
                            <li>
                                <div class="list-timeline-time" data-time="1564018249">19 phút trước</div>
                                <i class="fa fa-refresh list-timeline-icon bg-red"></i>
                                <div class="list-timeline-content text-danger">
                                    Khách hàng Đào Văn Mong (0123456789) vừa đánh giá 1 sao
                                </div>
                            </li>
                            <li>
                                <div class="list-timeline-time" data-time="1564018249">21 phút trước</div>
                                <i class="fa fa-refresh list-timeline-icon bg-red"></i>
                                <div class="list-timeline-content text-danger">
                                    Direct Sale Đào Thị Tú vừa chốt fail liên tục 2 khách
                                </div>
                            </li>
                            <li>
                                <div class="list-timeline-time" data-time="1564018249">23 phút trước</div>
                                <i class="fa fa-refresh list-timeline-icon bg-primary"></i>
                                <div class="list-timeline-content">
                                    Direct Sale <a href="#">Đặng Tình tứ</a> vừa chốt thành công liên tục <a href="#"> 3 khách </a>
                                </div>
                            </li>
                            <li>
                                <div class="list-timeline-time" data-time="1564018249">25 phút trước</div>
                                <i class="fa fa-refresh list-timeline-icon bg-red"></i>
                                <div class="list-timeline-content text-danger">
                                    Online Đào Văn Mong có tỷ lệ lịch trên tương tác dưới 10% ngày 23-07-2019
                                </div>
                            </li>
                            <?php //}?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
