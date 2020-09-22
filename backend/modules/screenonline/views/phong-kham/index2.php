<?php
$this->title = 'Screen Clinic';
$this->registerCssFile(\yii\helpers\Url::to('@web/modules') . '/screenonline/screenonline.css', ['depends' => [\backend\assets\AppAsset::class]]);
?>
    <div class="heading-element">
        <ul class="list-inline m-0">
            <li><a href="/"><i class="fa fa-home"></i></a></li>
        </ul>
    </div>
    <div class="db-full-screen">
        <div id="date-filter">
            <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control dateranges">
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <span class="fa fa-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="hk-row m-0">
            <div class="col-xl-8 col-lg-12">
                <div class="hk-row">
                    <?php
                    switch (count($dataCot1Trai)) {
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
                                <span class="box-icon bg-primary text-white mt-2"><i class="fa fa-calendar"></i></span>
                                <div class="box-content">
                                    <span class="box-text">Tổng lịch hẹn tháng <?= date('m') ?></span>
                                    <div class="box-number"><?= $lichHenTotalThang ?></div>
                                    <div class="progress progress-sm my-1">
                                        <div class="progress-bar bg-primary" style="width: 100%"></div>
                                    </div>
                                    <span class="progress-description">100%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    foreach ($dataCot1Trai as $key => $value) {
                        $percent = $lichHenTotalThang == 0 ? 0 : $value['lichHenTheoThang'] / $lichHenTotalThang * 100;
                        if ($percent <= 10) $bg_color = 'bg-danger';
                        elseif ($percent > 10 && $percent <= 50) $bg_color = 'bg-warning';
                        elseif ($percent > 50 && $percent <= 70) $bg_color = 'blue';
                        elseif ($percent > 70) $bg_color = 'bg-success';
                        ?>
                        <div class="<?= $col ?>">
                            <div class="card card-sm mb-1">
                                <div class="card-body">
                                    <span class="box-icon bg-primary text-white mt-2"><i
                                                class="fa fa-calendar"></i></span>
                                    <div class="box-content">
                                        <span class="box-text">Lịch hẹn CS.<?= $value['name'] ?> tháng <?= date('m') ?></span>
                                        <div class="box-number"><?= $value['lichHenTheoThang']; ?></div>
                                        <div class="progress progress-sm my-1">
                                            <div class="progress-bar <?= $bg_color ?>"
                                                 style="width: <?= number_format($percent, 2) . '%' ?>"></div>
                                        </div>
                                        <span class="progress-description"><?= number_format($percent, 2) . '%' ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <div class="hk-row">
                    <div class="col-lg-7">
                        <div id="apmt-percent-table">
                            <div class="card mb-1">
                                <div class="card-body p-0">
                                    <div class="table-wrap">
                                        <div class="table-responsive">
                                            <div class="table-explain p-1">
                                                <ul class="list-unstyled d-flex justify-content-center">
                                                    <li>
                                                        <span class="fa fa-circle blue"></span>
                                                        <span>Tỉ lệ khách online đến / tổng lịch hẹn</span>
                                                    </li>
                                                    <li>
                                                        <span class="fa fa-circle teal"></span>
                                                        <span>Tỉ lệ khách online chốt / khách online đến + khách vãng lai </span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <table class="table table-hover mb-0">
                                                <thead>
                                                <tr>
                                                    <th style="width: 200px;"></th>
                                                    <th>Tháng <?= date('m') ?></th>
                                                    <?php
                                                    foreach ($dataCot1TraiDong2 as $key => $item) {
                                                        ?>
                                                        <th>Cơ sở <?= $key ?></th>
                                                    <?php } ?>
                                                    <th>Dự trù</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>Lịch hẹn còn lại</td>
                                                    <td>
                                                        <div class="font-18"><?= $lichhenConLaiTrongThang; ?></div>
                                                    </td>
                                                    <?php
                                                    foreach ($dataCot1TraiDong2 as $key => $item) {
                                                        ?>
                                                        <td>
                                                            <div class="font-18"><?= $item['lichhenConLaiTrongThang']; ?></div>
                                                        </td>
                                                    <?php } ?>
                                                    <td>
                                                        <div class="font-18"><?= $lichHenDutruTrongThang + $lichHenTotalThang; ?></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tổng khách online đến</td>
                                                    <td>
                                                        <div class="font-18"><?= $khachDenTotal; ?></div>
                                                        <div class="progress progress-xs" style="margin:.25rem 0">
                                                            <div class="progress-bar bg-blue"
                                                                 style="width: <?= $phanTramKhachDenSoLichHen; ?>%"></div>
                                                        </div>
                                                        <div class="blue font-12"><?= $phanTramKhachDenSoLichHen; ?>%
                                                        </div>
                                                    </td>
                                                    <?php foreach ($khachdenTheoThang as $key => $item) { ?>
                                                        <td>
                                                            <div class="font-18"><?= $item['khachdenTheoThang']; ?></div>
                                                            <div class="progress progress-xs" style="margin:.25rem 0">
                                                                <div class="progress-bar bg-blue"
                                                                     style="width: <?= $item['phantram']; ?>%"></div>
                                                            </div>
                                                            <div class="blue font-12"><?= $item['phantram']; ?>%</div>
                                                        </td>
                                                    <?php } ?>
                                                    <td>
                                                        <div class="font-18"><?= $duTruKhachDenTrongThang; ?></div>
                                                        <div class="progress progress-xs" style="margin:.25rem 0">
                                                            <div class="progress-bar bg-blue"
                                                                 style="width: <?= $duTruKhachDenTrongThangPhanTram; ?>%"></div>
                                                        </div>
                                                        <div class="blue font-12"><?= $duTruKhachDenTrongThangPhanTram; ?>
                                                            %
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tổng khách vãng lai</td>
                                                    <td>
                                                        <div class="font-18"><?= $khacVangLaiResultTotal; ?></div>
                                                    </td>
                                                    <?php foreach ($khacVangLaiResult as $key => $value) { ?>
                                                        <td>
                                                            <div class="font-18"><?= $value['khachVangLaiTheoCoSo']; ?></div>
                                                        </td>
                                                    <?php } ?>
                                                    <td>
                                                        <div class="font-18"><?= $duTruKhachVangLai; ?></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tổng khách chốt</td>
                                                    <td>
                                                        <div class="font-18">
                                                            <?= $khachChotTrongThangResultTotal; ?>
                                                            <?php
                                                            $totalKhacDen = $khacVangLaiResultTotal + $khachDenTotal;
                                                            $phanTram = $totalKhacDen == 0 ? 0 : round(($khachChotTrongThangResultTotal / $totalKhacDen) * 100, 2);
                                                            ?>
                                                        </div>
                                                        <div class="progress progress-xs" style="margin:.25rem 0">
                                                            <div class="progress-bar bg-teal"
                                                                 style="width: <?= $phanTram; ?>%"></div>
                                                        </div>
                                                        <div class="teal font-12"><?= $phanTram; ?>%</div>
                                                    </td>
                                                    <?php foreach ($khachChotTrongThangResult as $key => $item) { ?>
                                                        <td>
                                                            <div class="font-18"><?= $item['khachChotTrongThang']; ?></div>
                                                            <div class="progress progress-xs" style="margin:.25rem 0">
                                                                <div class="progress-bar bg-teal"
                                                                     style="width: <?= $item['phantram']; ?>%"></div>
                                                            </div>
                                                            <div class="teal font-12"><?= $item['phantram']; ?>%</div>
                                                        </td>
                                                    <?php } ?>
                                                    <td>
                                                        <div class="font-18"><?= $duTruKhachChotTrongThang; ?></div>
                                                        <div class="progress progress-xs" style="margin:.25rem 0">
                                                            <div class="progress-bar bg-teal"
                                                                 style="width: <?= $duTruKhachChotTrongThangPhanTram; ?>%"></div>
                                                        </div>
                                                        <div class="teal font-12"><?= $duTruKhachChotTrongThangPhanTram; ?>
                                                            %
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="appointment-by-fanpage">
                            <div class="card mb-1">
                                <div class="card-header pb-1">
                                    <h4 class="card-title font-weight-600">Lịch hẹn theo Facebook Page</h4>
                                </div>
                                <div class="card-body p-0">
                                    <div id="appointment-by-fanpage-data"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div id="apmt-revenue-today-chart">
                            <div class="card mb-1">
                                <div class="card-body">
                                    <div id="column-line" class="height-400 echart-container"></div>
                                </div>
                            </div>
                        </div>
                        <div id="revenue-today">
                            <div id="revenue-today-data" class="hk-row mb-1"></div>
                        </div>
                        <div id="service-today">
                            <div id="service-today-data" class="hk-row"></div>
                        </div>
                    </div>
                </div>
                <div class="hk-row mb-1">
                    <div class="col-12">
                        <div id="clinic-kpi"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-12">
                <div class="hk-row">
                    <div class="col-xl-12 col-lg-6 col-12">
                        <div id="revenue-month">

                        </div>
                    </div>
                </div>
                <div class="hk-row">
                    <div class="col-xl-12 col-md-6 col-12">
                        <div id=services-revenue>
                            <div class="card mb-1">
                                <div class="card-header">
                                    <h4 class="card-title font-weight-bold">Doanh thu theo dịch vụ
                                        tháng <?= date('m-Y') ?></h4>
                                </div>
                                <div class="card-body">
                                    <div id="basic-pie" class="height-400 echart-container"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 col-md-6 col-12">
                        <div id="product-by-appointment">
                            <div class="card mb-1">
                                <div class="card-header pb-1">
                                    <h4 class="card-title font-weight-bold">Sản phẩm sử dụng
                                        tháng <?= date('m-Y') ?></h4>
                                </div>
                                <div class="card-body">
                                    <div id="product-chart" class="height-400"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hk-row m-0">
            <div class="col-xl-6 col-12">
                <div id="ratio-customer-done-by-direct-sale"></div>
            </div>
        </div>
    </div>

<?php
$urlAppointmentChart = \yii\helpers\Url::toRoute('get-data-apmt-chart');
$urlServiceRevenue = \yii\helpers\Url::toRoute('get-data-service-revenue');
$urlProductByApmt = \yii\helpers\Url::toRoute('get-data-product-by-apmt');
$urlApmtByFacebookPage = \yii\helpers\Url::toRoute('get-data-apmt-by-facebook-page');
$urlRevenueDay = \yii\helpers\Url::toRoute('get-data-revenue-day');
$urlRevenueMonth = \yii\helpers\Url::toRoute('get-data-revenue-month');
$urlKpiClinic = \yii\helpers\Url::toRoute('get-data-kpi-clinic');
$urlCustomerDoneByDs = \yii\helpers\Url::toRoute('get-data-customer-done-by-direct-sale');
$urlServiceToday = \yii\helpers\Url::toRoute('get-data-service-today');
$script = <<< JS
var d = new Date(),
    day = d.getDate(),
    month = d.getMonth(),
    year = d.getFullYear(),
    last_DoM = new Date(year, month + 1, 0).getDate();
var startDateReport = '1-' + (month + 1) + '-' + year,
    endDateReport = day + '-' + (month +1) + '-' + year,
    lastDateReport = last_DoM + '-' + (month +1) + '-' + year;

var csrf = $('meta[name=csrf-token]').attr('content') || null;

$('.dateranges').daterangepicker({
    opens: 'right',
    formatSubmit: 'D/M/Y',
    timeZone: 'Asia/Ho_Chi_Minh',
    showDropdowns: true,
    timePicker: false,
    format: 'DD/MM/YYYY',
    startDate:  moment().startOf('month'),
    ranges: {
        'Hôm nay': [moment(), moment()],
        'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        '7 ngày trước': [moment().subtract(6, 'days'), moment()],
        '30 ngày trước': [moment().subtract(29, 'days'), moment()],
        'Tháng hiện tại': [moment().startOf('month'), moment()],
        'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    locale: {
    format: 'D/M/Y',
    cancelLabel: 'Xóa',
    applyLabel: 'Cập nhật',
    daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
    monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
    "customRangeLabel": "Tùy chọn",
    },
    autoclose: true,
    maxDate: moment(),
},
function(start, end) {
    startDateReport = start.format('DD-MM-Y');
    endDateReport = end.format('DD-MM-Y');
});

$(function () {
    loadAppointmentChartBar(endDateReport, endDateReport);
    loadRevenueDay(endDateReport, endDateReport);
    loadRevenueMonth(startDateReport, endDateReport);
    loadAppointmentFacebookPage(startDateReport, endDateReport);
    loadKpiClinic(startDateReport, endDateReport);
    loadServiceRevenue(startDateReport, endDateReport);
    loadProductChart(startDateReport, endDateReport);
    loadServicesToday(endDateReport, endDateReport);
    loadCustomerDoneByDs(startDateReport, endDateReport);
});

$('.dateranges').on('change', function() {
    loadAppointmentChartBar(startDateReport, endDateReport);
    loadRevenueDay(startDateReport, endDateReport);
    loadRevenueMonth(startDateReport, endDateReport);
    loadAppointmentFacebookPage(startDateReport, endDateReport);
    loadServiceRevenue(startDateReport, endDateReport);
    loadProductChart(startDateReport, endDateReport);
    loadServicesToday(startDateReport, endDateReport);
    loadKpiClinic(startDateReport, endDateReport);
    loadCustomerDoneByDs(startDateReport, endDateReport);
});

function loadAppointmentChartBar(startDateReport, endDateReport) {
    $('#column-line').myLoading({msg: 'Đang tải dữ liệu...'});
    
    $.post('$urlAppointmentChart', { _csrf: csrf, startDateReport: startDateReport, endDateReport: endDateReport }, function (data) {  
        // Set paths
        // ------------------------------
        require.config({
            paths: {
                echarts: '../../../vendors/js/charts/echarts',
            }
        });
    
        // Configuration
        // ------------------------------
        require(
            [
                'echarts',
                'echarts/chart/bar',
                'echarts/chart/line',
            ],
    
            // Charts setup
            function (ec) {
    
                // Initialize chart
                // ------------------------------
                var myChart = ec.init(document.getElementById('column-line'));
    
                // Chart Options
                // ------------------------------
                chartOptions = {
    
                    // Setup grid
                    grid: {
                        x: 40,
                        x2: 40,
                        y: 60,
                        y2: 25
                    },
    
                    // Add tooltip
                    tooltip: {
                        trigger: 'axis'
                    },
    
                    // Add legend
                    legend: {
                        data: ['Lịch hẹn', 'Khách đến thực tế', 'Khách chốt']
                    },
    
                    // Add custom colors
                    color: ['#00a5a8', '#FF4558', '#2979FF', '#FF7D4D'],
    
                    // Enable drag recalculate
                    calculable: false,
    
                    // Horizontal axis
                    xAxis: [{
                        type: 'category',
                        data: data.listLabel
                    }],
    
                    // Vertical axis
                    yAxis : [
                        {
                            type : 'value',
                        },
                        {
                            type : 'value',
                        }
                    ],
    
                    // Add series
                    series : [
                        {
                            name:'Lịch hẹn',
                            type:'bar',
                            data:data.data.lichHenHomNay,
                            itemStyle: {
                                normal: {
                                    label: {
                                        show: true,
                                        textStyle: {
                                            fontWeight: 500
                                        }
                                    }
                                }
                            }
                        },
                        {
                            name:'Khách đến thực tế',
                            type:'bar',
                            data:data.data.khachDenHomNay,
                            itemStyle: {
                                normal: {
                                    label: {
                                        show: true,
                                        textStyle: {
                                            fontWeight: 500
                                        }
                                    }
                                }
                            }
                        },
                        {
                            name:'Khách chốt',
                            type:'bar',
                            data:data.data.khachChotHomNay,
                            itemStyle: {
                                normal: {
                                    label: {
                                        show: true,
                                        textStyle: {
                                            fontWeight: 500
                                        }
                                    }
                                }
                            }
                        },
                    ]
                };
    
                // Apply options
                // ------------------------------
                myChart.setOption(chartOptions);
    
                $(function () {
                    // Resize chart on menu width change and window resize
                    $(window).on('resize', resize);
    
                    // Resize function
                    function resize() {
                        setTimeout(function() {
    
                            // Resize chart
                            myChart.resize();
                        }, 200);
                    }
                });
            }
        );
        
        $('#column-line').myUnloading();
    });
}

function loadRevenueDay(startDateReport, endDateReport) {
    $('#revenue-today-data').myLoading({msg: 'Đang tải dữ liệu...'});
    $.post('$urlRevenueDay', { _csrf: csrf, startDateReport: startDateReport, endDateReport: endDateReport }, function(data) {
        $('#revenue-today-data').html(data).myUnloading();
    });
}

function loadRevenueMonth(startDateReport, endDateReport) {
    $('#revenue-month').myLoading({msg: 'Đang tải dữ liệu...'});
    $.post('$urlRevenueMonth', { _csrf: csrf, startDateReport: startDateReport, endDateReport: endDateReport }, function(data) {
        $('#revenue-month').html(data).myUnloading();
    });
}

function loadAppointmentFacebookPage(startDateReport, endDateReport) {
    $('#appointment-by-fanpage-data').myLoading({msg: 'Đang tải dữ liệu...'});
    $.post('$urlApmtByFacebookPage', { _csrf: csrf, startDateReport: startDateReport, endDateReport: endDateReport }, function(data) {
        $('#appointment-by-fanpage-data').html(data);
        $('#appointment-by-fanpage-data').html(data).myUnloading();
    });
}

function loadKpiClinic(startDateReport, endDateReport) {
    $('#clinic-kpi').myLoading({msg: 'Đang tải dữ liệu...'});
    $.post('$urlKpiClinic', { _csrf: csrf, startDateReport: startDateReport, endDateReport: endDateReport }, function(data) {
        $('#clinic-kpi').html(data).myUnloading();
    });
}

function loadServiceRevenue(startDateReport, endDateReport) {
    $('#basic-pie').myLoading({'msg': 'Đang tải dữ liệu...'});    
    $.post('$urlServiceRevenue', { _csrf: csrf, startDateReport: startDateReport, endDateReport: endDateReport }, function (data) {
        $('#services-revenue').find('.card-title').html(data.title);
        // Set paths
        // ------------------------------    
        require.config({
            paths: {
                echarts: '../../../vendors/js/charts/echarts',
            }
        });
    
    
        // Configuration
        // ------------------------------    
        require(
            [
                'echarts',
                'echarts/chart/pie',
            ],    
    
            // Charts setup
            function (ec) {
                // Initialize chart
                // ------------------------------
                var myChart = ec.init(document.getElementById('basic-pie'));
    
                // Chart Options
                // ------------------------------
                chartOptions = {
    
                    // Add tooltip
                    tooltip: {
                        trigger: 'item',
                        formatter: "{a} <br/> {b}: {c} ({d}%)"
                    },
    
                    // Add legend
                    legend: {
                        orient: 'horizontal',
                        x: 'center',
                        data: data.listLabel
                    },
    
                    // Add custom colors
                    color: ['#00A5A8', '#626E82', '#FF7D4D','#FF4558', '#16D39A'],
                    
                    // Enable drag recalculate
                    calculable: false,
    
                    // Add series
                    series: [{
                        name: 'Doanh thu',
                        type: 'pie',
                        radius: '70%',
                        center: ['50%', '57.5%'],
                        data: data.revenueByService
                    }]
                };
    
                // Apply options
                // ------------------------------    
                myChart.setOption(chartOptions);
    
                $(function () {    
                    // Resize chart on menu width change and window resize
                    $(window).on('resize', resize);
    
                    // Resize function
                    function resize() {
                        setTimeout(function() {
    
                            // Resize chart
                            myChart.resize();
                        }, 200);
                    }
                });
            }
        );
        $('#basic-pie').myUnloading();
    });
}

function loadProductChart(startDateReport, endDateReport) {
    $('#product-chart').myLoading({'msg': 'Đang tải dữ liệu...'});
    $.post('$urlProductByApmt', { _csrf: csrf, startDateReport: startDateReport, endDateReport: endDateReport }, function (data) {
        $('#product-by-appointment').find('.card-title').html(data.title);
        // Set paths
        // ------------------------------    
        require.config({
            paths: {
                echarts: '../../../vendors/js/charts/echarts',
            }
        });
    
    
        // Configuration
        // ------------------------------    
        require(
            [
                'echarts',
                'echarts/chart/pie',
            ],    
    
            // Charts setup
            function (ec) {
                // Initialize chart
                // ------------------------------
                var myChart = ec.init(document.getElementById('product-chart'));
    
                // Chart Options
                // ------------------------------
                chartOptions = {
    
                    // Add tooltip
                    tooltip: {
                        trigger: 'item',
                        formatter: "{a} <br/> {b}: {c} ({d}%)"
                    },
    
                    // Add legend
                    legend: {
                        orient: 'horizontal',
                        x: 'center',
                        data: data.listLabelProduct
                    },
    
                    // Add custom colors
                    color: ['#ffd775', '#e84a5f', '#7fd5c3','#ff7d4d', '#e974b9', '#00b5b8', '#455a64'],
                    
                    // Enable drag recalculate
                    calculable: false,
    
                    // Add series
                    series: [{
                        name: 'Số lượng',
                        type: 'pie',
                        radius: '70%',
                        center: ['50%', '57.5%'],
                        data: data.listDataProduct,
                        itemStyle: {
                            normal: {
                                label : {
                                    show: true,
                                    formatter: function(data){
                                        return data.name + "\\n" + data.value + " (" + data.percent + "%)";
                                    }
                                }
                            }
                        },
                    }]
                };
    
                // Apply options
                // ------------------------------    
                myChart.setOption(chartOptions);
    
                $(function () {    
                    // Resize chart on menu width change and window resize
                    $(window).on('resize', resize);
    
                    // Resize function
                    function resize() {
                        setTimeout(function() {
    
                            // Resize chart
                            myChart.resize();
                        }, 200);
                    }
                });
            }
        );
        $('#product-chart').myUnloading();
    });
}

function loadServicesToday(startDateReport, endDateReport) {
    $.post('$urlServiceToday', { _csrf: csrf, startDateReport: startDateReport, endDateReport: endDateReport }, function(data) {
        $('#service-today-data').html(data);
    });
}

function loadCustomerDoneByDs(startDateReport, endDateReport) {
    $.post('$urlCustomerDoneByDs', { _csrf: csrf, startDateReport: startDateReport, endDateReport: endDateReport }, function(data) {
        $('#ratio-customer-done-by-direct-sale').html(data);
    });
}

JS;
$this->registerJsFile('/vendors/js/charts/chart.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('/vendors/js/charts/echarts/echarts.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJS($script, \yii\web\View::POS_END);
?>