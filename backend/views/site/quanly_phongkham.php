<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 04-Mar-19
 * Time: 3:03 PM
 */

$this->title = 'Quản lý phòng khám';
$this->registerCssFile(\yii\helpers\Url::to('@web/modules') . '/screenonline/screenonline.css', ['depends' => [\backend\assets\AppAsset::class]]);
$this->registerCssFile(\yii\helpers\Url::to('@web/modules') . '/clinic-manager/calendar.css', ['depends' => [\backend\assets\AppAsset::class]]);
$this->registerCssFile('/vendors/plugins/calendar/packages/line-awesome/1.3.0/css/line-awesome.min.css', ['depends' => [\backend\assets\AppAsset::class]]);
$this->registerCssFile('/vendors/plugins/calendar/packages/core/main.css', ['depends' => [\backend\assets\AppAsset::class]]);
$this->registerCssFile('/vendors/plugins/calendar/packages/daygrid/main.css', ['depends' => [\backend\assets\AppAsset::class]]);
$this->registerCssFile('/vendors/plugins/calendar/packages/timegrid/main.css', ['depends' => [\backend\assets\AppAsset::class]]);
$this->registerCssFile('/vendors/plugins/calendar/packages/list/main.css', ['depends' => [\backend\assets\AppAsset::class]]);
$css = <<< CSS
.list-group-item{padding: .875rem 0;}
#appointment .card-group{margin-bottom:0;box-shadow:none}
#calendar-container .fc-view-container{max-height:425px;overflow:auto;}
CSS;

$this->registerCss($css);
$co_so = Yii::$app->user->identity->permission_coso;
?>

    <!--stats-->
    <section id="site-content" style="padding:7px;">
        <!--<div class="hk-row">
            <div class="col-12">
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
        </div>-->
        <div class="hk-row">
            <div class="col-lg-8">
                <div id="appointment" class="mb-1">
                    <div class="card mb-1">
                        <div class="card-header">
                            <h4 class="card-title font-weight-600"></h4>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div id="group-card" class="hk-row"></div>
                                <div class="separator"></div>
                                <div id="basic-column-chart" class="height-300"></div>
                            </div>
                        </div>
                    </div>


                </div>

                <div id="calendar-container" class="mb-1">
                    <div class="card card-sm">
                        <div class="card-header">
                            <h4 class="card-title font-weight-600">Lịch hẹn</h4>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="d-flex h-100">
                                    <div id="calendar" style="height: 100%;"></div>
                                </div>
                                <div class="calendar-note">
                                    <ul class="list-unstyled d-flex justify-content-center">
                                        <li><span class="fa fa-circle" style="color:#1890ff"></span> Khách chưa đến</li>
                                        <li><span class="fa fa-circle" style="color:#16d39a"></span> Khách đến</li>
                                        <li><span class="fa fa-circle" style="color:#FF7588"></span> Khách không đến
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="ratio-customer-done-by-direct-sale" class="mb-1"></div>
            </div>
            <div class="col-lg-4">
                <div class="hk-row">
                    <div id="revenue" class="hk-row m-0">
                        <div id="revenue-today" class="col-12 mb-1"></div>
                        <div id="revenue-week" class="col-12 mb-1"></div>
                        <div id="revenue-month" class="col-12 mb-1"></div>
                    </div>

                    <div id="product" class="col-lg-12 col-md-4 col-12"></div>

                    <div id="staff" class="col-lg-12 mb-1">
                        <div class="card card-sm">
                            <div class="card-header p-1">
                                <h4 class="card-title font-weight-bold">Nhân sự tại cơ sở</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <ul class="nav nav-pills mb-1" id="pills-tab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link active" id="doctor-tab" data-toggle="pill" href="#doctor"
                                               role="tab" aria-controls="doctor" aria-selected="true">Bác sĩ</a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="assistant-tab" data-toggle="pill" href="#assistant"
                                               role="tab" aria-controls="assistant" aria-selected="false">Trợ thủ</a>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <a class="nav-link" id="direct-sale-tab" data-toggle="pill"
                                               href="#direct-sale" role="tab" aria-controls="direct-sale"
                                               aria-selected="false">Direct Sales</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="doctor" role="tabpanel"
                                             aria-labelledby="doctor-tab">
                                            <ul class="list-group list-group-flush">
                                                <?php foreach ($doctors as $doctor) {
                                                    if ($doctor->permission_coso != $co_so) continue;
                                                    ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?= $doctor->fullname ?>
                                                        <span class="badge badge-default badge-pill bg-success float-right">Đang hoạt động</span>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <div class="tab-pane fade" id="assistant" role="tabpanel"
                                             aria-labelledby="assistant-tab">
                                            <ul class="list-group list-group-flush">
                                                <?php foreach ($assistants as $assistant) {
//                                                    if ($assistant->permission_coso != $co_so) continue;
                                                    ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?= $assistant ?>
                                                        <span class="badge badge-default badge-pill bg-success float-right">Đang hoạt động</span>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                        <div class="tab-pane fade" id="direct-sale" role="tabpanel"
                                             aria-labelledby="direct-sale-tab">
                                            <ul class="list-group list-group-flush">
                                                <?php foreach ($direct_sales as $direct_sale) { ?>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        <?= $direct_sale ?>
                                                        <span class="badge badge-default badge-pill bg-success float-right">Đang hoạt động</span>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="product-by-appointment" class="col-lg-12 col-md-8 col-12 mb-1">
                        <div class="card">
                            <div class="card-header pb-1">
                                <h4 class="card-title font-weight-bold">Sản phẩm sử dụng tháng <?= date('m-Y') ?></h4>
                            </div>
                            <div class="card-body">
                                <div id="product-chart" class="height-400"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--/stats-->

<?php
$urlCustomerDoneByDs = \yii\helpers\Url::toRoute(['/screenonline/phong-kham/get-data-customer-done-by-direct-sale']);
$urlProductByApmt = \yii\helpers\Url::toRoute(['/screenonline/phong-kham/get-data-product-by-apmt']);
$urlRevenueDay = \yii\helpers\Url::toRoute(['/screenonline/phong-kham/get-data-revenue-day']);
$urlRevenueWeek = \yii\helpers\Url::toRoute(['/screenonline/phong-kham/get-data-revenue-week']);
$urlRevenueMonth = \yii\helpers\Url::toRoute(['/screenonline/phong-kham/get-data-revenue-month']);
$urlProductToday = \yii\helpers\Url::toRoute(['/screenonline/online/get-data-product-today']);
$urlGetCardGroupApmt = \yii\helpers\Url::toRoute(['/screenonline/online/get-data-card-group-apmt']);
$urlCustomerCompareChart = \yii\helpers\Url::toRoute('get-data-customer-compare-chart');
$urlAppointment = \yii\helpers\Url::toRoute('get-data-appointment');

$script = <<< JS
var d = new Date(),
    day = d.getDate(),
    month = d.getMonth(),
    year = d.getFullYear(),
    last_DoM = new Date(year, month + 1, 0).getDate();
var startDateReport = '01-' + (month + 1) + '-' + year,
    endDateReport = day + '-' + (month +1) + '-' + year,
    lastDateReport = last_DoM + '-' + (month +1) + '-' + year;
var csrf = $('meta[name=csrf-token]').attr('content') || null;
var co_so = '$co_so';

var dt = new Date(),
    start = startOfWeek(dt),
    end = endOfWeek(dt);
var firstDayOfWeek = ((start.getDate() > 9) ? start.getDate() : ('0' + start.getDate())) + '-' + ((start.getMonth() > 8) ? (start.getMonth() + 1) : ('0' + (start.getMonth() + 1))) + '-' + start.getFullYear(),
    lastDayOfWeek = ((end.getDate() > 9) ? end.getDate() : ('0' + end.getDate())) + '-' + ((end.getMonth() > 8) ? (end.getMonth() + 1) : ('0' + (end.getMonth() + 1))) + '-' + end.getFullYear();



$.post('$urlCustomerDoneByDs', { _csrf: csrf, startDateReport: startDateReport, endDateReport: endDateReport }, function(data) {
    $('#ratio-customer-done-by-direct-sale').html(data);
});

$.post('$urlProductToday', { _csrf: csrf, startDateReport: endDateReport, endDateReport: endDateReport }, function(data) {
    $('#product').html(data);
});

$.post('$urlRevenueDay', { _csrf: csrf, startDateReport: endDateReport }, function(data) {
    $('#revenue-today').html(data);
});

$.post('$urlRevenueWeek', { _csrf: csrf, startDateReport: firstDayOfWeek, endDateReport: lastDayOfWeek }, function(data) {
    $('#revenue-week').html(data);
});

$.post('$urlRevenueMonth', { _csrf: csrf, startDateReport: startDateReport, endDateReport: endDateReport }, function(data) {
    $('#revenue-month').html(data);
});

$.post('$urlProductByApmt', { _csrf: csrf, startDateReport: startDateReport, endDateReport: endDateReport, co_so: co_so }, function (data) {
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
});



$(function () {
    loadCalendar();
    loadAppointment();
    
    $('#calendar-container, #appointment').on('click', 'a[data-action="reload"]', function() {
        loadCalendar();
        loadAppointment();
    })
});

function loadCalendar(){
    $.post('$urlAppointment',  { _csrf: csrf, startDateReport: startDateReport, endDateReport: lastDateReport }, function (data) {
        $('#calendar').empty();
        var date = new Date(),
            year = date.getFullYear(),
            month = addZero((date.getMonth() + 1)),
            day = addZero(date.getDate()),
            hours = addZero(date.getHours()),
            minutes = addZero(date.getMinutes()),
            seconds = addZero(date.getSeconds());
        var now = year + '-' + month + '-' + day + 'T' + hours + ':' + minutes + ':' + seconds;
    
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'vi',
            plugins: ['interaction', 'dayGrid', 'timeGrid', 'list'],
            height: 'parent',
            header: {
                left: 'prevYear,prev,next,nextYear today',
                center: 'title',
                right: 'timeGridDay,timeGridWeek,dayGridMonth,listWeek'
            },
            columnHeader: true,
            allDaySlot: false,
            // eventLimit: true,
            firstDay: 1,
            views: {
                dayGridMonth: { // name of view
                    titleFormat: {year: 'numeric', month: '2-digit', day: '2-digit'},
                    eventLimit: 3
                },
                week: {
                    // options apply to dayGridWeek and timeGridWeek views
                    titleFormat: {year: 'numeric', month: '2-digit', day: '2-digit'},
                    titleRangeSeparator: ' ~ ',
                    columnHeaderHtml: function (date) {
                        return '<div class="columnHeaderHtml"><div><p>' + addZero(date.getDate()) + '/' + addZero((date.getMonth() + 1)) + '</p><p>' + dayRecover(date.getDay()) + '</p></div></div>';
                    },
                    slotLabelFormat: [
                        {
                            hour: '2-digit',
                            minute: '2-digit',
                        }
                    ],
                    slotDuration: '00:15:00',
                    slotLabelInterval: '01:00:00',
                    axisFormat: 'H:mm'
                },
                day: {
                    // options apply to dayGridDay and timeGridDay views
                    titleFormat: {year: 'numeric', month: '2-digit', day: '2-digit'},
                    slotLabelFormat: [
                        {
                            hour: '2-digit',
                            minute: '2-digit',
                        }
                    ],
                    slotDuration: '00:15:00',
                    slotLabelInterval: '01:00:00',
                    axisFormat: 'H:mm'
                }
            },
            defaultView: 'dayGridMonth',
            defaultDate: date,
            displayEventTime: false,
            nowIndicator: true,
            now: now,
            navLinks: true, // can click day/week names to navigate views
            selectable: true,
            selectMirror: true,
            allDay: false,
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            events: data.customers,
            eventRender: function (info) {
                let start = info.event.start,
                    startTime = start ? addZero(start.getHours()) + ':' + addZero(start.getMinutes()) : '',
                    title = info.event.title,
                    description = info.event.extendedProps.description ? info.event.extendedProps.description : '--',
                    call = info.event.extendedProps.call ? info.event.extendedProps.call : '--';
    
                var popoverTemplate = [
                    '<div class="popover popover-calendar">',
                    '<div class="arrow"></div>',
                    '<div class="popover-header">',
                    '</div>',
                    '<div class="popover-body"></div>',
                    '</div>',
                ].join('');
    
                $(info.el).popover({
                    template: popoverTemplate,
                    title: function () {
                        let title = '<div>' +
                            '<div class="popover-title">' +
                            '<a class="close-popover"><i class="las la-times"></i></a>' +
                            '</div>' +
                            '</div>';
                        return $(title).html();
                    },
                    content: function () {
                        let content = '<div class="popover-content">' +
                                '<p class="pb-title"> ' + title + '</p>' +
                                '<div class="pb-phone"><p><span>Điện thoại</span>:</p>' + call + '</div>' +
                                '<div class="pb-description"><p><span>Mong muốn</span>:</p>' + description + '</div>' +
                                '<div class="pb-line"></div>' +
                                '<p class="pb-time"><span>Thời gian hẹn:</span>&nbsp;' + startTime + '</p>' +
                                '</div>';
                        $('.popover').popover('hide');
                        return $(content).html();
                    },
                    placement: 'right',
                    trigger: 'click',
                    container: 'body',
                    html: true
                }).popover('show');
            },
            eventClick: function (info) {
                info.jsEvent.preventDefault(); // don't let the browser navigate
                if (info.event.url) {
                    window.open(info.event.url);
                }
            },
        });
    
        calendar.render();
    });
}

function loadAppointment() {
    $.post('$urlGetCardGroupApmt', { _csrf: csrf, startDateReport: startDateReport, endDateReport: endDateReport, lastDateReport: lastDateReport }, function(data) {
        $('#group-card').html(data);
    });
    
    $.post('$urlCustomerCompareChart', { _csrf: csrf, startDateReport: firstDayOfWeek, endDateReport: lastDayOfWeek }, function (data) {
        console.log();
        
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
                'echarts/chart/line'
            ],
    
    
            // Charts setup
            function (ec) {
    
                // Initialize chart
                // ------------------------------
                var myChart = ec.init(document.getElementById('basic-column-chart'));
    
                // Chart Options
                // ------------------------------
                chartOptions = {
    
                    // Setup grid
                    grid: {
                        x: 40,
                        x2: 40,
                        y: 35,
                        y2: 25
                    },
    
                    // Add tooltip
                    tooltip: {
                        trigger: 'axis'
                    },
    
                    // Add legend
                    legend: {
                        data: ['Khách đến', 'Khách làm']
                    },
    
                    // Add custom colors
                    color: ['#67b7dc', '#fdd400'],
    
                    // Enable drag recalculate
                    calculable: false,
    
                    // Horizontal axis
                    xAxis: [{
                        type: 'category',
                        data: data.dataReturn.date
                    }],
    
                    // Vertical axis
                    yAxis: [{
                        type: 'value'
                    }],
    
                    // Add series
                    series: [
                        {
                            name: 'Khách đến',
                            type: 'bar',
                            data: data.dataReturn.come,
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
                            name: 'Khách làm',
                            type: 'bar',
                            data: data.dataReturn.done,
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
                        }
                    ]
                };
    
                // Apply options
                // ------------------------------
    
                myChart.setOption(chartOptions);
    
    
                // Resize chart
                // ------------------------------
    
                $(function () {
    
                    // Resize chart on menu width change and window resize
                    $(window).on('resize', resize);
                    $(".menu-toggle").on('click', resize);
    
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
    });
}

/*$('.dateranges').daterangepicker({
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
});*/

function startOfWeek(date) {
    var diff = date.getDate() - date.getDay() + (date.getDay() === 0 ? -6 : 1);
    return new Date(date.setDate(diff));}

function endOfWeek(date) {
    var diff = date.getDate() - date.getDay() + (date.getDay() === 0 ? 0 : 7);
    
    return new Date(date.setDate(diff)); 
}

function addZero(i) {
    if (i <= 9) {
        i = '0' + i;
    }
    return i;
}

function dayRecover(d) {
    if (d == 0) return 'Sun';
    else if (d == 1) return 'Mon';
    else if (d == 2) return 'Tue';
    else if (d == 3) return 'Wed';
    else if (d == 4) return 'Thu';
    else if (d == 5) return 'Fri';
    else if (d == 6) return 'Sat';
}

function popoverCloseByOutsideClick(e) {
    var isNotPopover = !$(e.target).hasClass('.popover'),
        isNotPopoverChild = ($(e.target).parents('.popover').length === 0),
        isNotEvent = !$(e.target).hasClass('.fc-event-container'),
        isNotEventChild = ($(e.target).parents('.fc-event-container').length === 0);
    if (isNotPopover && isNotPopoverChild && isNotEvent && isNotEventChild) {
        closePopovers();
    }
}

function closePopovers() {
    $.each($(".popover"), function (i, el) {
        if ($(el).hasClass("show")) $(el).removeClass("show");
    });
}

$('body').on('click', '.close-popover', function (e) {
    $(this).parents('.popover').popover('hide');
}).on("click", popoverCloseByOutsideClick);
JS;
$this->registerJsFile('/vendors/plugins/calendar/packages/core/main.js', ['depends' => [\backend\assets\AppAsset::class]]);
$this->registerJsFile('/vendors/plugins/calendar/packages/core/locales-all.js', ['depends' => [\backend\assets\AppAsset::class]]);
$this->registerJsFile('/vendors/plugins/calendar/packages/interaction/main.js', ['depends' => [\backend\assets\AppAsset::class]]);
$this->registerJsFile('/vendors/plugins/calendar/packages/daygrid/main.js', ['depends' => [\backend\assets\AppAsset::class]]);
$this->registerJsFile('/vendors/plugins/calendar/packages/timegrid/main.js', ['depends' => [\backend\assets\AppAsset::class]]);
$this->registerJsFile('/vendors/plugins/calendar/packages/list/main.js', ['depends' => [\backend\assets\AppAsset::class]]);
$this->registerJsFile('/vendors/js/charts/chart.min.js', ['depends' => [\backend\assets\AppAsset::class]]);
$this->registerJsFile('/vendors/js/charts/echarts/echarts.js', ['depends' => [\backend\assets\AppAsset::class]]);

$this->registerJS($script, \yii\web\View::POS_END);

?>