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
var d = new Date(),
    day = d.getDate(),
    y = d.getFullYear(),
    m = d.getMonth();
var startDate = '01-' + (+m + +1) + '-' + y;
var endDate = day + '-' + (+m + +1) + '-' + y;
var start = new Date(d.getFullYear(), d.getMonth(), 1);

var startDateReport = '01/' + (m + 1) + '/' + y;
var endDateReport = day + '/' + (m + 1) + '/' + y;

$(document).ready(function (e) {


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
    loadDateRangePicker(option_date);

    getData();

    document.addEventListener('keyup', evt => {
        if (evt.keyCode === 32) {
            getData();
        }
    })

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
    })


    //select pie chart
    $('select#percent_listing').on('change', function (e) {
        console.log($(this).val());
        getData();
    });


    $(document).on('click', '.main_dimension input', function (e) {
        // let filter = $(this).data('filter')
        // console.log(filter);
        getData();
    });
    //onchange filter---------------------------
    var list_onchange = $('.subfilter select')
    list_onchange.map(function () {
        return this;
    }).on('change', function (e) {
        getData();
    })
    /*
    var list_onchange = $('.subfilter select , .chotkhong input')
    list_onchange.map(function () {
        return this;
    }).on('change', function (e) {
        getData();
    })
    $('.datkhong input').on('change', function (e) {
        let this_val = parseInt($(this).val())
        let parent_denkhong = $(this).parents('.subsub_filter').find('.denkhong');
        let parent_chotkhong = $(this).parents('.subsub_filter').find('.chotkhong');

        let input_denkhong = parent_denkhong.find('input');
        if (this_val === 0) {
            input_denkhong.attr('disabled', true);

            if (input_denkhong.attr('disabled') == 'disabled') {
                parent_chotkhong.find('input').attr('disabled', true);
            }
        } else {
            // input_denkhong.attr('checked', false);
            input_denkhong.attr('disabled', false);
        }
        getData()
    })
    $('.denkhong input').on('change', function (e) {
        let this_val = parseInt($(this).val())
        let parent = $(this).parents('.subsub_filter').find('.chotkhong');
        if (this_val === 0) {
            parent.find('input').attr('disabled', true);
        } else {
            parent.find('input').attr('disabled', false);
        }
        getData()
    })*/

    //end onchange filter---------------------------
    function getData() {
        let form_data = $('form').serializeArray();
        let data = {
            'data': form_data,
            'startDateReport': startDateReport,
            'endDateReport': endDateReport,
        };
        console.log(data);
        $.ajax({
            method: "GET",
            url: report_url_ajax,
            data: data,
        }).done(function (res) {
            console.log(res);
            drawChart(res);
            drawTable(res);
            drawChartPie(res);
            // console.log(res.filter.hide_column);
            // console.log(res.filter.dataSet);
            $('#dtBasicExample').DataTable({
                "columnDefs": [
                    {
                        "targets": res.filter.hiden_column,
                        "visible": false,
                        "searchable": false
                    }
                ],
                "order": [[7, "desc"]],
                "data": res.filter.dataSet,
            });
            $('th select').on('click', function (e) {
                e.stopPropagation();
            })
        });
    }

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


    var chartCompare = null;
    var myPieChart = null;

    function drawTable(res) {
        $("#table_overview").html(res.table_html);
    }

    function compare(a, b) {
        let number_a = a.value;
        let number_b = b.value;
        // console.log(number_a);
        // console.log(number_b);
        if (number_a > number_b) {
            return -1;
        }
        if (number_a < number_b) {
            return 1;
        }
        return 0;
    }

    function drawChartPie(res) {
        let dataChart = [];
        let dts = res.filter.dataSet;
        if (dts) {
            switch ($('select#percent_listing').val()) {
                case 'doanhthu':
                    let temp_dts = 0;
                    let temp_dataChart = [];
                    dts.forEach(function (e, t) {
                        // console.log(t);
                        // console.log('value');
                        // console.log(e);
                        temp_dataChart.push({label: e[0], value: parseInt((e[7]).replace(/,/g, ''))});
                    });
                    temp_dataChart.sort(compare);

                    temp_dataChart.forEach(function (e, t) {
                        if (t < 4) {
                            dataChart.push(e);
                        } else {
                            temp_dts += e.value;
                        }
                    })
                    if (temp_dts !== 0) {
                        dataChart.push({label: 'Khác', value: temp_dts})
                    }
                    break;
                default:

                    break;
            }
        }
        let config = {
            type: 'pie',
            data: {
                datasets: [{
                    data: [null, undefined].includes(dataChart) ? 0 : dataChart.map(function (e) {
                        return e.value;
                    }),
                    backgroundColor: [
                        window.chartColors.red,
                        window.chartColors.orange,
                        window.chartColors.yellow,
                        window.chartColors.green,
                        window.chartColors.blue,
                    ],
                }],
                labels: [null, undefined].includes(dataChart) ? 'Undefined Name' : dataChart.map(function (e) {
                    return e.label;
                }),
            },
            options: {
                plugins: {
                    datalabels: {
                        formatter: (value, ctx) => {
                            let sum = 0;
                            let dataArr = ctx.chart.data.datasets[0].data;
                            dataArr.map(data => {
                                sum += data;
                            });
                            let percentage = (value * 100 / sum).toFixed(2) + "%";
                            return percentage;
                        },
                        color: '#fff',
                    }
                },
                responsive: true,
                tooltips: {
                    enabled: false,
                    /*callbacks: {
                        label: function (tooltipItem, data) {
                            return data['labels'][tooltipItem['index']] + ': ' + data['datasets'][0]['data'][tooltipItem['index']] + '%';
                        }
                    }*/
                }
            }
        };
        if (myPieChart) {
            myPieChart.destroy();
        }
        var overview_miniPie = document.getElementById('overview-miniPie').getContext('2d');
        myPieChart = new Chart(overview_miniPie, config);
    }

    function drawChart(data) {
        // overview-graph-lineChart
        var config = {};
        var dataDate = data.dataDate === undefined ? [] : data.dataDate.map(function (e) {
            var a = new Date(e * 1000)
            var options = {year: 'numeric', month: 'numeric', day: 'numeric'};
            return a.toLocaleDateString('vi-VN', options);
        });
        var dataTitle = "Doanh Thu Biểu Đồ";
        let singlelinecolor = "#2793DB";

        config = {
            type: 'bar',
            data: {
                labels: data.dataLabel,
                datasets: [{
                    label: data.dataSet.dathen.label,
                    backgroundColor: "pink",
                    borderColor: "red",
                    borderWidth: 1,
                    stack: 'dathen',
                    data: Object.values(data.dataSet.dathen.data),
                    yAxisID: 'y-axis-1',

                }, {
                    label: data.dataSet.dathenfail.label,
                    backgroundColor: "#FFA48F",
                    borderColor: "#FF6F6B",
                    borderWidth: 1,
                    stack: 'dathen',
                    data: Object.values(data.dataSet.dathenfail.data),
                    yAxisID: 'y-axis-1',

                }, {
                    label: data.dataSet.den.label,
                    backgroundColor: "#82B4FF",
                    borderColor: "#2D27E8",
                    borderWidth: 1,
                    stack: 'den',
                    data: Object.values(data.dataSet.den.data),
                    yAxisID: 'y-axis-1',

                }, {
                    label: data.dataSet.khongden.label,
                    backgroundColor: "#A4CBFF",
                    borderColor: "#79A2FF",
                    borderWidth: 1,
                    stack: 'den',
                    data: Object.values(data.dataSet.khongden.data),
                    yAxisID: 'y-axis-1',

                }, {
                    label: data.dataSet.lam.label,
                    backgroundColor: "#B4EB2F",
                    borderColor: "#13EB19",
                    borderWidth: 1,
                    stack: 'lam',
                    data: Object.values(data.dataSet.lam.data),
                    yAxisID: 'y-axis-1',

                }, {
                    label: data.dataSet.khonglam.label,
                    backgroundColor: "#B7EBB9",
                    borderColor: "#86EB57",
                    borderWidth: 1,
                    stack: 'lam',
                    data: Object.values(data.dataSet.khonglam.data),
                    yAxisID: 'y-axis-1',

                },

                    {
                        label: data.dataSet.total_customer.label,
                        backgroundColor: "transparent",
                        borderColor: "orange",
                        borderWidth: 1,
                        data: Object.values(data.dataSet.total_customer.data),
                        type: "line"
                    }]
            },
            options: {
                responsive: true,
                plugins: {
                    datalabels: {
                        // hide datalabels for all datasets
                        display: false
                    }
                },
                title: {
                    display: true,
                    text: 'Biểu Đồ Thống Kê Nhân Khẩu Học'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        title: function (t, d) {
                            var a = new Date(t[0].xLabel * 1000)
                            var options = {year: 'numeric', month: 'numeric', day: 'numeric'};
                            return a.toLocaleDateString('vi-VN', options);
                        }
                    }
                },
                hover: {
                    mode: 'closet',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        tipe: "time",
                        scaleLabel: {
                            display: true,
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
                        ticks: {
                            callback: function (e) {
                                var a = new Date(e * 1000)
                                var options = {year: 'numeric', month: 'numeric', day: 'numeric'};
                                return a.toLocaleDateString('vi-VN', options);
                            }
                        }
                    },


                    ],
                    yAxes: [{
                        type: 'linear', // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: true,
                        position: 'left',
                        scaleLabel: {
                            display: true,
                            labelString: 'Total'
                        },
                        id: 'y-axis-1',
                    },],
                },
            }
        };

        var ctx = document.getElementById('overview-graph-lineChart').getContext('2d');

        /*
            labels: array of days to show in the xAxes, you can build it like: labels: ['period1day1#period2day2', 'period1day2#period2day2']...
          values_first: period 1 values of every day in the labels array
          values_second: period 2 values of every day in the labels array
        */

        if (chartCompare) {
            chartCompare.destroy();
        }
        chartCompare = new Chart(ctx, config);

    }


});
