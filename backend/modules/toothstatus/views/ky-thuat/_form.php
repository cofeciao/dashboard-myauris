<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\toothstatus\models\KyThuatRang */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ky-thuat-rang-form">

    <?php $form = ActiveForm::begin([
        'enableAjaxValidation' => true,
        'validationUrl' => \yii\helpers\Url::toRoute(['validate-form', 'id' => $model->primaryKey])
    ]); ?>
    <div class="form-actions">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'link_video')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 10, 'id' => 'content']) ?>

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

