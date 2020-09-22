<?php

use yii\helpers\Url;
use yii\web\View;

$urlData = Url::toRoute('get-data');
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


$('body').on('change', '.filter-data-from-online-report, #page-online', function() {
    callData();
});


$(document).ready(function() {
    $(window).ready(function(){
        callData();
    });
});


function callData() {
    // $('#tuongtac-line, #sdt-line, #call-line, #calendar-new-line, #calendar-about-line, #auris-line, #hieu-qua-line, #hieu-suat-line').css('border', '1px solid #CDCDCD');
    // $('#tuongtac-line, #sdt-line, #call-line, #calendar-new-line, #calendar-about-line, #auris-line, #hieu-qua-line, #hieu-suat-line').myLoading({msg:'Đang tải dữ liệu...'});

   reason_fail = $('#reason-fail').val();
   // page_online = $('#page-online').val();
   
   $.ajax({
       url: '$urlData',
       method: "POST",
       dataType: "json",
       data:{
           "startDateReport": startDateReport, 
           "endDateReport":endDateReport, 
       },
       success: function (data) {
           aData = data.data;
           formatData(aData);
       }
    });
}
var SumArray =[];
function formatData(data){
    var sumLichHen = sumKhachDen = sumKhachLam = sumTuongTac = sumLichMoi = 0;
    for(let i = 0; i < data.length; i++) {
        sumLichMoi += parseInt(data[i].lich_moi);
        sumLichHen += parseInt(data[i].tong_lich_hen);
        sumKhachDen += parseInt(data[i].tong_khach_den);
        sumKhachLam += parseInt(data[i].tong_khach_lam);
        sumTuongTac += (data[i].tuong_tac !== null) ? parseInt(data[i].tuong_tac) : 0;
    }
    SumArray = {
        'sumLichMoi' : numeral(sumLichMoi).format('0,0'),
        'sumLichHen' : numeral(sumLichHen).format('0,0'),
        'sumKhachDen' : numeral(sumKhachDen).format('0,0'),
        'sumKhachLam' : numeral(sumKhachLam).format('0,0'),
        'sumTuongTac' : numeral(sumTuongTac).format('0,0'),
    };
    
    data = data.sort(function(a,b) {
        return b.tong_khach_lam - a.tong_khach_lam;
    }); // sort

    buildTable(data);
}

function buildTable(data) {
      var result  = '';
      var stt = 0;
      
        result += '<tr  class="font-weight-bold">';
        result += '<td scope="row"></td>';
        result += '<td class="text-left"><b>Tổng</b></td>';
        result += '<td>'+SumArray.sumTuongTac+'</td>';
        result += '<td>'+SumArray.sumLichMoi+'</td>';
        result += '<td>'+SumArray.sumLichHen+'</td>';
        result += '<td>'+SumArray.sumKhachDen+'</td>';
        result += '<td>'+SumArray.sumKhachLam+'</td>';
        result += '</tr>';
      
  for (let i = 0; i < data.length; i++) {
      stt++;
      item = data[i];
        var tuong_tac =  (item.tuong_tac !== null) ? numeral(item.tuong_tac) : numeral(0);
        var lich_moi =  (item.lich_moi !== null) ? numeral(item.lich_moi) : numeral(0);

        tuong_tac = tuong_tac.format('0,0');
        lich_moi = lich_moi.format('0,0');

        result += '<tr>';
        result += '<td scope="row">'+stt+'</td>';
        result += '<td class="text-left">'+item.fullname+'</td>';
        result += '<td>'+tuong_tac+'</td>';
        result += '<td>'+lich_moi+'</td>';
        result += '<td>'+item.tong_lich_hen+'</td>';
        result += '<td>'+item.tong_khach_den+'</td>';
        result += '<td>'+item.tong_khach_lam+'</td>';
        result += '</tr>';
  }
  $("#table-report").html(result);
}

JS;
$this->registerJS($script, View::POS_END);
