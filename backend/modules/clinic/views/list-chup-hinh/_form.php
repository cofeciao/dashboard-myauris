<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\clinic\models\ListChupHinh */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
    'id' => 'form-list-chup-hinh',
    'action' => \yii\helpers\Url::toRoute(['create-or-update', 'id' => $model->primaryKey]),
    'enableAjaxValidation' => true,
    'validationUrl' => \yii\helpers\Url::toRoute(['validate-list-chup-hinh', 'id' => $model->primaryKey])
]); ?>
<div class="modal-body">
    <?= $form->field($model, 'name')->textInput([
        'maxlength' => true,
        'placeholder' => $model->getAttributeLabel('name')
    ]) ?>

    <?php if (Yii::$app->controller->action->id == 'create')
        $model->status = 1;
    ?>
    <?= $form->field($model, 'status')->checkbox() ?>
</div>
<div class="modal-footer">
    <?= Html::resetButton('<i class="ft-x"></i> Reset', ['class' =>
        'btn btn-warning mr-1']) ?>
    <?= Html::submitButton(
        '<i class="fa fa-check-square-o"></i> Save',
        ['class' => 'btn btn-primary']
    ) ?>
</div>
<?php ActiveForm::end(); ?>
