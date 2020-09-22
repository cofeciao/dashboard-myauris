<?php

use backend\modules\toothstatus\models\TinhTrangRang;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$getListTinhTrangRang = TinhTrangRang::getListTinhTrangRang();
$listTinhTrangRang = ArrayHelper::map($getListTinhTrangRang, 'name', 'name');
$listTinhTrangRangOptions = [];
foreach ($getListTinhTrangRang as $tinhTrangRang) {
    $listTinhTrangRangOptions[$tinhTrangRang->name] = [
        'data-js' => $tinhTrangRang->js_bac_si
    ];
}
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
        #<span><?= str_replace('rang-', 'Răng ', $model->tag) ?></span>
    </div>
    <div class="tag-tham-kham">
        <?= $form->field($model, 'tinh_trang_rang')->dropDownList(
            $listTinhTrangRang,
            [
                'options' => $listTinhTrangRangOptions,
                'class' => 'form-control ui dropdown search dropdown-tinh-trang-rang dropdown-tinh-trang-rang-' . $model->tag,
                'multiple' => 'true',
                'prompt' => 'Tình trạng răng...'
            ])->label(false) ?>
        <?= $form->field($model, 'ketqua_thamkham')->textInput([
            'class' => 'form-control ketqua-thamkham ketqua-thamkham-' . $model->tag,
            'data-old' => $model->ketqua_thamkham,
            'autocomplete' => 'off',
            'placeholder' => $model->getAttributeLabel('ketqua_thamkham')
        ])->label(false) ?>
    </div>
    <div class="tag-save d-none">
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
