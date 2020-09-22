<div class="modal-header bg-blue-grey bg-lighten-2 white d-block">
    <div class="d-flex">
        <h4 class="modal-title">Tên Campaign/Nhóm Test: <span><?= $model->name; ?></span></span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <h5 class="modal-subtitle">Chiến dịch: <span><?= $chienDich->name; ?></span></h5>
</div>
<?= $this->render('_form-campaign-ajax', [
    'model' => $model,
    'chienDich' => $chienDich,
    'readonly' => $readonly,
]) ?>