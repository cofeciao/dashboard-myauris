<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\general\models\ContactPhone */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'id' => 'form-contact',
]); ?>
<div class="modal-body">
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

    <?php if (Yii::$app->controller->id == 'create') {
    $model->status = 1;
}
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

