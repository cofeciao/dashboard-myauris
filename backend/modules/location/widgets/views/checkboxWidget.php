<?php
$id = '';
if (isset($model)) {
    $id = $model->id;
}
?>
<div class="checkbox-toggle">
    <input type="checkbox" id="check-toggle-<?= $id; ?>"
           class="check-toggle"
           value="<?= $id; ?>" <?php if (isset($model) && $model->IsPublished == 1) {
    echo 'checked';
} ?> />
    <label for="check-toggle-<?= $id; ?>"></label>
</div>