<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\setting\models\Setting */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(['id' => 'form-setting-kv']); ?>
<div class="modal-body">
    <?= $form->field($model, 'param')->textInput() ?>

    <?= $form->field($model, 'key_value')->textInput() ?>

    <?= $form->field($model, 'value')->textarea(['rows' => 6]) ?>

</div>
<div class="modal-footer">
    <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
        'btn btn-warning mr-1']) ?>
    <?= Html::submitButton(
            '<i class="fa fa-check-square-o"></i> Save',
            ['class' => 'btn btn-primary']
        ) ?>
</div>

<?php ActiveForm::end(); ?>

