<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 08-04-2019
 * Time: 10:02 AM
 */

/* @var $listTag array */

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
                        <div class="col-xl-4 col-lg-5 col-md-5 col-12">
                            <div class="tooth-model">
                                <div class="form-group">
                                    <label class="control-label">Mô hình răng</label>
                                    <div class="form-rang">
                                        <div class="ham ham-tren" data-width="486" data-height="400">
                                            <div class="trai">
                                                <div class="rang rang-8" data-tag="rang-18"
                                                     data-tag-name="Răng 18"></div>
                                                <div class="rang rang-7" data-tag="rang-17"
                                                     data-tag-name="Răng 17"></div>
                                                <div class="rang rang-6" data-tag="rang-16"
                                                     data-tag-name="Răng 16"></div>
                                                <div class="rang rang-5" data-tag="rang-15"
                                                     data-tag-name="Răng 15"></div>
                                                <div class="rang rang-4" data-tag="rang-14"
                                                     data-tag-name="Răng 14"></div>
                                                <div class="rang rang-3" data-tag="rang-13"
                                                     data-tag-name="Răng 13"></div>
                                                <div class="rang rang-2" data-tag="rang-12"
                                                     data-tag-name="Răng 12"></div>
                                                <div class="rang rang-1" data-tag="rang-11"
                                                     data-tag-name="Răng 11"></div>
                                            </div>
                                            <div class="phai">
                                                <div class="rang rang-1" data-tag="rang-21"
                                                     data-tag-name="Răng 21"></div>
                                                <div class="rang rang-2" data-tag="rang-22"
                                                     data-tag-name="Răng 22"></div>
                                                <div class="rang rang-3" data-tag="rang-23"
                                                     data-tag-name="Răng 23"></div>
                                                <div class="rang rang-4" data-tag="rang-24"
                                                     data-tag-name="Răng 24"></div>
                                                <div class="rang rang-5" data-tag="rang-25"
                                                     data-tag-name="Răng 25"></div>
                                                <div class="rang rang-6" data-tag="rang-26"
                                                     data-tag-name="Răng 26"></div>
                                                <div class="rang rang-7" data-tag="rang-27"
                                                     data-tag-name="Răng 27"></div>
                                                <div class="rang rang-8" data-tag="rang-28"
                                                     data-tag-name="Răng 28"></div>
                                            </div>
                                        </div>
                                        <div class="ham ham-duoi" data-width="486" data-height="400">
                                            <div class="trai">
                                                <div class="rang rang-8" data-tag="rang-48"
                                                     data-tag-name="Răng 48"></div>
                                                <div class="rang rang-7" data-tag="rang-47"
                                                     data-tag-name="Răng 47"></div>
                                                <div class="rang rang-6" data-tag="rang-46"
                                                     data-tag-name="Răng 46"></div>
                                                <div class="rang rang-5" data-tag="rang-45"
                                                     data-tag-name="Răng 45"></div>
                                                <div class="rang rang-4" data-tag="rang-44"
                                                     data-tag-name="Răng 44"></div>
                                                <div class="rang rang-3" data-tag="rang-43"
                                                     data-tag-name="Răng 43"></div>
                                                <div class="rang rang-2" data-tag="rang-42"
                                                     data-tag-name="Răng 42"></div>
                                                <div class="rang rang-1" data-tag="rang-41"
                                                     data-tag-name="Răng 41"></div>
                                            </div>
                                            <div class="phai">
                                                <div class="rang rang-1" data-tag="rang-31"
                                                     data-tag-name="Răng 31"></div>
                                                <div class="rang rang-2" data-tag="rang-32"
                                                     data-tag-name="Răng 32"></div>
                                                <div class="rang rang-3" data-tag="rang-33"
                                                     data-tag-name="Răng 33"></div>
                                                <div class="rang rang-4" data-tag="rang-34"
                                                     data-tag-name="Răng 34"></div>
                                                <div class="rang rang-5" data-tag="rang-35"
                                                     data-tag-name="Răng 35"></div>
                                                <div class="rang rang-6" data-tag="rang-36"
                                                     data-tag-name="Răng 36"></div>
                                                <div class="rang rang-7" data-tag="rang-37"
                                                     data-tag-name="Răng 37"></div>
                                                <div class="rang rang-8" data-tag="rang-38"
                                                     data-tag-name="Răng 38"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5 col-lg-6 col-md-7 col-12">
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
                                        'enableAjaxValidation' => true,
                                        'validationUrl' => Url::toRoute(['validate-huong-dieu-tri', 'id' => $customer->id]),
                                        'action' => Url::toRoute(['submit-huong-dieu-tri', 'id' => $customer->id]),
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
window['tagRang'] = {};
$('body').on('click', '.rang', function(){
    $('.rang').removeClass('active');
    $(this).addClass('active');
    var data_tag = $(this).attr('data-tag'),
        data_tag_name = $(this).attr('data-tag-name'),
        tag = $('.rang-tag').find('.tag.tag-'+ data_tag) || null,
        div;
    $(".tag:not(.tag-template) .ketqua-thamkham").filter(function() {
        return !$(this).val() && !(typeof $(this).closest('.tag').find('.dropdown-tinh-trang-rang select').val() === 'object' && $(this).closest('.tag').find('.dropdown-tinh-trang-rang select').val().length > 0) && !$(this).closest('.tag').hasClass('tag-template') && !$(this).closest('.tag').hasClass('tag-'+ data_tag);
    }).closest('.tag').remove();
    $('.rang-tag').find('.tag').removeClass('active');
    if(tag == null || tag.length <= 0){
        /*$.when(*/$.ajax({
            type: 'POST',
            url: '$urlLoadTagHtml',
            data: {
                id: '$customer->id',
                tag: data_tag
            }
        }).done(function(res){
            div = $(res);
            div.removeClass('tag-template').addClass('active tag-'+ data_tag).attr('data-tag', data_tag).find('.tag-name > span').text(data_tag_name);
            $('.rang-tag').append(div);
            $('.rang-tag').find('select.dropdown').dropdown();
            
            $('.rang-tag').stop().animate({
                scrollTop: $('.rang-tag').find('.tag.tag-'+ data_tag).position().top
            }, '400');
        })/*).done(function(){
            div.find('.ketqua-thamkham').focus();
        })*/;
    } else {
        $('.tag.tag-'+ data_tag).addClass('active');
        div = $('.tag.tag-'+ data_tag);
        /*div.find('.ketqua-thamkham').focus();*/
    }
}).on('click', '.tag', function(){
    $('.rang, .tag').removeClass('active');
    $(this).addClass('active');
    var data_tag = $(this).attr('data-tag');
    $('.form-rang').find('.rang[data-tag="'+ data_tag +'"]').addClass('active');
    /*$(this).find('.ketqua-thamkham').focus();*/
    $('.rang-tag').stop().animate({
        scrollTop: $('.rang-tag').find('.tag.tag-'+ data_tag).position().top
    }, '400');
}).on('click', '.tag-remove', function() {
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
}).on('change paste keyup', '.ketqua-thamkham', function(){
    var v = $(this).val(),
        old_v = $(this).attr('data-old') || '';
    if(v != old_v) $(this).closest('.tag').addClass('has-changed');
    else $(this).closest('.tag').removeClass('has-changed');
}).on('click', '.tag-save', function(){
    var tag = $(this).closest('.tag');
    // if(tag.hasClass('has-changed')){
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
    // }
}).on('blur', '.tag', function() {
    $(this).removeClass('active');
    var tag_name = $(this).attr('data-tag');
    $('.rang[data-tag="'+ tag_name +'"]').removeClass('active');
}).on('beforesubmit submit', '.form-tag', function(e) {
    e.preventDefault();
    return false;
}).on('beforeSubmit', '#form_huong-dieu-tri', function(e){
    e.preventDefault();
    var form = $(this),
        formData = new FormData(form[0]),
        url = form.attr('action');
    form.myLoading({
        opacity: true
    });
    $.ajax({
        type: 'POST',
        url: url,
        dataType: 'json',
        data: formData,
        cache: false,
        processData: false,
        contentType: false
    }).done(function(res){
        if(res.code == 200){
            toastr.success(res.msg);
            $('.rang-tag .form-tag .tag-save').each(function(){
                $(this).trigger('click');
            });
        } else {
            toastr.warning(res.msg);
        }
        form.myUnloading();
    }).fail(function(f){
        toastr.error('Có lỗi xảy ra');
        form.myUnloading();
    });
    return false;
}).on('keyup', '.ketqua-thamkham', function(e){
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
$('.dropdown-tinh-trang-rang').on('change', function(){
    var tag = $(this).closest('.tag'),
        dataTag = tag.attr('data-tag'),
        rang = $('.form-rang .rang[data-tag="'+ dataTag +'"]'),
        options = $(this).find('option:selected'),
        listJs = [];
    options.each(function(){
        var dataJs = $(this).attr('data-js') || null;
        if(dataJs !== null){
            listJs.push(dataJs);
        }
    });
    if(!Object.keys(window['tagRang']).includes(dataTag) || JSON.stringify(window['tagRang'][dataTag]) !== JSON.stringify(listJs)){
        add = difference(listJs, window['tagRang'][dataTag]);
        remove = difference(window['tagRang'][dataTag], listJs);
        if(remove.length > 0){
            remove.forEach(function(v){
                if(typeof v === 'string' && typeof eval('window["remove_'+ v +'"]') === 'function'){
                    eval('window["remove_'+ v +'"](".rang[data-tag='+ dataTag +']")');
                }
            });
        }
        if(add.length > 0){
            add.forEach(function(v){
                if(typeof v === 'string' && typeof eval('window["add_'+ v +'"]') === 'function'){
                    eval('window["add_'+ v +'"](".rang[data-tag='+ dataTag +']")');
                }
            });
        }
    }
    window['tagRang'][dataTag] = listJs;
}).trigger('change');
function getElementRang(el){
    console.log('getElementRang', el);
    if(typeof el === 'string') el = $(el) || null;
    if(el.length <= 0) el = null;
    return el;
}
function add_mat_gay_rang(el){
    el = getElementRang(el);
    if(el === null) return false;
    console.log('add mat gay rang', el);
}
function remove_mat_gay_rang(el){
    el = getElementRang(el);
    if(el === null) return false;
    console.log('remove mat gay rang', el);
}
function interSelection(a, b){
    if(typeof a !== 'object') a = [];
    if(typeof b !== 'object') b = [];
    return a.filter(x => b.includes(x));
}
function difference(a, b){
    if(typeof a !== 'object') a = [];
    if(typeof b !== 'object') b = [];
    return a.filter(x => !b.includes(x));
}

$(document).ready(function() {
    setTimeout(function() {
        setHeight();
    }, 1000);
});

$(window).on('resize', function() {
    console.log('resize');
    setTimeout(function() {
        setHeight();
    }, 1000);
});

function setHeight(){     
    var screenWidth = $(window).width(), 
        dataWidth = $('.ham').data('width'), 
        dataHeight = $('.ham').data('height');
     var newHeight, newWidth;
    
    if (screenWidth >= 768) {
        newHeight = $('.ham').outerWidth() * dataHeight / dataWidth;
        newWidth = $('.ham').outerWidth();
        
        $('.ham').css({
            width : newWidth,
            height : newHeight
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
