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

$('body').on('change', '.filter-data-from-online-report, #direct-sale, #id-coso, #id-page, #id-phongkhamdichvu,  #id-trangthaidichvu', function() {
    callData();
});

$(document).ready(function() {
    $(window).ready(function(){
        callData();
    });
});


function callData() {
    // $(' #overview-miniPie').css('border', '1px solid #CDCDCD');
    // $(' #overview-miniPie').myLoading({msg:'Đang tải dữ liệu...'});

   id_coso = $('#id-coso').val();
   direct_sale = $('#direct-sale').val();
   id_page = $("#id-page").val();
   id_phongkhamdichvu = $("#id-phongkhamdichvu").val();
   id_trangthaidichvu = $("#id-trangthaidichvu").val();
   console.log(id_trangthaidichvu);
   
   $.ajax({
       url: '$urlData',
       method: "POST",
       dataType: "json",
       data:{
           "startDateReport": startDateReport, 
           "endDateReport":endDateReport, 
           "direct_sale": direct_sale, 
           "id_coso": id_coso, 
           "id_page" : id_page,
           "id_phongkhamdichvu" : id_phongkhamdichvu,
           "id_trangthaidichvu" : id_trangthaidichvu
       },
       success: function (data) {
            // $(' #overview-miniPie').myUnloading();
            // $(' #overview-miniPie').css('border', 'none');
           buildTable(data.data.dataQuery, data.data.listNameSanPham);
           // drawChart(data.data.date,data.data.result, data.data.lable);
           buildMiniPie(data.data.dataQuery, data.data.listNameSanPham);
       }
    });
}
function buildTable(dataQuery,listNameSanPham){
    var SUM_so_luong = SUM_tien = stt = 0;
    for (let i = 0; i < dataQuery.length; i++) {
        SUM_so_luong += dataQuery[i].so_luong;
        SUM_tien     += dataQuery[i].tien;
    }
    dataQuery = dataQuery.sort(function(a, b) {
        return b.tien - a.tien;
    }); // sort

    html = '';
    html += '<tr class="font-weight-bold" >';
    html += '<td></td>';
    html += '<td class="text-center" >Tổng</td>';
    html += '<td class="text-right" >'+numeral(SUM_so_luong).format('0,0')+'</td>';
    html += '</div></td>';
    html += '<td class="text-right">'+numeral(SUM_tien).format('0,0')+'</td>';
    html += '</tr>';
    
    for (let i = 0; i < dataQuery.length; i++) {
        
        stt++;
        var tien = numeral(dataQuery[i].tien);
        var so_luong = numeral(dataQuery[i].so_luong);
        objSanPham = listNameSanPham.find(function(element){
            return element.san_pham === dataQuery[i].san_pham;
        });
        nameSanPham = (objSanPham !== undefined) ? objSanPham.name : "";
        
        html += '<tr>';
        html += '<td>'+stt+'</td>';
        html += '<td>'+nameSanPham+'</td>';
        html += '<td class="text-right">'+so_luong.format('0,0')+'</td>';
        html += '</div></td>';
        html += '<td class="text-right">'+tien.format('0,0')+'</td>';
        html += '</tr>';
    }
    $("#tong-san-pham").html("TỔNG : "+ numeral(SUM_so_luong).format('0,0'));
    $("#table-report-sanpham").html(html);
}

function buildMiniPie(dataQuery, listNameSanPham){
    
    dataQuery = dataQuery.sort(function(a, b) {
        return b.so_luong - a.so_luong;
    }); // sort
    tableData = [];
    dataLable = [];
    var sum = 0;
    if(dataQuery.length > 6){
        for (let i = 0; i < dataQuery.length; i++) {
            if(i <= 5){
                objSanPham = listNameSanPham.find(function(element){
                    return element.san_pham === dataQuery[i].san_pham;
                });
                nameSanPham = (objSanPham !== undefined) ? objSanPham.name : "";
                tableData.push({
                    value :  dataQuery[i].so_luong,
                    name : nameSanPham
                });
                dataLable.push(nameSanPham);
            }else{
                sum += dataQuery[i].so_luong;
            }
        }
        tableData.push({
            value :  sum,
            name : "Khác"
        });
        dataLable.push("Khác");
    }else{
        for (let i = 0; i < dataQuery.length; i++) {
            objSanPham = listNameSanPham.find(function(element){
                return element.san_pham === dataQuery[i].san_pham;
            });
            nameSanPham = (objSanPham !== undefined) ? objSanPham.name : "";
            tableData.push({
                value :  dataQuery[i].so_luong,
                name : nameSanPham
            });
            dataLable.push(nameSanPham);
        }
    }
    
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
            'echarts/chart/funnel'
        ],

        // Charts setup
        function (ec) {
            // Initialize chart
            // ------------------------------
            var myChart = ec.init(document.getElementById('overview-miniPie'));

            // Chart Options
            // ------------------------------
            chartOptions = {

                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },

                // Add legend
                legend: {
                    // orient: 'vertical',
                    // x: 'left',
                    data: dataLable//['Firefox', 'Chrome','nghia']
                },

                // Add custom colors
               // color: ['#FF4558', '#16D39A','#16D39B'],

                // Enable drag recalculate
                calculable: true, 
                
                // Display toolbox
                toolbox: {
                    show: true,
                    orient: 'vertical',
                    feature: {
                        restore: {
                            show: true,
                            title: 'Restore'
                        },
                        saveAsImage: {
                            show: true,
                            title: 'Same as image',
                            lang: ['Save']
                        }
                    }
                },

                // Add series
                series: [{
                    name: 'Lý do chốt fail',
                    type: 'pie',
                    radius: '70%',
                    center: ['50%', '57.5%'],
                    data: tableData
                    
                }]
            };

            // Apply options
            // ------------------------------

            myChart.setOption(chartOptions);

            // Resize chart
            // ------------------------------

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
                // color: ['#F98E76', '#16D39A', '#2DCEE3', '#FF7588', '#626E82'],

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
