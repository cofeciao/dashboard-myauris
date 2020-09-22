<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\issue\models\Issue;
use dosamigos\datepicker\DatePicker;
use backend\modules\user\models\PhongBan;
use yii\helpers\ArrayHelper;
use backend\modules\user\models\User;

/* @var $this yii\web\View */
/* @var $model backend\modules\issue\models\Issue */
/* @var $form yii\widgets\ActiveForm */

if (Yii::$app->controller->action->id == 'create') {
    $model->status = 1;
    $model->end_date_expected = time();
}
if ($model->end_date_expected != null) {
    $model->end_date_expected = date('d-m-Y', $model->end_date_expected);
}
$menuPhongBan = PhongBan::getMenuPhongBan(null, null, '|-', []);
$options = [];
foreach ($menuPhongBan as $phongban) {
    $options[$phongban['id']] = [
        'alias' => $phongban['alias']
    ];
}
?>
    <div class="issue-form">

        <?php $form = ActiveForm::begin(); ?>
        <div class="form-actions">
            <div class="row">
                <div class="col-md-6 col-12">
                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-12">
                    <?= $form->field($model, 'parts')->dropDownList(ArrayHelper::map($menuPhongBan, 'id', 'name'), [
                        'placeholder' => 'Chọn bộ phận liên quan',
                        'multiple' => true,
                        'id' => 'select-part',
                        'options' => $options,
                        'class' => 'form-control',
                    ]) ?>
                </div>
                <div class="col-md-6 col-12">
                    <div id="content-select-user">
                        <?= $form->field($model, 'user')->dropDownList(ArrayHelper::map(User::getAllUsers([User::USER_DEVELOP, User::USER_ADMINISTRATOR]), 'id', 'fullname'), [
                            'placeholder' => 'Chọn nhân viên...',
                            'class' => 'form-control select2',
                            'id' => 'select-user',
                            'multiple' => true,
                        ]) ?>
                    </div>
                </div>
            </div>
            <?= $form->field($model, 'description')->textarea(['rows' => 5]) ?>
            <div class="row">
                <div class="col-md-6 col-12">
                    <?= $form->field($model, 'level')->dropDownList(Issue::LEVEL, []) ?>
                </div>
                <div class="col-md-6 col-12">
                    <?= $form->field($model, 'end_date_expected')->widget(DatePicker::class, [
                        'clientOptions' => [
                            'format' => 'dd-mm-yyyy',
                            'autoclose' => true,
                            'todayHighlight' => true,
                            'startDate' => '+0d'
                        ]
                    ]) ?>
                </div>
            </div>
            <?= $form->field($model, 'status')->checkbox() ?>
        </div>
        <div class="form-actions">
            <?= Html::resetButton('<i class="ft-x"></i> Cancel', ['class' => 'btn btn-warning mr-1']) ?>
            <?= Html::submitButton('<i class="fa fa-check-square-o"></i> Save', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php
$url_load_user = Url::toRoute(['load-users-by-parts']);
$script = <<< JS
$('#select-part, #select-user').select2();
function loadUser(val){
    return new Promise(resolve => {
        if(typeof val === "object" && val.length > 0){
            $.ajax({
                type: 'POST',
                url: '$url_load_user',
                dataType: 'json',
                data: {
                    parts: val
                }
            }).done(function(res){
                var data = [];
                if(res.code === 200) {
                    $("#content-select-user").slideDown();
                    if(typeof data === "string") {
                        data.push(res.data);
                    } else if(typeof res.data === "object" && Object.keys(res.data).length > 0){
                        Object.keys(res.data).forEach(function(k){
                            data.push(k);
                        });
                    }
                }
                $('#select-user').val(data).trigger('change');
                resolve();
            });
        } else {
            $("#select-user").val([]).trigger('change');
            resolve();
        }
    });
}
$('#select-part').on('select2:select', function(e){
    var val = $('#select-part').val(),
        id = e.params.data.id,
        alias = $('#select-part option[value="'+ id +'"]').attr('alias') || null,
        options = $('#select-part option[alias*="'+ alias +'"]');
    options.each(function(k, v){
        val.push(v.value);
    });
    console.log(val);
    $('#select-part').val(val).trigger('change');
    loadUser(val).then(res => {});
}).on('select2:unselect', function(e){
    var val = $('#select-part').val(),
        id = e.params.data.id,
        alias = $('#select-part option[value="'+ id +'"]').attr('alias') || null,
        parent_alias = !alias.includes('/') ? alias : alias.slice(0, alias.lastIndexOf('/') - 1),
        options = $('#select-part option[alias*="'+ alias +'"]');
    options.each(function(k, v){
        if(val.indexOf(v.value + '') !== -1){
            val.splice(val.indexOf(v.value + ''), 1);
        }
    });
    $('#select-part').val(val).trigger('change');
    loadUser(val).then(res => {});
});
JS;
$this->registerJs($script, \yii\web\View::POS_END);