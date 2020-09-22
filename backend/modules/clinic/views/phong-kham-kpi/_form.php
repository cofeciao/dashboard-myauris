<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\customer\models\Dep365CustomerOnlineDichVu;

/* @var $this yii\web\View */
/* @var $model backend\modules\clinic\models\PhongKhamKpi */
/* @var $form yii\widgets\ActiveForm */
if ($model->kpi_time != null) {
    $model->kpi_time = date('m-Y', $model->kpi_time);
}
?>

<?php $form = ActiveForm::begin([
    'id' => 'form-phong-kham-kpi',
    'class' => 'form form-horizontal',
    'enableAjaxValidation' => true,
    'enableClientValidation' => true,
    'validationUrl' => Yii::$app->controller->action->id == 'create' ? Url::toRoute(['validate-phong-kham-kpi']) : Url::toRoute(['validate-phong-kham-kpi', 'id' => $model->primaryKey]),
    'action' => Yii::$app->controller->action->id == 'create' ? Url::toRoute(['submit-phong-kham-kpi']) : Url::toRoute(['submit-phong-kham-kpi', 'id' => $model->primaryKey]),
]); ?>

<div class="modal-body">
    <div class="form-body">
        <?= $form->field($model, 'kpi_tuong_tac')->textInput() ?>

        <?= $form->field($model, 'kpi_lich_hen')->textInput() ?>

        <?= $form->field($model, 'kpi_lich_moi')->textInput() ?>

        <?= $form->field($model, 'kpi_khach_den')->textInput() ?>

        <?= $form->field($model, 'kpi_khach_lam')->textInput() ?>

        <?= $form->field($model, 'kpi_time')->widget(\dosamigos\datepicker\DatePicker::class, [
            'template' => '{input}{addon}<span class="input-group-addon1 clear-value"><span class="fa fa-times"></span></span>',
            'clientOptions' => [
                'viewMode' => 'months',
                'minViewMode' => 'months',
                'format' => 'mm-yyyy',
                'autoclose' => true,
                'startDate' => "+0d"
            ],
            'clientEvents' => [],
        ]) ?>

        <?php echo $form->field($model, 'id_dich_vu')
            ->dropDownList(ArrayHelper::map(Dep365CustomerOnlineDichVu::find()->published()->all(), 'id', 'name'), [
                'class' => 'ui dropdown search form-control',
                'autofocus' => '', 'prompt' => 'Chọn dịch vụ...'])
        ?>

        <?php if (Yii::$app->controller->action->id == 'create')
            $model->status = 1;
        ?>
        <?= $form->field($model, 'status')->checkbox() ?>
    </div>
</div>
<div class="modal-footer">
    <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
        'btn btn-warning mr-1']) ?>
    <?= Html::submitButton('<i class="fa fa-check-square-o"></i> Save',
        ['class' => 'btn btn-primary']
    ) ?>
</div>

<?php ActiveForm::end(); ?>

<?php
$script = <<< JS
var form = $('#form-phong-kham-kpi');
form.on('beforeSubmit', function(e) {
    e.preventDefault();
    
    var url = form.attr('action') || null,
        formData = new FormData(form[0]);
    
    if(url !== null) {
        form.myLoading({
            opacity: true
        });
        
        $.ajax({
            type: 'POST',
            url: url,
            dataType: 'json',
            data: formData,
            cache: false,
            processData: false,
            contentType: false
        }).done(function(res){
            if (res.code == 200) {                
                $('.modal-header').find('.close').trigger('click');
                $.pjax.reload({url: window.location.href, method: 'POST', container:'#custom-pjax'});
                setTimeout(function(){
                    toastr.success(res.msg, 'Thông báo');
                },500);
            } else {
                form.myUnloading();
                toastr.error(res.msg, 'Thông báo');
            }
        }).fail(function(err) {
            form.myUnloading();
            console.log('fail', err);
        });
    }
    return false;
})
JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>
