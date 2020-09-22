<?php
$this->title = Yii::t('backend', 'Update');
?>
<div class="modal-header bg-blue-grey bg-lighten-2 white">
    <h4 class="modal-title"><?= $this->title . ': ' . $model->name; ?></span></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?= $this->render('_form-ajax', [
    'model' => $model,
]) ?>