<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap\ActiveForm;

$form = ActiveForm::begin([
    'id' => 'form-comment',
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-comment', 'id' => $model->id_de_xuat_chi]),
    'action' => Url::toRoute(['submit-comment', 'id' => $model->id_de_xuat_chi])
]);
echo $form->field($model, 'comment')->textarea(['class' => 'form-control ipt-comment', 'rows' => 6]);
echo Html::submitButton('Bình luận', ['class' => 'btn btn-primary']);
ActiveForm::end();

$script = <<< JS
$('body').on('beforeSubmit', '#form-comment', function(e){
    e.preventDefault();
    var form = $(this),
        url = form.attr('action'),
        formData = new FormData(form[0]);
    $.ajax({
        type: 'POST',
        url: url,
        dataType: 'json',
        data: formData,
        cache: false,
        contentType: false,
        processData: false
    }).done(res => {
        if(res.code === 200){
            $('.ipt-comment').val('');
            $.pjax.reload({container: '#pjax-comments', url: window.location.href});
        } else {
            toastr.error(res.msg);
        }
    }).fail(f => {
        toastr.error('Đã xảy ra lỗi');
    });
    return false;
});
JS;
$this->registerJs($script, View::POS_END);
