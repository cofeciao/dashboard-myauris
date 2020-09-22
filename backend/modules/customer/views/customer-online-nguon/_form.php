<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\customer\models\Dep365Agency;

/* @var $this yii\web\View */
/* @var $model backend\modules\customer\models\Dep365CustomerOnlineNguon */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'id' => 'create-customer-online-nguon'
]); ?>
    <div class="modal-body">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'agency_id')->dropDownList(ArrayHelper::map(Dep365Agency::getAgency(), 'id', 'name'), ['class' => 'ui dropdown form-control', 'multiple' => 'multiple', 'prompt' => 'Chọn Agency...']) ?>

        <?= $form->field($model, 'mota')->textarea(['rows' => 4]) ?>

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

<?php
$script = <<< JS
$(".ui.dropdown").dropdown();
JS;
$this->registerJs($script, \yii\web\View::POS_END);
