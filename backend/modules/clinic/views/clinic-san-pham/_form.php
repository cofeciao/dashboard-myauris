<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\clinic\models\PhongKhamDichVu;

/* @var $this yii\web\View */
/* @var $model backend\modules\clinic\models\PhongKhamSanPham */
/* @var $form yii\widgets\ActiveForm */
if ($model->don_gia != null) {
    $model->don_gia = number_format($model->don_gia, 0, '', '.');
}
?>

<?php $form = ActiveForm::begin([
    'id' => 'clinicSanPhamAjax',
]); ?>
    <div class="modal-body">
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'services_id')->dropDownList(ArrayHelper::map(PhongKhamDichVu::getDichVu(), 'id', 'name'), ['prompt' => 'Chọn dịch vụ..']) ?>
        <?= $form->field($model, 'don_gia')->textInput(['id' => 'don-gia']) ?>
        <?= $form->field($model, 'mota')->textarea(['rows' => 2]) ?>
        <?php if (Yii::$app->controller->id == 'create') {
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
<?php ActiveForm::end() ?>
<?php
$script = <<< JS
$('#don-gia').on('change paste keyup', function(){
    var v = $(this).val().replace(/\./g, '');
    $(this).val(addCommas(v));
});
JS;
$this->registerJs($script);

$css = <<< CSS
.ui.multiple.dropdown>.text{margin:0;}
CSS;
$this->registerCss($css);
