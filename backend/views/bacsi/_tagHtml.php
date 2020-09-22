<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="tag tag-<?= $model->tag ?>" data-tag="<?= $model->tag ?>">
    <?php
    $form = ActiveForm::begin([
        'options' => [
            'id' => 'form-tag-' . $model->tag,
            'class' => 'form-tag'
        ],
    ]);
    ?>
    <div class="tag-name">
        #<span><?= $model->tag ?></span>
    </div>
    <div class="tag-tham-kham">
        <?= $form->field($model, 'ketqua_thamkham')->textInput(['class' => 'form-control ketqua-thamkham', 'data-old' => $model->ketqua_thamkham, 'autocomplete' => 'off'])->label(false) ?>
    </div>
    <div class="tag-save">
        <i class="fa fa-check"></i>
    </div>
    <div class="tag-remove">
        <i class="ft-trash-2"></i>
    </div>
    <?= $form->field($model, 'id')->hiddenInput(['class' => 'key'])->label(false) ?>
    <?= $form->field($model, 'customer_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'tag')->hiddenInput(['class' => 'ipt-tag-name'])->label(false) ?>
    <?php ActiveForm::end() ?>
</div>