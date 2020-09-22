<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\booking\models\TimeWork */
/* @var $form yii\widgets\ActiveForm */
$listTime = [];
$open_time = strtotime(\backend\modules\booking\models\TimeWork::TIME_START);
$close_time = strtotime(\backend\modules\booking\models\TimeWork::TIME_END);
$output = "";
for ($i=$open_time; $i<$close_time; $i+=1800) {
    $listTime[date("H:i", $i)] = date("H:i", $i);
}
?>
<?php $form = ActiveForm::begin([
    'id' => 'form-time-work'
]); ?>
    <div class="modal-body">
        <div class="form-group form-time">
            <label class="control-label">
                <div>Thời gian làm việc</div>
                <div class="choose-time-content">
                    <div class="choose-time">
                        <?= $form->field($model, 'time')->dropDownList($listTime, ['class' => 'list-time form-control ui dropdown'])->label(false) ?>
                    </div>
                    <div class="show-time" onclick="$('.list-time').trigger('click')">
                        <?= $form->field($model, 'name')->textInput(['id' => 'time-name', 'maxlength' => true, 'readonly' => true])->label(false) ?>
                    </div>
                </div>
            </label>
        </div>
        <?= $form->field($model, 'sort')->textInput() ?>

        <?php if (Yii::$app->controller->action->id == 'create') {
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

<?php ActiveForm::end(); ?>
<?php
$this->registerCss('
.form-time > label {
    width: 100%;
}
.choose-time-content {
    display: flex;
    width: 100%;
    flex-wrap: nowrap;
}
.show-time {
    position: relative;
    width: calc(100% - 130px);
}
.show-time:after {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
}
.choose-time {
    width: 120px;
    margin-right: 10px;
}
');
$script = <<< JS
$(".ui.dropdown").dropdown();
function getTimeAfter30m(time){
    var d = new Date(),
        day = d.getDate(),
        month = d.getMonth() + 1,
        year = d.getFullYear(),
        date = year +'-'+ month +'-'+ day +' '+ time,
        new_date = new Date(new Date(date).getTime() + 30 * 60 * 1000),
        new_hour = new_date.getHours(),
        new_minute = new_date.getMinutes();
    if(new_hour < 10) new_hour = '0'+ new_hour;
    if(new_minute < 10) new_minute = '0'+ new_minute;
    return new_hour +':'+ new_minute;
}
$('body').on('change', '.list-time > select', function(){
    $('#time-name').val($(this).val() +' - '+ getTimeAfter30m($(this).val()));
});
if($('#time-name').val() == '') $('body').find('.list-time > select').trigger('change');
JS;
$this->registerJs($script, \yii\web\View::POS_END);
