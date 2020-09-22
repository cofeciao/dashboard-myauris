<?php
$this->title = Yii::t('backend', 'Cập nhật ngày: ' . date('d-m-Y', $model->ngay_chay));
?>
    <div class="modal-header bg-blue-grey bg-lighten-2 white">
        <h4 class="modal-title"><?= $this->title; ?></span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?= $this->render('_form', [
    'model' => $model
]) ?>