<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 08-04-2019
 * Time: 10:02 AM
 */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$name = $customer->full_name == null ? $customer->forename : $customer->full_name;

$this->title = Yii::t('backend', 'Khách hàng ' . $name);

?>
    <section id="contentual-update">
        <?php
        if (Yii::$app->session->hasFlash('alert')) {
            ?>
            <div class="alert <?= Yii::$app->session->getFlash('alert')['class'] ?> alert-dismissible"
                 role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <?= Yii::$app->session->getFlash('alert')['body'] ?>
            </div>
            <?php
        }
        ?>
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    Khách hàng: <strong><?= $name; ?></strong>
                </h4>
            </div>
            <div class="card-content collapse show">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-4 col-12">
                            <div class="tooth-model">
                                <div class="form-group">
                                    <label class="control-label">Mô hình răng</label>
                                    <div class="form-rang">
                                        <div class="ham ham-tren" data-width="486" data-height="400">
                                            <div class="trai">
                                                <div class="rang rang-8" data-tag="rang-28"></div>
                                                <div class="rang rang-7" data-tag="rang-27"></div>
                                                <div class="rang rang-6" data-tag="rang-26"></div>
                                                <div class="rang rang-5" data-tag="rang-25"></div>
                                                <div class="rang rang-4" data-tag="rang-24"></div>
                                                <div class="rang rang-3" data-tag="rang-23"></div>
                                                <div class="rang rang-2" data-tag="rang-22"></div>
                                                <div class="rang rang-1" data-tag="rang-21"></div>
                                            </div>
                                            <div class="phai">
                                                <div class="rang rang-1" data-tag="rang-11"></div>
                                                <div class="rang rang-2" data-tag="rang-12"></div>
                                                <div class="rang rang-3" data-tag="rang-13"></div>
                                                <div class="rang rang-4" data-tag="rang-14"></div>
                                                <div class="rang rang-5" data-tag="rang-15"></div>
                                                <div class="rang rang-6" data-tag="rang-16"></div>
                                                <div class="rang rang-7" data-tag="rang-17"></div>
                                                <div class="rang rang-8" data-tag="rang-18"></div>
                                            </div>
                                        </div>
                                        <div class="ham ham-duoi" data-width="486" data-height="400">
                                            <div class="trai">
                                                <div class="rang rang-8" data-tag="rang-38"></div>
                                                <div class="rang rang-7" data-tag="rang-37"></div>
                                                <div class="rang rang-6" data-tag="rang-36"></div>
                                                <div class="rang rang-5" data-tag="rang-35"></div>
                                                <div class="rang rang-4" data-tag="rang-34"></div>
                                                <div class="rang rang-3" data-tag="rang-33"></div>
                                                <div class="rang rang-2" data-tag="rang-32"></div>
                                                <div class="rang rang-1" data-tag="rang-31"></div>
                                            </div>
                                            <div class="phai">
                                                <div class="rang rang-1" data-tag="rang-41"></div>
                                                <div class="rang rang-2" data-tag="rang-42"></div>
                                                <div class="rang rang-3" data-tag="rang-43"></div>
                                                <div class="rang rang-4" data-tag="rang-44"></div>
                                                <div class="rang rang-5" data-tag="rang-45"></div>
                                                <div class="rang rang-6" data-tag="rang-46"></div>
                                                <div class="rang rang-7" data-tag="rang-47"></div>
                                                <div class="rang rang-8" data-tag="rang-48"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-4 col-12">
                            <div class="information">
                                <div class="examination-result">
                                    <div class="form-group">
                                        <label class="control-label">Kết quả khám</label>
                                        <div class="rang-tag">
                                            <?php
                                            foreach ($listTag as $tag) {
                                                echo $this->render('_tagHtml', ['model' => $tag]);
                                            }
                                            ?>
                                        </div>
                                        <!--<div class="tag tag-template" data-tag="">
                                            <div class="tag-name">
                                                #<span></span>
                                            </div>
                                            <div class="tag-tham-kham">
                                                <input class="form-control ketqua-thamkham" type="text"/>
                                            </div>
                                            <div class="tag-remove" data-id="">
                                                <i class="ft-trash-2"></i>
                                            </div>
                                        </div>-->
                                    </div>
                                </div>
                                <div class="huong-dieu-tri">
                                    <?php $form = ActiveForm::begin([
                                        'id' => 'form_huong-dieu-tri',
                                    ]) ?>
                                    <?= $form->field($customer, 'customer_huong_dieu_tri')
                                        ->textarea([
                                            'rows' => 6,
                                            'placeholder' => 'Hướng điều trị',
                                            'id' => 'description',
                                        ]) ?>
                                    <div class="form-group pull-right">
                                        <?= Html::submitButton(
                                            '<i class="fa fa-check-square-o"></i> Save',
                                            ['class' => 'btn btn-primary']
                                        ) ?>
                                    </div>
                                    <?php ActiveForm::end() ?>

                                    <div class="form-group pull-right">
                                        <?= Html::a('<i class="ft-x"></i> Close', Url::toRoute('index'), ['class' => 'btn btn-warning mr-1']) ?>
                                        <?php /*<?= Html::button('<i class="ft-x"></i> Close', ['class' => 'btn btn-default mr-1 close-update', 'data-pjax' => 0]) ?>
                                        <?= Html::submitButton('<i class="fa fa-check-square-o"></i> Save', ['class' => 'btn btn-primary', 'data-pjax' => 0]) ?>*/ ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
$urlLoadTagHtml = Url::toRoute('load-tag-html');
$urlCreateOrUpdateTag = Url::toRoute('create-or-update-tag');
$urlDeleteTag = Url::toRoute('delete-tag');
$script = <<< JS
$('body').on('click', '.rang', function(){
    $('.rang').removeClass('active');
    $(this).addClass('active');
    var data_tag = $(this).attr('data-tag'),
        tag = $('.rang-tag').find('.tag.tag-'+ data_tag) || null,
        div;
    $(".tag:not(.tag-template) .ketqua-thamkham").filter(function() {
        return !$(this).val() && !$(this).closest('.tag').hasClass('tag-template') && !$(this).closest('.tag').hasClass('tag-'+ data_tag);
    }).closest('.tag').remove();
    $('.rang-tag').find('.tag').removeClass('active');
    if(tag == null || tag.length <= 0){
        $.when($.ajax({
            type: 'POST',
            url: '$urlLoadTagHtml',
            data: {
                id: '$customer->id',
                tag: data_tag
            }
        }).done(function(res){
            div = $(res);
            div.removeClass('tag-template').addClass('active tag-'+ data_tag).attr('data-tag', data_tag).find('.tag-name > span').text(data_tag);
            $('.rang-tag').append(div);
        })).done(function(){
            div.find('.ketqua-thamkham').focus();
        });
    } else {
        $('.tag.tag-'+ data_tag).addClass('active');
        div = $('.tag.tag-'+ data_tag);
        div.find('.ketqua-thamkham').focus();
    }
});
$('body').on('click', '.tag', function(){
    $('.rang, .tag').removeClass('active');
    $(this).addClass('active');
    var data_tag = $(this).attr('data-tag');
    $('.form-rang').find('.rang[data-tag="'+ data_tag +'"]').addClass('active');
    $(this).find('.ketqua-thamkham').focus();
});
$('body').on('click', '.tag-remove', function() {
    var tag = $(this).closest('.tag'),
        tag_name = tag.attr('data-tag'),
        id = tag.find('.key').val() || null,
        v = tag.find('.ketqua-thamkham').val().trim(),
        c;
    if(id == null){
        if(v != ''){
            c = confirm('Bạn thực sự muốn xoá?');
            if(!c) return false;
        }
        console.log($('.rang[data-tag="'+ tag_name +'"]'));
        tag.remove();
        setTimeout(function(){
            $('.rang[data-tag="'+ tag_name +'"]').removeClass('active');
        }, 10);
    } else {
        var c = confirm('Bạn thực sự muốn xoá?');
        if(c){
            $.ajax({
                type: 'POST',
                url: '$urlDeleteTag',
                dataType: 'json',
                data: {
                    id: id
                }
            }).done(function(res){
                if(res.code == 200){
                    toastr.success(res.msg, 'Thông báo');
                    tag.remove();
                    setTimeout(function(){
                        $('.rang[data-tag="'+ tag_name +'"]').removeClass('active');
                    }, 10);
                } else {
                    toastr.error(res.msg, 'Thông báo');
                }
            });
        }
    }
});
/*$('body').on('change paste keyup', '.ketqua-thamkham', function(){
    var v = $(this).val(),
        old_v = $(this).attr('data-old') || '';
    if(v != old_v) $(this).closest('.tag').addClass('has-changed');
    else $(this).closest('.tag').removeClass('has-changed');
});*/
$('body').on('click', '.tag-save', function(){
    var tag = $(this).closest('.tag');
    /*if(tag.hasClass('has-changed')){*/
        var v = tag.find('.ketqua-thamkham').val().trim(),
            tag_name = tag.find('.ipt-tag-name').val() || null;
        if(/*v != '' && */tag != null){
            var form = document.getElementById('form-tag-'+ tag_name),
                form_data = new FormData(form),
                key = $('#form-tag-'+ tag_name).find('.key').val() || '';
            $.ajax({
                type: 'POST',
                url: '$urlCreateOrUpdateTag?id=' + key,
                dataType: 'json',
                data: form_data,
                cache: false,
                contentType: false,
                processData: false
            }).done(function(res){
                if(res.code == 200){
                    toastr.success(res.data.msg, 'Thông báo!');
                    $('#form-tag-'+ tag_name).find('.key').val(res.data.key);
                    $('#form-tag-'+ tag_name).find('.ketqua-thamkham').attr({
                        'data-old': res.data.ketqua_thamkham,
                        'value': res.data.ketqua_thamkham
                    });
                    $('#form-tag-'+ tag_name).closest('.tag').removeClass('has-changed');
                } else {
                    toastr.error(res.data.msg, 'Thông báo!');
                }
            });
        }
    /*} else {
        toastr.success('Dữ liệu không thay đổi', 'Thông báo!');
    }*/
});
$('body').on('blur', '.tag', function() {
    $(this).removeClass('active');
    var tag_name = $(this).attr('data-tag');
    $('.rang[data-tag="'+ tag_name +'"]').removeClass('active');
})
$('body').on('beforesubmit submit', '.form-tag', function(e) {
    e.preventDefault();
    return false;
});
$('body').on('keyup', '.ketqua-thamkham', function(e){
    var ipt = $(this);
    switch(e.keyCode){
        case 13:
            ipt.closest('.tag').find('.tag-save').trigger('click');
            break;
        case 27:
            var v = ipt.attr('data-old');
            ipt.attr('value', v).val(v);
            ipt.blur();
            break;
        default:;
    }
    return true;
});

setHeight();

$(window).on('resize', function(){
    setHeight();
})

function setHeight(){
    var sw = $(window).width(), w = $('.ham').data('width'), h = $('.ham').data('height');
    if (sw >= 768) {
        h = $('.ham').outerWidth() * h / w;
        $('.ham').css({
            'width' : w,
            'height' : h
        });
    }
    var h1 = $('.tooth-model').outerHeight(), 
        h2 = $('.huong-dieu-tri').outerHeight(),
        h3 = h1 - h2 - 75;
    $('.rang-tag').css('height', h3);
}
JS;
$this->registerJs($script, \yii\web\View::POS_END);
$this->registerCss("
#form_huong-dieu-tri #mceu_29, #form_huong-dieu-tri #mceu_29 #mceu_29-body, #form_huong-dieu-tri #mceu_29 #mceu_29-body *{white-space:unset}
.card-header:first-child {
    border-radius: calc(0.25rem - 1px) calc(0.25rem - 1px) 0 0;
}
.card-body {
    flex: 1 1 auto;
    padding: 1.5rem;
}
");
