<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\quytac\models\SupportCatagory;

/* @var $this yii\web\View */
/* @var $model backend\modules\quytac\models\Support */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="support-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'catagory_id')->dropDownList(ArrayHelper::map(SupportCatagory::find()->all(), 'id', 'name'), ['class' => 'select form-control', 'prompt' => 'Chọn nhóm']) ?>

        <?= $form->field($model, 'desription')->textarea(['row' => '6']) ?>

        <?= $form->field($model, 'content')->textarea(['id' => 'desc']) ?>

        <?php if (Yii::$app->controller->action->id == 'create') {
    $model->status = 1;
}
        ?>
        <?= $form->field($model, 'status')->checkbox() ?>
    </div>
    <div class="form-actions">
        <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
            'btn btn-warning mr-1']) ?>
        <?= Html::submitButton(
                '<i class="fa fa-check-square-o"></i> Save',
                ['class' => 'btn btn-primary']
            ) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

