<?php

use dosamigos\datepicker\DatePicker;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\modules\location\models\Province;
use backend\modules\setting\models\Dep365CoSo;

$this->title = \Yii::t('backend', 'Thêm khách hàng cũ');
$avatarDefault = Url::to('@web/local') . '/default/avatar-default.png';
$avatar = ($customer->avatar != null && file_exists(Yii::$app->basePath . '/web/uploads/avatar/70x70/' . $customer->avatar)) ? Url::to('@web/uploads') . '/avatar/70x70/' . $customer->avatar : $avatarDefault;
?>
    <section id="dom">
        <div class="row">
            <div class="col-12">
                <?php
                if (Yii::$app->session->hasFlash('alert')) {
                    ?>
                    <div class="alert <?= Yii::$app->session->getFlash('alert')['class']; ?> alert-dismissible"
                         role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                        <?= Yii::$app->session->getFlash('alert')['body']; ?>
                    </div>
                    <?php
                }
                ?>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><?= $this->title; ?></h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                <li><a class="block-page"
                                       onclick='window.location="<?= \Yii::$app->getRequest()->getUrl(); ?>"'><i
                                                class="ft-rotate-cw"></i></a></li>
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                <li><a data-action="close"><i class="ft-x"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show" id="content-import-customer">
                        <div class="card-body card-dashboard">
                            <div class="customer-content import-customer-content">
                                <?php
                                $form = ActiveForm::begin([
                                    'id' => 'form-import-customer',
                                ]);
                                ?>
                                <div class="cc-block">
                                    <div class="ccb-header">
                                        <div class="ccbh-title">Thông tin khách hàng</div>
                                    </div>
                                    <div class="ccb-content">
                                        <div class="c-content">
                                            <div class="c-info">
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-6">
                                                        <?= $form->field($customer, 'name')->textInput() ?>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6">
                                                        <?= $form->field($customer, 'birthday')->widget(
                                    DatePicker::class,
                                    [
                                                            'template' => '{input}{addon}',
                                                            'clientOptions' => [
                                                                'autoclose' => true,
                                                                'format' => 'dd-mm-yyyy',
                                                            ],
                                                            'options' => [
                                                                'autocomplete' => 'off',
                                                            ]
                                                        ]
                                ); ?>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6">
                                                        <?= $form->field($customer, 'phone')->textInput(['id' => 'customer-phone']) ?>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6">
                                                        <?= $form->field($customer, 'province')->dropDownList(ArrayHelper::map(Province::getProvince(), 'id', 'name'), ['class' => 'select2 form-control', 'prompt' => 'Chọn tỉnh thành...', 'style' => 'width:100%']) ?>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6">
                                                        <?= $form->field($customer, 'co_so')->dropDownList(ArrayHelper::map(Dep365CoSo::find()->published()->all(), 'id', 'name'), ['prompt' => 'Chọn cơ sở...']) ?>
                                                    </div>
                                                    <div class="col-lg-4 col-md-6">
                                                        <?= $form->field($customer, 'face_customer')->textInput() ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="c-avatar">
                                                <div class="customer-avatar">
                                                    <div class="avatar-upload">
                                                        <img src="<?= $avatar ?>" class="img-avatar"/>
                                                        <div class="avatar-upload-icon">
                                                            <i class="fa fa-upload"></i>
                                                        </div>
                                                    </div>
                                                    <div class="avatar-text">
                                                        <p class="avatar-text-title">Upload avatar</p>
                                                        <p class="avatar-text-note">(Tối đa 5Mb)</p>
                                                    </div>
                                                    <?= $form->field($customer, 'avatar')->fileInput(['class' => 'hidden ipt-avatar'])->label(false) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php ActiveForm::end() ?>
                                <?php
                                $form = ActiveForm::begin([
                                    'id' => 'form-chup-hinh',
                                    'options' => [
                                        'class' => 'form-upload',
                                        'form-name' => 'FormChupHinh'
                                    ],
                                    'action' => Url::toRoute(['/clinic/chup-hinh/upload-image']),
                                ]);
                                ?>
                                <div class="cc-block">
                                    <div class="ccb-header">
                                        <div class="ccbh-title">Chụp hình</div>
                                        <div class="ccbh-edit" style="display: none;">
                                            <button type="button" class="btn btn-sm btn-danger remove-image">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="ccb-content">
                                        <?= $form->field($formChupHinh, 'fileImage')->fileInput(['multiple' => 'multiple', 'class' => 'btn-upload hidden'])->label(false) ?>
                                        <div class="g-list-file">
                                            <?php
                                            if (count($listChupHinh) > 0) {
                                                foreach ($listChupHinh as $file) {
                                                    ?>
                                                    <div class="g-file">
                                                        <div>
                                                            <img class="g-thumb" src="<?= $file['thumbnailLink'] ?>">
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                            } ?>
                                            <div class="g-btn-upload">
                                                <div class="btn-upload-tmp"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php ActiveForm::end() ?>
                                <?php
                                $form = ActiveForm::begin([
                                    'id' => 'form-chup-banh-moi',
                                    'options' => [
                                        'class' => 'form-upload',
                                        'form-name' => 'FormChupBanhMoi'
                                    ],
                                    'action' => Url::toRoute(['/clinic/chup-banh-moi/upload-image']),
                                ]);
                                ?>
                                <div class="cc-block">
                                    <div class="ccb-header">
                                        <div class="ccbh-title">Chụp banh môi</div>
                                        <div class="ccbh-edit" style="display: none;">
                                            <button type="button" class="btn btn-sm btn-danger remove-image">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="ccb-content">
                                        <?= $form->field($formChupBanhMoi, 'fileImage')->fileInput(['multiple' => 'multiple', 'class' => 'btn-upload hidden'])->label(false) ?>
                                        <div class="g-list-file">
                                            <?php
                                            if (count($listChupBanhMoi) > 0) {
                                                foreach ($listChupBanhMoi as $file) {
                                                    ?>
                                                    <div class="g-file">
                                                        <div>
                                                            <img class="g-thumb" src="<?= $file['thumbnailLink'] ?>">
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                            } ?>
                                            <div class="g-btn-upload">
                                                <div class="btn-upload-tmp"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php ActiveForm::end() ?>
                                <?php
                                $form = ActiveForm::begin([
                                    'id' => 'form-chup-cui',
                                    'options' => [
                                        'class' => 'form-upload',
                                        'form-name' => 'FormChupCui'
                                    ],
                                    'action' => Url::toRoute(['/clinic/chup-cui/upload-image']),
                                ]);
                                ?>
                                <div class="cc-block">
                                    <div class="ccb-header">
                                        <div class="ccbh-title">Chụp cùi</div>
                                        <div class="ccbh-edit" style="display: none;">
                                            <button type="button" class="btn btn-sm btn-danger remove-image">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="ccb-content">
                                        <?= $form->field($formChupCui, 'fileImage')->fileInput(['multiple' => 'multiple', 'class' => 'btn-upload hidden'])->label(false) ?>
                                        <div class="g-list-file">
                                            <?php
                                            if (count($listChupCui) > 0) {
                                                foreach ($listChupCui as $file) {
                                                    ?>
                                                    <div class="g-file">
                                                        <div>
                                                            <img class="g-thumb" src="<?= $file['thumbnailLink'] ?>">
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                            } ?>
                                            <div class="g-btn-upload">
                                                <div class="btn-upload-tmp"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php ActiveForm::end() ?>
                                <?php
                                $form = ActiveForm::begin([
                                    'id' => 'form-chup-final',
                                    'options' => [
                                        'class' => 'form-upload',
                                        'form-name' => 'FormChupFinal'
                                    ],
                                    'action' => Url::toRoute(['/clinic/chup-final/upload-image']),
                                ]);
                                ?>
                                <div class="cc-block">
                                    <div class="ccb-header">
                                        <div class="ccbh-title">Chụp kết thúc</div>
                                        <div class="ccbh-edit" style="display: none;">
                                            <button type="button" class="btn btn-sm btn-danger remove-image">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="ccb-content">
                                        <?= $form->field($formChupFinal, 'fileImage')->fileInput(['multiple' => 'multiple', 'class' => 'btn-upload hidden'])->label(false) ?>
                                        <div class="g-list-file">
                                            <?php
                                            if (count($listChupFinal) > 0) {
                                                foreach ($listChupFinal as $file) {
                                                    ?>
                                                    <div class="g-file">
                                                        <div>
                                                            <img class="g-thumb" src="<?= $file['thumbnailLink'] ?>">
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                            } ?>
                                            <div class="g-btn-upload">
                                                <div class="btn-upload-tmp"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php ActiveForm::end() ?>
                                <?php
                                $form = ActiveForm::begin([
                                    'id' => 'form-hinh-tknc',
                                    'options' => [
                                        'class' => 'form-upload',
                                        'form-name' => 'FormHinhTknc'
                                    ],
                                    'action' => Url::toRoute(['/clinic/tknc/upload-image']),
                                ]);
                                ?>
                                <div class="cc-block">
                                    <div class="ccb-header">
                                        <div class="ccbh-title">Thiết kế nụ cười</div>
                                        <div class="ccbh-edit" style="display: none;">
                                            <button type="button" class="btn btn-sm btn-danger remove-image">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="ccb-content">
                                        <?= $form->field($formHinhTknc, 'fileImage')->fileInput(['multiple' => 'multiple', 'class' => 'btn-upload hidden'])->label(false) ?>
                                        <div class="g-list-file">
                                            <?php
                                            if (count($listHinhTknc) > 0) {
                                                foreach ($listHinhTknc as $file) {
                                                    ?>
                                                    <div class="g-file">
                                                        <div>
                                                            <img class="g-thumb" src="<?= $file['thumbnailLink'] ?>">
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                            } ?>
                                            <div class="g-btn-upload">
                                                <div class="btn-upload-tmp"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php ActiveForm::end() ?>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn btn-primary btn-submit">
                                    <i class="fa fa-check-square-o"></i> Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="mymodal">
        <div class="mymodal-content">
            <div class="mymodal-header">
                <div class="mymodal-title">Chọn khách hàng</div>
                <div class="mymodal-close"></div>
            </div>
            <div class="mymodal-body">
                <div class="list-customer row">
                    <div class="customer-default col-12" data-customer="">
                        <div class="customer-content">
                            <div class="customer-info">
                                <div class="customer-name">Khách hàng mới</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="customer-temp customer-row col-6" data-customer="">
        <div class="customer-content">
            <div class="customer-avatar">
                <img src="/local/default/avatar-default.png">
            </div>
            <div class="customer-detail">
                <div class="customer-info"><span class="customer-name">Nguyên Trần</span> <span
                            class="customer-birthday">(20-09-1992)</span></div>
                <div class="customer-phone">0778722149</div>
            </div>
        </div>
    </div>
<?php
$urlValidateData = Url::toRoute(['validate-customer']);
$urlImportCustomer = Url::toRoute(['import-customer']);
$urlSubmitImportCustomer = Url::toRoute(['submit-import-customer', 'id' => $id]);
$urlCheckCustomerPhone = Url::toRoute(['check-customer-phone', 'id' => $id]);
$urlImportById = Url::toRoute(['import-customer', 'id' => '']);
$scriptId = $id == null ? 'null' : $id;
$script = <<< JS
var id = $scriptId,
    listUpload = [],
    countUpload = 0;
function validateData(){
    return new Promise(function(resolve, reject){
        var data_customer = new FormData($('#form-import-customer')[0]);
        $.ajax({
            type: 'POST',
            url: '$urlValidateData',
            dataType: 'json',
            data: data_customer,
            cache: false,
            contentType: false,
            processData: false,
        }).done(function(res) {
            if(res.length <= 0) resolve();
            reject('Có lỗi!');
        }).fail(function(err) {
            reject('Lỗi kiểm tra dữ liệu!');
        })
    });
}
function saveDataCustomer(){
    return new Promise(function(resolve, reject){
        var form = $('#form-import-customer')[0],
            form_data = new FormData(form);
        $.ajax({
            type: 'POST',
            url: '$urlSubmitImportCustomer',
            dataType: 'json',
            data: form_data,
            cache: false,
            processData: false,
            contentType: false,
        }).done(function(res){
            if(res.code === 200){
                resolve(res);
            } else reject(res.msg);
        }).fail(function(err){
            console.log(err);
            reject('Có lỗi xảy ra!');
        });
    });
}
function uploadImages(listUpload){
    countUpload = listUpload.length;
    if(countUpload > 0){
        listUpload.forEach(function(v, k){
            uploadImage(v, k);
        });
    } else {
        toastr.success('Hoàn tất', 'Thông báo');
        setTimeout(function(){
            $.when($(window).unbind('beforeunload')).done(function(){
                window.location.href = '$urlImportCustomer';
            });
        }, 3000);
    }
    /*if(listUpload.length > 0){
        var upload = listUpload[0];
        $.when($('html, body').animate({scrollTop: $('form.form-upload[form-name="'+ upload.form_name +'"]').offset().top - $('.header-navbar').outerHeight()}, 1000)).done(function(){
            uploadImage(upload).then(function(){
                listUpload.splice(0, 1);
                uploadImages(listUpload);
            });
        });
    } else {
        toastr.success('Upload hình ảnh hoàn tất', 'Thông báo');
        setTimeout(function(){
            $.when($(window).unbind('beforeunload')).done(function(){
                window.location.href = '$urlImportCustomer';
            });
        }, 3000);
    }*/
}
function uploadImage(upload, k){
    // return new Promise(function(resolve, reject){
        $.ajax({
            type: 'POST',
            url: upload.url,
            dataType: 'json',
            data: upload.data,
            cache: false,
            contentType: false,
            processData: false,
        }).done(function(res){
            var dataImage = res.data.dataImage || null,
                file = $('form.form-upload[form-name="'+ upload.form_name +'"]').find('.g-file-'+ upload.k);
            if(res.code === 200){
                if(dataImage != null){
                    if(dataImage.id === null || dataImage.id === undefined){
                        console.log('dataImage id null');
                        file.children('div').myUnloading().prepend('<div class="upload-fail"><i class="fa fa-times"></i></div>');
                        setTimeout(function(){
                            file.remove();
                        }, 3000);
                        countUpload -= 1;
                        if(countUpload <= 0){
                            toastr.success('Upload hình ảnh hoàn tất', 'Thông báo');
                            setTimeout(function(){
                                $.when($(window).unbind('beforeunload')).done(function(){
                                    window.location.href = '$urlImportCustomer';
                                });
                            }, 3000);
                        }
                    } else {
                        console.log('upload success');
                        file.children('div').prepend('<div class="upload-success"><i class="fa fa-check"></i></div>').myUnloading();
                        setTimeout(function(){
                            $.when(file.find('.upload-success').fadeOut()).done(function(){
                                file.find('.upload-success').remove();
                            });
                        }, 3000);
                        countUpload -= 1;
                        if(countUpload <= 0){
                            toastr.success('Upload hình ảnh hoàn tất', 'Thông báo');
                            setTimeout(function(){
                                $.when($(window).unbind('beforeunload')).done(function(){
                                    window.location.href = '$urlImportCustomer';
                                });
                            }, 3000);
                        }
                    }
                } else {
                    console.log('dataImage null');
                    file.children('div').myUnloading().prepend('<div class="upload-fail"><i class="fa fa-times"></i></div>');
                    setTimeout(function(){
                        file.remove();
                    }, 3000);
                    countUpload -= 1;
                    if(countUpload <= 0){
                        toastr.success('Upload hình ảnh hoàn tất', 'Thông báo');
                        setTimeout(function(){
                            $.when($(window).unbind('beforeunload')).done(function(){
                                window.location.href = '$urlImportCustomer';
                            });
                        }, 3000);
                    }
                }
            } else {
                toastr.error(res.data.msg, 'Lỗi!');
                file.children('div').myUnloading().prepend('<div class="upload-fail"><i class="fa fa-times"></i></div>');
                setTimeout(function(){
                    file.remove();
                }, 3000);
                countUpload -= 1;
                if(countUpload <= 0){
                    toastr.success('Upload hình ảnh hoàn tất', 'Thông báo');
                    setTimeout(function(){
                        $.when($(window).unbind('beforeunload')).done(function(){
                            window.location.href = '$urlImportCustomer';
                        });
                    }, 3000);
                }
            }
        }).fail(function(err){
            console.log('upload error', upload, err);
            toastr.error('Lỗi upload hình', 'Thông báo upload hình');
            file.children('div').myUnloading().prepend('<div class="upload-fail"><i class="fa fa-times"></i></div>');
            setTimeout(function(){
                file.remove();
            }, 3000);
            countUpload -= 1;
            if(countUpload <= 0){
                toastr.success('Upload hình ảnh hoàn tất', 'Thông báo');
                setTimeout(function(){
                    $.when($(window).unbind('beforeunload')).done(function(){
                        window.location.href = '$urlImportCustomer';
                    });
                }, 3000);
            }
        });
    // });
}
$('body').on('click', '.avatar-upload-icon', function() {
    $(this).closest('.customer-avatar').find('.ipt-avatar').trigger('click');
}).on('change', '.ipt-avatar', function() {
    var input = this;
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e){
            $(input).closest('.customer-avatar').find('.img-avatar').prop('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        $(input).closest('.customer-avatar').find('.img-avatar').prop('src', '$avatarDefault');
    };
}).on('change', '.btn-upload', function(){
    var input = this;
    $(input).closest('.ccb-content').find('.g-file-temp').remove();
    if (input.files && input.files[0]) {
        $(input).closest('.cc-block').find('.ccbh-edit').slideDown();
        $.each(input.files, function(k, v){
            var reader = new FileReader();
            reader.onload = function(e){
                $(input).closest('.cc-block').find('.g-btn-upload').before('<div class="g-file-temp g-file-'+ k +'"><div><img class="g-thumb" src="'+ e.target.result +'"></div></div>');
            }
            reader.readAsDataURL(v);
        });
    } else {
        $(input).closest('.cc-block').find('.ccbh-edit').slideUp();
    };
    return false;
}).on('click', '.btn-upload-tmp', function() {
    $(this).closest('.ccb-content').find('.btn-upload').trigger('click');
}).on('click', '.remove-image', function() {
    $(this).slideUp().closest('.cc-block').find('.g-list-file > .g-file-temp').remove();
    $(this).closest('.cc-block').find('.btn-upload').val('').trigger('change');
}).on('change paste', '#customer-phone', function(){
    $('#form-import-customer').myLoading();
    var phone = $(this).val();
    $.ajax({
        type: 'POST',
        url: '$urlCheckCustomerPhone',
        dataType: 'json',
        data: {
            phone: phone
        }
    }).done(function(res){
        $('#form-import-customer').myUnloading();
        console.log('check phone result', res);
        if(res.length > 0){
            res.forEach(function(customer){
                var row = $('.customer-temp').clone().removeClass('customer-temp');
                if(id === null || id !== customer.id) row.attr('data-customer', customer.id);
                else row.addClass('current');
                row.find('.customer-avatar img').prop('src', customer.avatar);
                row.find('.customer-name').text(customer.name);
                row.find('.customer-birthday').text("("+ customer.birthday +")");
                row.find('.customer-phone').text(phone);
                row.insertBefore($('.customer-default'));
            });
            $('.mymodal').addClass('open');
        }
    }).fail(function(err){
        $('#form-import-customer').myUnloading();
        console.log('check phone error', err);
    });
}).on('click', '.customer-row, .customer-default', function(){
    var data_customer = $(this).attr('data-customer') || null;
    if(data_customer === null){
        $('.mymodal-close').trigger('click');
    } else {
        var url = '$urlImportById'+ data_customer;
        window.location.href = url;
    }
}).on('click', '.mymodal-close', function(){
    $('.mymodal').removeClass('open').find('.customer-row').remove();
}).on('click', '.btn-submit', function(e) {
    e.preventDefault();
    $('.content-body').myLoading({
        fixed: true,
        opacity: true
    });
    validateData().then(function(){
        $(window).bind('beforeunload', function(){
            setTimeout(function(){
                $('body').myUnloading();
            }, 50);
            return "";
        });
        $.when($('html, body').animate({scrollTop: $('form#form-import-customer').offset().top - $('.header-navbar').outerHeight()}, 1000)).done(function(){
            saveDataCustomer().then(function(result) {
                $('.content-body').myUnloading();
                toastr.success(result.msg, 'Thông báo');
                var id = result.id || null;
                $('.form-upload').each(function() {
                    var url = $(this).attr('action') || null,
                        form_name = $(this).attr('form-name') || null,
                        _csrf = $(this).find('input[name="_csrf"]').val() || null,
                        input = $(this).find('.btn-upload') || null;
                    if(form_name != null && input != null && input[0].files.length > 0){
                        Object.keys(input[0].files).forEach(function(k){
                            var form_data = new FormData();
                            form_data.append('_csrf', _csrf);
                            form_data.append(form_name +'[id]', id);
                            form_data.append(form_name +'[fileImage][]', input[0].files[k])
                            listUpload.push({
                                k: k,
                                form_name: form_name,
                                url: url +'?id='+ id,
                                data: form_data,
                            });
                        });
                    }
                });
                if(id != null && listUpload.length > 0){
                    toastr.warning('Bắt đầu upload hình ảnh khách hàng', 'Thông báo');
                    $('.content-body').find('.g-btn-upload, .ccbh-edit').hide();
                    $('.content-body').find('.g-file-temp').children('div').myLoading({
                        opacity: true
                    });
                }
                uploadImages(listUpload);
            }, function(err) {
                $('.content-body').myUnloading();
                toastr.error(err, 'Thông báo');
                $(window).unbind('beforeunload');
            });
        });
    },function(err){
        $('.content-body').myUnloading();
        toastr.error(err, 'Thông báo');
    });
    return false;
});
JS;
$this->registerJs($script, \yii\web\View::POS_END);
