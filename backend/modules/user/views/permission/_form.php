<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\user\models\RbacAuthRule;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\RbacAuthItem */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['id' => 'form-create-permission']); ?>
<div class="modal-body">
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
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