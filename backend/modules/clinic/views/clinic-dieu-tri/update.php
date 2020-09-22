<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\clinic\models\PhongKhamLichDieuTri */

$this->title = Yii::t('backend', 'Update');
$this->params['breadcrumbs'][] = ['label' => 'Phòng khám lịch điều trị', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
if ($customer) {
    $name = $customer->full_name == null ? $customer->forename : $customer->full_name;
}
?>

<div class="modal-header bg-blue-grey bg-lighten-2 white">
    <h4 class="modal-title">Lịch điều trị: <?= $name; ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?= $this->render('_form', [
    'model' => $model,
    'customer' => $customer,
]) ?>