<?php

/**
 * Created by PhpStorm.
 * User: USER
 * Date: 22-Apr-19
 * Time: 3:31 PM
 */


$this->title = "Biểu đồ Adwords";


?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Biểu đồ Adsword</h4>
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                        <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                        <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                        <li><a data-action="close"><i class="ft-x"></i></a></li>
                    </ul>

                </div>
            </div>
            <div class="card-content collapse show">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-3 col-sm-3 col-md-3">
                            <div class="input-group">
                                <input type="text" class="form-control filter-data-from-adsword-report">
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>


                        <!--select post type, select metadata-->
                        <!--<div class="col-xl-3 col-lg-3 col-3 col-sm-3 col-md-3">
                                <div class="input-group">
                                    <?php /*= Html::dropDownList('post_type', '',
                                        Yii::$app->controller->module->params['adsword-type'],
                                        ['class' => 'form-control', 'id' => 'post_type']); */ ?>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-3 col-sm-3 col-md-3">
                                <div class="input-group">
                                    <?php /*= Html::dropDownList('meta_name', '',
                                        Yii::$app->controller->module->params['meta_name'][1],
                                        [
                                            'class'         => 'form-control',
                                            'data-metadata' => json_encode(Yii::$app->controller->module->params['meta_name']),
                                            'id'            => 'meta_name'
                                        ]); */ ?>
                                </div>
                            </div>-->
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4">
                            <div id="ketqua-chart" class="height-400 echart-container"></div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div id="tonglich-pie" class="height-400 echart-container"></div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <div id="dathen-pie" class="height-400 echart-container"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-md-12 text-center">
                            <div style="font-size: 20px;padding-bottom:10px">
                                Tổng Tiền: <span class="segment-header-context-text sum_money" style="font-size: 24px;">
                                    0 đ
                                </span>

                            </div>

                        </div>

                        <div class="col-lg-12 col-md-12">
                            <div id="column-chart" class="height-400 echart-container"></div>
                        </div>
                    </div>
                    <div class="row table-keyword hide">

                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <h1 class="display-4">Đặt hẹn</h1>
                            <div id="dathen-column" class="height-400 echart-container"></div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <h1 class="display-4">Zalo</h1>
                            <div id="zalo-column" class="height-400 echart-container"></div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <h1 class="display-4">Hotline</h1>
                            <div id="hotline-column" class="height-400 echart-container"></div>
                        </div>
                        <?php /*<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                <h1 class="display-4">Số Điện Thoại</h1>
                                <div id="aff-column" class="height-400 echart-container"></div>
                            </div>

                        </div>
                        <div class="row">*/ ?>
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <h1 class="display-4">Đặt Lịch</h1>
                            <div id="website-column" class="height-400 echart-container"></div>
                        </div>
                        <?php /*<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                <h1 class="display-4">Message Website</h1>
                                <div id="messWeb-column" class="height-400 echart-container"></div>
                            </div>*/ ?>
                    </div>

                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <h1 class="display-4">Hotline SEO</h1>
                            <div id="hotlineSEO-column" class="height-400 echart-container"></div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
$this->registerJsFile('/vendors/js/charts/echarts/echarts.js', ['depends' => 'yii\web\JqueryAsset']);
$urlSdt = \yii\helpers\Url::toRoute('get-data');
$script = <<<JS



var d = new Date();
day = d.getDate();
y = d.getFullYear();
m = d.getMonth();

var startDateReport = '01-'+ (m +1)+'-'+y;
var endDateReport = day +'-'+ (m +1) +'-'+y;

$('.filter-data-from-adsword-report').daterangepicker({
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
$('body').on('change', ' #meta_name, .filter-data-from-adsword-report', function() {
    callData();
});
$('body').on('change','#post_type',function(e){

let post_type = $(this).val();
let meta_select = $('#meta_name');
let option = meta_select.data('metadata');
/*
{amount_money: "Số tiền", appearance: "Hiển thị", click: "Click", cpc: "Cpc"}
* option[post_type]
* */
meta_select.empty(); // remove old options
$.each(option[post_type], function(key,value) {
  meta_select.append($("<option></option>")
     .attr("value", key).text(value));
});
    
    callData();
})
$(document).ready(function() {
    $(window).ready(function(){
                    callData();
    });
});

function callData() {
    var post_type = $('#post_type').val();
    var meta_name = $('#meta_name').val();
    
    $.ajax({
       url: '$urlSdt',
       method: "POST",
       data:{
           "startDateReport": startDateReport, 
           "endDateReport":endDateReport,
           "post_type":post_type,
           "meta_name":meta_name},
       success: function(data){
            window['abc']=data;
            console.log(data);
           drawChart(data);
           $('.table-keyword').html(data.tablekeyword);
       }
    });
}

function drawChart(data) {
    
    var color_single=["#fff600"];
    var color_result=["#0B5394","#3C78D8","#6D9EEB", "#A4C2F4" ,  "#EFEFEF"];
    var color_lich=['#7F6017','#BF9128','#F1C235' ];
    var color_dathen=['#274E13', '#38761D','#6AA84F'];

        var series_data={
            zalo_legend:{
              data: [data.dataSet.dathen.label, data.dataSet.fail.label, data.dataSet.kbm.label]
            },
            zalo_series:[
                    {
                        name:data.dataSet.dathen.label,
                        type:'bar',
                        stack:'status',
                        data:(data.dataSet.zalo.dathen.value===undefined || data.dataSet.zalo.dathen.value===null)?[]:Object.values(data.dataSet.zalo.dathen.value)
                    },
                    {
                        name:data.dataSet.fail.label,
                        type:'bar',
                        stack:'status',
                        data:[null,undefined].includes(data.dataSet.zalo.fail.value)?[]:Object.values(data.dataSet.zalo.fail.value)
                    },
                    {
                        name:data.dataSet.kbm.label,
                        type:'bar',
                        stack:'status',
                        data:[null,undefined].includes(data.dataSet.zalo.kbm.value)?[]:Object.values(data.dataSet.zalo.kbm.value)
                    },
                ],
            hotline_legend:{
                data: [data.dataSet.dathen.label, data.dataSet.fail.label, data.dataSet.kbm.label]
            },
            hotline_series:  [
                
                {
                    name:data.dataSet.dathen.label,
                    type:'bar',
                    stack:'status',
                    data:[null,undefined].includes(data.dataSet.hotline.dathen.value)?[]:Object.values(data.dataSet.hotline.dathen.value)
                },
                {
                    name:data.dataSet.fail.label,
                    type:'bar',
                    stack:'status',
                    data:[null,undefined].includes(data.dataSet.hotline.fail.value)?[]:Object.values(data.dataSet.hotline.fail.value)
                },
                {
                    name:data.dataSet.kbm.label,
                    type:'bar',
                    stack:'status',
                    data:[null,undefined].includes(data.dataSet.hotline.kbm.value)?[]:Object.values(data.dataSet.hotline.kbm.value)
                },
            ],

            hotlineSEO_legend:{
                data: [data.dataSet.dathen.label, data.dataSet.fail.label, data.dataSet.kbm.label]
            },
            hotlineSEO_series:  [
                
                {
                    name:data.dataSet.dathen.label,
                    type:'bar',
                    stack:'status',
                    data:[null,undefined].includes(data.dataSet.hotlineSEO.dathen.value)?[]:Object.values(data.dataSet.hotlineSEO.dathen.value)
                },
                {
                    name:data.dataSet.fail.label,
                    type:'bar',
                    stack:'status',
                    data:[null,undefined].includes(data.dataSet.hotlineSEO.fail.value)?[]:Object.values(data.dataSet.hotlineSEO.fail.value)
                },
                {
                    name:data.dataSet.kbm.label,
                    type:'bar',
                    stack:'status',
                    data:[null,undefined].includes(data.dataSet.hotlineSEO.kbm.value)?[]:Object.values(data.dataSet.hotlineSEO.kbm.value)
                },
            ],
            /*aff_legend:{
                data: [data.dataSet.dathen.label, data.dataSet.fail.label, data.dataSet.kbm.label]
            },
            aff_series:  [
                
                {
                    name:data.dataSet.dathen.label,
                    type:'bar',
                    stack:'status',
                    data:[null,undefined].includes(data.dataSet.affiliate.dathen.value)?[]:Object.values(data.dataSet.affiliate.dathen.value)
                },
                {
                    name:data.dataSet.fail.label,
                    type:'bar',
                    stack:'status',
                    data:[null,undefined].includes(data.dataSet.affiliate.fail.value)?[]:Object.values(data.dataSet.affiliate.fail.value)
                },
                {
                    name:data.dataSet.kbm.label,
                    type:'bar',
                    stack:'status',
                    data:[null,undefined].includes(data.dataSet.affiliate.kbm.value)?[]:Object.values(data.dataSet.affiliate.kbm.value)
                },
            ],*/
            website_legend:{
                data: [data.dataSet.dathen.label, data.dataSet.fail.label, data.dataSet.kbm.label]
            },
            website_series:  [
                
                {
                    name:data.dataSet.dathen.label,
                    type:'bar',
                    stack:'status',
                    data:[null,undefined].includes(data.dataSet.website.dathen.value)?[]:Object.values(data.dataSet.website.dathen.value)
                },
                {
                    name:data.dataSet.fail.label,
                    type:'bar',
                    stack:'status',
                    data:[null,undefined].includes(data.dataSet.website.fail.value)?[]:Object.values(data.dataSet.website.fail.value)
                },
                {
                    name:data.dataSet.kbm.label,
                    type:'bar',
                    stack:'status',
                    data:[null,undefined].includes(data.dataSet.website.kbm.value)?[]:Object.values(data.dataSet.website.kbm.value)
                },
            ],
            /*mess_legend:{
                data: [data.dataSet.dathen.label, data.dataSet.fail.label, data.dataSet.kbm.label]
            },
            mess_series:  [
            
                {
                    name:data.dataSet.dathen.label,
                    type:'bar',
                    stack:'status',
                    data:[null,undefined].includes(data.dataSet.mess.dathen.value)?[]:Object.values(data.dataSet.mess.dathen.value)
                },
                {
                    name:data.dataSet.fail.label,
                    type:'bar',
                    stack:'status',
                    data:[null,undefined].includes(data.dataSet.mess.fail.value)?[]:Object.values(data.dataSet.mess.fail.value)
                },
                {
                    name:data.dataSet.kbm.label,
                    type:'bar',
                    stack:'status',
                    data:[null,undefined].includes(data.dataSet.mess.kbm.value)?[]:Object.values(data.dataSet.mess.kbm.value)
                },
            ],*/
        };

    $('.sum_money').html([null,undefined].includes(data.dataSet.meta.value)?[]:((Object.values(data.dataSet.meta.value)).reduce((a, b) => parseFloat(a) + Math.round(parseFloat(b)))).toLocaleString("vi", {style: 'currency', currency: 'VND'}));
    
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
         // Charts setup
        function (ec) {

            // Initialize combined chart
            // ------------------------------
            var ketqua_Chart = ec.init(document.getElementById('ketqua-chart'));
            var columnChart = ec.init(document.getElementById('column-chart'));

            // Column Chart Options
            // ------------------------------
            columnChartOptions = {

                // Setup grid
                grid: {
                    x: 65,
                    x2: 47,
                    y: 35,
                    y2: 70
                },

                // Add tooltip
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    }
                },
                

                // Add custom colors
                color: ((color_single.concat(color_result)).concat(color_lich)).concat(color_dathen),

                // Add toolbox
                toolbox: {
                    show: true,
                    orient: 'vertical',
                    x: 'right',
                    y: 35,
                    feature: {
                        restore: {
                            show: true,
                            title: 'Restore'
                        },
                    }
                },
                // Enable drag recalculate
                calculable: false,

                // Add legend
                legend: {
                    data:[data.dataSet.meta.label, data.dataSet.website.label/*, data.dataSet.affiliate.label*/,data.dataSet.hotline.label/*,data.dataSet.mess.label*/,data.dataSet.zalo.label,data.dataSet.hotlineSEO.label, data.dataSet.dathen.label, data.dataSet.fail.label, data.dataSet.kbm.label,data.dataSet.dathen.status.lam.label, data.dataSet.dathen.status.khonglam.label, data.dataSet.dathen.status.khongden.label],
                },
                
                // Horizontal axis
                 xAxis: [{
                    type: 'category',
                    data: data.dataDate===undefined?[]:data.dataDate.map(function(e){
                    var a=new Date(e*1000)
                    var options = { year: 'numeric', month: 'numeric', day: 'numeric' };
                    return a.toLocaleDateString('vi-VN', options);
                    }),
                    axisLabel: {
                        rotate: '45'
                    }
                }],


                // Vertical axis
                yAxis: [{
                    type: 'value',
                    name:'Kết quả',
                    splitArea: {show: true},
                    position: 'right'
                },
                {
                        type : 'value',
                        name : data.dataSet.meta.label,
                        axisLabel : {
                            formatter: '{value}'
                        }
                    }
                ],

                // Add series
                series: [
                    {
                        name: data.dataSet.meta.label,
                        type: 'line',
                        stack: 'Lonely',
                        barGap: 0,
                        yAxisIndex: 1,
                        data: [null,undefined].includes(data.dataSet.meta.value)?[]:Object.values(data.dataSet.meta.value),
                    },
                    {
                        name: data.dataSet.website.label,
                        type: 'bar',
                        stack: 'Total',
                        data: [null,undefined].includes(data.dataSet.website.value)?[]:Object.values(data.dataSet.website.value),
                    },
                     /*{
                        name: data.dataSet.affiliate.label,
                        type: 'bar',
                        stack: 'Total',
                        data: [null,undefined].includes(data.dataSet.affiliate.value)?[]:Object.values(data.dataSet.affiliate.value),
                    },*/
                       {
                        name: data.dataSet.hotline.label,
                        type: 'bar',
                        stack: 'Total',
                        data: [null,undefined].includes(data.dataSet.hotline.value)?[]:Object.values(data.dataSet.hotline.value),
                    },
                     /*{
                        name: data.dataSet.mess.label,
                        type: 'bar',
                        stack: 'Total',
                        data: [null,undefined].includes(data.dataSet.mess.value)?[]:Object.values(data.dataSet.mess.value),
                    },*/
                    {
                        name: data.dataSet.zalo.label,
                        type: 'bar',
                        stack: 'Total',
                        data: [null,undefined].includes(data.dataSet.zalo.value)?[]:Object.values(data.dataSet.zalo.value),
                    },

                    {
                        name: data.dataSet.hotlineSEO.label,
                        type: 'bar',
                        stack: 'Total',
                        data: [null,undefined].includes(data.dataSet.hotlineSEO.value)?[]:Object.values(data.dataSet.hotlineSEO.value),
                    },
                   
                 
                    //status
                     {
                        name:data.dataSet.dathen.label,
                        type:'bar',
                        stack:'status',
                        data: [null,undefined].includes(data.dataSet.dathen.value)?[]:Object.values(data.dataSet.dathen.value)
                    },
                    {
                        name:data.dataSet.fail.label,
                        type:'bar',
                        stack:'status',
                        data:[null,undefined].includes(data.dataSet.fail.value)?[]:Object.values(data.dataSet.fail.value)
                    },
                    {
                        name:data.dataSet.kbm.label,
                        type:'bar',
                        stack:'status',
                        data:[null,undefined].includes(data.dataSet.kbm.value)?[]:Object.values(data.dataSet.kbm.value)
                    },
                    {
                        name:data.dataSet.dathen.status.lam.label,
                        type:'bar',
                        stack:'detaildathen',
                        data:(data.dataSet.dathen.status.lam.value===undefined || data.dataSet.dathen.status.lam.value===null)?[]:Object.values(data.dataSet.dathen.status.lam.value)
                    },
                    {
                        name:data.dataSet.dathen.status.khonglam.label,
                        type:'bar',
                        stack:'detaildathen',
                        data:(data.dataSet.dathen.status.khonglam.value===undefined || data.dataSet.dathen.status.khonglam.value===null)?[]:Object.values(data.dataSet.dathen.status.khonglam.value)
                    },
                    {
                        name:data.dataSet.dathen.status.khongden.label,
                        type:'bar',
                        stack:'detaildathen',
                        data:(data.dataSet.dathen.status.khongden.value===undefined || data.dataSet.dathen.status.khongden.value===null)?[]:Object.values(data.dataSet.dathen.status.khongden.value)
                    },
                ]
            };
            
            
            // Initialize detail chart
            var tonglich_pie= ec.init(document.getElementById('tonglich-pie'));

            
            // Pie Chart Options
            // ------------------------------
             var pieChart_SubSum={ zalo:[null,undefined].includes(data.dataSet.zalo.value)?0:Object.values(data.dataSet.zalo.value).reduce((a, b) => parseFloat(a) + Math.round(parseFloat(b))), 
                                 /*mess:[null,undefined].includes(data.dataSet.mess.value)?0:Object.values(data.dataSet.mess.value).reduce((a, b) => parseFloat(a) + Math.round(parseFloat(b))),*/  
                                 website:[null,undefined].includes(data.dataSet.website.value)?0:Object.values(data.dataSet.website.value).reduce((a, b) => parseFloat(a) + Math.round(parseFloat(b))),  
                                 /*affiliate:[null,undefined].includes(data.dataSet.affiliate.value)?0:Object.values(data.dataSet.affiliate.value).reduce((a, b) => parseFloat(a) + Math.round(parseFloat(b))),*/  
                                 hotline:[null,undefined].includes(data.dataSet.hotline.value)?0:Object.values(data.dataSet.hotline.value).reduce((a, b) => parseFloat(a) + Math.round(parseFloat(b))),
                                 // hotlineSEO
                                 hotlineSEO:[null,undefined].includes(data.dataSet.hotlineSEO.value)?0:Object.values(data.dataSet.hotlineSEO.value).reduce((a, b) => parseFloat(a) + Math.round(parseFloat(b))),  };

            ketqua_ChartOptions = {

                // Add title
                title: {
                    text: 'Kết quả',
                    // subtext: 'Open source data',
                    x: 'center'
                },

                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },

                // Add legend
                legend: {
                    orient: 'vertical',
                    x: 'left',
                    center: ['50%', '57.5%'],
                    data:[data.dataSet.website.label
                    /*, data.dataSet.affiliate.label*/,
                    data.dataSet.hotline.label
                    /*,data.dataSet.mess.label*/,
                    data.dataSet.zalo.label,
                    data.dataSet.hotlineSEO.label],
                },

                // Add custom colors
                color: color_result,

                // Enable drag recalculate
                calculable: false,

                // Add series
                series: [{
                    name: 'Kết Quả',
                    type: 'pie',
                    radius: '50%',
                   center: ['50%', '57.5%'],
                   itemStyle: {
                        normal: {
                            label : {
                                show: true,
                                position: 'outside',
                                formatter: function(data){
                                    return data.value + "("+data.percent+'%)';
                                }
                            }
                        }
                    },
                    data: [
                        {
                            value: pieChart_SubSum.zalo, 
                            name: data.dataSet.zalo.label
                        },
                        /*{
                            value: pieChart_SubSum.mess,  
                            name: data.dataSet.mess.label
                            },*/
                        {
                            value: pieChart_SubSum.website,  
                            name: data.dataSet.website.label
                            },
                        /*{
                            value: pieChart_SubSum.affiliate,  
                            name: data.dataSet.affiliate.label
                            },*/
                        {
                            value: pieChart_SubSum.hotline,  
                            name: data.dataSet.hotline.label
                        },
                        {
                            value: pieChart_SubSum.hotlineSEO,  
                            name: data.dataSet.hotlineSEO.label
                        }
                    ]
                }]
                
            };

            // Chart Options
            // ------------------------------
            tonglich_chartOptions = {

                // Add title
                title: {
                    text: 'Tổng lịch',
                    subtext: '',
                    x: 'center'
                },

                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },

                // Add legend
                legend: {
                    orient: 'vertical',
                    x: 'left',
                    data: [data.dataSet.dathen.label, data.dataSet.fail.label, data.dataSet.kbm.label]
                },

                // Add custom colors
                color: color_lich,
                // Add series
                series: [{
                    name: 'Lịch',
                    type: 'pie',
                    radius: '50%',
                    itemStyle: {
                        normal: {
                            label : {
                                show: true,
                                position: 'outside',
                                fontSize:32,
                                formatter: function(data){
                                    return data.value + "("+data.percent+'%)';
                                }
                            }
                        }
                    },
                    center: ['50%', '57.5%'],
                    data: [
                            {value: data.dataSet.dathen.value===undefined?0:Object.values(data.dataSet.dathen.value).reduce((a, b) => parseFloat(a) + Math.round(parseFloat(b))), 
                            name: data.dataSet.dathen.label},
                            {value: data.dataSet.fail.value===undefined?0:Object.values(data.dataSet.fail.value).reduce((a, b) => parseFloat(a) + Math.round(parseFloat(b))), 
                            name: data.dataSet.fail.label},
                            {value: data.dataSet.kbm.value===undefined?0:Object.values(data.dataSet.kbm.value).reduce((a, b) => parseFloat(a) + Math.round(parseFloat(b))), 
                            name: data.dataSet.kbm.label},
                    ]
                }]
            };
            
            
// Initialize dathen chart
            var dathen_pie= ec.init(document.getElementById('dathen-pie'));

            // Chart Options
            // ------------------------------
            dathen_chartOptions = {

                // Add title
                title: {
                    text: 'Tổng đặt hẹn',
                    subtext: '',
                    x: 'center'
                },

                // Add tooltip
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b}: {c} ({d}%)"
                },

                // Add legend
                legend: {
                    orient: 'vertical',
                    x: 'left',
                    data: [data.dataSet.dathen.status.lam.label, data.dataSet.dathen.status.khonglam.label, data.dataSet.dathen.status.khongden.label]
                },

                // Add custom colors
                color: color_dathen,

                // Add series
                series: [{
                    name: 'Đặt hẹn',
                    type: 'pie',
                    radius: '50%',
                    itemStyle: {
                        normal: {
                            label : {
                                show: true,
                                position: 'outside',
                                fontSize:32,
                                formatter: function(data){
                                    return data.value + "("+data.percent+'%)';
                                }
                            }
                        }
                    },
                    center: ['50%', '57.5%'],
                    data: [
                            {value: [null,undefined].includes(data.dataSet.dathen.status.lam.value)?0:Object.values(data.dataSet.dathen.status.lam.value).reduce((a, b) => parseFloat(a) + Math.round(parseFloat(b))), 
                            name: data.dataSet.dathen.status.lam.label},
                            {value: [null,undefined].includes(data.dataSet.dathen.status.khonglam.value)?0:Object.values(data.dataSet.dathen.status.khonglam.value).reduce((a, b) => parseFloat(a) + Math.round(parseFloat(b))), 
                            name: data.dataSet.dathen.status.khonglam.label},
                            {value: [null,undefined].includes(data.dataSet.dathen.status.khongden.value)?0:Object.values(data.dataSet.dathen.status.khongden.value).reduce((a, b) => parseFloat(a) + Math.round(parseFloat(b))), 
                            name: data.dataSet.dathen.status.khongden.label},
                    ]
                }]
            };

            // Apply options
            // ------------------------------

            // Connect charts
            ketqua_Chart.connect(columnChart);
            tonglich_pie.connect(columnChart);
            dathen_pie.connect(columnChart);
            columnChart.connect(ketqua_Chart);
            columnChart.connect(tonglich_pie);
            columnChart.connect(dathen_pie);

            // Apply options
            // ------------------------------
            console.log(ketqua_ChartOptions);
            ketqua_Chart.setOption(ketqua_ChartOptions );
            columnChart.setOption(columnChartOptions);
            tonglich_pie.setOption(tonglich_chartOptions);
            dathen_pie.setOption(dathen_chartOptions);

            //-----------------end combined chart
            
            //zalo-line chart
            
            var dathen_column = ec.init(document.getElementById('dathen-column'));

            // Chart Options
            // ------------------------------
            dathen_chartOptions = {

                // Setup grid
                grid: {
                    x: 65,
                    x2: 47,
                    y: 35,
                    y2: 70
                },

                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: {
                    data: [data.dataSet.dathen.status.lam.label, data.dataSet.dathen.status.khonglam.label, data.dataSet.dathen.status.khongden.label]
                },

                // Add custom colors
                color: color_dathen,

                // Enable drag recalculate
                calculable: true,

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: data.dataDate===undefined?[]:data.dataDate.map(function(e){
                    var a=new Date(e*1000)
                    var options = { year: 'numeric', month: 'numeric', day: 'numeric' };
                    return a.toLocaleDateString('vi-VN', options);
                    }),
                    axisLabel: {
                        rotate: '45'
                    }
                }],

                // Vertical axis
                yAxis: [{
                    type: 'value',
                    name:'Lịch',
                    splitArea: {show: true},
                }],

                // Add series
                series : [
                    
                    
                    {
                        name:data.dataSet.dathen.status.lam.label,
                        type:'bar',
                        stack:'detaildathen',
                        data:(data.dataSet.dathen.status.lam.value===undefined || data.dataSet.dathen.status.lam.value===null)?[]:Object.values(data.dataSet.dathen.status.lam.value)
                    },
                    {
                        name:data.dataSet.dathen.status.khonglam.label,
                        type:'bar',
                        stack:'detaildathen',
                        data:(data.dataSet.dathen.status.khonglam.value===undefined || data.dataSet.dathen.status.khonglam.value===null)?[]:Object.values(data.dataSet.dathen.status.khonglam.value)
                    },
                    {
                        name:data.dataSet.dathen.status.khongden.label,
                        type:'bar',
                        stack:'detaildathen',
                        data:(data.dataSet.dathen.status.khongden.value===undefined || data.dataSet.dathen.status.khongden.value===null)?[]:Object.values(data.dataSet.dathen.status.khongden.value)
                    },
                ]
            };

            // Apply options
            // ------------------------------

            dathen_column.setOption(dathen_chartOptions);
            
             //zalo-line chart
            
            var zalo_column = ec.init(document.getElementById('zalo-column'));

            
            
            
            // Chart Options
            // ------------------------------
            zalo_chartOptions = {

                // Setup grid
                grid: {
                    x: 65,
                    x2: 47,
                    y: 35,
                    y2: 70
                },

                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: series_data.zalo_legend,

                // Add custom colors
                color: color_lich,

                // Enable drag recalculate
                calculable: true,

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: data.dataDate===undefined?[]:data.dataDate.map(function(e){
                    var a=new Date(e*1000)
                    var options = { year: 'numeric', month: 'numeric', day: 'numeric' };
                    return a.toLocaleDateString('vi-VN', options);
                    }),
                    axisLabel: {
                        rotate: '45'
                    }
                }],

                // Vertical axis
                yAxis: [{
                    type: 'value',
                    name:'Lịch',
                    splitArea: {show: true},
                }
                ],

                // Add series
                series : series_data.zalo_series
            };

            // Apply options
            // ------------------------------

            zalo_column.setOption(zalo_chartOptions);
            
            
            
            // hotline column-line chart
            
            var hotline_column = ec.init(document.getElementById('hotline-column'));

            // Chart Options
            // ------------------------------
            hotline_chartOptions = {
               
                 // Setup grid
                grid: {
                    x: 65,
                    x2: 47,
                    y: 35,
                    y2: 70
                },

                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: series_data.hotline_legend,

                // Add custom colors
                color: color_lich,

                // Enable drag recalculate
                calculable: true,

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: [null,undefined].includes(data.dataDate)?[]:data.dataDate.map(function(e){
                    var a=new Date(e*1000)
                    var options = { year: 'numeric', month: 'numeric', day: 'numeric' };
                    return a.toLocaleDateString('vi-VN', options);
                    }),
                    axisLabel: {
                        rotate: '45'
                    }
                }],


                // Vertical axis
                yAxis: [{
                    type: 'value',
                    name:'Lịch',
                    splitArea: {show: true},
                }
                ],

                // Add series
                series : series_data.hotline_series,
            };

            // Apply options
            // ------------------------------

            hotline_column.setOption(hotline_chartOptions);


            /**
                Hotline SEO
             */

            var hotlineSEO_column = ec.init(document.getElementById('hotlineSEO-column'));

            // Chart Options
            // ------------------------------
            hotlineSEO_chartOptions = {
            
                // Setup grid
                grid: {
                    x: 65,
                    x2: 47,
                    y: 35,
                    y2: 70
                },

                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: series_data.hotlineSEO_legend,

                // Add custom colors
                color: color_lich,

                // Enable drag recalculate
                calculable: true,

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: [null,undefined].includes(data.dataDate)?[]:data.dataDate.map(function(e){
                    var a=new Date(e*1000)
                    var options = { year: 'numeric', month: 'numeric', day: 'numeric' };
                    return a.toLocaleDateString('vi-VN', options);
                    }),
                    axisLabel: {
                        rotate: '45'
                    }
                }],


                // Vertical axis
                yAxis: [{
                    type: 'value',
                    name:'Lịch',
                    splitArea: {show: true},
                }
                ],

                // Add series
                series : series_data.hotlineSEO_series,
            };

            // Apply options
            // ------------------------------

            hotlineSEO_column.setOption(hotlineSEO_chartOptions);

            
            /* // aff column-line chart
            var aff_column = ec.init(document.getElementById('aff-column'));

            // Chart Options
            // ------------------------------
            aff_chartOptions = {

                 // Setup grid
                grid: {
                    x: 65,
                    x2: 47,
                    y: 35,
                    y2: 70
                },

                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: series_data.aff_legend,

                // Add custom colors
                color: color_lich,

                // Enable drag recalculate
                calculable: true,

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: [null,undefined].includes(data.dataDate)?[]:data.dataDate.map(function(e){
                    var a=new Date(e*1000)
                    var options = { year: 'numeric', month: 'numeric', day: 'numeric' };
                    return a.toLocaleDateString('vi-VN', options);
                    }),
                    axisLabel: {
                        rotate: '45'
                    }
                }],


                // Vertical axis
                yAxis: [{
                    type: 'value',
                    name:'Lịch',
                    splitArea: {show: true},
                }
                ],

                // Add series
                series : series_data.aff_series
            };

            // Apply options
            // ------------------------------

            aff_column.setOption(aff_chartOptions);*/
            
            
            /*// messWeb column-line chart
            
            var messWeb_column = ec.init(document.getElementById('messWeb-column'));

            // Chart Options
            // ------------------------------
            messWeb_chartOptions = {

                // Setup grid
                grid: {
                    x: 65,
                    x2: 47,
                    y: 35,
                    y2: 70
                },

                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: series_data.mess_legend,

                // Add custom colors
                color: color_lich,

                // Enable drag recalculate
                calculable: true,

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: [null,undefined].includes(data.dataDate)?[]:data.dataDate.map(function(e){
                    var a=new Date(e*1000)
                    var options = { year: 'numeric', month: 'numeric', day: 'numeric' };
                    return a.toLocaleDateString('vi-VN', options);
                    }),
                    axisLabel: {
                        rotate: '45'
                    }
                }],


                // Vertical axis
                yAxis: [{
                    type: 'value',
                    name:'Lịch',
                    splitArea: {show: true},
                }],

                // Add series
                series : series_data.mess_series,
            };

            // Apply options
            // ------------------------------

            messWeb_column.setOption(messWeb_chartOptions);*/
            //end messweb


            // Website column-line chart
            
            var website_column = ec.init(document.getElementById('website-column'));

            // Chart Options
            // ------------------------------
            website_chartOptions = {

                 // Setup grid
                grid: {
                    x: 65,
                    x2: 47,
                    y: 35,
                    y2: 70
                },

                // Add tooltip
                tooltip: {
                    trigger: 'axis'
                },

                // Add legend
                legend: series_data.website_legend,

                // Add custom colors
                color: color_lich,

                // Enable drag recalculate
                calculable: true,

                // Horizontal axis
                xAxis: [{
                    type: 'category',
                    data: [null,undefined].includes(data.dataDate)?[]:data.dataDate.map(function(e){
                    var a=new Date(e*1000)
                    var options = { year: 'numeric', month: 'numeric', day: 'numeric' };
                    return a.toLocaleDateString('vi-VN', options);
                    }),
                    axisLabel: {
                        rotate: '45'
                    }
                }],


                // Vertical axis
                yAxis: [{
                    type: 'value',
                    name:'Lịch',
                    splitArea: {show: true},
                }
                ],

                // Add series
                series : series_data.website_series,
            };
            
            // Apply options
            // ------------------------------

            website_column.setOption(website_chartOptions);
            //end website
        
        
            
            $(function () {

                // Resize chart on menu width change and window resize
                $(window).on('resize', resize);
                $(".menu-toggle").on('click', resize);

                // Resize function
                function resize() {
                    setTimeout(function() {

                        // Resize chart
                        // myChart.resize();
                    }, 200);
                }
            });
        }
    );
}


JS;

$this->registerJs($script);
