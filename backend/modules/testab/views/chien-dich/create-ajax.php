<?php
$this->title = Yii::t('backend', 'Create');

if (isset($error) && $error != null) {
    ?>
<div class="modal-header bg-warning white">
    <h4 class="modal-title">Warning!</span></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body border-0">
    <p class="text-center"><?= $error ?></p>
</div>
<div class="modal-footer border-0 d-flex justify-content-center">
    <?= \yii\helpers\Html::button('OK', ['class' => 'btn btn-md btn-outline-warning btn-min-width', 'data-dismiss' => 'modal'])?>
</div>
    <?php
} else {
        ?>
    <div class="modal-header bg-blue-grey bg-lighten-2 white">
        <h4 class="modal-title"><?= $this->title; ?></span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <?= $this->render('_form-ajax', [
        'model' => $model,
    ]) ?>

<?php
    } ?>
