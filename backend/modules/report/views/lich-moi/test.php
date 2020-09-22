<?php

use yii\web\JqueryAsset;
use yii\web\View;

$css = <<< CSS
#mainView{flex-grow:1;height:inherit;position:relative;overflow:visible}
#view{vertical-align:top;width:auto;height:auto;margin:16px;box-shadow:0 1px 1px 0 rgba(0,0,0,0.12);border-radius:2px}
#reportContainer{min-height:300px;position:relative}
#reportLoading{position:absolute;width:100%;height:100%;z-index:71}
#report{margin:0;min-width:99%;min-height:500px}
.report-header-toolbar-simple{background:none repeat scroll 0 0 #f8f9fa;border-bottom:1px solid #e7e7e7;clear:right;margin:0 -1px;min-width:600px;padding:0}
.reporttoolbar-simple{display:flex;justify-content:space-between;min-height:32px;padding:0 4px 4px}
#reportHeader-reportToolbar-title span{color:#444;font:bold 18px Roboto,sans-serif}
.report-toolbar-button-simple{border:1px solid transparent;color:rgba(0,0,0,.65);cursor:pointer;display:inline-block;font:bold 11px Roboto,sans-serif;min-width:18px;padding:6px 10px 6px 8px;position:relative;text-align:center;text-transform:uppercase}
.report-toolbar-button-simple:hover{background:#e6e6e6;border-radius:3px;color:rgba(0,0,0,.75)}
.report-toolbar-button-icon{color:#757575;display:inline-block;height:18px;margin:0 8px;vertical-align:-5px;width:18px}
.report-toolbar-button-save-icon{background:url("https://ssl.gstatic.com/analytics/20191017-00/web/save.svg");background-size:18px 18px}
.report-toolbar-button-export-icon{background:url("https://ssl.gstatic.com/analytics/20191017-00/web/export.svg");background-size:18px 18px}
.report-toolbar-button-share-icon{background:url("https://ssl.gstatic.com/analytics/20191017-00/web/share.svg");background-size:18px 18px}
.report-toolbar-button-assistant-icon{background:url("https://ssl.gstatic.com/analytics/20191017-00/web/assistant_new.svg");background-size:18px 18px}
.reportToolbarDivider{border-right:1px solid #ccc;display:inline-block;height:60%;margin:4px;width:1px}
.report-header-widget{display:flex;justify-content:space-between}
.report-header-widget-left{align-items:flex-start;display:flex;flex-direction:column;justify-content:flex-start}
.segmentHeader{position:relative;margin-top:-1px}
.segmentCard-simple{-moz-border-radius:4px;-webkit-border-radius:4px;border-radius:4px;display:inline-block;min-height:40px;margin:10px 0 5px 10px;padding:10px;position:relative;vertical-align:top;background:#fff;border:1px solid #e5e5e5;white-space:nowrap;width:323px;z-index:20}
.overview.action-change{color:#005c9c;cursor:pointer;display:inline-block;font:bold 13px Roboto,sans-serif;margin-bottom:-1px;padding:6px 8px;background:-webkit-linear-gradient(top,#f3f3f3,#fff);background:-moz-linear-gradient(top,#f3f3f3,#fff);border-color:#ccc #ccc #fff;border-radius:2px 2px 0 0;border-style:solid;border-width:1px}
.modeSelector{list-style:none;margin:0;padding:0;vertical-align:middle;line-height:30px}
.modeSelector>li{display:block;float:left;background-color:#f3f3f3;padding:8px 7px;font:bold 11px Roboto,sans-serif;color:#444;border:1px solid #ccc;background-image:-webkit-linear-gradient(top,#fefefe,#f3f3f3);background-image:-moz-linear-gradient(top,#fefefe,#f3f3f3);cursor:pointer;min-width:18px;text-align:center}
.modeSelector>li:first-child{border-radius:3px 0 0 3px}
.modeSelector>li:last-child{border-radius:0 3px 3px 0}
.modeSelector>li.active,.modeSelector>li:active{background-color:#dfdfdf;background-image:-webkit-linear-gradient(top,#f0f0f0,#dfdfdf);background-image:-moz-linear-gradient(top,#f0f0f0,#dfdfdf);border-color:#ccc;box-shadow:inset 0 1px 5px rgba(0,0,0,.3);color:#222}
.modeSelector>li:hover{border-color:#ccc;box-shadow:inset 0 1px 2px rgba(0,0,0,.2);color:#222}
#overview-graph-lineChart{height:400px}
.sparkline-metric-analytics{ border-bottom: 0 none; border-right: 1px solid #cbcbcb; text-align: left; margin: 15px 0 10px 10px; width: 165px; position: relative; display: -moz-inline-box; display: inline-block; }
#overview-miniPie{width:520px;height:310px;}
#overview-dimensionSummary-miniTable .table th{border-top:0}
.daterangepicker.dropdown-menu .ranges{float:right}
.daterangepicker.opensright:before{left:auto;right:69px}
.daterangepicker.opensright:after{left:auto;right:70px;}
.date-compare-selector .form-control{line-height:1.35!important;}
.date-compare-selector .form-control.disabled{opacity:.5}
.ui.dropdown>.text{font-weight:400}
CSS;
$this->registerCss($css);
$this->title = 'Report lịch mới';
?>
<div id="mainView">
    <div id="view">
        <div style="background-color:#fff;min-width:600px;vertical-align:top;width:100%;border-radius:2px;">
            <div id="reportContainer">
                <div id="reportLoading" style="display:none"></div>
                <div id="report">
                    <div class="reportContent">
                        <div style="margin-bottom:5px;min-height:50px;">
                            <div id="reportHeader">
                                <div id="reportHeader-toolbarSection">
                                    <div id="reportHeader-reportToolbar" class="report-header-toolbar-simple">
                                        <div class="reporttoolbar-simple">
                                            <div style="align-items:center;display:flex;justify-content:flex-start">
                                                <div id="reportHeader-reportToolbar-title">
                                                    <span>Tổng quan về đối tượng</span>
                                                </div>
                                            </div>
                                            <div style="align-items:center;display:flex;justify-content:flex-end;">
                                                <span class="saveReportControlButton report-toolbar-button-simple createSaveReport">
                                                    <span class="report-toolbar-button-icon report-toolbar-button-save-icon"></span>
                                                    Lưu
                                                </span>
                                                <span class="exportReportControlButton report-toolbar-button-simple exportMenu">
                                                    <span class="report-toolbar-button-icon report-toolbar-button-export-icon"></span>
                                                    Xuất
                                                </span>
                                                <span class="shareReportControlButton report-toolbar-button-simple">
                                                    <span class="report-toolbar-button-icon report-toolbar-button-share-icon"></span>
                                                    Chia sẻ
                                                </span>
                                                <span class="reportToolbarDivider"></span>
                                                <span class="reportHeaderDataAssistantButton report-toolbar-button-simple">
                                                    <span class="report-toolbar-button-icon report-toolbar-button-assistant-icon"></span>
                                                    Thông tin chi tiết
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end #reportHeader-toolbarSection -->
                                <div id="reportHeader-customSection">
                                    <div class="report-header-widget">
                                        <div class="report-header-widget-left">
                                            <div class="segmentContext">
                                                <div id="reportHeader-segmentHeader">
                                                    <div class="segmentHeader selectSegments">
                                                        <div class="viewRoot" style="display:inline-block">
                                                            <div class="segmentCard-simple segmentHeaderData">
                                                                <div style="padding:4px 16px 4px 4px;font-size:14px;">
                                                                    <div style="overflow: hidden; padding: 0 4px; text-overflow: ellipsis; white-space: nowrap;">
                                                                        Tất cả người dùng</div>
                                                                    <div style="overflow: hidden; padding: 0 4px; text-overflow: ellipsis; white-space: nowrap; color: #666; font-size: 90%">
                                                                        <span class="segment-header-context-text">
                                                                            <span style="color:#666;font-size:90%">100,00% Số phiên</span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="report-header-widget-right">
                                            <div id="reportHeader-dateControl" class="report-header-datepicker" style="display:flex;align-items:center;margin-top:10px;margin-right:10px;">
                                                <div class="input-group">
                                                    <input type="text" id="datepicker-container" class="form-control">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <span class="fa fa-calendar"></span>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="date-compare-selector" style="float:left;display:flex;align-items:center;width:100%">
                                                    <label style="margin:0 10px;white-space:nowrap">
                                                        <input type="checkbox" name="" class="date-compare-mode">
                                                        So sánh với
                                                    </label>
                                                    <select name="datecontrol-compare-selector" class="ui dropdown form-control" disabled readonly style="line-height:1.25">
                                                        <option value="1">Kỳ trước đó</option>
                                                        <option value="2">Tháng trước đó</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end #reportHeader-customSection -->
                            </div>
                            <!-- end #reportHeader -->
                        </div>

                        <div id="tabControl">
                            <div style="border-bottom:1px solid #ccc;clear:both;margin-top:3px;">
                                <div style="padding-left:10px">
                                    <span class="overview action-change">
                                        <span style="color:#222;cursor:auto">Tổng quan</span>
                                    </span>
                                </div>
                            </div>

                        </div>
                        <div class="tabContainer">
                            <div id="tab">
                                <div style="clear:both;padding:0 10px 10px">
                                    <div id="overview-graphOptions" style="clear:both;padding:10px 10px 0 10px;">
                                        <div class="ui floating dropdown icon button">
                                            <span class="text">Số phiên</span>
                                            <i class="caret down icon"></i>
                                            <div class="menu">
                                                <div class="ui icon search input">
                                                    <i class="search icon"></i>
                                                    <input type="text">
                                                </div>
                                                <div class="scrolling menu">
                                                    <div class="item">
                                                        <div class="ui red empty circular label"></div>
                                                        % phiên mới
                                                    </div>
                                                    <div class="item">
                                                        <div class="ui blue empty circular label"></div>
                                                        Người dùng
                                                    </div>
                                                    <div class="item active selected">
                                                        <div class="ui black empty circular label"></div>
                                                        Số phiên
                                                    </div>
                                                    <div class="item">
                                                        <div class="ui purple empty circular label"></div>
                                                        Số trang / phiên
                                                    </div>
                                                    <div class="item">
                                                        <div class="ui orange empty circular label"></div>
                                                        Thời gian trung bình của phiên
                                                    </div>
                                                    <div class="item">
                                                        <div class="ui empty circular label"></div>
                                                        Tỷ lệ thoát
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-inline-block mr-1">so với</div>
                                        <div class="ui floating dropdown icon button">
                                            <span class="text">Chọn số liệu</span>
                                            <i class="caret down icon"></i>
                                            <div class="menu">
                                                <div class="ui icon search input">
                                                    <i class="search icon"></i>
                                                    <input type="text">
                                                </div>
                                                <div class="scrolling menu">
                                                    <div class="item">
                                                        <div class="ui red empty circular label"></div>
                                                        % phiên mới
                                                    </div>
                                                    <div class="item">
                                                        <div class="ui blue empty circular label"></div>
                                                        Người dùng
                                                    </div>
                                                    <div class="item">
                                                        <div class="ui black empty circular label"></div>
                                                        Số phiên
                                                    </div>
                                                    <div class="item">
                                                        <div class="ui purple empty circular label"></div>
                                                        Số trang / phiên
                                                    </div>
                                                    <div class="item">
                                                        <div class="ui orange empty circular label"></div>
                                                        Thời gian trung bình của phiên
                                                    </div>
                                                    <div class="item">
                                                        <div class="ui empty circular label"></div>
                                                        Tỷ lệ thoát
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="float-right">
                                            <ul class="modeSelector">
                                                <li class="modeSelector-0 action-mode analytics-nthHour">Hàng giờ</li>
                                                <li class="modeSelector-1 action-mode analytics-nthDay active">Ngày</li>
                                                <li class="modeSelector-2 action-mode analytics-nthWeek">Tuần</li>
                                                <li class="modeSelector-3 action-mode analytics-nthMonth">Tháng</li>
                                            </ul>
                                        </div>
                                        <div style="clear:both"></div>

                                    </div>
                                    <!-- end #overview-graphOptions -->
                                    <div id="overview-graph">
                                        <div id="overview-graph-lineChart"></div>
                                    </div>
                                    <!-- end #overview-graph -->
                                    <div class="row" style="margin:20px 0 0;">
                                        <div class="col-8">
                                            <div id="overview-sparkline" style="margin:0 0 30px;text-align:left">
                                                <div class="sparkline-metric-analytics visits-group">
                                                    <div class="sparkline-metric-analytics.visits">Số phiên</div>
                                                    <div style="display:inline-block;margin:5px 0;text-align:left">
                                                        <div>31.791</div>
                                                        <div class="action-graph target-analytics.visits"></div>
                                                    </div>
                                                </div>
                                                <div class="sparkline-metric-analytics totalVisitors-group">
                                                    <div class="sparkline-metric-analytics.visits">Người dùng</div>
                                                    <div style="display:inline-block;margin:5px 0;text-align:left">
                                                        <div>23.486</div>
                                                        <div class="action-graph target-analytics.visits"></div>
                                                    </div>
                                                </div>
                                                <div class="sparkline-metric-analytics pageviews-group">
                                                    <div class="sparkline-metric-analytics.visits">Số lần xem trang</div>
                                                    <div style="display:inline-block;margin:5px 0;text-align:left">
                                                        <div>76.716</div>
                                                        <div class="action-graph target-analytics.visits"></div>
                                                    </div>
                                                </div>
                                                <div class="sparkline-metric-analytics avgPageviews-group">
                                                    <div class="sparkline-metric-analytics.visits">Số trang / phiên</div>
                                                    <div style="display:inline-block;margin:5px 0;text-align:left">
                                                        <div>2,49</div>
                                                        <div class="action-graph target-analytics.visits"></div>
                                                    </div>
                                                </div>
                                                <div class="sparkline-metric-analytics bounceRate-group">
                                                    <div class="sparkline-metric-analytics.visits">Tỷ lệ thoát</div>
                                                    <div style="display:inline-block;margin:5px 0;text-align:left">
                                                        <div>61,93%</div>
                                                        <div class="action-graph target-analytics.visits"></div>
                                                    </div>
                                                </div>
                                                <div class="sparkline-metric-analytics percentNewVisits-group">
                                                    <div class="sparkline-metric-analytics.visits">% phiên mới</div>
                                                    <div style="display:inline-block;margin:5px 0;text-align:left">
                                                        <div>68,96%</div>
                                                        <div class="action-graph target-analytics.visits"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- end #overview-sparkline -->
                                            <div id="overview-dimensionSummary">
                                                <div id="overview-dimensionSummary-miniTable">
                                                    <table class="table">
                                                        <thead>
                                                        <tr>
                                                            <th>Tỉnh / Thành phố</th>
                                                            <th>Khách đặt hẹn</th>
                                                            <th>Đến / Không đến</th>
                                                            <th>Tỷ lệ đến</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            <td>Hồ Chí Minh</td>
                                                            <td>288</td>
                                                            <td>203 / 85</td>
                                                            <td class="text-center font-small-2">
                                                                70 %
                                                                <div class="progress progress-md mt-0 mb-0">
                                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 70%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Đồng Nai</td>
                                                            <td>33</td>
                                                            <td>22 / 11</td>
                                                            <td class="text-center font-small-2">
                                                                67 %
                                                                <div class="progress progress-md mt-0 mb-0">
                                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 67%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Bình Dương</td>
                                                            <td>22</td>
                                                            <td>17 / 5</td>
                                                            <td class="text-center font-small-2">
                                                                77 %
                                                                <div class="progress progress-md mt-0 mb-0">
                                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 77%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Nước Ngoài</td>
                                                            <td>18</td>
                                                            <td>14 / 4</td>
                                                            <td class="text-center font-small-2">
                                                                78 %
                                                                <div class="progress progress-md mt-0 mb-0">
                                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 78%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <!-- end #overview-dimensionSummary-miniTable -->
                                            </div>
                                            <!-- end #overview-dimensionSummary -->
                                        </div>
                                        <div class="col-4">
                                            <div id="overview-miniPie"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$script = <<< JS
var d = new Date(),
day = d.getDate(),
y = d.getFullYear(),
m = d.getMonth();
var startDate = '01-' + (+m + +1) + '-' + y;
var endDate = day + '-' + (+m + +1) + '-' + y;
var start = new Date(d.getFullYear(), d.getMonth(), 1);

$('#datepicker-container').daterangepicker({
    formatSubmit: 'D/M/Y',
    timeZone: 'Asia/Ho_Chi_Minh',
    showDropdowns: true,
    timePicker: false,
    format: 'DD/MM/YYYY',
    startDate: start,
    ranges: {
           'Hôm nay': [moment(), moment()],
           'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           '7 ngày qua': [moment().subtract(6, 'days'), moment()],
           '30 ngày qua': [moment().subtract(29, 'days'), moment()],
           'Tháng hiện tại': [moment().startOf('month'), moment()],
           'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        },
    locale: {
        format: 'D/M/Y',
        cancelLabel: 'Xóa',
        applyLabel: 'Cập nhật',
        daysOfWeek: ['T2', 'T3', 'T4', 'T5', 'T6', 'T7','CN'],
        monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
        "customRangeLabel": "Tùy chọn",
    },
    autoclose: true,
    maxDate: moment(),
},
function(start, end) {
    startDate = start.format('DD-MM-Y');
    endDate = end.format('DD-MM-Y');
});

$(function () {    
    $('.date-compare-mode').on('change', function () {
        // console.log($(this).prop('checked');
        if ($(this).is(':checked') === true) {
            $('select[name=datecontrol-compare-selector]').removeAttr('disabled readonly');
            $('.date-compare-selector').find('.ui.dropdown').removeClass('disabled');
        } else {
            $('select[name=datecontrol-compare-selector]').attr({
                'disabled': '', 
                'readonly': ''
            });
            $('.date-compare-selector').find('.ui.dropdown').addClass('disabled');
        }
    })
})

$(window).on("load", function(){
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
                    data: ['Email marketing', 'Advertising alliance']
                },

                // Add custom colors
                // color: ['#F98E76', '#16D39A', '#2DCEE3', '#FF7588', '#626E82'],

                // Add horizontal axis
                xAxis: [{
                    type: 'category',
                    boundaryGap: false,
                    data: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']
                }],

                // Add vertical axis
                yAxis: [{
                    type: 'value'
                }],

                // Add series
                series: [
                    {
                        name: 'Email marketing',
                        type: 'line',
                        stack: 'Total',
                        itemStyle: {normal: {areaStyle: {type: 'default'}}},
                        data: [120, 132, 101, 134, 90, 230, 210]
                    },
                    {
                        name: 'Advertising alliance',
                        type: 'line',
                        stack: 'Total',
                        itemStyle: {normal: {areaStyle: {type: 'default'}}},
                        data: [220, 182, 191, 234, 290, 330, 310]
                    },
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

$(window).on("load", function(){

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
                    data: ['Firefox', 'Chrome']
                },

                // Add custom colors
                color: ['#FF4558', '#16D39A'],

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
                    name: 'Browsers',
                    type: 'pie',
                    radius: '70%',
                    center: ['50%', '57.5%'],
                    data: [
                        {value: 135, name: 'Firefox'},
                        {value: 335, name: 'Chrome'}
                    ]
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
});



JS;
$this->registerJsFile('/vendors/js/charts/echarts/echarts.js', ['depends' => [JqueryAsset::class]]);
$this->registerJsFile('/vendors/js/charts/flot/jquery.flot.min.js', ['depends' => [JqueryAsset::class]]);
$this->registerJsFile('/vendors/js/charts/flot/jquery.flot.resize.js', ['depends' => [JqueryAsset::class]]);
$this->registerJsFile('/vendors/js/charts/flot/jquery.flot.time.js', ['depends' => [JqueryAsset::class]]);
$this->registerJsFile('/vendors/js/charts/flot/jquery.flot.selection.js', ['depends' => [JqueryAsset::class]]);
$this->registerJsFile('/vendors/js/charts/flot/jquery.flot.symbol.js', ['depends' => [JqueryAsset::class]]);
$this->registerJS($script, View::POS_END);
?>
