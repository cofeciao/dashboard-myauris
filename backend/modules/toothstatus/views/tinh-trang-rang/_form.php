<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\toothstatus\models\KyThuatRang;
use backend\modules\user\models\User;

/* @var $this yii\web\View */
/* @var $model backend\modules\toothstatus\models\TinhTrangRang */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="tinh-trang-rang-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="form-actions">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'ky_thuat')->dropDownList(
            ArrayHelper::map(KyThuatRang::find()->published()->all(), 'name', 'name'),
            ['class' => 'form-control ui dropdown', 'multiple' => 'true', 'prompt' => 'Chọn kỹ thuật...']
        ) ?>

        <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>

        <span>Hình ảnh: (Kích thước: 300x300)</span>
        <?= $form->field($model, 'image')->fileInput()->label(false) ?>
        <?php
        if ($model->image != null) {
            ?>
            <div class="mb-2">
                <?= Html::img('/uploads/rang/tinh-trang-rang/300x300/' . $model->image, ['width' => 200]); ?>
            </div>
            <?php
        }
        ?>
        <br/>
        <?php if (Yii::$app->controller->action->id == 'create') {
            $model->status = 1;
        }
        ?>
        <?php if (in_array($roleName, [User::USER_DEVELOP, User::USER_ADMINISTRATOR]) ||
            Yii::$app->user->can('bacsiBacsiUpdate')) {
            echo $form->field($model, 'js_bac_si')->textInput(['placeholder' => $model->getAttributeLabel('js_bac_si')]);
        } ?>
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

