<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin([
    'id' => 'campaignAjax',
]); ?>
<div class="modal-body">
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mota')->textarea(['rows' => 4]) ?>

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
$tit = Yii::t('backend', 'Notification');

$script = <<< JS
$('body').find('button[type=reset]').unbind('click').bind('click', function(){
    $('.modal-header').find('.close').trigger('click');
});

$('body').find('form#campaignAjax').unbind('beforeSubmit').bind('beforeSubmit', function(e) {
   e.preventDefault();
   var currentUrl = $(location).attr('href');
   var formData = $('#campaignAjax').serialize();
   
    $.ajax({
        url: $('#campaignAjax').attr('action'),
        type: 'POST',
        data: formData,
        dataType: 'json',
    })
    .done(function(res) {
        if (res.status == 200) {
            $('.modal-header').find('.close').trigger('click');
            $.pjax.reload({url: currentUrl, method: 'POST', container:'#campaign-ajx'});
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

$this->registerJs($script);

?>


