<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\customer\models\Dep365CustomerOnlineDichVu;

/* @var $this yii\web\View */
/* @var $model backend\modules\customer\models\Dep365CustomerOnlineFanpage */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'id' => 'form-customer-fanpage',
]); ?>
<div class="modal-body">
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'mota')->textarea(['rows' => 4]) ?>

    <?= $form->field($model, 'id_facebook')->textInput() ?>

    <?= $form->field($model, 'id_dich_vu')
        ->dropDownList(ArrayHelper::map(Dep365CustomerOnlineDichVu::find()->published()->all(), 'id', 'name'), [
            'class' => 'ui dropdown search form-control',
            'autofocus' => '', 'prompt' => 'Chọn dịch vụ...'])
    ?>

    <?php
    if (Yii::$app->controller->action->id == 'create') {
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