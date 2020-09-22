<?php
$customer = '';
if (Yii::$app->controller->action->id == 'create') {
    $title = 'Thêm mới';
}

if (Yii::$app->controller->action->id == 'update') {
    $customer = $model->name;
    $title = 'Cập nhật khuyến mãi: ';
}
?>
<div class="modal-header bg-blue-grey bg-lighten-2 white">
    <h4 class="modal-title"><?= $title; ?> <span><?= $customer; ?></span></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?= $this->render('_form', [
    'model' => $model
]) ?>
