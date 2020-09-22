<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\appmyauris\models\AppMyaurisGroupSanPham */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="app-myauris-group-san-pham-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?php
                echo $form->field($model, 'list')->dropDownList($model::getListSanPham(), [
                    'class' => 'dropdown ui search form-control',
                    'prompt' => 'San pham...',
                    'style' => 'width: 100%',
                    'multiple' => 'true'
                ]);
                ?>
        <?php if (Yii::$app->controller->action->id == 'create')
            $model->status = 1;
        ?>
        <?= $form->field($model, 'status')->checkbox() ?>
    </div>
    <div class="form-actions">
        <?= Html::resetButton('<i class="ft-x"></i> Cancel', ['class' =>
            'btn btn-warning mr-1']) ?>
        <?= Html::submitButton('<i class="fa fa-check-square-o"></i> Save' ,
            ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

