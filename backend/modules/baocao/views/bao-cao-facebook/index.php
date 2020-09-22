<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 22-Apr-19
 * Time: 3:31 PM
 */

use backend\modules\customer\models\Dep365CustomerOnline;
use yii\helpers\Html;
use common\models\User;

$this->title = "Biểu đồ Facebook";

$this->registerCssFile('/vendors/css/charts/morris.css');
$tongTien = 0;
$tongTuongtac = 0;
$sdtTong = 0;
$calendarTotal = 0;

$user = new \backend\modules\user\models\User();
$roleUser = $user->getRoleName(Yii::$app->user->id);
$roleDev = User::USER_DEVELOP;
$roleAdmin = User::USER_ADMINISTRATOR;
$roleManagerKiemSoat = User::USER_MANAGER_KIEM_SOAT;
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
                        <h4 class="card-title"><?= $this->title; ?></h4>
                        <hr>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3 col-4 mb-1">
                                    <p>Tổng tiền chạy: <span
                                                id="tongMoney"><?= number_format($tongTien, 0, ',', '.'); ?></span>
                                        VND</p>
                                    <p>Tổng tương tác: <span
                                                id="tuongTacTong"><?= number_format($tongTuongtac, 0, ',', '.'); ?></span>
                                    </p>
                                </div>
                                <?php
                                if ($roleUser == $roleDev || $roleUser == $roleAdmin || $roleUser == User::USER_MANAGER_KIEM_SOAT) {
                                    ?>
                                    <div class="col-md-3 col-3 mb-1">
                                        <p>Tổng số điện thoại: <span
                                                    id="sdtTong"><?= number_format($sdtTong, 0, ',', '.'); ?></span>
                                        </p>
                                        <p>Tổng số lịch: <span
                                                    id="calendar-baocao"><?= number_format($calendarTotal, 0, ',', '.'); ?></span>
                                        </p>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>

                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-3 col-sm-3 col-md-3">
                                    <div class="input-group">
                                        <input type="text"
                                               class="form-control filter-data-from-facebook-report">
                                        <div class="input-group-append">
                                        <span class="input-group-text">
                                            <span class="fa fa-calendar"></span>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if ($roleUser == $roleDev) {
                                    ?>
                                    <div class="col-xl-3 col-lg-3 col-3 col-sm-3 col-md-3">
                                        <div class="input-group">
                                            <?= Html::dropDownList(
                                        'manager-ads',
                                        '',
                                        $ads,
                                        ['class' => 'form-control', 'prompt' => 'Tất cả...', 'id' => 'manager-ads']
                                    ); ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="col-xl-3 col-lg-3 col-3 col-sm-3 col-md-3">
                                    <div class="input-group">
                                        <?= Html::dropDownList(
                                    'khu-vuc',
                                    '',
                                    $khuVuc,
                                    ['class' => 'form-control', 'prompt' => 'Khu vực...', 'id' => 'khu-vuc']
                                ); ?>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-3 col-sm-3 col-md-3">
                                    <div class="input-group">
                                        <?= Html::dropDownList(
                                                'san-pham',
                                                '',
                                                $sanPham,
                                                ['class' => 'form-control', 'prompt' => 'Sản phẩm...', 'id' => 'san-pham']
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
                                        <div class="card">
                                            <div class="card-content collapse show">
                                                <div class="card-body">
                                                    <div id="tuongtac-line" class="height-400 echart-container"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    if ($roleUser == $roleDev || $roleUser == $roleAdmin || $roleUser == User::USER_MANAGER_KIEM_SOAT) {
                                        ?>
                                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                            <div class="card">
                                                <div class="card-content collapse show">
                                                    <div class="card-body">
                                                        <div id="sdt-line" class="height-400 echart-container"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                            <div class="card">
                                                <div class="card-content collapse show">
                                                    <div class="card-body">
                                                        <div id="calendar-line"
                                                             class="height-400 echart-container"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                            <div class="card">
                                                <div class="card-content collapse show">
                                                    <div class="card-body">
                                                        <div id="sdt-tt-line" class="height-400 echart-container"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                            <div class="card">
                                                <div class="card-content collapse show">
                                                    <div class="card-body">
                                                        <div id="lich-sdt-line"
                                                             class="height-400 echart-container"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                            <div class="card">
                                                <div class="card-content collapse show">
                                                    <div class="card-body">
                                                        <div id="lich-tt-line"
                                                             class="height-400 echart-container"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
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
$urlSdt = \yii\helpers\Url::toRoute('get-data');

$this->registerJsFile('/vendors/js/charts/echarts/echarts.js', ['depends' => 'yii\web\JqueryAsset']);

$script = <<< JS
var d = new Date();
day = d.getDate();
y = d.getFullYear();
m = d.getMonth();

var startDateReport = '01-'+ (m +1)+'-'+y;
var endDateReport = day +'-'+ (m +1) +'-'+y;

$('.filter-data-from-facebook-report').daterangepicker({
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

$('body').on('change', '#manager-ads, #khu-vuc, #san-pham, .filter-data-from-facebook-report', function() {
    callData();
});

$(document).ready(function() {
    $(window).ready(function(){
        callData();
    });
});

function callData() {
    $('#sdt-line, #tuongtac-line, #calendar-line, #sdt-tt-line, #lich-sdt-line, #lich-tt-line').css('border', '1px solid #CDCDCD');
    $('#sdt-line, #tuongtac-line, #calendar-line, #sdt-tt-line, #lich-sdt-line, #lich-tt-line').myLoading({msg:'Đang tải dữ liệu...'});
    ads = $('#manager-ads').val();
    loc = $('#khu-vuc').val();
    sanpham = $('#san-pham').val();
    $.ajax({
       url: '$urlSdt',
       method: "POST",
       dataType: "json",
       data:{"startDateReport": startDateReport, "endDateReport":endDateReport, "ads": ads, 'loc':loc, 'sanpham': sanpham},
       success: function (data) {
           console.log(data.data.money_sodienthoai);
           $('#sdt-line, #tuongtac-line, #calendar-line, #sdt-tt-line, #lich-sdt-line, #lich-tt-line').myUnloading();
           $('#sdt-line, #tuongtac-line, #calendar-line, #sdt-tt-line, #lich-sdt-line, #lich-tt-line').css('border', 'none');
           $('#tongMoney').html(addCommas(parseInt(data.tongMeny)));
           $('#tuongTacTong').html(addCommas(parseInt(data.tuongTacTong)));
           $('#sdtTong').html(addCommas(parseInt(data.sdtTong)));
           $('#calendar-baocao').html(addCommas(parseInt(data.calendarTotal)));
           tuongTacVSgiaTuongTac(data.data.ngay_chay, data.data.tuong_tac, data.data.money_tuongtac);
           if('$roleUser' == '$roleDev' || '$roleUser' == '$roleAdmin' || '$roleUser' == '$roleManagerKiemSoat') {
               soDienThoaiVSgiaSdt(data.data.ngay_chay, data.data.so_dien_thoai, data.data.money_sodienthoai);
               lichVSGiaLich(data.data.ngay_chay, data.data.lich_hen, data.data.money_lichhen);
               SdtVSTuongTac(data.data.ngay_chay, data.data.sdt_tuongtac);
               lichVSsdt(data.data.ngay_chay, data.data.lich_sdt);
               lichVStuongtac(data.data.ngay_chay, data.data.lich_tuongtac);
           }
       }
    });
}

function tuongTacVSgiaTuongTac(dataDate, dataTuongTac, dataGiaTuongTac) {
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
                    data: ['Tương tác', 'Giá 1 tương tác (VND)']
                },

                // Add custom colors
                color: ['#00A5A8', '#FF4558'],
                
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
                        data: dataTuongTac,
                    },
                    {
                        type : 'value',
                        name : 'Giá 1 tương tác (VND)',
                        axisLabel : {
                            formatter: function (value, index) {
                                return addCommas(parseInt(value));
                            }
                        },
                        data:dataGiaTuongTac,
                    },
                ],
                
                // Add series
                series : [
                    {
                        name:'Tương tác',
                        type:'bar',
                        data: dataTuongTac,
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
                        name:'Giá 1 tương tác (VND)',
                        type:'line',
                        yAxisIndex: 1,
                        data:dataGiaTuongTac,
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

function soDienThoaiVSgiaSdt(dataDate, dataSdt, dataGiaSdt) {
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
                    data: ['Số điện thoại', 'Giá 1 SDT (VND)']
                },

                // Add custom colors
                color: ['#00A5A8', '#FF4558'],
                
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
                    {
                        type : 'value',
                        name : 'Giá 1 SDT (VND)',
                        axisLabel : {
                            formatter: function (value, index) {
                                return addCommas(parseInt(value));
                            }
                        },
                        data:dataGiaSdt,
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
                    },
                    {
                        name:'Giá 1 SDT (VND)',
                        type:'line',
                        yAxisIndex: 1,
                        data:dataGiaSdt,
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

function lichVSGiaLich(dataDate, dataLich, dataGiaLich) {
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
            var myChart = ec.init(document.getElementById('calendar-line'));
            chartOptions = {
                
                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: {
                    data: ['Lịch', 'Giá 1 lịch (VND)']
                },

                // Add custom colors
                color: ['#00A5A8', '#FF4558'],
                
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
                        name : 'Lịch',
                        axisLabel : {
                            formatter: '{value}'
                        },
                        data: dataLich,
                    },
                    {
                        type : 'value',
                        name : 'Giá 1 lịch (VND)',
                        axisLabel : {
                            formatter: function (value, index) {
                                return addCommas(parseInt(value));
                            }
                        },
                        data:dataGiaLich,
                    },
                ],
                
                // Add series
                series : [
                    {
                        name:'Lịch',
                        type:'bar',
                        data: dataLich,
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
                        name:'Giá 1 lịch (VND)',
                        type:'line',
                        yAxisIndex: 1,
                        data:dataGiaLich,
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

function SdtVSTuongTac(dataDate, dataSdtVSTuongTac) {
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
            var myChart = ec.init(document.getElementById('sdt-tt-line'));
            chartOptions = {
                
                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: {
                    data: ['SDT/Tương Tác']
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
                        name : 'Tỷ lệ',
                        axisLabel : {
                            formatter: '{value}%'
                        },
                        data: dataSdtVSTuongTac,
                    },
                ],
                
                // Add series
                series : [
                    {
                        name:'SDT/Tương Tác',
                        type:'bar',
                        data: dataSdtVSTuongTac,
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

function lichVSsdt(dataDate, dataLichVSSdt) {
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
            var myChart = ec.init(document.getElementById('lich-sdt-line'));
            chartOptions = {
                
                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: {
                    data: ['Lịch/SDT']
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
                        name : 'Tỷ lệ',
                        axisLabel : {
                            formatter: '{value}%'
                        },
                        data: dataLichVSSdt,
                    },
                ],
                
                // Add series
                series : [
                    {
                        name:'Lịch/SDT',
                        type:'bar',
                        data: dataLichVSSdt,
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

function lichVStuongtac(dataDate, dataLichVSTuongtac) {
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
            var myChart = ec.init(document.getElementById('lich-tt-line'));
            chartOptions = {
                
                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: {
                    data: ['Lịch/Tương tác']
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
                        name : 'Tỷ lệ',
                        axisLabel : {
                            formatter: '{value}%'
                        },
                        data: dataLichVSTuongtac,
                    },
                ],
                
                // Add series
                series : [
                    {
                        name:'Lịch/Tương tác',
                        type:'bar',
                        data: dataLichVSTuongtac,
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
