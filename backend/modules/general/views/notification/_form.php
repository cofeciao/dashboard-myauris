<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\general\models\Dep365Notification */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'id' => 'form-notification',
]); ?>
<div class="modal-body">
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'icon')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 2]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 4]) ?>

    <?php if (Yii::$app->controller->id == 'create') {
    $model->status = 1;
}
    ?>
    <?= $form->field($model, 'status')->checkbox() ?>
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
