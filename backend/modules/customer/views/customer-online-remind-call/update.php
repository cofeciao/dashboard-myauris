<?php

/* @var $this yii\web\View */
/* @var $model backend\modules\customer\models\Dep365CustomerOnlineRemindCall */

$this->title = Yii::t('backend', 'Update');
if ($model->customerHasOne != null) {
    $this->title .= ': ' . ($model->customerHasOne->full_name != null ? $model->customerHasOne->full_name : $model->customerHasOne->name);
}
$this->params['breadcrumbs'][] = ['label' => 'Dep365 Customer Online Remind Calls', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
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