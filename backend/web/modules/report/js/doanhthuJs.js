'use strict';

window.chartColors = {
    red: 'rgb(255, 99, 132)',
    orange: 'rgb(255, 159, 64)',
    yellow: 'rgb(255, 205, 86)',
    green: 'rgb(75, 192, 192)',
    blue: 'rgb(54, 162, 235)',
    purple: 'rgb(153, 102, 255)',
    grey: 'rgb(201, 203, 207)'
};

var d = new Date(),
    day = d.getDate(),
    y = d.getFullYear(),
    m = d.getMonth();
// var startDate = '01-' + (+m + +1) + '-' + y;
var endDate = day + '-' + (+m + +1) + '-' + y;
var start = new Date(d.getFullYear(), d.getMonth(), 1);

var startDateReport = '01/' + (m + 1) + '/' + y;
var endDateReport = day + '/' + (m + 1) + '/' + y;
$(document).ready(function () {

    getData();

    let option_date = {
        autoClose: false,
        format: 'DD/MM/YYYY',
        separator: '~',
        startOfWeek: 'monday',
        // startDate: startDate,
        endDate: endDate,
        showShortcuts: true,
        extraClass: 'date-range-picker19',
        shortcuts:
            {
                'prev-days': [3, 7, 30],
                'prev': ['week', 'month'],
                'next-days': null,
                'next': null
            },
        monthSelect: true,
        yearSelect: true,

        /*
        get value when on click
        getValue: function () {
            console.log($(this).val());
            return $(this).val();
        },*/
        // batchMode: 'month',
    };
    let option_month = {
        autoClose: false,
        format: 'DD/MM/YYYY',
        separator: '~',
        startOfWeek: 'monday',
        // startDate: startDate,
        endDate: endDate,
        showShortcuts: true,
        extraClass: 'date-range-picker19',
        shortcuts:
            {
                'prev-days': [3, 7, 30],
                'prev': ['week', 'month'],
                'next-days': null,
                'next': null
            },
        monthSelect: true,
        yearSelect: true,
        singleMonth: true,
        /*setValue: function (s) {
            if (!$(this).attr('readonly') && !$(this).is(':disabled') && s !== $(this).val()) {
                console.log('setvalue');
                console.log(s);
                console.log($(this).val(s));
            }
        },*/
        // batchMode: 'month',
    };
    loadDateRangePicker(option_date);


    $('body').on('change', '#datepicker-container', function (e) {
        let value = $('#datepicker-container').val();
        value = value.split('~');
        if ($.isArray(value) && value.length > 1) {
            startDateReport = value[0];
            endDateReport = value[1];
            getData();
        }
    }).on('change', 'input.date-compare-mode', function (e) {
        if ($('input.date-compare-mode').prop('checked')) {
            let datepicker = $('#datepicker-container');
            if ((datepicker.val()).length === 0) {
                // console.log(($('#datepicker-container').val()).length);
                datepicker.val(startDateReport + '~' + endDateReport);
            }
        }
    }).on('click', '.reload-data', function () {
        $('#datepicker-container').trigger('change');
    }).on('change', '.date-compare-mode', function () {
        let select = $('select[name=datecontrol-compare-selector]');
        if ($(this).is(':checked') === true) {
            select.prop("disabled", true);
            select.parent().removeClass('disabled');

        } else {
            select.prop("disabled", true);
            select.parent().addClass('disabled');
        }
    });


    $('select[name="datecontrol-compare-selector"]').on('change', function (e) {
        let val = $(this).children("option:selected").val();
        let datepicker = $('#datepicker-container');

        if (val == 1) {
            datepicker.data('dateRangePicker').destroy();
            loadDateRangePicker(option_date)
        } else if (val == 2) {
            datepicker.data('dateRangePicker').destroy();
            loadDateRangePicker(option_month)
        }
        getData()
    });

    $('.menu.mainfilter .item').on('click', function (e) {
        $('.sub-kind span.text').text('Chọn số liệu');
        var url = $(this).parents('.mainfilter').data('url');
        let key = $(this).data('key');
        var data = {key: key};
        if (data.key === 'default') {
            $(this).siblings().removeClass('active');
            getData();
        }

        $.ajax({
            url: url,
            data: data,
            method: "GET",
            success: function (data) {
                $('.sub-kind .scrolling.menu').html(data);
            }
        })
    })
    $(document).on('click', '.sub-kind .item', function (e) {
        getData();
    })

    var array_filter = ['fanpage', 'coso', 'sanpham', 'dichvu', 'directsale', 'onlinesale'];
    $.each(array_filter, function (index, value) {

    });

    $('.subfilter > select').on('change', function () {
        getData();
    });

});

function loadDateRangePicker(option) {

    $('#datepicker-container').dateRangePicker(option)
        .bind('datepicker-change', function (event, obj) {
            /* This event will be triggered when second date is selected */
            // console.log(obj);
            var value = (obj.value).split('~');
            if ($.isArray(value) && value.length > 1) {
                startDateReport = value[0];
                endDateReport = value[1];
                getData();
            }
        });
}

function getData() {
    let data_sub_filter = $('form#sub_filter').serializeArray();

    let compare_kind = [true].includes($('input.date-compare-mode').prop('checked')) ? $('select[name="datecontrol-compare-selector"]').children("option:selected").val() : 0;
    let url = $('#view').data('url');
    let data = {
        'data_sub_filter': data_sub_filter,
        'startDateReport': startDateReport,
        'endDateReport': endDateReport,
        'compare_kind': compare_kind
    };

    /*$.ajax({
        url: url,
        data: data,
        method: "GET",
        success: function (data) {
            /!*var dataexample = {
                "debug": [{
                    "date": "2020-05-01 00:00:00.000000",
                    "timezone_type": 3,
                    "timezone": "Asia/Ho_Chi_Minh"
                }, {"date": "2020-05-27 00:00:00.000000", "timezone_type": 3, "timezone": "Asia/Ho_Chi_Minh"}],
                "dataCompareKey": "0",
                "dataDate": [1588266000, 1588352400, 1588438800, 1588525200, 1588611600, 1588698000, 1588784400, 1588870800, 1588957200, 1589043600, 1589130000, 1589216400, 1589302800, 1589389200, 1589475600, 1589562000, 1589648400, 1589734800, 1589821200, 1589907600, 1589994000, 1590080400, 1590166800, 1590253200, 1590339600, 1590426000, 1590512400],
                "dataSet": {
                    "doanhthu": {
                        "label": "Doanh Thu",
                        "first": {
                            "sql": "SELECT `p`.`ngay_tao`, sum(p.tien_thanh_toan) AS `thanh_tien` FROM `phong_kham_don_hang_w_thanh_toan` `p` LEFT JOIN `dep365_customer_online` `d` ON p.customer_id=d.id WHERE (`p`.`ngay_tao` BETWEEN 1588266000 AND 1590512400) AND (d.id = p.customer_id) GROUP BY `p`.`ngay_tao`",
                            "value": {
                                "1588266000": "438000000",
                                "1588352400": "202000000",
                                "1588438800": "367600000",
                                "1588525200": "498200000",
                                "1588611600": "454400000",
                                "1588698000": "391300000",
                                "1588784400": "206000000",
                                "1588870800": "364500000",
                                "1588957200": "459350000",
                                "1589043600": "225900000",
                                "1589130000": "480500000",
                                "1589216400": "446300000",
                                "1589302800": "393200000",
                                "1589389200": "143000000",
                                "1589475600": "170450000",
                                "1589562000": 0,
                                "1589648400": 0,
                                "1589734800": 0,
                                "1589821200": 0,
                                "1589907600": 0,
                                "1589994000": 0,
                                "1590080400": 0,
                                "1590166800": 0,
                                "1590253200": 0,
                                "1590339600": 0,
                                "1590426000": 0,
                                "1590512400": 0
                            }
                        }
                    }
                },
                "debug_table": "SELECT `p`.`name`, sum(ph.thanh_tien-ph.chiet_khau) AS `thanh_tien`, sum(tt.tien_thanh_toan) AS `tien_thuc_thu` FROM `dep365_customer_online` `d`, `province` `p`, `phong_kham_don_hang` `ph`, `phong_kham_don_hang_w_thanh_toan` `tt` WHERE (tt.phong_kham_don_hang_id = ph.id and d.province = p.id and ph.customer_id = d.id) AND (`ph`.`thanh_tien` > 0) AND (`tt`.`tam_ung` IN (0, 1)) AND (`tt`.`ngay_tao` BETWEEN 1588266000 AND 1590512400) GROUP BY `p`.`name` ORDER BY `thanh_tien` DESC",
                "dataPie": {
                    "name": ["Hồ Chí Minh", "Tây Ninh", "Bình Dương", "Đồng Nai", "Khác"],
                    "tien_thuc_thu": ["3505900000", "361600000", "280500000", "223400000", 868800000]
                },
                "table_html": "<div style=\"font-size:18px;font-weight:500;padding-bottom:20px;padding-top:20px\">Tổng quan doanh thu 01/05/2020 - 27/05/2020</div><div id=\"overview-sparkline\" style=\"margin:0 0 30px;text-align:left\">\n    <div class=\"sparkline-metric-analytics visits-group\">\n        <div class=\"sparkline-metric-analytics.visits\">Tổng Thu</div>\n        <div style=\"display:inline-block;margin:5px 0;text-align:left\">\n            <div>12,751,700,000</div>\n            <div class=\"action-graph target-analytics.visits\"></div>\n        </div>\n    </div>\n    <div class=\"sparkline-metric-analytics totalVisitors-group\">\n        <div class=\"sparkline-metric-analytics.visits\">Tổng Thực Thu</div>\n        <div style=\"display:inline-block;margin:5px 0;text-align:left\">\n            <div>5,240,200,000</div>\n            <div class=\"action-graph target-analytics.visits\"></div>\n        </div>\n    </div>\n    <div class=\"sparkline-metric-analytics pageviews-group\">\n        <div class=\"sparkline-metric-analytics.visits\">Tổng Nợ\n        </div>\n        <div style=\"display:inline-block;margin:5px 0;text-align:left\">\n            <div>7,511,500,000</div>\n            <div class=\"action-graph target-analytics.visits\"></div>\n        </div>\n    </div>\n\n</div>\n\n\n<div id=\"overview-dimensionSummary-miniTable\">\n\n    <table class=\"table\">\n        <thead>\n        <tr>\n            <th>Tỉnh / Thành phố</th>\n            <th>Tổng Thu</th>\n            <th>Tiền Thực Thu</th>\n            <th>Tiền Nợ</th>\n        </tr>\n        </thead>\n        <tbody>\n            <tr>\n            <td>Hồ Chí Minh</td>\n            <td>8,729,900,000</td>\n            <td>3,505,900,000</td>\n            <td>5,224,000,000</td>\n        </tr><tr>\n            <td>Tây Ninh</td>\n            <td>963,300,000</td>\n            <td>361,600,000</td>\n            <td>601,700,000</td>\n        </tr><tr>\n            <td>Bình Dương</td>\n            <td>810,500,000</td>\n            <td>280,500,000</td>\n            <td>530,000,000</td>\n        </tr><tr>\n            <td>Đồng Nai</td>\n            <td>510,800,000</td>\n            <td>223,400,000</td>\n            <td>287,400,000</td>\n        </tr><tr>\n            <td>Hà Nội</td>\n            <td>294,000,000</td>\n            <td>147,000,000</td>\n            <td>147,000,000</td>\n        </tr><tr>\n            <td>Đồng Tháp</td>\n            <td>226,900,000</td>\n            <td>137,300,000</td>\n            <td>89,600,000</td>\n        </tr><tr>\n            <td>Bà Rịa - Vũng Tàu</td>\n            <td>172,200,000</td>\n            <td>87,000,000</td>\n            <td>85,200,000</td>\n        </tr><tr>\n            <td>Vĩnh Long</td>\n            <td>167,400,000</td>\n            <td>82,700,000</td>\n            <td>84,700,000</td>\n        </tr><tr>\n            <td>Cần Thơ</td>\n            <td>152,000,000</td>\n            <td>76,000,000</td>\n            <td>76,000,000</td>\n        </tr><tr>\n            <td>Cà Mau</td>\n            <td>134,000,000</td>\n            <td>67,000,000</td>\n            <td>67,000,000</td>\n        </tr><tr>\n            <td>Bến Tre</td>\n            <td>132,200,000</td>\n            <td>66,100,000</td>\n            <td>66,100,000</td>\n        </tr><tr>\n            <td>Bình Phước</td>\n            <td>128,000,000</td>\n            <td>63,100,000</td>\n            <td>64,900,000</td>\n        </tr><tr>\n            <td>Sóc Trăng</td>\n            <td>96,600,000</td>\n            <td>38,800,000</td>\n            <td>57,800,000</td>\n        </tr><tr>\n            <td>Nước Ngoài</td>\n            <td>70,200,000</td>\n            <td>35,100,000</td>\n            <td>35,100,000</td>\n        </tr><tr>\n            <td>An Giang</td>\n            <td>69,000,000</td>\n            <td>24,000,000</td>\n            <td>45,000,000</td>\n        </tr><tr>\n            <td>Long An</td>\n            <td>40,000,000</td>\n            <td>5,000,000</td>\n            <td>35,000,000</td>\n        </tr><tr>\n            <td>Trà Vinh</td>\n            <td>30,000,000</td>\n            <td>15,000,000</td>\n            <td>15,000,000</td>\n        </tr><tr>\n            <td>Kiên Giang</td>\n            <td>24,700,000</td>\n            <td>24,700,000</td>\n            <td>0</td>\n        </tr>\n        </tbody>\n    </table>\n</div>\n\n",
                "get": {
                    "data_sub_filter": [{"name": "fanpage", "value": ""}, {
                        "name": "coso",
                        "value": ""
                    }, {"name": "sanpham", "value": ""}, {"name": "dichvu", "value": ""}, {
                        "name": "direct_sale",
                        "value": ""
                    }, {"name": "online_sale", "value": ""}],
                    "startDateReport": "01/5/2020",
                    "endDateReport": "27/5/2020",
                    "compare_kind": "0"
                }
            };*!/
        }
    })*/

    $.get(url, data, function (data) {
        drawChart(data);
        drawTable(data);
    })
}

var chartCompare = null;

function drawTable(data) {
    $('#overview-dimensionSummary').html(data.table_html);
}

function drawChart(data) {
    // console.log(data);
    // overview-graph-lineChart

    var config = {};
    var dataDate = data.dataDate === undefined ? [] : data.dataDate.map(function (e) {
        var a = new Date(e * 1000);
        var options = {year: 'numeric', month: 'numeric', day: 'numeric'};
        return a.toLocaleDateString('vi-VN', options);
    });
    var dataTitle = "Biểu đồ doanh thu";
    let singlelinecolor = "#2793DB";

    if (data.dataCompareKey == 0) {
        config = {
            type: 'line',
            data: {
                labels: dataDate,
                datasets: [{
                    label: data.dataSet.doanhthu.label,
                    data: Object.values(data.dataSet.doanhthu.first.value),
                    borderColor: singlelinecolor,
                    borderWidth: 3,
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    datalabels: {
                        // hide datalabels for all datasets
                        display: false
                    }
                },
                responsive: true,
                title: {
                    display: true,
                    text: dataTitle
                },
                tooltips: {
                    mode: 'nearest',
                    intersect: false,
                    callbacks: {
                        label: function (t, d) {
                            var xLabel = d.datasets[t.datasetIndex].label;
                            var yLabel = t.yLabel >= 1000 ? t.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' đ' : t.yLabel + ' đ';
                            return xLabel + ': ' + yLabel;
                        }
                    }
                },
                scales: {
                    xAxes: [{
                        display: true,
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Total'
                        },
                        ticks: {
                            beginAtZero: true,
                            callback: function (value, index, values) {
                                return value.toLocaleString("vi", {style: 'currency', currency: 'VND'});
                            }
                        }
                    }]
                }
            }
        };
    } else {
        var dateFirst = [null, undefined].includes(data.dataSet.doanhthu.first.value) ? [] : Object.keys(data.dataSet.doanhthu.first.value);
        var dateSecond = [null, undefined].includes(data.dataSet.doanhthu.second.value) ? [] : Object.keys(data.dataSet.doanhthu.second.value);
        var dateLabels = [];

        for (var i = 0; i < dateFirst.length; i++) {
            dateLabels[i] = dateFirst[i] + '#' + dateSecond[i];
        }
        // console.log([null, undefined].includes(data.dataSet.doanhthu.first.value) ? [] : Object.values(data.dataSet.doanhthu.first.value));
        // console.log([null, undefined].includes(data.dataSet.doanhthu.second.value) ? [] : Object.values(data.dataSet.doanhthu.second.value));
        config = {
            type: 'line',
            data: {

                labels: dateLabels,
                datasets: [{
                    type: 'line',
                    borderColor: "#E25F5F",
                    label: 'Hiện Tại',
                    data: [null, undefined].includes(data.dataSet.doanhthu.first.value) ? [] : Object.values(data.dataSet.doanhthu.first.value),
                    borderWidth: 3,
                    xAxisID: "x-axis-1",
                }, {
                    type: 'line',
                    borderColor: "#2793DB",
                    label: 'Trước Đó',
                    data: [null, undefined].includes(data.dataSet.doanhthu.second.value) ? [] : Object.values(data.dataSet.doanhthu.second.value),
                    borderWidth: 3,
                    xAxisID: "x-axis-2",
                },]

            },

            options: {
                maintainAspectRatio: false,
                plugins: {
                    datalabels: {
                        // hide datalabels for all datasets
                        display: false
                    }
                },
                tooltips: {
                    mode: 'nearest',
                    intersect: false,
                    callbacks: {
                        label: function (t, d) {
                            var xLabel = d.datasets[t.datasetIndex].label;
                            var yLabel = t.yLabel >= 1000 ? t.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' đ' : t.yLabel + ' đ';
                            return xLabel + ': ' + yLabel;
                        },
                        title: function (t, d) {
                            var xLabel = d.labels[t[0].index];
                            var dataIndex = [t[0].datasetIndex];
                            let split = xLabel.split("#")[dataIndex];
                            var label = parseInt(split) * 1000;
                            // console.log(label)
                            var options = {year: 'numeric', month: 'numeric', day: 'numeric'};
                            var date = new Date(label)
                            label = date.toLocaleDateString('vi-VN', options);
                            return label;
                        }
                    }
                },
                scales: {
                    xAxes: [{
                        display: true,
                        tipe: "time",
                        scaleLabel: {
                            display: true,
                            labelString: 'Hiện Tại'
                        },
                        time: {
                            displayFormats: {
                                'day': 'MMM DD',
                                'week': 'MMM DD',
                                'month': 'MMM DD',
                                'quarter': 'MMM DD',
                                'year': 'MMM DD',
                            }
                        },
                        id: "x-axis-1",
                        ticks: {
                            callback: function (label) {
                                var label = label.split("#")[0];
                                var date = new Date(label * 1000)
                                console.log(date);
                                var options = {year: 'numeric', month: 'numeric', day: 'numeric'};
                                return date.toLocaleDateString('vi-VN', options);

                            }
                        }
                    },
                        {
                            display: true,
                            tipe: "time",
                            id: "x-axis-2",
                            scaleLabel: {
                                display: true,
                                labelString: 'Trước Đó'

                            },
                            ticks: {
                                beginAtZero: true,
                                callback: function (label) {
                                    var label = label.split("#")[1];
                                    var date = new Date(label * 1000)
                                    var options = {year: 'numeric', month: 'numeric', day: 'numeric'};
                                    return date.toLocaleDateString('vi-VN', options);

                                }
                            }
                        }

                    ],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Total'
                        },
                        ticks: {
                            callback: function (value, index, values) {
                                return value.toLocaleString("vi", {style: 'currency', currency: 'VND'});
                            }
                        }
                    }]
                }
            }

        };
    }

    var ctx = document.getElementById('overview-graph-lineChart');
    ctx.height = 200;
    ctx.getContext('2d');

    /*
        labels: array of days to show in the xAxes, you can build it like: labels: ['period1day1#period2day2', 'period1day2#period2day2']...
      values_first: period 1 values of every day in the labels array
      values_second: period 2 values of every day in the labels array
    */

    if (chartCompare) {
        chartCompare.destroy();
    }
    chartCompare = new Chart(ctx, config);

    let sumvaluePieMap = 0;
    data.dataPie['thuc_thu_hd'].map(data => {
        sumvaluePieMap += parseInt(data)
    });

    let valuePieMap = data.dataPie['thuc_thu_hd'].map(function (value) {
        let parse = (parseFloat(value) / parseFloat(sumvaluePieMap)) * 100;
        return parse.toFixed(2);
    });

    config = {
        type: 'pie',
        data: {
            datasets: [{
                data: [null, undefined].includes(data.dataPie['thuc_thu_hd']) ? 0 : valuePieMap,
                backgroundColor: [
                    window.chartColors.red,
                    window.chartColors.orange,
                    window.chartColors.yellow,
                    window.chartColors.green,
                    window.chartColors.blue,
                ],
            }],
            labels: [null, undefined].includes(data.dataPie['name']) ? 'Undefined Name' : data.dataPie['name'],
        },
        options: {
            plugins: {
                datalabels: {
                    // hide datalabels for all datasets
                    display: false
                }
            },
            responsive: true,
            tooltips: {
                callbacks: {
                    label: function (tooltipItem, data) {
                        return data['labels'][tooltipItem['index']] + ': ' + data['datasets'][0]['data'][tooltipItem['index']] + '%';
                    }
                }
            }
        }
    };
    if (overview_miniPie) {
        overview_miniPie.destroy();
    }
    var overview_miniPie = document.getElementById('overview-miniPie').getContext('2d');
    var myPieChart = new Chart(overview_miniPie, config);
}





