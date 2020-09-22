<?php
if ($idModal != '') {
    $idM = $idModal;
} else {
    $idM = 'imgModal';
}
?>
<div class="modal fade text-left" id="<?= $idM; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel1">Quản lý hình ảnh</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <iframe src="<?= FRONTEND_HOST_INFO; ?>/5F4143DD0785DD1BC9590C016B6EFB53/dialog.php?type=2&field_id=<?= $id; ?>"
                        style="width: 100%; height: 500px;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn grey btn-outline-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
