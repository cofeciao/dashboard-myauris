<?php
use yii\helpers\Html;
?>
<div class="modal-footer">
    <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
        'btn btn-warning mr-1']) ?>
    <?= Html::submitButton(
        '<i class="fa fa-check-square-o"></i> Save',
        ['class' => 'btn btn-primary block-menu-left', 'data-pjax' => 0]
    ) ?>
</div>