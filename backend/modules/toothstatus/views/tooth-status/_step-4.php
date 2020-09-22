<?php

use yii\helpers\Url;
use yii\web\JqueryAsset;

$css = <<< CSS
.tooth-service-content{background:#fff;border:1px solid #84077f;border-radius:10px;box-shadow:6px 6px 10px #919191;-webkit-transition:all .3s ease-in-out;-moz-transition:all .3s ease-in-out;-ms-transition:all .3s ease-in-out;-o-transition:all .3s ease-in-out;transition:all .3s ease-in-out}
.tooth-service-name{font-size:20pt;margin-top:5px;margin-bottom:20px;line-height:1}
.tooth-service-price{margin-top:20px;margin-bottom:20px;font-size:20pt}
.tooth-service-price > span{color:#fff}
.col-4.tooth-service-image{padding-left:2px;padding-right:2px}
.tooth-choose .tooth-choose-content{position:relative;font-weight:600;font-size:20pt;margin:0 0 25px;padding:55px 15px 20px;background:linear-gradient(180deg,#feae7c,#fc50b1);-webkit-border-radius:0 0 25px 25px;-moz-border-radius:0 0 25px 25px;border-radius:0 0 25px 25px;color:#fff;text-align:center;line-height:1}
.tooth-choose .tooth-choose-content:before{content:"";position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.3);-webkit-border-radius:0 0 25px 25px;-moz-border-radius:0 0 25px 25px;border-radius:0 0 25px 25px}
.tooth-choose.active .tooth-choose-content:before{display:none}
.tooth-service-rating{display:flex;justify-content:center;align-items:center;font-size:16pt;color:#f54f89}
.rating{border:none}
.rating > input{display:none}
.rating > label:before{margin:5px;font-size:1.25em;font-family:FontAwesome;display:inline-block;content:"\\f005"}
.rating > .half:before{content:"\\f089";position:absolute}
.rating > label{color:#ddd;float:right;margin-bottom:0}
.rating > label.checked, .rating > label.checked ~ label{color:#FFD700}
.rating > input:checked + label:hover,.rating > input:checked ~ label:hover,.rating > label:hover ~ input:checked ~ label,.rating > input:checked ~ label:hover ~ label{color:#FFED85}
.logo{position:absolute;left:17px;top:62px;}
.beaf-next, .beaf-prev {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 3rem;
    z-index: 9;
    color: #fff;
    height: 60px;
    width: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(0, 0, 0, 0.5);
    border-radius: 3px;
    cursor: pointer;
}
.beaf-next:hover, .beaf-prev:hover {
    background: rgba(0, 0, 0, 0.8);
}
.beaf-next {
    right: 10px;
}
.beaf-prev {
    left: 10px;
}
.tooth-services-container .no-item {
    width: 100%;
    font-size: 1.5rem;
}
#modalCenter .modal-dialog {
    height: calc(100% - 1.75rem * 2);
}
#modalCenter .modal-content,
#modalCenter .modal-body {
    height: 100%;
}
#modalCenter .modal-body {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}
#modalCenter button.close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    z-index: 2;
}
.js-img-compare-title {
    width: 100%;
    padding-right: 30px;
    height: 68px;
}
#modalCenter .modal-inner {
    width: 100%;
    height: calc(100% - 60px - 1rem);
    overflow: hidden;
    min-height: 400px;
    border: solid 1px #ccc;
    position: relative;
    transition: all .3s ease-in-out;
    display: flex;
    align-items: center;
}
#modalCenter .modal-inner.loading:before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #efefef;
    transition: all 1s ease-in-out;
    z-index: 9;
}
#modalCenter .modal-inner.loading:after {
    content: "";
    position: absolute;
    top: 0;
    bottom: 0;
    width: 200%;
    left: -100%;
    background-image: linear-gradient(to right, #e0e0e0 0%, transparent 25%,  #e0e0e0 50%, transparent 75%, #e0e0e0 100%);
    animation: js-compare-bg 1s infinite;
    transition: all 1s ease-in-out;
    z-index: 9;
}
#modalCenter .modal-content {
    background: #efefef;
}
#modalCenter .modal-icon {
    width: 100px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
}
#modalCenter .modal-title {
    width: calc(100% - 100px);
}
@keyframes js-compare-bg {
    0% {left: -100%;}
    20% {left: -80%;}
    40% {left: -60%;}
    60% {left: -40%;}
    80% {left: -20%;}
    100% {left: 0%;}
}
CSS;

$this->registerCss($css);
?>
    <div class="tooth-step step-4">
        <input type="hidden" id="ipt-tooth-choose" value="">
        <input type="hidden" id="ipt-tooth-service" value="">
        <div class="container" style="position:relative">
            <div class="logo"><img src="/images/logo-new.png" alt=""></div>
            <div class="row tooth-choose-container">
            </div>
            <div class="row tooth-services-container">
            </div>
        </div>
        <div class="step-4-button-group text-center mt-2 mb-4">
            <button type="button" class="btn btn-warning btn-come-back">Quay lại</button>
            <button type="button" class="btn btn-success btn-back-home">Home</button>
        </div>
    </div>
    <div class="col-6 tooth-choose tmp" data-choose="">
        <div class="tooth-choose-content">
            <div class="tooth-choose-inner"></div>
        </div>
    </div>
    <div class="col-6 tooth-service tmp" data-service="">
        <div class="tooth-service-content">
            <div class="tooth-service-inner">
                <div class="tooth-service-name"></div>
                <div class="tooth-service-images form-row">
                </div>
                <div class="tooth-service-price btn btn-pink">Từ: <span></span></div>
                <div class="tooth-service-rating">Đánh giá:
                    <fieldset class="rating">
                        <label class="full star star5"></label>
                        <label class="half star star4half"></label>
                        <label class="full star star4"></label>
                        <label class="half star star3half"></label>
                        <label class="full star star3"></label>
                        <label class="half star star2half"></label>
                        <label class="full star star2"></label>
                        <label class="half star star1half"></label>
                        <label class="full star star1"></label>
                        <label class="half star starhalf"></label>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
    <div class="col-4 tooth-service-image tmp">
        <div>
            <img class="img-fluid" src="" alt="">
        </div>
    </div>
    <div class="modal fade" id="modalCenter" tabindex="-1" role="dialog" aria-labelledby="modalCenterTitle"
         aria-hidden="true">
        <div class="beaf-next"><i class="fa fa-angle-right"></i></div>
        <div class="beaf-prev"><i class="fa fa-angle-left"></i></div>
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="min-height: 300px; overflow-x: hidden">
                <div class="modal-body border-0" style="min-height: 300px;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="row mb-1 js-img-compare-title">
                        <div class="modal-title d-flex align-items-center">
                            <div class="ba-title" style="font-size:24px;font-weight:600;">DIGITAL SMILE DESIGN</div>
                        </div>
                        <div class="modal-icon">
                            <div class="ba-copyright text-right">
                                <img src="/images/logo-new.png" alt="logo" height="45">
                            </div>
                        </div>
                    </div>
                    <div class="modal-inner loading">
                    </div>
                </div>
                <div class="modal-footer p-0 border-0"></div>
            </div>
        </div>
    </div>
<?php
$urlLoadLuaChon = Url::toRoute(['load-lua-chon']);
$urlLoadDichVu = Url::toRoute(['load-dich-vu']);
$urlShowBeaf = Url::toRoute(['show-beaf']);
$this->registerCssFile('https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js');
$script = <<< JS
    var current_service = null,
        current_image = null,
        first_time = true;
    if([null, undefined, ''].includes(data.age)){
        loadStep(3);
    } else {
        data.step = 4;
        dataStep.push(4);
        localStorage.setItem('data', JSON.stringify(data));
        $('body').myLoading({
            opacity: true
        });
        $.ajax({
            type: 'POST',
            url: '$urlLoadLuaChon',
            dataType: 'json',
            data: {
                treatment: data.treatment,
                age: data.age
            }
        }).done(function(res){
            if(res.length > 0){
                $.each(res, function(k, v){
                    var tmp = $('.tooth-choose.tmp').clone();
                    tmp.removeClass('tmp').attr('data-choose', v.id).find('.tooth-choose-inner').html(v.name);
                    $('.tooth-choose-container').append(tmp);
                });
                if([null, undefined].includes(data.service)){
                    $('.tooth-choose').eq(0).trigger('click');
                }
            }
            $('body').myUnloading();
        }).fail(function(f){
            toastr.error('Có lỗi xảy ra');
            $('body').myUnloading();
            loadStep(3);
        });
        $('#modalCenter').on('hidden.bs.modal', function(){
            $('#modalCenter').find('.js-img-compare').removeAttr('style').html('');
        }).on('click', '.beaf-prev', function(){
            if($(this).closest('#modalCenter').find('.modal-inner').hasClass('loaded') && ![null, undefined, ''].includes(current_image)){
                $('#modalCenter').find('.modal-inner').removeClass('loaded').addClass('loading');
                var image = current_image - 1;
                if(image < 0) image = 2;
                var el = $('.tooth-service-image.active').closest('.tooth-service-images').find('.tooth-service-image[data-image="'+ image +'"]') || null;
                if(![null, undefined].includes(el) && el.length > 0){
                    el.trigger('click');
                }
            } else return false;
        }).on('click', '.beaf-next', function(){
            if($(this).closest('#modalCenter').find('.modal-inner').hasClass('loaded') && ![null, undefined, ''].includes(current_image)){
                $('#modalCenter').find('.modal-inner').removeClass('loaded').addClass('loading');
                var image = current_image + 1;
                if(image > 2) image = 0;
                var el = $('.tooth-service-image.active').closest('.tooth-service-images').find('.tooth-service-image[data-image="'+ image +'"]') || null;
                if(![null, undefined].includes(el) && el.length > 0){
                    el.trigger('click');
                }
            } else return false;
        });
        $('.tooth-step').on('click tap', '.tooth-choose', function(){
            var choose = $(this).attr('data-choose') || null;
            if(choose !== null){
                $('.tooth-choose').removeClass('active');
                $(this).addClass('active');
                $('#ipt-tooth-choose').val(choose);
                loadServices(choose);
            }
        }).on('click tap', '.tooth-service-image', function(){
            $('.tooth-service').removeClass('active');
            $(this).addClass('active');
            loadFancybox($(this));
        });
        function loadFancybox(el){
            var image = el.attr('data-image') || null,
                service = el.closest('.tooth-service').attr('data-service') || null;
            loadModalImageBeaf('#modalCenter').then(function(){
                $.ajax({
                    url: '$urlShowBeaf',
                    type: 'POST',
                    dataType: 'html',
                    data: {
                        service: service,
                        image: image
                    }
                }).done(function(data){
                    setTimeout(function(){
                        $('#modalCenter').find('.modal-inner').html(data);
                        current_service = service;
                        current_image = parseInt(image);
                    }, 3000);
                }).fail(function(error){
                    console.log(error);
                    $('#modalCenter').modal('hide');
                    toastr.error('Có lỗi khi tải dữ liệu');
                });
            });
        }
        function loadModalImageBeaf(modal){
            return new Promise(function(resolve, reject){
                $(modal).find('.modal-inner').removeAttr('style').html('').addClass('loading');
                if(!$(modal).hasClass('show')) $(modal).modal('show');
                resolve();
            });
        }
        function loadServices(choose){
            $('body').myLoading({
                opacity: true,
                msg: 'Tải dịch vụ...'
            });
            $('.tooth-services-container').empty();
            $.ajax({
                type: 'POST',
                url: '$urlLoadDichVu',
                // dataType: 'json',
                data: {
                    status: data.status,
                    age: data.age,
                    choose: choose
                }
            }).done(function(res){
                if(res.length > 0){
                    if(typeof res[0] === 'object'){
                        $.each(res, function(k, v){
                            var service_tmp = $('.tooth-service.tmp').clone();
                            service_tmp.removeClass('tmp').attr('data-service', v.id).find('.tooth-service-name').html(v.name);
                            service_tmp.find('.tooth-service-price').children('span').html(v.price);
                            var list_images = v.list_images;
                            if(typeof list_images === 'object'){
                                $.each(list_images, function(k_image, v_image){
                                    var service_image_tmp = $('.tooth-service-image.tmp').clone();
                                    service_image_tmp.removeClass('tmp').attr('data-image', k_image).find('img').attr({
                                        src: v_image.before.thumb,
                                        alt: v.name
                                    });
                                    service_tmp.find('.tooth-service-images').append(service_image_tmp);
                                });
                            }
                            if(![null, undefined].includes(v.star)){
                                var star = v.star + '';
                                star = star.replace('.5', 'half');
                                if(star === '0half') star = 'half';
                                service_tmp.find('.star'+ star).addClass('checked');
                            }
                            $('.tooth-services-container').append(service_tmp);
                        });
                    } else {
                        $('.tooth-services-container').html(res[0]);
                    }
                }
                $('body').myUnloading();
            }).fail(function(f){
                toastr.error('Có lỗi xảy ra khi tải dịch vụ');
                $('body').myUnloading();
            });
        }
    }
JS;
$this->registerJs($script);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js');
