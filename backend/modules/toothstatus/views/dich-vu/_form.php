<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$pathImage = Yii::getAlias('@frontendUrl') . '/uploads/rang/dich-vu/300x300/';
$defaultImage = Yii::getAlias('@frontendUrl') . '/images/ico/favicon.png';
$customer_image = json_decode($model->customer_image, true);
$image_0b = $image_0a = $image_1b = $image_1a = $image_2b = $image_2a = null;
if (is_array($customer_image)) {
    foreach ($customer_image as $k => $image) {
        $before = 'image_' . $k . 'b';
        $after = 'image_' . $k . 'a';
        if (is_array($image)) {
            if (isset($image['before'])) {
                $$before = $image['before'];
            }
            if (isset($image['after'])) {
                $$after = $image['after'];
            }
        }
    }
}

$css = <<< CSS
.customer-image {
    height: 250px;
}
.customer-image {
    height: 250px;
    border: solid 1px #ccc;
}
.customer-image, .avatar-upload-icon {
    border-radius: 5px;
}
.customer-image:hover .avatar-upload-icon {
    opacity: 1;
}
.customer-image:hover .avatar-upload-icon i {
    font-size: 3rem;
}
.image-upload {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 5px;
}
.image-upload .img-upload {
    max-width: 100%;
    max-height: 100%;
}
span.btn-remove-image {
    position: absolute;
    top: -10px;
    right: -10px;
    width: 22px;
    height: 22px;
    display: none;
    align-items: center;
    justify-content: center;
    background: red;
    color: #fff;
    border-radius: 3px;
    opacity: .6;
    z-index: 3;
    cursor: pointer;
}
.has-avatar span.btn-remove-image {
    display: flex;
}
.customer-image:hover .btn-remove-image {
    opacity: 1;
}
CSS;

$this->registerCss($css);
?>

    <div class="lua-chon-loai-dich-vu-form">

        <?php $form = ActiveForm::begin([
            'id' => 'form-dich-vu'
        ]); ?>
        <div class="form-actions">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'tinh_trang_rang')->dropDownList(
            ArrayHelper::map(\backend\modules\toothstatus\models\TinhTrangRang::getListTinhTrangRang(), 'name', 'name'),
            ['class' => 'form-control ui dropdown', 'multiple' => 'true', 'prompt' => 'Tình trạng răng...']
        ) ?>

            <?= $form->field($model, 'do_tuoi')->dropDownList(
                    ArrayHelper::map(\backend\modules\toothstatus\models\DoTuoi::getListDoTuoi(), 'name', 'name'),
                    ['class' => 'form-control ui dropdown', 'multiple' => 'true', 'prompt' => 'Độ tuổi...']
                ) ?>

            <?= $form->field($model, 'lua_chon')->dropDownList(
                    ArrayHelper::map(\backend\modules\toothstatus\models\LuaChon::getListLuaChon(), 'name', 'name'),
                    ['class' => 'form-control ui dropdown', 'multiple' => 'true', 'prompt' => 'Lựa chọn...']
                ) ?>

            <?= $form->field($model, 'price')->textInput(['class' => 'form-control ipt-price']) ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

            <?= $form->field($model, 'star')->textInput()->input('number', ['step' => '0.5']) ?>

            <div class="form-group">
                <label class="control-label">Hình khách hàng</label>
            </div>
            <div class="form-group row align-items-center">
                <label class="control-label col-md-2 col-12">Khách 1:</label>
                <div class="row ccb-content p-0 col-md-10 col-12">
                    <div class="col-md-4 col-6 mb-2 mb-md-0">
                        <label class="control-label">Before:</label>
                        <div class="customer-image d-flex align-items-center justify-content-center position-relative<?= !in_array($image_0b, [null, '']) ? ' has-avatar' : '' ?>"
                             data-image="<?= !in_array($image_0b, [null, '']) ? $image_0b : '' ?>" data-key="before-0">
                            <span class="btn-remove-image">
                                <i class="fa fa-times"></i>
                            </span>
                            <div class="image-upload">
                                <img src="<?= !in_array($image_0b, [null, '']) ? $pathImage . $image_0b : $defaultImage ?>"
                                     class="img-upload">
                                <div class="avatar-upload-icon">
                                    <i class="fa fa-upload"></i>
                                </div>
                            </div>
                            <?= $form->field($model, 'image_0b')->fileInput(['class' => 'btn-upload d-none'])->label(false) ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-6 mb-2 mb-md-0">
                        <label class="control-label">After:</label>
                        <div class="customer-image d-flex align-items-center justify-content-center position-relative<?= !in_array($image_0a, [null, '']) ? ' has-avatar' : '' ?>"
                             data-image="<?= !in_array($image_0a, [null, '']) ? $image_0a : '' ?>" data-key="after-0">
                            <span class="btn-remove-image">
                                <i class="fa fa-times"></i>
                            </span>
                            <div class="image-upload">
                                <img src="<?= !in_array($image_0a, [null, '']) ? $pathImage . $image_0a : $defaultImage ?>"
                                     class="img-upload">
                                <div class="avatar-upload-icon">
                                    <i class="fa fa-upload"></i>
                                </div>
                            </div>
                            <?= $form->field($model, 'image_0a')->fileInput(['class' => 'btn-upload d-none'])->label(false) ?>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-group row align-items-center">
                <label class="control-label col-md-2 col-12">Khách 2:</label>
                <div class="row ccb-content p-0 col-md-10 col-12">
                    <div class="col-md-4 col-6 mb-2 mb-md-0">
                        <label class="control-label">Before:</label>
                        <div class="customer-image d-flex align-items-center justify-content-center position-relative<?= !in_array($image_1b, [null, '']) ? ' has-avatar' : '' ?>"
                             data-image="<?= !in_array($image_1b, [null, '']) ? $image_1b : '' ?>" data-key="before-1">
                            <span class="btn-remove-image">
                                <i class="fa fa-times"></i>
                            </span>
                            <div class="image-upload">
                                <img src="<?= !in_array($image_1b, [null, '']) ? $pathImage . $image_1b : $defaultImage ?>"
                                     class="img-upload">
                                <div class="avatar-upload-icon">
                                    <i class="fa fa-upload"></i>
                                </div>
                            </div>
                            <?= $form->field($model, 'image_1b')->fileInput(['class' => 'btn-upload d-none'])->label(false) ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-6 mb-2 mb-md-0">
                        <label class="control-label">After:</label>
                        <div class="customer-image d-flex align-items-center justify-content-center position-relative<?= !in_array($image_1a, [null, '']) ? ' has-avatar' : '' ?>"
                             data-image="<?= !in_array($image_1a, [null, '']) ? $image_1a : '' ?>" data-key="after-1">
                            <span class="btn-remove-image">
                                <i class="fa fa-times"></i>
                            </span>
                            <div class="image-upload">
                                <img src="<?= !in_array($image_1a, [null, '']) ? $pathImage . $image_1a : $defaultImage ?>"
                                     class="img-upload">
                                <div class="avatar-upload-icon">
                                    <i class="fa fa-upload"></i>
                                </div>
                            </div>
                            <?= $form->field($model, 'image_1a')->fileInput(['class' => 'btn-upload d-none'])->label(false) ?>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="form-group row align-items-center">
                <label class="control-label col-md-2 col-12">Khách 3:</label>
                <div class="row ccb-content p-0 col-md-10 col-12">
                    <div class="col-md-4 col-6 mb-2 mb-md-0">
                        <label class="control-label">Before:</label>
                        <div class="customer-image d-flex align-items-center justify-content-center position-relative<?= !in_array($image_2b, [null, '']) ? ' has-avatar' : '' ?>"
                             data-image="<?= !in_array($image_2b, [null, '']) ? $image_2b : '' ?>" data-key="before-2">
                            <span class="btn-remove-image">
                                <i class="fa fa-times"></i>
                            </span>
                            <div class="image-upload">
                                <img src="<?= !in_array($image_2b, [null, '']) ? $pathImage . $image_2b : $defaultImage ?>"
                                     class="img-upload">
                                <div class="avatar-upload-icon">
                                    <i class="fa fa-upload"></i>
                                </div>
                            </div>
                            <?= $form->field($model, 'image_2b')->fileInput(['class' => 'btn-upload d-none'])->label(false) ?>
                        </div>
                    </div>
                    <div class="col-md-4 col-6 mb-2 mb-md-0">
                        <label class="control-label">After:</label>
                        <div class="customer-image d-flex align-items-center justify-content-center position-relative<?= !in_array($image_2a, [null, '']) ? ' has-avatar' : '' ?>"
                             data-image="<?= !in_array($image_2a, [null, '']) ? $image_2a : '' ?>" data-key="after-2">
                            <span class="btn-remove-image">
                                <i class="fa fa-times"></i>
                            </span>
                            <div class="image-upload">
                                <img src="<?= !in_array($image_2a, [null, '']) ? $pathImage . $image_2a : $defaultImage ?>"
                                     class="img-upload">
                                <div class="avatar-upload-icon">
                                    <i class="fa fa-upload"></i>
                                </div>
                            </div>
                            <?= $form->field($model, 'image_2a')->fileInput(['class' => 'btn-upload d-none'])->label(false) ?>
                        </div>
                    </div>
                </div>
            </div>
            <hr>

            <?php if (Yii::$app->controller->action->id == 'create') {
                    $model->status = 1;
                }
            ?>
            <?= $form->field($model, 'status')->checkbox() ?>
        </div>
        <div class="form-actions">
            <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
                'btn btn-warning mr-1']) ?>
            <?= Html::submitButton(
                    '<i class="fa fa-check-square-o"></i> Save',
                    ['class' => 'btn btn-primary']
                ) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php
$urlDelete = \yii\helpers\Url::toRoute(['delete-image', 'id' => $model->primaryKey]);
$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];
$script = <<< JS
$('.ipt-price').on('change paste keyup', function(){
    var val = $(this).val() || '';
    val = val.replace(/\./g, '').replace(/[^0-9]/gi, '');
    $(this).val(addCommas(val));
}).trigger('change');
$('body').on('click', '.avatar-upload-icon', function() {
    $(this).closest('.customer-image').find('.btn-upload').trigger('click');
}).on('click', '.btn-remove-image', function(){
    var content = $(this).closest('.customer-image'),
        img = content.attr('data-image') || null,
        key = content.attr('data-key') || null;
    if(key === null){
        content.removeClass('has-avatar').find('.img-upload').attr('src', '$defaultImage');
        content.find('.btn-upload').val('').closest('.form-group').removeClass('has-error').find('.help-block').html('');
    } else {
        Swal.fire({
            title: 'Bạn có chắc muốn xoá?',
            text: "Bạn sẽ không khôi phục lại được!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Vâng, xoá nó!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    cache: false,
                    data:{
                        key: key,
                        img: img
                    },
                    url: "$urlDelete",
                    dataType: "json",
                    success: function(data){
                        if(data.status == 'success') {
                            toastr.success('$deleteSuccess', 'Thông báo');
                            content.removeClass('has-avatar').attr({
                                'data-image': '',
                                'data-key': ''
                            }).find('.img-upload').attr('src', '$defaultImage');
                            content.find('.btn-upload').val('').closest('.form-group').removeClass('has-error').find('.help-block').html('');
                        } else {
                            toastr.error('$deleteDanger', 'Thông báo');
                        }
                    }
                });
            }
        });
    }
}).on('change', '.btn-upload', function(){
    var input = this;
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e){
            $(input).closest('.customer-image').addClass('has-avatar img-temp').find('.img-upload').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        var content = $(input).closest('.customer-image'),
            img = content.attr('data-image') || null;
        if(img === null){
            content.removeClass('has-avatar img-temp').find('.img-upload').attr('src', '$defaultImage');
        } else {
            content.addClass('has-avatar').removeClass('img-temp').find('.img-upload').attr('src', '$pathImage' + img);
        }
    }
    return false;
}).on('beforeSubmit', '#form-dich-vu', function(){
    $(this).myLoading({
        opacity: true
    });
});
JS;
$this->registerJs($script, \yii\web\View::POS_END);
