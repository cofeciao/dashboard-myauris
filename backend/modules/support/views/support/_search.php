<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\support\models\search\SupportSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="support-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'catagory_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'slug') ?>

    <?= $form->field($model, 'question') ?>

    <?php // echo $form->field($model, 'anwser')?>

    <?php // echo $form->field($model, 'status')?>

    <?php // echo $form->field($model, 'created_at')?>

    <?php // echo $form->field($model, 'updated_at')?>

    <?php // echo $form->field($model, 'created_by')?>

    <?php // echo $form->field($model, 'updated_by')?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
