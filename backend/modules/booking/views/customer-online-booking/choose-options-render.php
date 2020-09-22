<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php $form = ActiveForm::begin([
    'id' => 'form-choose-options-render',
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validation-form-choose-options']),
    'action' => Url::toRoute(['submit-form-choose-options'])
]); ?>
<?= $form->field($model, 'options')->radioList(\backend\modules\booking\models\form\ChooseOptionsRenderForm::RANGE_OPTIONS, [
    'item' => function ($index, $label, $name, $checked, $value) {
        return '
            <div>
                <label class="square-checkbox">
                    <input type="radio" ' . ($checked ? 'checked' : '') . ' name="' . $name . '" value="' . $value . '">
                    <span></span>' . $label . '
                </label>
            </div>
        ';
    }
]) ?>
<?= Html::submitButton('Đồng ý', ['class' => 'd-none', 'id' => 'submit-choose-options-render']) ?>
<?php ActiveForm::end() ?>
<?php
$script = <<< JS
$('body').find('#form-choose-options-render').unbind('beforeSubmit').bind('beforeSubmit', function(e){
    e.preventDefault();
    mymodal.myModal.find('.modal-content').myLoading({
        opacity: true
    });
    var form_data = new FormData($('#form-choose-options-render')[0]);
    $.ajax({
        type: 'POST',
        url: $('#form-choose-options-render').attr('action'),
        dataType: 'json',
        data: form_data,
        cache: false,
        contentType: false,
        processData: false
    }).done(function(res){
        if(res.code == 200){
            toastr.success(res.msg, 'Thông báo');
            mymodal.myModal.find(mymodal.el['cancel']).trigger('click');
        } else {
            toastr.error(res.msg, 'Thông báo');
            mymodal.myModal.find('.modal-content').myUnloading();
        }
    }).fail(function(f){
        console.log('fail', f);
        toastr.error('Có lỗi xảy ra', 'Thông báo');
        mymodal.myModal.find('.modal-content').myUnloading();
    });
    return false;
});
JS;
$this->registerJs($script);
