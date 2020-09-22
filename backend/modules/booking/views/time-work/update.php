<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\booking\models\TimeWork */

$this->title = Yii::t('backend', 'Update');
$this->params['breadcrumbs'][] = ['label' => 'Time Works', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="modal-header bg-blue-grey bg-lighten-2 white">
    <h4 class="modal-title"><?=  $this->title.': ' . $model->id; ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?= $this->render('_form', [
    'model' => $model,
]) ?>