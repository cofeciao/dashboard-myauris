<?php

use dosamigos\datetimepicker\DateTimePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$readOnly = false;
if (Yii::$app->controller->action->id == 'update') {
    $readOnly = true;
}
if (Yii::$app->controller->action->id == 'create') {
    $model->date_start = strtotime(date('d-m-Y H:i:s'));
}
$model->date_start = date('d-m-Y H:i', $model->date_start);
?>
<?php $form = ActiveForm::begin([
    'id' => 'form-khuyen-mai',
    'class' => 'form form-horizontal',
    'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    'validationUrl' => Yii::$app->controller->action->id == 'create' ? Url::toRoute(['validate-khuyen-mai']) : Url::toRoute(['validate-khuyen-mai', 'id' => $model->primaryKey]),
    'action' => Yii::$app->controller->action->id == 'create' ? Url::toRoute('submit-khuyen-mai') : Url::toRoute(['submit-khuyen-mai', 'id' => $model->primaryKey]),
]); ?>
    <div class="modal-body">
        <div class="form-body">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'code')->textInput(['maxlength' => true, 'readonly' => $readOnly]) ?>

            <?= $form->field($model, 'remaining')->input('number', ['maxlength' => true]) ?>

            <?php /*<div class="row">
            <div class="col-8">*/ ?>
            <?= $form->field($model, 'price')->textInput(['class' => 'form-control on-keyup']) ?>
            <?php /*</div>
            <div class="col-4">
                <?= $form->field($model, 'type')->dropDownList(\backend\modules\clinic\models\PhongKhamKhuyenMai::TYPE, []) ?>
            </div>
        </div>*/ ?>


            <?= $form->field($model, 'date_start')->widget(DateTimePicker::class, [
                'clientOptions' => [
                    'format' => 'dd-mm-yyyy h:i',
                    'autoclose' => true,
                    'todayHighlight' => true,
                    'startDate' => "+0d"
                ],
                'clientEvents' => [

                ],
                'options' => [
                    'readonly' => 'readonly',
                    'class' => 'form-control'
                ]
            ]) ?>

            <?= $form->field($model, 'date_end')->widget(DateTimePicker::class, [
                'template' => '{input}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>{addon}',
                'clientOptions' => [
                    'format' => 'dd-mm-yyyy h:i',
                    'autoclose' => true,
                    'startDate' => "+0d"
                ],
                'clientEvents' => [],
            ]) ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

            <?php if (Yii::$app->controller->action->id == 'create') {
                $model->status = 1;
            }
            ?>
            <?= $form->field($model, 'status')->checkbox() ?>
        </div>

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
$('.on-keyup').trigger('change');
JS;
$this->registerJs($script);
