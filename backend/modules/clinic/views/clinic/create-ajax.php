<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 29-Apr-19
 * Time: 11:25 AM
 */

$customer = '';
if (Yii::$app->controller->action->id == 'create') {
    $title = 'Thêm mới';
}

if (Yii::$app->controller->action->id == 'render-and-update') {
    $customer = $model->full_name == null ? $model->name : $model->full_name;
    $title = 'Cập nhật khách: ';
}

?>
<div class="modal-header bg-blue-grey bg-lighten-2 white">
    <h4 class="modal-title"><?= $title; ?> <span><?= $customer; ?></span></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?= $this->render('_form', [
    'model' => $model,
    'listAccept' => $listAccept
]) ?>
