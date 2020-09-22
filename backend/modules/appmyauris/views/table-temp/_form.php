<?php

use backend\modules\toothstatus\models\DoTuoi;
use backend\modules\toothstatus\models\TinhTrangRang;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\appmyauris\models\TableTemp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="table-temp-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">
        <?= $form->field($model, 'id_tinh_trang_rang')->dropDownList(ArrayHelper::map(TinhTrangRang::getListTinhTrangRangAPI(), 'id', 'name')) ?>

        <?= $form->field($model, 'id_do_tuoi')->dropDownList(ArrayHelper::map(DoTuoi::getListDoTuoi(),'id','name')) ?>

        <?= $form->field($model, 'image_before')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'image_after')->textInput(['maxlength' => true]) ?>

        <?php echo $form->field($model, 'name')->textInput() ?>

        <?php if (Yii::$app->controller->action->id == 'create')
            $model->status = 1;
        ?>
        <?= $form->field($model, 'status')->checkbox() ?>

        <?= $form->field($model, 'nguoi_noi_tieng')->checkbox() ?>

    </div>
    <div class="form-actions">
        <?= Html::resetButton('<i class="ft-x"></i> Cancel', ['class' =>
            'btn btn-warning mr-1']) ?>
        <?= Html::submitButton('<i class="fa fa-check-square-o"></i> Save' ,
            ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

