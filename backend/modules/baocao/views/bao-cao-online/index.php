<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23-Apr-19
 * Time: 4:48 PM
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = "Online Overview";
$this->registerCssFile('/vendors/css/charts/morris.css');
$tongTuongtac = 0;
$sdtTong = 0;
$sdtTongCall = 0;
$datlichMoi = 0;
$khachDen = 0;
$lichHen = 0;
?>
    <section id="dom">
        <div class="row">
            <div class="col-12">
                <?php
                if (Yii::$app->session->hasFlash('alert')) {
                    ?>
                    <div class="alert <?= Yii::$app->session->getFlash('alert')['class']; ?> alert-dismissible"
                         role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <?= Yii::$app->session->getFlash('alert')['body']; ?>
                    </div>
                    <?php
                }
                ?>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <?= $this->title; ?>
                            <div class="reload-bao-cao pull-right">
                                <i class="fa fa-refresh"></i>
                            </div>
                        </h4>
                        <hr>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3 col-4 mb-1">
                                    <p>Tổng tương tác: <span
                                                id="tongTuongTac"><?= number_format($tongTuongtac, 0, ',', '.'); ?></span>
                                    </p>
                                    <p>Tổng số điện thoại: <span
                                                id="sdtTong"><?= number_format($sdtTong, 0, ',', '.'); ?></span>
                                    </p>
                                </div>
                                <div class="col-md-3 col-4 mb-1">
                                    <p>Tổng sdt gọi được: <span
                                                id="sdtTongCall"><?= number_format($sdtTongCall, 0, ',', '.'); ?></span>
                                    </p>
                                    <p>Tổng lịch mới: <span
                                                id="datlichMoi"><?= number_format($datlichMoi, 0, ',', '.'); ?></span>
                                    </p>
                                </div>
                                <div class="col-md-3 col-4 mb-1">
                                    <p>Tổng lịch hẹn: <span
                                                id="lichHen"><?= number_format($lichHen, 0, ',', '.'); ?></span>
                                    </p>
                                    <p>Tổng khách đến: <span
                                                id="khachDen"><?= number_format($khachDen, 0, ',', '.'); ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-3 col-sm-3 col-md-6 col-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control filter-data-from-online-report">
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <span class="fa fa-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-3 col-sm-3 col-md-6 col-6">
                                    <div class="input-group">
                                        <?= Html::dropDownList(
                                            'page-online',
                                            '',
                                            $page,
                                            ['class' => 'form-control', 'prompt' => 'Chọn page...', 'id' => 'page-online']
                                        ); ?>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-3 col-sm-3 col-md-6 col-6">
                                    <div class="input-group">
                                        <?= Html::dropDownList(
                                            'loc-online',
                                            '',
                                            $loc,
                                            ['class' => 'form-control', 'prompt' => 'Chọn khu vực...', 'id' => 'loc-online']
                                        ); ?>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-3 col-sm-3 col-md-6 col-6">
                                    <div class="input-group">
                                        <?= Html::dropDownList(
                                            'nv-online',
                                            '',
                                            $online,
                                            ['class' => 'form-control', 'prompt' => 'Chọn nhân viên...', 'id' => 'nv-online']
                                        ); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <section id="combination-charts">
                                <!-- Column + Line Chart -->
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                        <div id="tuongtac-line" class="height-400 echart-container"></div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                        <div id="sdt-line" class="height-400 echart-container"></div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                        <div id="call-line" class="height-400 echart-container"></div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                        <div id="calendar-new-line"
                                             class="height-400 echart-container"></div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                        <div id="calendar-about-line"
                                             class="height-400 echart-container"></div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                        <div id="auris-line" class="height-400 echart-container"></div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                        <div id="hieu-qua-line" class="height-400 echart-container"></div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                        <div id="hieu-suat-line" class="height-400 echart-container"></div>
                                    </div>
                                </div>
                                <!-- Column + Pie Chart -->
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
$urlData = \yii\helpers\Url::toRoute('get-data');

$this->registerJsFile('/vendors/js/charts/echarts/echarts.js', ['depends' => 'yii\web\JqueryAsset']);
$script = <<< JS
var d = new Date();
day = d.getDate();
y = d.getFullYear();
m = d.getMonth();

var startDateReport = '01-'+ (m +1)+'-'+y;
var endDateReport = day +'-'+ (m +1) +'-'+y;

$('.filter-data-from-online-report').daterangepicker({
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

$('body').on('change', '.filter-data-from-online-report, #page-online, #loc-online, #nv-online', function() {
    callData();
}).on('click', '.reload-bao-cao', function(){
    callData();
});

$(document).ready(function() {
    $(window).ready(function(){
        callData();
    });
});

function callData() {
    $('#tuongtac-line, #sdt-line, #call-line, #calendar-new-line, #calendar-about-line, #auris-line, #hieu-qua-line, #hieu-suat-line').css('border', '1px solid #CDCDCD');
    $('#tuongtac-line, #sdt-line, #call-line, #calendar-new-line, #calendar-about-line, #auris-line, #hieu-qua-line, #hieu-suat-line').myLoading({msg:'Đang tải dữ liệu...'});
    pageonline = $('#page-online').val();
    loc = $('#loc-online').val();
    nv = $('#nv-online').val();
    $.ajax({
       url: '$urlData',
       method: "POST",
       dataType: "json",
       data:{"startDateReport": startDateReport, "endDateReport":endDateReport, "pageonline": pageonline, "loc": loc, "nv":nv},
       success: function (data) {
           console.log(data.datlichMoi);
           $('#tuongtac-line, #sdt-line, #call-line, #calendar-new-line, #calendar-about-line, #auris-line, #hieu-qua-line, #hieu-suat-line').myUnloading();
           $('#tuongtac-line, #sdt-line, #call-line, #calendar-new-line, #calendar-about-line, #auris-line, #hieu-qua-line, #hieu-suat-line').css('border', 'none');
           $('#tongTuongTac').html(addCommas(parseInt(data.tongTuongTac)));
           $('#sdtTong').html(addCommas(parseInt(data.sdtTong)));
           $('#sdtTongCall').html(addCommas(parseInt(data.sdtTongCall)));
           $('#datlichMoi').html(addCommas(parseInt(data.datlichMoi)));
           $('#khachDen').html(addCommas(parseInt(data.khachDen)));
           $('#lichHen').html(addCommas(parseInt(data.lichHen)));
           
           tuongTac(data.data.ngay_tuongtac, data.data.tuongtac);
           sdt(data.data.ngay_sdt, data.data.sdt);
           call(data.data.ngay_sdtCall, data.data.call);
           calendarNew(data.data.ngay_calendarNew, data.data.calendar_new);
           calendarAbout(data.data.ngay_calendarAbout, data.data.calendar_about);
           customerAuris(data.data.ngay_auris, data.data.auris);
           hieuQua(data.data.ngay, data.data.hieu_qua);
           hieuSuat(data.data.ngay, data.data.hieu_suat);
       }
    });
}
function hieuSuat(dataDate, dataTyle) {
    require.config({
        paths: {
            echarts: '../../../vendors/js/charts/echarts'
        }
    });
    require(
        [
            'echarts',
            'echarts/chart/bar',
            'echarts/chart/line',
            'echarts/chart/scatter',
            'echarts/chart/pie'
        ],
        
        function (ec) {
            var myChart = ec.init(document.getElementById('hieu-suat-line'));
            chartOptions = {
                
                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: {
                    data: ['Hiệu suất (Lịch mới / Tương tác)']
                },

                // Add custom colors
                color: ['#cc0963'],
                
                // Enable drag recalculate
                calculable: true,

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: dataDate,
                    axisLabel: {
                        rotate: '45'
                    }
                }],
    
                // Vertical axis
                yAxis : [
                    {
                        type : 'value',
                        name : 'Tỷ lệ',
                        axisLabel : {
                            formatter: '{value}%'
                        },
                        data: dataTyle,
                    },
                ],
                
                // Add series
                series : [
                    {
                        name:'Hiệu suất (Lịch mới / Tương tác)',
                        type:'bar',
                        data: dataTyle,
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

            myChart.setOption(chartOptions);

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
}

function hieuQua(dataDate, dataTyle) {
    require.config({
        paths: {
            echarts: '../../../vendors/js/charts/echarts'
        }
    });
    require(
        [
            'echarts',
            'echarts/chart/bar',
            'echarts/chart/line',
            'echarts/chart/scatter',
            'echarts/chart/pie'
        ],
        
        function (ec) {
            var myChart = ec.init(document.getElementById('hieu-qua-line'));
            chartOptions = {
                
                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: {
                    data: ['Hiệu quả (Khách đến / Lịch hẹn)']
                },

                // Add custom colors
                color: ['#cc0963'],
                
                // Enable drag recalculate
                calculable: true,

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: dataDate,
                    axisLabel: {
                        rotate: '45'
                    }
                }],
    
                // Vertical axis
                yAxis : [
                    {
                        type : 'value',
                        name : 'Tỷ lệ',
                        axisLabel : {
                            formatter: '{value}%'
                        },
                        data: dataTyle,
                    },
                ],
                
                // Add series
                series : [
                    {
                        name:'Hiệu quả (Khách đến / Lịch hẹn)',
                        type:'bar',
                        data: dataTyle,
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

            myChart.setOption(chartOptions);

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
}

function tuongTac(dataDate, dataTuongtac) {
    require.config({
        paths: {
            echarts: '../../../vendors/js/charts/echarts'
        }
    });
    require(
        [
            'echarts',
            'echarts/chart/bar',
            'echarts/chart/line',
            'echarts/chart/scatter',
            'echarts/chart/pie'
        ],
        
        function (ec) {
            var myChart = ec.init(document.getElementById('tuongtac-line'));
            chartOptions = {
                
                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: {
                    data: ['Tương tác']
                },

                // Add custom colors
                color: ['#00A5A8'],
                
                // Enable drag recalculate
                calculable: true,

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: dataDate,
                    axisLabel: {
                        rotate: '45'
                    }
                }],
    
                // Vertical axis
                yAxis : [
                    {
                        type : 'value',
                        name : 'Tương tác',
                        axisLabel : {
                            formatter: '{value}'
                        },
                        data: dataTuongtac,
                    },
                ],
                
                // Add series
                series : [
                    {
                        name:'Tương tác',
                        type:'bar',
                        data: dataTuongtac,
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

            myChart.setOption(chartOptions);

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
}

function sdt(dataDate, dataSdt) {
    require.config({
        paths: {
            echarts: '../../../vendors/js/charts/echarts'
        }
    });
    require(
        [
            'echarts',
            'echarts/chart/bar',
            'echarts/chart/line',
            'echarts/chart/scatter',
            'echarts/chart/pie'
        ],
        
        function (ec) {
            var myChart = ec.init(document.getElementById('sdt-line'));
            chartOptions = {
                
                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: {
                    data: ['Số điện thoại']
                },

                // Add custom colors
                color: ['#00A5A8'],
                
                // Enable drag recalculate
                calculable: true,

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: dataDate,
                    axisLabel: {
                        rotate: '45'
                    }
                }],
    
                // Vertical axis
                yAxis : [
                    {
                        type : 'value',
                        name : 'Số điện thoại',
                        axisLabel : {
                            formatter: '{value}'
                        },
                        data: dataSdt,
                    },
                ],
                
                // Add series
                series : [
                    {
                        name:'Số điện thoại',
                        type:'bar',
                        data: dataSdt,
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

            myChart.setOption(chartOptions);

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
}

function call(dataDate, dataCall) {
    require.config({
        paths: {
            echarts: '../../../vendors/js/charts/echarts'
        }
    });
    require(
        [
            'echarts',
            'echarts/chart/bar',
            'echarts/chart/line',
            'echarts/chart/scatter',
            'echarts/chart/pie'
        ],
        
        function (ec) {
            var myChart = ec.init(document.getElementById('call-line'));
            chartOptions = {
                
                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: {
                    data: ['Gọi được']
                },

                // Add custom colors
                color: ['#00A5A8'],
                
                // Enable drag recalculate
                calculable: true,

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: dataDate,
                    axisLabel: {
                        rotate: '45'
                    }
                }],
    
                // Vertical axis
                yAxis : [
                    {
                        type : 'value',
                        name : 'Gọi được',
                        axisLabel : {
                            formatter: '{value}'
                        },
                        data: dataCall,
                    },
                ],
                
                // Add series
                series : [
                    {
                        name:'Gọi được',
                        type:'bar',
                        data: dataCall,
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

            myChart.setOption(chartOptions);

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
}

function calendarNew(dataDate, dataCalendarNew) {
    require.config({
        paths: {
            echarts: '../../../vendors/js/charts/echarts'
        }
    });
    require(
        [
            'echarts',
            'echarts/chart/bar',
            'echarts/chart/line',
            'echarts/chart/scatter',
            'echarts/chart/pie'
        ],
        
        function (ec) {
            var myChart = ec.init(document.getElementById('calendar-new-line'));
            chartOptions = {
                
                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: {
                    data: ['Lịch mới']
                },

                // Add custom colors
                color: ['#00A5A8'],
                
                // Enable drag recalculate
                calculable: true,

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: dataDate,
                    axisLabel: {
                        rotate: '45'
                    }
                }],
    
                // Vertical axis
                yAxis : [
                    {
                        type : 'value',
                        name : 'Lịch mới',
                        axisLabel : {
                            formatter: '{value}'
                        },
                        data: dataCalendarNew,
                    },
                ],
                
                // Add series
                series : [
                    {
                        name:'Lịch mới',
                        type:'bar',
                        data: dataCalendarNew,
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

            myChart.setOption(chartOptions);

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
}

// Lịch trong khoảng thời gian (time_lichhen)
function calendarAbout(dataDate, dataCalendarAbout) {
    require.config({
        paths: {
            echarts: '../../../vendors/js/charts/echarts'
        }
    });
    require(
        [
            'echarts',
            'echarts/chart/bar',
            'echarts/chart/line',
            'echarts/chart/scatter',
            'echarts/chart/pie'
        ],
        
        function (ec) {
            var myChart = ec.init(document.getElementById('calendar-about-line'));
            chartOptions = {
                
                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: {
                    data: ['Lịch hẹn']
                },

                // Add custom colors
                color: ['#00A5A8'],
                
                // Enable drag recalculate
                calculable: true,

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: dataDate,
                    axisLabel: {
                        rotate: '45'
                    }
                }],
    
                // Vertical axis
                yAxis : [
                    {
                        type : 'value',
                        name : 'Lịch hẹn',
                        axisLabel : {
                            formatter: '{value}'
                        },
                        data: dataCalendarAbout,
                    },
                ],
                
                // Add series
                series : [
                    {
                        name:'Lịch hẹn',
                        type:'bar',
                        data: dataCalendarAbout,
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

            myChart.setOption(chartOptions);

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
}

function customerAuris(dataDate, dataAuris) {
    require.config({
        paths: {
            echarts: '../../../vendors/js/charts/echarts'
        }
    });
    require(
        [
            'echarts',
            'echarts/chart/bar',
            'echarts/chart/line',
            'echarts/chart/scatter',
            'echarts/chart/pie'
        ],
        
        function (ec) {
            var myChart = ec.init(document.getElementById('auris-line'));
            chartOptions = {
                
                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: {
                    data: ['Khách đến']
                },

                // Add custom colors
                color: ['#00A5A8'],
                
                // Enable drag recalculate
                calculable: true,

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: dataDate,
                    axisLabel: {
                        rotate: '45'
                    }
                }],
    
                // Vertical axis
                yAxis : [
                    {
                        type : 'value',
                        name : 'Khách đến',
                        axisLabel : {
                            formatter: '{value}'
                        },
                        data: dataAuris,
                    },
                ],
                
                // Add series
                series : [
                    {
                        name:'Khách đến',
                        type:'bar',
                        data: dataAuris,
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

            myChart.setOption(chartOptions);

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
}

JS;

$this->registerJs($script, \yii\web\View::POS_END);
