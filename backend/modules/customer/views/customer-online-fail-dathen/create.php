<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\customer\models\Dep365CustomerOnlineFailDathen */

$this->title = Yii::t('backend', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Online Support'), 'url' => ['/customer']];
$this->params['breadcrumbs'][] = ['label' => 'Đặt hẹn Fail', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="modal-header bg-blue-grey bg-lighten-2 white">
        <h4 class="modal-title"><?= $this->title; ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?= $this->render('_form', [
    'model' => $model,
]) ?>