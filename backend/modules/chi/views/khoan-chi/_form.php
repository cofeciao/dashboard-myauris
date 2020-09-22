<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\chi\models\DanhMucChi;

/* @var $this yii\web\View */
/* @var $model backend\modules\chi\models\KhoanChi */
/* @var $form yii\widgets\ActiveForm */


?>

    <div class="khoan-chi-form">

        <?php $form = ActiveForm::begin(); ?>
        <div class="form-actions">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'danhMucChi', [
                'template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>{error}'
            ])->dropDownList(ArrayHelper::map(DanhMucChi::getDanhMucChi(), 'id', 'name'), [
                'class' => 'form-control ui dropdown search',
                'prompt' => $model->getAttributeLabel('danhMucChi')
            ]) ?>
            <?php if (!empty($model->nhomChiHasOne) && !empty($model->category)) {

                $arr_cat = [$model->category => $model->nhomChiHasOne->name];
            } else {
                $arr_cat = [];
            } ?>
            <?= $form->field($model, 'category')->dropDownList($arr_cat, [
                'class' => 'form-control ui dropdown seach',
                'prompt' => $model->getAttributeLabel('category')
            ]) ?>

            <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textarea(['maxlength' => true, 'id' => 'desc']) ?>

            <?php if (Yii::$app->controller->action->id == 'create') {
                $model->status = 1;
            }
            ?>
            <?= $form->field($model, 'status')->checkbox() ?>
        </div>
        <div class="form-actions">
            <?= Html::resetButton('<i class="ft-x"></i> Cancel', ['class' =>
                'btn btn-warning mr-1']) ?>
            <?= Html::submitButton(
                '<i class="fa fa-check-square-o"></i> Save',
                ['class' => 'btn btn-primary']
            ) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>


<?php
$urlLoadNhomChi = Url::toRoute(['/chi/nhom-chi/load-nhom-chi-by-danh-muc']);
$script = <<< JS
$('body').on('change', '#khoanchi-danhmucchi', function(){
    var danhmucchi = $(this).val() || null;
    if(danhmucchi != null){
        $.ajax({
            type: 'POST',
            url: '$urlLoadNhomChi',
            dataType: 'json',
            data: {
                danhmucchi: danhmucchi
            }
        }).done(res => {
            console.log(res);
            $('#khoanchi-category').find('option[value!=""]').remove();
            if(res.code === 200){
                $('#khoanchi-category').append(res.data);
            } else {
                toastr.error(res.msg);
            }
        }).fail(f => {
            console.log(f);
            toastr.error('Có lỗi xảy ra khi load Nhóm chi');
        });
    }
});
JS;

$this->registerJs($script, View::POS_END);
