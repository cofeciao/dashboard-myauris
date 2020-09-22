<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use backend\modules\baocao\models\BaocaoChayAdsFace;
use yii\helpers\ArrayHelper;
use backend\modules\customer\models\Dep365CustomerOnlineFanpage;
use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\customer\models\Dep365CustomerOnlineDichVu;

/* @var $this yii\web\View */
/* @var $model backend\modules\baocao\models\BaocaoChayAdsFace */
/* @var $form yii\widgets\ActiveForm */
if (Yii::$app->controller->action->id == 'create') {
    $model->ngay_chay = date('d-m-Y', strtotime('-1 day'));
}

if (Yii::$app->controller->action->id == 'update') {
    $model->ngay_chay = date('d-m-Y', $model->ngay_chay);
    $model->so_tien_chay = number_format($model->so_tien_chay, 0, ',', '.');
    $model->hien_thi = number_format($model->hien_thi, 0, ',', '.');
    $model->tiep_can = number_format($model->tiep_can, 0, ',', '.');
    $model->binh_luan = number_format($model->binh_luan, 0, ',', '.');
    $model->tin_nhan = number_format($model->tin_nhan, 0, ',', '.');
    $model->status = 1;
}

?>
<?php $form = ActiveForm::begin([
    'id' => 'baocaoAdsAjax',
    'options' => [
        'class' => 'baocao-chay-ads-face-form',
    ],
    'enableAjaxValidation' => true,
    'validationUrl' => \yii\helpers\Url::toRoute(['validate-chay-ads-face', 'id' => $model->primaryKey])
]); ?>
<div class="modal-body">
    <?php //echo $form->field($model, 'don_vi')->dropDownList(BaocaoChayAdsFace::getDonViChayAdvertising(), ['prompt' => 'Chọn đơn vị chạy ...'])?>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'so_tien_chay')->textInput(['maxlength' => true, 'class' => 'form-control on-keyup']) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'hien_thi')->textInput(['class' => 'form-control on-keyup']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'tiep_can')->textInput(['class' => 'form-control on-keyup']) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'binh_luan')->textInput(['class' => 'form-control on-keyup']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'tin_nhan')->textInput(['class' => 'form-control on-keyup']) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'page_chay', ['template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])
                ->dropDownList(ArrayHelper::map(Dep365CustomerOnlineFanpage::getListFanpage(), 'id', 'name'), ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn page chạy...']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'location_id', ['template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])
                ->dropDownList(ArrayHelper::map(BaocaoLocation::getBaocaoLocation(), 'id', 'name'), ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn khu vực chạy...']) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'san_pham', ['template' => '<div class="input-group"><div class="w-100">{label}</div>{input}<span class="input-group-addon clear-option"><span class="fa fa-times"></span></span></div>'])
                ->dropDownList(ArrayHelper::map(Dep365CustomerOnlineDichVu::getSanPhamDichVu(), 'id', 'name'), ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn sản phẩm...']) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'ngay_chay')->widget(DatePicker::class, [
                'template' => '{input}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>{addon}',
                'clientOptions' => [
                    'format' => 'dd-mm-yyyy',
                    'autoclose' => true,
                ],
                'clientEvents' => [],
                'options' => [
                    'readonly' => 'readonly',
                    'class' => 'form-control'
                ]
            ]) ?>
        </div>
    </div>

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

<?php
$tit = Yii::t('backend', 'Notification');

$script = <<< JS
$('body').find('form#baocaoAdsAjax').unbind('beforeSubmit').bind('beforeSubmit', function(e) {
   e.preventDefault();
   var currentUrl = $(location).attr('href');
   var formData = $('#baocaoAdsAjax').serialize();
   
    $.ajax({
        url: $('#baocaoAdsAjax').attr('action'),
        type: 'POST',
        data: formData,
        dataType: 'json',
    })
    .done(function(res) {
        if (res.status == 200) {
            $('.modal-header').find('.close').trigger('click');
            $.pjax.reload({url: currentUrl, method: 'POST', container:'#chay-face-ads-pjax'});
            setTimeout(function(){
                toastr.success(res.mess, '$tit');
            },500);
        } else {
            toastr.error(res.mess, '$tit');
        }
    });
   
   return false;
});

JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>



