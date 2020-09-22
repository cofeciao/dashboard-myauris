<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 24-Apr-19
 * Time: 10:53 PM
 */

use yii\helpers\Html;

$this->title = 'Online hiệu suất';
$trungbinhTuongtacVSdt = 0;
$trungbinhTyLeGoiVSdt = 0;
$trungbinhLichMoiVGoiDuoc = 0;
$trungbinhKhachDenVLichHen= 0;
$trungbinhLichMoiVTuongtac = 0;
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
                                    <p>SDT / Tương tác: <span
                                                id="trungbinhTuongtacVSdt"><?= number_format($trungbinhTuongtacVSdt, 0, ',', '.'); ?></span>
                                    </p>
                                    <p>Gọi được / SDT: <span
                                                id="trungbinhTyLeGoiVSdt"><?= number_format($trungbinhTyLeGoiVSdt, 0, ',', '.'); ?></span>
                                    </p>
                                </div>
                                <div class="col-md-3 col-4 mb-1">
                                    <p>Lịch mới / gọi được: <span
                                                id="trungbinhLichMoiVGoiDuoc"><?= number_format($trungbinhLichMoiVGoiDuoc, 0, ',', '.'); ?></span>
                                    </p>
                                    <p>Khách đến / Lịch hẹn: <span
                                                id="trungbinhKhachDenVLichHen"><?= number_format($trungbinhKhachDenVLichHen, 0, ',', '.'); ?></span>
                                    </p>
                                </div>
                                <div class="col-md-3 col-4 mb-1">
                                    <p>Lịch mới / tương tác: <span
                                                id="trungbinhLichMoiVTuongtac"><?= number_format($trungbinhLichMoiVTuongtac, 0, ',', '.'); ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-3 col-sm-3 col-md-6 col-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control filter-data-from-online-hieu-suat">
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
                            </div>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <section id="combination-charts">
                                <!-- Column + Line Chart -->
                                <div class="row">
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div id="sdt-tuongtac-line"
                                             class="height-400 echart-container"></div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div id="call-sdt-line"
                                             class="height-400 echart-container"></div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div id="calendarnew-call-line"
                                             class="height-400 echart-container"></div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div id="customer-calendarnew-line"
                                             class="height-400 echart-container"></div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12">
                                        <div id="calendarnew-tuongtac-line"
                                             class="height-400 echart-container"></div>
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

var startDateReport = '01-'+ (+m + +1)+'-'+y;
var endDateReport = day +'-'+ (+m + +1) +'-'+y;

$('.filter-data-from-online-hieu-suat').daterangepicker({
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

$('body').on('change', '.filter-data-from-online-hieu-suat, #page-online, #loc-online', function() {
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
    $('#sdt-tuongtac-line, #call-sdt-line, #calendarnew-call-line, #customer-calendarnew-line, #calendarnew-tuongtac-line').css('border', '1px solid #CDCDCD');
    $('#sdt-tuongtac-line, #call-sdt-line, #calendarnew-call-line, #customer-calendarnew-line, #calendarnew-tuongtac-line').myLoading({msg:'Đang tải dữ liệu...'});
    pageonline = $('#page-online').val();
    loc = $('#loc-online').val();
    $.ajax({
       url: '$urlData',
       method: "POST",
       dataType: "json",
       data:{"startDateReport": startDateReport, "endDateReport":endDateReport, "pageonline": pageonline, "loc": loc},
       success: function (data) {
           console.log(data.data.sdt_tuongtac);
           $('#sdt-tuongtac-line, #call-sdt-line, #calendarnew-call-line, #customer-calendarnew-line, #calendarnew-tuongtac-line').myUnloading();
           $('#sdt-tuongtac-line, #call-sdt-line, #calendarnew-call-line, #customer-calendarnew-line, #calendarnew-tuongtac-line').css('border', 'none');
           
           $('#trungbinhTuongtacVSdt').html(addCommas(parseFloat(data.data.trungbinhTuongtacVSdt)) + '%');
           $('#trungbinhTyLeGoiVSdt').html(addCommas(parseFloat(data.data.trungbinhTyLeGoiVSdt)) + '%');
           $('#trungbinhLichMoiVGoiDuoc').html(addCommas(parseFloat(data.data.trungbinhLichMoiVGoiDuoc)) + '%');
           $('#trungbinhKhachDenVLichHen').html(addCommas(parseFloat(data.data.trungbinhKhachDenVLichHen)) + '%');
           $('#trungbinhLichMoiVTuongtac').html(addCommas(parseFloat(data.data.trungbinhLichMoiVTuongtac)) + '%');
           
           sdtTuongtac(data.nv, data.data.sdt_tuongtac);
           callSdt(data.nv, data.data.call_sdt);
           calendarnewCall(data.nv, data.data.calendarnew_call);
           customerCalendarnew(data.nv, data.data.customer_calendarnew);
           calendarnewTuongtac(data.nv, data.data.calendarnew_tuongtac);
       }
    });
}
function calendarnewTuongtac(dataNv, dataTyle) {
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
            var myChart = ec.init(document.getElementById('calendarnew-tuongtac-line'));
            chartOptions = {
                grid: {
                    x: 40,
                    x2: 40,
                    y: 35,
                    y2: 80
                },
                
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
                    data: dataNv,
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
                            formatter: '{value} %'
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

function customerCalendarnew(dataNv, dataTyle) {
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
            var myChart = ec.init(document.getElementById('customer-calendarnew-line'));
            chartOptions = {
                grid: {
                    x: 40,
                    x2: 40,
                    y: 35,
                    y2: 80
                },
                
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
                    data: dataNv,
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
                            formatter: '{value} %'
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

function calendarnewCall(dataNv, dataTyle) {
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
            var myChart = ec.init(document.getElementById('calendarnew-call-line'));
            chartOptions = {
                grid: {
                    x: 40,
                    x2: 40,
                    y: 35,
                    y2: 80
                },
                
                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: {
                    data: ['Tỷ lệ lịch mới / Gọi được']
                },

                // Add custom colors
                color: ['#00A5A8'],
                
                // Enable drag recalculate
                calculable: true,

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: dataNv,
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
                            formatter: '{value} %'
                        },
                        data: dataTyle,
                    },
                ],
                
                // Add series
                series : [
                    {
                        name:'Tỷ lệ lịch mới / Gọi được',
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

function callSdt(dataNv, dataTyle) {
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
            var myChart = ec.init(document.getElementById('call-sdt-line'));
            chartOptions = {
                grid: {
                    x: 40,
                    x2: 40,
                    y: 35,
                    y2: 80
                },
                
                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: {
                    data: ['Tỷ lệ gọi được / Số điện thoại']
                },

                // Add custom colors
                color: ['#00A5A8'],
                
                // Enable drag recalculate
                calculable: true,

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: dataNv,
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
                            formatter: '{value} %'
                        },
                        data: dataTyle,
                    },
                ],
                
                // Add series
                series : [
                    {
                        name:'Tỷ lệ gọi được / Số điện thoại',
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

function sdtTuongtac(dataNv, dataTyle) {
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
            var myChart = ec.init(document.getElementById('sdt-tuongtac-line'));
            chartOptions = {
                grid: {
                    x: 40,
                    x2: 40,
                    y: 35,
                    y2: 80
                },
                
                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: {
                    data: ['Tỷ lệ số điện thoại / Tương tác']
                },

                // Add custom colors
                color: ['#00A5A8'],
                
                // Enable drag recalculate
                calculable: true,

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: dataNv,
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
                            formatter: '{value} %'
                        },
                        data: dataTyle,
                    },
                ],
                
                // Add series
                series : [
                    {
                        name:'Tỷ lệ số điện thoại / Tương tác',
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

JS;

$this->registerJs($script, \yii\web\View::POS_END);
