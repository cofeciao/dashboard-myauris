<div class="modal-header bg-blue-grey bg-lighten-2 white">
    <h4 class="modal-title">Thêm mới <span><?= $chienDich->name; ?></span></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<?= $this->render('_form-campaign-ajax', [
    'model' => $model,
    'chienDich' => $chienDich,
]) ?>