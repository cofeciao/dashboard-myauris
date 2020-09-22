<?php

use backend\modules\user\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\support\models\SupportCatagory;

$user = new User();
$roleUser = $user->getRoleName(Yii::$app->user->id);
?>

<?php $form = ActiveForm::begin(['id' => 'form-support']); ?>
<div class="modal-body">
    <?php
//    if ($roleUser != User::USER_DATHEN) {
        echo $form->field($model, 'name')->textInput(['maxlength' => true]);
//    } else {
//        echo '- Câu hỏi: ';
//        echo $model->name . '<br />';
//    }
    ?>

    <?php
//    if ($roleUser != User::USER_DATHEN) {
        ?>
        <?= $form->field($model, 'catagory_id')->dropDownList(ArrayHelper::map(SupportCatagory::getCatagory(), 'id', 'name'), ['class' => 'ui dropdown form-control', 'prompt' => 'Chọn nhóm / phòng ban']) ?>
        <?php
//    } else {
//        echo '- Phòng: ';
//        echo SupportCatagory::getCatagoryById($model->catagory_id)->name . '<br />';
//    }
    ?>


    <?php
//    if ($roleUser != User::USER_DATHEN) {
        ?>
        <?= $form->field($model, 'question')->textarea(['id' => 'content']) ?>
        <?php
//    } else {
//        echo '- Nội dung câu hỏi: <br />';
//        echo $model->question . '<br />';
//    }
    ?>

    <?php
    if (in_array($roleUser, [User::USER_DATHEN, User::USER_DEVELOP])) {
        ?>
        <?= $form->field($model, 'anwser')->textarea(['id' => 'content1']) ?>
        <?= $form->field($model, 'time')->input('number', [
            'min' => 0,
            'step' => 0.1
        ]) ?>
        <?php
    }
    ?>
    <?php if (Yii::$app->controller->action->id == 'create') {
        $model->status = 1;
    }
    ?>
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
$script = <<< JS
    $('.ui.dropdown').dropdown({forceSelection: false});
    tinymce.init({
        selector: 'textarea#content, textarea#content1, textarea#content2,textarea#content3, textarea#content4',
        height: 350,
        width: "",
        plugins: [
            "codemirror advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern imagetools code fullscreen"
        ],
        toolbar1: "undo redo bold italic underline strikethrough cut copy paste| alignleft aligncenter alignright alignjustify bullist numlist outdent indent blockquote searchreplace | styleselect formatselect fontselect fontsizeselect ",
        toolbar2: "table | hr removeformat | subscript superscript | charmap emoticons ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft | link unlink anchor image media | insertdatetime preview | forecolor backcolor print fullscreen code ",
        image_advtab: true,
        menubar: false,
        toolbar_items_size: 'small',

        relative_urls: false,
        remove_script_host: false,
        filemanager_title: "Media Manager",
        external_filemanager_path: homeUrl() + "/5F4143DD0785DD1BC9590C016B6EFB53/",
        external_plugins: {"filemanager": homeUrl() + "/5F4143DD0785DD1BC9590C016B6EFB53/plugin.min.js"},
    });
JS;
$this->registerJs($script, \yii\web\View::POS_END);
?>

