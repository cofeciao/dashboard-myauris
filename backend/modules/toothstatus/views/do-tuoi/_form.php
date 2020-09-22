<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\toothstatus\models\TinhTrangRang;

/* @var $this yii\web\View */
/* @var $model backend\modules\toothstatus\models\DoTuoi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="do-tuoi-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

        <span>Hình ảnh: (Kích thước: 300x300)</span>
        <?= $form->field($model, 'image')->fileInput()->label(false) ?>
        <?php
        if ($model->image != null) {
            ?>
            <div class="mb-2">
                <?= Html::img('/uploads/rang/do-tuoi/300x300/' . $model->image, ['width' => 200]); ?>
            </div>
            <?php
        }
        ?>
        <br/>

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

