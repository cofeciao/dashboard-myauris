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


$('body').on('change', '.filter-data-from-online-report, #page-online, #province', function() {
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
   page_online = $('#page-online').val();
   province = $('#province').val() || null;
   
   $.ajax({
       url: '$urlData',
       method: "POST",
       dataType: "json",
       data:{
           "startDateReport": startDateReport, 
           "endDateReport":endDateReport, 
           "page_online": page_online, 
           "province": province
       },
       success: function (data) {
           // console.log(data);
           KhachDen = data.data.aResult_KhachDen;
            KhachChot = data.data.aResult_KhachChot;
            ChotFail = data.data.aResult_ChotFail;
            KhongDen = data.data.aResult_KhongDen;
            LichHen = data.data.aResult_LichHen;
            listNameProvince = data.data.listNameProvince;
            DoanhThu = data.data.aResult_DoanhThu;
           // console.log(KhachDen,KhachChot, ChotFail, KhongDen,LichHen, listNameProvince );
           formatData(KhachDen,KhachChot, ChotFail, KhongDen,LichHen,DoanhThu, listNameProvince );
       }
    });
}
 var BigArray = [];
var SumArray =[];
function formatData(KhachDen,KhachChot, ChotFail, KhongDen,LichHen,DoanhThu, listNameProvince ){
    BigArray = [];
    var sumLichHen = sumKhachDen = sumKhongDen = sumKhachChot = sumChotFail = sumDoanhThu = 0;
    for (let i = 0; i < listNameProvince.length; i++) {
         objKhachDen = KhachDen.find(function(element){
            return element.province === listNameProvince[i].province;
        });
         objKhachChot = KhachChot.find(function(element){
            return element.province === listNameProvince[i].province;
        });
         objChotFail = ChotFail.find(function(element){
            return element.province === listNameProvince[i].province;
        });
        
         objKhongDen = KhongDen.find(function(element){
            return element.province === listNameProvince[i].province;
        });
         objLichHen = LichHen.find(function(element){
            return element.province === listNameProvince[i].province;
        });
         
         objDoanhThu = DoanhThu.find(function(element){
            return element.province === listNameProvince[i].province;
        });
         
        itemArray = {
            'name' : listNameProvince[i].name,
            'countLichHen' : (objLichHen !== undefined) ? objLichHen.count : 0,
            'countKhachDen' : (objKhachDen !== undefined) ? objKhachDen.count : 0,
            'countKhongDen' : (objKhongDen !== undefined) ? objKhongDen.count : 0,
            'countKhachChot' : (objKhachChot !== undefined) ? objKhachChot.count : 0,
            'countChotFail' : (objChotFail !== undefined) ? objChotFail.count : 0,
            'totalDoanhThu' :  (objDoanhThu !== undefined) ? objDoanhThu.sum : 0  // sum doanh thu
        };
        sumLichHen += (objLichHen !== undefined) ? objLichHen.count : 0;
        sumKhachDen += (objKhachDen !== undefined) ? objKhachDen.count : 0;
        sumKhongDen += (objKhongDen !== undefined) ? objKhongDen.count : 0;
        sumKhachChot += (objKhachChot !== undefined) ? objKhachChot.count : 0;
        sumChotFail += (objChotFail !== undefined) ? objChotFail.count : 0;
        sumDoanhThu +=  (objDoanhThu !== undefined) ? objDoanhThu.sum : 0;
        
        BigArray.push(itemArray);
    }
    SumArray = {
        'sumLichHen' : sumLichHen,
        'sumKhachDen' : sumKhachDen,
        'sumKhongDen' : sumKhongDen,
        'sumKhachChot' : sumKhachChot,
        'sumChotFail' : sumChotFail,
        'sumDoanhThu' : sumDoanhThu
    };
    
    // BigArray = BigArray.sort(function(a,b) {
    //     return b.countLichHen - a.countLichHen;
    // }); // sort
     BigArray = BigArray.sort(function(a,b) {
        return b.totalDoanhThu - a.totalDoanhThu;
    }); // sort
    
    buildTable(BigArray);
}

function buildTable(aData) {
      var result  = '';
      var stt = 0;
      
       var sdoanhthu = numeral(SumArray.sumDoanhThu);
        result += '<tr  class="font-weight-bold">';
        result += '<td scope="row"></td>';
        result += '<td class="text-left"><b>Tổng</b></td>';
        result += '<td>'+SumArray.sumLichHen+'</td>';
        result += '<td>'+SumArray.sumKhachDen+'</td>';
        result += '<td>'+SumArray.sumKhongDen+'</td>';
        result += '<td>'+SumArray.sumKhachChot+'</td>';
        result += '<td>'+SumArray.sumChotFail+'</td>';
        result += '<td>'+sdoanhthu.format('0,0')+'</td>';
        result += '</tr>';
      
  for (let i = 0; i < aData.length; i++) {
      stt++;
      item = aData[i];
      var doanhthu = numeral(item.totalDoanhThu);
        result += '<tr>';
        result += '<td scope="row">'+stt+'</td>';
        result += '<td class="text-left">'+item.name+'</td>';
        result += '<td>'+item.countLichHen+'</td>';
        result += '<td>'+item.countKhachDen+'</td>';
        result += '<td>'+item.countKhongDen+'</td>';
        result += '<td>'+item.countKhachChot+'</td>';
        result += '<td>'+item.countChotFail+'</td>';
        result += '<td>'+doanhthu.format('0,0')+'</td>';
        result += '</tr>';
  }
  $("#table-report").html(result);
}

JS;
$this->registerJS($script, View::POS_END);
