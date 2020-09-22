<?php

use yii\helpers\Url;
use backend\modules\user\models\User;

?>
    <!--stats-->
    <section id="dom">
        <div class="customer-status">
            <?php
            $user = new User();
            $roleUser = $user->getRoleName(Yii::$app->user->id);
            if (in_array($roleUser, [User::USER_NHANVIEN_ONLINE, User::USER_MANAGER_ONLINE, $roleUser == User::USER_DEVELOP])) {
                ?>
                <div class="alert alert-danger user-alert" role="alert"></div>
                <div class="alert alert-warning user-alert" role="alert"></div>
                <?php
            }
            ?>
            <div class="row height-100 count-customer load-on-ready"
                 url-load="<?php echo Url::toRoute(['load-data-count-customer']) ?>"></div>
            <div class="row">
                <div class="col-xl-8 row-left">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body dat-hen-chart" style="height: 390px;">
                                        <canvas id="dathen-chart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-12">
                            <div class="card ">
                                <div class="card-header border-0">
                                    <h4 class="card-title">Khách hàng mới</h4>
                                    <a class="heading-elements-toggle"><i
                                                class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a class="btn-reload" data-content="#load-new-customer"><i
                                                            class="ft-rotate-cw"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content" id="load-new-customer">
                                    <div class="card-content table-responsive position-relative px-1">
                                        <div class="media-list load-on-ready height-300"
                                             url-load="<?php echo Url::toRoute(['load-data-new-customer']) ?>"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Khách hàng vừa đến</h4>
                                    <a class="heading-elements-toggle"><i
                                                class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a class="btn-reload" data-content="#recent-buyers"><i
                                                            class="ft-rotate-cw"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content" id="recent-buyers">
                                    <div class="card-content table-responsive position-relative px-1">
                                        <div class="media-list load-on-ready height-300"
                                             url-load="<?php echo \yii\helpers\Url::toRoute(['load-data-report']) ?>">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header border-0">
                                    <h4 class="card-title">Khách đặt hẹn</h4>
                                    <a class="heading-elements-toggle"><i
                                                class="fa fa-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a class="btn-reload" data-content="#load-province"><i
                                                            class="ft-rotate-cw"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content" id="load-province">
                                    <div class="table-responsive height-300 load-on-ready"
                                         url-load="<?php echo Url::toRoute(['load-province-customer']) ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 row-right">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Các hoạt động gần đây</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a class="btn-reload" data-content="#recent-action"><i class="ft-rotate-cw"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content position-relative px-1">
                            <div id="recent-action" class="dashboard-right load-on-ready"
                                 url-load="<?php echo Url::toRoute(['load-user-timeline']) ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/stats-->
<?php
$thismonth = "Tháng " . date('m');
$lastmonth = "Tháng " . date('m', strtotime(date('d-m-Y') . ' -1 months'));
$url_data = yii\helpers\Url::toRoute('load-data');
$url_alert = yii\helpers\Url::toRoute('load-user-alert');
$script = <<< JS

$('body').find('.load-on-ready').myLoading({
size: 'sm'
});
$('body').find('.dat-hen-chart').myLoading();
function loadData(el){
    if(el != null && el != undefined){
        var urlLoad = el.attr('url-load') || null;
        if(urlLoad != null){
            el.load(urlLoad);
        }
    }
}
$(document).ready(function() {
     getAlert();
     $('.dat-hen-chart').each(async function() {
         let c = await callData();   
        callData().then(function(res) {
            $('.dat-hen-chart').myUnloading();
        }, function(err) {
        })
     });
    $('.load-on-ready').each(function() {
        var el = $(this);
        $.when(loadData(el)).done(function(){
            window.onload = function(){
                 el.myUnloading();
            };
        })
    });
    $('body').on('click', '.btn-reload', function() {
        var content = $(this).attr('data-content');
        $(content).myLoading({size: 'sm'});
        $.when(loadData($(content))).done(function() {
            $(content).myUnloading();
        })
    })
})
var a;
function callData(){
    return new Promise(function(resolve, reject){
         $.ajax({
            url: '$url_data',
            method: "POST",
            dataType: "JSON",
         }).done(function(data){
             a = data;
             var date = $.map(data.data_report.date, function(value, index) {return [value];});
              var this_month = $.map(data.data_report.this_month, function(value, index) {return [value];});
              var last_month = $.map(data.data_report.last_month, function(value, index) {return [value];});
              booking(date,this_month,last_month);
              resolve('thanh cong');
         }).fail(function(err){
             console.log('callData fail', err);
             reject('that bai');
         });
     })
}
function getAlert() {
    $.ajax({
        url: '$url_alert',
        method: "POST",
        dataType: "JSON",
        success:function(data) {
          if(data.data.booking_fail > 0){
               $('.alert-danger.user-alert').addClass("show")
               .html("Hôm qua bạn có <a href='/customer/customer-online/?Dep365CustomerOnlineSearch[alert]=true'>" +
                "<span class='badge round badge-warning'>"+data.data.booking_fail+"</span> khách hàng </a> đặt hẹn mà không đến");
          }
           if(data.data.care > 0){
               $('.alert-warning.user-alert').addClass("show")
               .html("Bạn có <a href='/customer/customer-online-remind-call/?CustomerOnlineRemindCallSearch[alert]=true'> " +
                "<span class='badge round badge-danger'>"+data.data.care+"</span> khách hàng </a> cần được chăm sóc trong tuần qua");
          }
         
          
        }
    });
}
function booking(date,this_month,last_month) {
   // Chart Options
    var ctx = $("#dathen-chart");
    var chartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
            position: 'bottom',
        },
        hover: {
            mode: 'label'
        },
        scales: {
            xAxes: [{
                display: true,
                gridLines: {
                    color: "#f3f3f3",
                    drawTicks: true,
                },
                scaleLabel: {
                    display: false,
                    labelString: 'Thời gian'
                }
            }],
            yAxes: [{
                display: true,
                gridLines: {
                    color: "#f3f3f3",
                    drawTicks: true,
                },
                scaleLabel: {
                    display: true,
                    labelString: 'Lịch hẹn'
                }
            }]
        },
        title: {
            display: false,
            text: 'Lượng khách hàng đặt hẹn tháng này và tháng trước'
        }
    };

    // Chart Data

    var chartData = {
        labels: date,
        datasets: [{
            label:'$thismonth',
            data: this_month,
            backgroundColor: "rgba(81,117,224,.7)",
            borderColor: "transparent",
            pointBorderColor: "#5175E0",
            pointBackgroundColor: "#FFF",
            pointBorderWidth: 2,
            pointHoverBorderWidth: 2,
            pointRadius: 4,
        },
          {  label: '$lastmonth',
            data: last_month,
            backgroundColor: "rgba(209,212,219,.4)",
            borderColor: "transparent",
            pointBorderColor: "#D1D4DB",
            pointBackgroundColor: "#FFF",
            pointBorderWidth: 2,
            pointHoverBorderWidth: 2,
            pointRadius: 4,
        }]
    };

    var config = {
        type: 'line',

        // Chart Options
        options : chartOptions,

        // Chart Data
        data : chartData
    };

    // Create the chart
    var areaChart = new Chart(ctx, config);
    }
    

JS;
$this->registerJs($script, \yii\web\View::POS_END);
$this->registerJsFile('/vendors/js/charts/raphael-min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('/vendors/js/charts/chart.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('/vendors/js/charts/morris.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile('/vendors/css/charts/morris.css', ['depends' => [\yii\bootstrap\BootstrapAsset::class]]);
?>
