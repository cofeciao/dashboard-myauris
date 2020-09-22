<?php
/**
 * Created by PhpStorm.
 * User: luken
 * Date: 8/17/2020
 * Time: 10:54
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Smart Report';
$css = <<< CSS
.table th, .table td {
padding: 0.75rem 1.5rem;
}
.table thead th {
border-bottom: 1px solid #e3ebf3;
}
.card-header {
padding-bottom: 1.5rem
}
.filter-group {
margin-bottom: 1.5rem
}
CSS;
$this->registerCss($css);
?>
<div id="smart-report">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title font-weight-600"><?= $this->title ?></h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="filter-group">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div id="date-filter">
                                        <div class="form-group">
                                            <?= \dosamigos\datepicker\DatePicker::widget([
                                                'name' => 'datepicker',
                                                'id' => 'datepicker',
                                                'clientOptions' => [
                                                    'autoclose' => true,
                                                    'format' => 'mm-yyyy',
                                                    'minViewMode' => 'months',
                                                ]
                                            ]) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <?= Html::dropDownList('danh-muc-chi', null, ArrayHelper::map($danhMucChi, 'id', 'name'), [
                                            'class' => 'ui dropdown form-control search',
                                            'prompt' => 'Danh mục chi..', 'id' => 'expenses-list'
                                        ]);
                                        ?>
                                        <span class="input-group-addon clear-option"><span
                                                    class="fa fa-times"></span></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <?= Html::dropDownList('nhom-chi', null, [], [
                                            'class' => 'ui dropdown form-control search',
                                            'prompt' => 'Nhóm chi..', 'id' => 'expenses-group'
                                        ]) ?>
                                        <span class="input-group-addon clear-option"><span
                                                    class="fa fa-times"></span></span>
                                    </div>
                                </div>
                                <div class="col-sm-3 d-none">
                                    <div class="input-group">
                                        <?= Html::dropDownList('khoan-chi', null, [], [
                                            'class' => 'ui dropdown form-control search', 'prompt' => 'Khoản chi..', 'id' => 'expenses'
                                        ]) ?>
                                        <span class="input-group-addon clear-option"><span
                                                    class="fa fa-times"></span></span>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <?php echo Html::a('<i class="fa fa-plus"> Cập nhật</i>', ['create'], ['title' => 'Cập nhật', 'data-pjax' => 0, 'class' => 'btn btn-default pull-left']) ?>
                                </div>
                            </div>
                        </div>
                        <div id="content-data" class="content-data">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$urlGetExpensesList = Url::toRoute('get-data');
$urlGetExpensesGroup = Url::toRoute('get-expenses-group');
$urlGetExpenses = Url::toRoute('get-expenses');
$urlGetData = Url::toRoute('get-data');

$script = <<< JS
var csrf = $('meta[name=csrf-token]').attr('content') || null;
var d = new Date(),
    day = d.getDate(),
    month = d.getMonth(),
    year = d.getFullYear(),
    last_DoM = new Date(year, month + 1, 0).getDate();
var startDateReport = '1-' + (month + 1) + '-' + year,
    endDateReport = day + '-' + (month +1) + '-' + year,
    lastDateReport = last_DoM + '-' + (month +1) + '-' + year;

// $('.dateranges').daterangepicker({
//     opens: 'right',
//     formatSubmit: 'D/M/Y',
//     timeZone: 'Asia/Ho_Chi_Minh',
//     showDropdowns: true,
//     timePicker: false,
//     format: 'DD/MM/YYYY',
//     startDate:  moment().startOf('month'),
//     ranges: {
//         // 'Hôm nay': [moment(), moment()],
//         // 'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
//         // '7 ngày trước': [moment().subtract(6, 'days'), moment()],
//         // '30 ngày trước': [moment().subtract(29, 'days'), moment()],
//         'Tháng hiện tại': [moment().startOf('month'), moment()],
//         'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
//     },
//     locale: {
//         format: 'D/M/Y',
//         cancelLabel: 'Xóa',
//         applyLabel: 'Cập nhật',
//         daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
//         monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
//             "customRangeLabel": "Tùy chọn",
//     },
//     autoclose: true,
//     maxDate: moment(),
// },
// function(start, end) {
//     startDateReport = start.format('DD-MM-Y');
//     endDateReport = end.format('DD-MM-Y');
// });

$(function () {
    $('#expenses-list').on('change', function() {
        var idDMChi = $(this).val() || null;
        
        $.post('$urlGetExpensesGroup', { idDMChi: idDMChi }, function (data) {
            $('#expenses-group option[value!=""]').remove();
            $.each(data.expensesGroup, function (i, v) {
                var option = '<option value="' + v.id + '">' + v.name + '</option>';
                $('#expenses-group').append(option);
            });
        });
        
        loadData(startDateReport, lastDateReport);
    });
    
    $('#expenses-group').on('change', function() {
        loadData(startDateReport, lastDateReport);
    });
    
    $('#datepicker').on('change', function () {
        var dateInpput = $(this).val(),
            arr = dateInpput.split("-"),
            lastDayOfMonth = new Date(parseInt(arr[1]), parseInt(arr[0]), 0).getDate(),
            startDateReport = '01-' + dateInpput,
            lastDateReport = lastDayOfMonth + '-' + dateInpput;
        console.log(startDateReport, lastDateReport);
        loadData(startDateReport, lastDateReport);
    });
    
    loadData(startDateReport, lastDateReport);
});

function loadData(from, to) {
    var idDMChi = $('#expenses-list').val() || null,
        idNhomChi = $('#expenses-group').val() || null;
    
    $('#content-data').myLoading({msg: 'Đang tải dữ liệu...'});
    $.get('$urlGetData', {idDMChi: idDMChi, idNhomChi: idNhomChi, from: from, to: to}, function (data) {
        $('#content-data').html(data).myUnloading();
    })
}
JS;
$this->registerJs($script, \yii\web\View::POS_END);
?>

