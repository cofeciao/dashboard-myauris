<?php
use yii\web\JqueryAsset;
use yii\web\View;

$urlData = \yii\helpers\Url::toRoute('get-data');
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

$('body').on('change', '.filter-data-from-online-report, #id-online, #id-page, #id-location, #fail-dathen, #id-coso', function() {
    callData();
});

$(document).ready(function() {
    $(window).ready(function(){
        callData();
    });
});


function callData() {
    $('#overview-graph-lineChart').css('border', '1px solid #CDCDCD');
    $('#overview-graph-lineChart').myLoading({msg:'Đang tải dữ liệu...'});

   id_online = $('#id-online').val();
   id_location = $('#id-location').val();
   id_page = $('#id-page').val();
   fail_dathen  = $('#fail-dathen').val();
   id_coso  = $('#id-coso').val();
   $.ajax({
       url: '$urlData',
       method: "POST",
       dataType: "json",
       data:{
           "startDateReport": startDateReport, 
           "endDateReport":endDateReport, 
           "id_online": id_online, 
           "id_location": id_location, 
           "id_page": id_page, 
           "fail_dathen": fail_dathen, 
           "id_coso": id_coso 
       },
       success: function (data) {
           $('#overview-graph-lineChart').css('border', '1px solid #CDCDCD');
           $('#overview-graph-lineChart').myLoading({msg:'Đang tải dữ liệu...'});
           
           drawChart(data.data.date,data.data.result, data.data.lable);
       }
    });
}


function drawChart(dataDate, dataTyle, dataLable){
    
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
            var myChart = ec.init(document.getElementById('overview-graph-lineChart'));
            
            // Chart Options
            // ------------------------------
            chartOptions = {

                // Setup grid
                grid: {
                    x: 40,
                    x2: 20,
                    y: 35,
                    y2: 25
                },

                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: {
                    data: dataLable //['Email marketing', 'Advertising alliance','NGHIA']
                },
                // Add custom colors
                // color: ['#626E82', '#16D39A', '#2DCEE3', '#FF7588', '#626E82'],

                // Add horizontal axis
                xAxis: [{
                    type: 'category',
                    boundaryGap: false,
                    data: dataDate// ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']
                }],

                // Add vertical axis
                yAxis: [{
                    type: 'value'
                }],

                // Add series
                series: dataTyle
            };
            // Apply options

            myChart.setOption(chartOptions);
            // Resize chart

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
}

JS;
$this->registerJsFile('/vendors/js/charts/echarts/echarts.js', ['depends' => [JqueryAsset::class]]);
$this->registerJsFile('/vendors/js/charts/flot/jquery.flot.min.js', ['depends' => [JqueryAsset::class]]);
$this->registerJsFile('/vendors/js/charts/flot/jquery.flot.resize.js', ['depends' => [JqueryAsset::class]]);
$this->registerJsFile('/vendors/js/charts/flot/jquery.flot.time.js', ['depends' => [JqueryAsset::class]]);
$this->registerJsFile('/vendors/js/charts/flot/jquery.flot.selection.js', ['depends' => [JqueryAsset::class]]);
$this->registerJsFile('/vendors/js/charts/flot/jquery.flot.symbol.js', ['depends' => [JqueryAsset::class]]);
$this->registerJS($script, View::POS_END);
