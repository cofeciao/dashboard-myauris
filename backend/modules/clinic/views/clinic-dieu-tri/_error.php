<?php
/* @var $class string */

/* @var $error string */

use yii\helpers\Html;

if (!isset($class)) $class = 'warning';
?>
<div class="modal-header bg-blue-grey bg-lighten-2 white">
    <h4 class="modal-title">Error</h4>
    <button type="button" class="close" id="custom-modal-close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="alert alert-<?= $class ?>"><?= $error ?></div>
</div>
<div class="modal-footer">
    <?= Html::label(
        '<i class="fa fa-times"></i> Đóng',
        'custom-modal-close',
        ['class' => 'btn btn-' . $class . ' block-menu-left', 'data-pjax' => 0]
    ) ?>
</div>