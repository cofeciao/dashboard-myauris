<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\clinic\models\PhongKhamDonHangWThanhToan */

$title = 'Cập nhật';
if ($model->tam_ung != null && array_key_exists($model->tam_ung, \backend\models\doanhthu\ThanhToanModel::THANHTOAN_TYPE)) {
    $title .= ' ' . mb_strtolower(\backend\models\doanhthu\ThanhToanModel::THANHTOAN_TYPE[$model->tam_ung]);
}
if ($model->customerHasOne != null) {
    $title .= ': ' . ($model->customerHasOne->full_name != null ? $model->customerHasOne->full_name : $model->customerHasOne->name);
}
?>
    <div class="modal-header bg-blue-grey bg-lighten-2 white">
        <h4 class="modal-title"><?= $title; ?> <span></span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?= $this->render('_form', [
    'model' => $model,
    'listThanhToan' => $listThanhToan,
    'readOnly' => $readOnly
]) ?>