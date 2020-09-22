<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 24-May-19
 * Time: 4:26 PM
 */

use yii\helpers\Html;

$this->title = 'Pancake Upload Data';
?>
    <section id="dom">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <div class="btn-add-campaign clearfix" style="margin-top:0px;position:relative">
                                 Pancake Upload Data
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3 col-3">
                                        <div class="input-group">
                                            <input type="text" class="form-control filter-data-from-online-report">
                                            <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <span class="fa fa-calendar"></span>
                                                    </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-1 col-1">
                                        <?= Html::button('Lưu nhân viên', ['class' => 'btn btn-sx btn-primary', 'id' => 'help-pancake']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
$urlDel = \yii\helpers\Url::toRoute('data');
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






$('body').on('click', '#help-pancake', function() {
    
    $('body').myLoading({
        fixed:true,
        msg: "Kiểm tra dữ liệu",
    });
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '$urlDel',
        data:{
            startDateReport:startDateReport,
            endDateReport:endDateReport
        }
    }).done(function(res) {
        console.log(res);
        toastr.success(res.msg, 'Thông báo');
        $('body').myUnloading();
    }).fail(function(err) {
        toastr.error(err.msg, 'Lỗi');
        $('body').myUnloading();
    });
});

JS;
$this->registerJs($script, \yii\web\View::POS_END);
