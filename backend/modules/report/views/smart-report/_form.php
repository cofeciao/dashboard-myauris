<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\report\models\SmartReportModel */
/* @var $form yii\widgets\ActiveForm */

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
.table .form-control {
    text-align: right;
}
CSS;
$this->registerCss($css);
?>
    <div class="smart-report-model-form" style="position: relative; padding-bottom: 52px;">

        <?php $form = ActiveForm::begin([
            'id' => 'form-smart-report',
            'class' => 'form form-horizontal',
            'enableAjaxValidation' => true,
            'enableClientValidation' => true,
            'validationUrl' => Url::toRoute(['validate-smart-report']),
            'action' => Url::toRoute(['submit-smart-report']),
        ]); ?>
        <div class="form-content">

            <div class="row justify-content-between">
                <div class="col-sm-3">
                    <?= $form->field($model, 'report_timestamp')->widget(\dosamigos\datepicker\DatePicker::class, [
                        'template' => '{input}{addon}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>',
                        'clientOptions' => [
                            'viewMode' => 'months',
                            'minViewMode' => 'months',
                            'format' => 'mm-yyyy',
                            'autoclose' => true,
                        ],
                        'clientEvents' => [],
                    ]) ?>
                </div>
                <div class="col-sm-3">
                    <?php echo Html::a('Xem báo cáo', ['index'], ['title' => 'Xem báo cáo', 'data-pjax' => 0, 'class' => 'btn btn-default pull-right mt-2']) ?>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th colspan="3"></th>
                        <th class="text-right font-weight-bold">Đã chi trong tháng</th>
                        <th class="text-right font-weight-bold">Chờ duyệt</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($data as $dm_id => $dm_chi) {
                        ?>
                        <tr>
                            <td class="text-center bg-yellow font-weight-bold"
                                colspan="3"><?= $dm_chi['name'] ?></td>
                            <td colspan="5"></td>
                        </tr>
                        <?php
                        foreach ($dm_chi['nhom_chi'] as $nhomchi_id => $nhom_chi) {
                            ?>
                            <tr>
                                <td class="text-left bg-blue bg-lighten-3"
                                    colspan="3"><?= $nhom_chi['name'] ?></td>
                                <td class="text-right font-weight-bold"></td>
                                <td class="text-right font-weight-bold"></td>
                            </tr>
                            <?php
                            foreach ($nhom_chi['khoan_chi'] as $khoanchi_id => $khoan_chi) {
                                ?>
                                <tr>
                                    <td><?= $nhom_chi['code'] ?></td>
                                    <td><?= $khoan_chi['code'] ?></td>
                                    <td><?= $khoan_chi['name'] ?></td>
                                    <td class="text-right">
                                        <?= $form->field($model, 'data[' . $khoanchi_id . '][tien_da_chi]')->textInput(['class' => 'form-control on-keyup', 'maxlength' => true])->label(false) ?>
                                    </td>
                                    <td class="text-right">
                                        <?= $form->field($model, 'data[' . $khoanchi_id . '][tien_cho_duyet]')->textInput(['class' => 'form-control on-keyup', 'maxlength' => true])->label(false) ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="form-actions text-right" style="position: fixed; bottom: 0; left: 0; right: 0; margin-top: 0; background-color: #fff; padding: 10px 20px;">
            <?= Html::resetButton('<i class="ft-x"></i> Cancel', ['class' =>
                'btn btn-warning mr-1']) ?>
            <?= Html::submitButton('<i class="fa fa-check-square-o"></i> Save',
                ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php
$urlGetData = Url::toRoute('create');
$script = <<< JS
$("body").find('.on-keyup').unbind('keyup').bind('keyup', function () {
    var order_discount  = $(this).val().replace(/[^0-9]/gi, '');
    order_discount = order_discount.replace(/\./g, '');
    if(order_discount.trim() != '')
        $(this).val(addCommas(parseInt(order_discount)));
});

$('#form-smart-report').on('beforeSubmit', function(e) {
    e.preventDefault();
    var formData = $('#form-smart-report').serialize();
    
    $.ajax({
        url: $('#form-smart-report').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: formData,
    }).done(function (response) {
        toastr.success(response.msg, 'Thông báo');
    });
    return false;    
});
$('body').on('change', '#smartreportform-report_timestamp', function () {
    var dateInpput = $(this).val(),
        arr = dateInpput.split("-"),
        lastDayOfMonth = new Date(parseInt(arr[1]), parseInt(arr[0]), 0).getDate(),
        startDateReport = '01-' + dateInpput,
        endDateReport = lastDayOfMonth + '-' + dateInpput;
    
    // loadData(startDateReport, endDateReport);
    window.location.href='$urlGetData?from='+startDateReport+'&to='+endDateReport;
})

function loadData(from, to) {
    var idDMChi = $('#expenses-list').val() || null,
        idNhomChi = $('#expenses-group').val() || null;
    
    $('#content-data').myLoading({msg: 'Đang tải dữ liệu...'});
    $.get('$urlGetData', {idDMChi: idDMChi, idNhomChi: idNhomChi, from: from, to: to}, function (data) {
        $('#content-data').empty().html(data).myUnloading();
    })
}
JS;
$this->registerJs($script, \yii\web\View::POS_END);
?>