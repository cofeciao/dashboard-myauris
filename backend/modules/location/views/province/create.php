<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\location\models\Province */

$this->title = Yii::t('backend', 'Create');
$this->params['breadcrumbs'][] = ['label' => 'Location', 'url' => ['/location']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Province'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="modal-header bg-blue-grey bg-lighten-2 white">
    <h4 class="modal-title"><?= $this->title; ?></span></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?= $this->render('_form', [
    'model' => $model,
]) ?>
