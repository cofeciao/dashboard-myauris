<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\location\models\Province;
use backend\modules\location\models\Country;

/* @var $this yii\web\View */
/* @var $model backend\modules\location\models\Province */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin(['id' => 'form-location-province']); ?>
<div class="modal-body">
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'Type')->dropDownList(Province::provinceType()) ?>

    <?= $form->field($model, 'TelephoneCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ZipCode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'CountryId')->dropDownList(ArrayHelper::map(Country::find()->where(['status' => 1])->all(), 'id', 'CommonName')) ?>

    <?= $form->field($model, 'CountryCode')->dropDownList(ArrayHelper::map(Country::find()->where(['status' => 1])->all(), 'CountryCode', 'CountryCode')) ?>

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

