<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 19-04-2019
 * Time: 07:00 PM
 */
?>
<div class="sp-title">Lịch điều trị</div>
<div class="sp-content">
    <div class="form-group row c-col-2">
        <div class="col-6 c-group">
            <label class="control-label c-label">Ekip bác sĩ:</label>
            <div class="c-info"><?= $model->ekipInfoHasOne ? $model->ekipInfoHasOne->fullname : '-' ?></div>
        </div>
        <div class="col-6 c-group">
            <label class="control-label c-label">Thời gian điều trị:</label>
            <div class="c-info"><?= $model->time_dieu_tri ? date('d-m-Y', $model->time_dieu_tri) : '-' ?></div>
        </div>
    </div>
    <div class="form-group row c-col-2">
        <div class="col-6 c-group">
            <label class="control-label c-label">Bắt đầu:</label>
            <div class="c-info"><?= $model->time_start ? date('d-m-Y', $model->time_start) : '-' ?></div>
        </div>
        <div class="col-6 c-group">
            <label class="control-label c-label">Kết thúc:</label>
            <div class="c-info"><?= $model->time_end ? date('d-m-Y', $model->time_end) : '-' ?></div>
        </div>
    </div>
    <div class="form-group row c-col-2">
        <div class="col-12 c-group">
            <label class="control-label c-label">Hướng điều trị:</label>
            <div class="c-info"><?= $model->huong_dieu_tri ?></div>
        </div>
    </div>
    <div class="form-group row c-col-2">
        <div class="col-12 c-group">
            <label class="control-label c-label">Ghi chú:</label>
            <div class="c-info"><?= $model->note ?></div>
        </div>
    </div>
</div>