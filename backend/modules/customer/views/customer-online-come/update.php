<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\customer\models\Dep365CustomerOnlineCome */

$this->title = Yii::t('backend', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Khách hàng Online'), 'url' => ['/customer/customer-online']];
$this->params['breadcrumbs'][] = ['label' => 'Trạng thái khách đến', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="modal-header bg-blue-grey bg-lighten-2 white">
    <h4 class="modal-title"><?=  $this->title.': ' . $model->name; ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?= $this->render('_form', [
    'model' => $model,
]) ?>
