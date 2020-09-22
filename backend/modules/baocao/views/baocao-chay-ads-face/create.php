<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\baocao\models\BaocaoChayAdsFace */

$this->title = Yii::t('backend', 'Tạo mới báo cáo');
$this->params['breadcrumbs'][] = ['label' => 'Baocao Chay Ads Faces', 'url' => ['index']];
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