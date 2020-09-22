<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$id = Yii::$app->getRequest()->getQueryParam('id');
$url = Url::toRoute(['upload', 'id' => $id]);

$this->title = Yii::t('backend', 'Tải lên hình khách hàng');
$this->params['breadcrumbs'][] = $this->title;
?>

    <section id="dom" class="cls-dom">
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
                        <h4 class="card-title">
                            <span class="customer-info">
                                <p><?= $this->title ?>: <?= $customer->full_name; ?></p>
                                <p>Mã khách hàng: <?= $customer->customer_code ?></p>
                            </span>
                        </h4>
                        <div class="g-download-list">
                            <button type="button" class="btn btn-primary">Tải về <span></span> hình</button>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <?php $form = ActiveForm::begin(['id' => 'dpz-multiple-files']); ?>
                            <div class="g-list-file">
                                <?php foreach ($listFile as $file) { ?>
                                    <div class="g-file">
                                        <div>
                                            <div class="g-tools">
                                                <span>
                                                    <a class="g-download g-image"
                                                       data-image="<?= $file['webContentLink'] ?>"
                                                       data-id="<?= $file['id'] ?>"
                                                       data-title="<?= $customer->full_name ?>"
                                                       data-type="<?= $file['type'] ?>">
                                                        <i class="fa fa-download"></i>
                                                    </a>
                                                </span>
                                                <span>
                                                    <a class="g-check">
                                                        <label class="square-checkbox">
                                                            <input type="checkbox">
                                                            <span></span>
                                                        </label>
                                                    </a>
                                                </span>
                                            </div>
                                            <div class="g-view">
                                                <i class="fa fa-search"></i>
                                            </div>
                                            <img class="g-thumb" src="<?= $file['thumbnailLink'] ?>">
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="g-btn-upload">
                                    <?= $form->field($model, 'fileImage[]')->fileInput(['multiple' => 'multiple', 'class' => 'btn-upload', 'title' => 'Cập nhật hình khách hàng'])->label(false) ?>
                                </div>
                            </div>
                            <?= $form->field($model, 'id')->hiddenInput(['id' => 'customer'])->label(false) ?>
                            <div class="form-actions">
                                <?= Html::a('<i class="fa fa-angle-left"></i> Quay lại', Url::toRoute('index'), ['class' => "btn btn-primary"]) ?>
                                <?= Html::a('Chụp hình <i class="fa fa-angle-right"></i>', Url::toRoute(['chup-hinh/upload', 'id' => $id]), ['class' => "btn btn-success"]) ?>
                                <?= Html::a('Chụp banh môi <i class="fa fa-angle-right"></i>', Url::toRoute(['chup-banh-moi/upload', 'id' => $id]), ['class' => "btn btn-success"]) ?>
                                <?= Html::a('Chụp cùi <i class="fa fa-angle-right"></i>', Url::toRoute(['chup-cui/upload', 'id' => $id]), ['class' => "btn btn-success"]) ?>
                                <?= Html::a('Thiết kế nụ cười <i class="fa fa-angle-right"></i>', Url::toRoute(['tknc/upload', 'id' => $id]), ['class' => "btn btn-success"]) ?>
                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <a id="target"></a>
<?php
$urlDownload = Url::toRoute('download');
$urlUploadImage = Url::toRoute(['upload-image', 'id' => $customer->id]);
$urlReloadImage = Url::toRoute('reload');
//$this->registerJsFile('/vendors/plugins/fancybox/dist/jquery.fancybox.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$script = <<< JS
var intervalDownload,
    listDownload = {},
    isDownloading = false,
    listImageFancy = [];
function openGDownload(btnDownload){
    return new Promise((resolve, reject) => {
        var id = btnDownload.attr('data-id') || null,
            type = btnDownload.attr('data-type') || null;
        if(id === null) reject(false);
        var downloadUrl = "$urlDownload?fileId="+ id +"&type="+ type,
            req = new XMLHttpRequest();
        req.open("GET", downloadUrl, true);
        req.responseType = "blob";
        
        req.onload = function (event) {
            var blob = req.response,
                fileName = null,
                contentType = req.getResponseHeader("content-type");
            
            // IE/EDGE seems not returning some response header
            if (req.getResponseHeader("content-disposition")) {
                var contentDisposition = req.getResponseHeader("content-disposition");
                fileName = contentDisposition.substring(contentDisposition.indexOf("=")+1).replace(/"/g, "");
            } else {
                fileName = "unnamed." + contentType.substring(contentType.indexOf("/")+1).replace(/"/g, "");
            }
            
            if (window.navigator.msSaveOrOpenBlob) {
                // Internet Explorer
                window.navigator.msSaveOrOpenBlob(new Blob([blob], {type: contentType}), fileName);
            } else {
                var el = document.getElementById("target");
                el.href = window.URL.createObjectURL(blob);
                el.download = fileName;
                el.click();
                btnDownload.closest('.g-file').children('div').myUnloading();
                $('#target').removeAttr('href download');
                resolve(true);
            }
        };
        req.send();
    });
}
function openListGDownload(){
    if(isDownloading == true) return false;
    clearInterval(intervalDownload);
    if(listDownload.length <= 0){
        $('.g-download-list').slideUp();
        return false;
    }
    isDownloading = true;
    var btn = listDownload[0];
    listDownload = listDownload.splice(1, listDownload.length);
    intervalDownload = setInterval(function(){
        openListGDownload();
    }, 1000);
    openGDownload($(btn)).then(
        function(){
            $.when($(btn).closest('.g-tools').removeClass('active').find('.g-check > label > input').prop('checked', false)).done(function(){
                isDownloading = false;
                if(listDownload.length <= 0){
                    $('.g-list-file').removeClass('downloading');
                    return false;
                }
            });
        }
    ).catch(function(){
        $.when($(btn).closest('.g-tools').removeClass('active').find('.g-check > label > input').prop('checked', false)).done(function(){
            isDownloading = false;
            if(listDownload.length <= 0){
                $('.g-list-file').removeClass('downloading');
                return false;
            }
        });
    });
}
function countGCheck(){
    $('.g-download-list > button > span').text($('.g-tools.active').length);
}

$('.g-file').each(function(){
    var title = $(this).find('.g-image').attr('data-title'),
        image = $(this).find('.g-image').attr('data-image'),
        thumb = $(this).find('.g-thumb').attr('src');
    listImageFancy.push({
        src: image,
        opts: {
            caption: title,
            thumb: thumb
        }
    });
});
$('body').on('click', '.g-download', function(){
    $(this).closest('.g-file').children('div').myLoading({
        msg: 'Đang tải...'
    });
    openGDownload($(this));
});
$('body').on('click', '.g-view', function(){
    var i = $(this).closest('.g-file').index(),
        a = listImageFancy.slice(i, listImageFancy.length),
        b = listImageFancy.slice(0, i),
        tmp = $.merge(a, b);
    $.fancybox.open(tmp, {
        loop: true,
        buttons : [
            'download',
            'thumbs',
            'close'
        ]
    });
});
$('body').on('change', '.g-check > .square-checkbox > input', function(){
    $.when($(this).closest('.g-tools').toggleClass('active')).done(function(){
        countGCheck();
        if($('.g-tools.active').length > 0){
            if(!$('.g-download-list').is(':visible')) $('.g-download-list').slideDown();
        } else {
            $('.g-download-list').slideUp();
        }
    });
});
$('body').on('click', '.g-download-list > button', function(){
    $(this).slideUp();
    $('.g-tools.active').closest('.g-file').children('div').myLoading({
        msg: 'Đang tải...'
    });
    $('.g-list-file').addClass('downloading');
    listDownload = $('.g-tools.active .g-download');
    openListGDownload();
});
$('body').on('change', '.btn-upload', function(){
    var input = this;
    if(input.files.length > 10){
        toastr.error('Chỉ có thể upload tối đa 10 tấm hình', 'Cảnh báo!');
        $(input).val('');
    } else {
        if (input.files && input.files[0]) {
            $.when($.each(input.files, function(k, v){
                var reader = new FileReader();
            
                reader.onload = function(e){
                    var temp = $('<div class="g-file-temp g-file-'+ k +'"><div><img class="g-thumb" src="'+ e.target.result +'"></div></div>');
                    temp.insertBefore('.g-btn-upload').children('div').myLoading({
                        msg: 'Đang tải...'
                    });
                }
            
                reader.readAsDataURL(v);
            })).done(function(){
                uploadImages(input);
            });
        };
    }
    return false;
});
function uploadImages(input){
    var form = document.getElementById('dpz-multiple-files');
        a = new FormData(form);
    Object.keys(input.files).forEach(function(k){
        var v = input.files[k];
        var form_data = new FormData();
        form_data.append('_csrf', $('#dpz-multiple-files').children('input[name="_csrf"]').val());
        form_data.append('FormChupFinal[id]', $('#dpz-multiple-files #customer').val());
        form_data.append('FormChupFinal[fileImage][]', v);
        $.ajax({
            type: 'post',
            url: '$urlUploadImage',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
        }).done(function(res){
            var dataImage = res.data.dataImage || null;
            if(res.code === 200){
                if(dataImage != null){
                    var file = $('.g-file-'+ k);
                    if(dataImage.id === null || dataImage.id === undefined){
                        console.log('dataImage id null');
                        $('.g-file-'+ k).children('div').myUnloading().prepend('<div class="upload-fail"><i class="fa fa-times"></i></div>');
                        setTimeout(function(){
                            $('.g-file-'+ k).remove();
                        }, 3000);
                    } else {
                        if(dataImage.image === null || dataImage.image === undefined || dataImage.image === ''){
                            console.log('dataImage image null');
                            file.removeClass('g-file-temp g-file-'+ k).addClass('g-file not-ready').children('div').prepend('<div class="upload-fail"><i class="fa fa-times"></i></div><div class="g-file-reload" data-image="'+ dataImage.id +'"><i class="fa fa-refresh"></i></div>').myUnloading();
                            setTimeout(function(){
                                $.when(file.find('.upload-fail').fadeOut()).done(function(){
                                    file.find('.upload-fail').remove();
                                });
                            }, 3000);
                        } else {
                            console.log('upload success');
                            file.removeClass('g-file-temp g-file-'+ k).addClass('g-file').children('div').prepend('<div class="upload-success"><i class="fa fa-check"></i></div><div class="g-tools"><span><a class="g-download g-image" data-image="'+ dataImage.image +'" data-id="'+ dataImage.id +'" data-type="'+ dataImage.type +'" data-title="'+ dataImage.title +'"><i class="fa fa-download"></i></a></span><span><a class="g-check"><label class="square-checkbox"><input type="checkbox"><span></span></label></a></span></div><div class="g-view"><i class="fa fa-search"></i></div>').myUnloading();
                            file.find('.g-thumb').attr('src', dataImage.thumb);
                            setTimeout(function(){
                                $.when(file.find('.upload-success').fadeOut()).done(function(){
                                    file.find('.upload-success').remove();
                                });
                            }, 3000);
                            listImageFancy.push({
                                src: dataImage.image,
                                opts: {
                                    caption: dataImage.title,
                                    thumb: dataImage.thumb
                                }
                            });
                        }
                    }
                }
            } else {
                toastr.error(res.data.msg, 'Lỗi!');
                $('.g-file-'+ k).children('div').myUnloading().prepend('<div class="upload-fail"><i class="fa fa-times"></i></div>');
                setTimeout(function(){
                    $('.g-file-'+ k).remove();
                }, 3000);
            }
        }).fail(function(err){
            console.log('upload error', err);
            $('.g-file-'+ k).children('div').myUnloading().prepend('<div class="upload-fail"><i class="fa fa-times"></i></div>');
            setTimeout(function(){
                $('.g-file-'+ k).remove();
            }, 3000);
        });
    });
    $('.btn-upload').val('');
}
$('body').on('beforesubmit submit', '#dpz-multiple-files', function(e){
    e.preventDefault();
    return false;
});
$('body').on('click', '.g-file-reload', function(){
    var btn_reload = $(this),
        id = btn_reload.attr('data-image') || null,
        file = btn_reload.closest('.g-file');
    if(id === null) file.remove();
    $.ajax({
        type: 'POST',
        url: '$urlReloadImage',
        dataType: 'json',
        data: {
            id: id
        }
    }).done(function(res){
        if(res.code === 200){
            var dataImage = res.data.dataImage;
            btn_reload.remove();
            file.children('div').prepend('<div class="upload-success"><i class="fa fa-check"></i></div><div class="g-tools"><span><a class="g-download g-image" data-image="'+ dataImage.image +'" data-id="'+ dataImage.id +'" data-title="'+ dataImage.title +'"><i class="fa fa-download"></i></a></span><span><a class="g-check"><label class="square-checkbox"><input type="checkbox"><span></span></label></a></span></div><div class="g-view"><i class="fa fa-search"></i></div>').myUnloading();
            file.find('.g-thumb').attr('src', dataImage.thumb);
            setTimeout(function(){
                $.when(file.find('.upload-success').fadeOut()).done(function(){
                    file.find('.upload-success').remove();
                });
            }, 3000);
            listImageFancy.push({
                src: dataImage.image,
                opts: {
                    caption: dataImage.title,
                    thumb: dataImage.thumb
                }
            });
        } else {
            toastr.error(res.data.msg, 'Thông báo');
            file.children('div').prepend('<div class="upload-fail"><i class="fa fa-times"></i></div>');
            setTimeout(function(){
                $.when(file.find('.upload-fail').fadeOut()).done(function(){
                    file.find('.upload-fail').remove();
                });
            }, 3000);
        }
    });
});
JS;
$this->registerJs($script, \yii\web\View::POS_END);
