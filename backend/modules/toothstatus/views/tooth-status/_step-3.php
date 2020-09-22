<?php

use yii\helpers\Url;

?>
    <div class="tooth-step step-3">
        <input type="hidden" id="ipt-tooth-age" value="">
        <div class="container">
            <div class="row align-items-center justify-content-center position-relative">
                <div class="logo"><img src="/images/logo-new.png" alt=""></div>
                <div class="step-title">Chọn độ tuổi</div>
            </div>
            <div class="row tooth-age-container">
            </div>
            <div class="step-3-button-group text-center mt-2 mb-4">
                <button type="button" class="btn btn-warning btn-come-back">Quay lại</button>
                <button type="button" class="btn btn-success btn-back-home">Home</button>
                <button type="button" class="btn btn-success next-step d-none">Tiếp theo</button>
            </div>
        </div>
    </div>
    <div class="col-4 tooth-age tmp" data-age="">
        <div class="tooth-age-content">
            <div class="tooth-age-inner">
                <div class="tooth-age-image">
                    <img class="img-fluid" src="" alt=""/>
                </div>
                <div class="tooth-age-name btn btn-pink"></div>
            </div>
        </div>
    </div>
<?php
$urlLoadDoTuoi = Url::toRoute(['load-do-tuoi']);
$script = <<< JS
    if([null, undefined, ''].includes(data.status)){
        loadStep(2);
    } else {
        data.step = 3;
        dataStep.push(3);
        localStorage.setItem('data', JSON.stringify(data));
        $('body').myLoading({
            opacity: true
        });
        $.ajax({
            type: 'POST',
            url: '$urlLoadDoTuoi',
            dataType: 'json',
            data: {}
        }).done(function(res){
            if(res.length > 0){
                $.each(res, function(k, v){
                    var tmp = $('.tooth-age.tmp').clone();
                    tmp.removeClass('tmp').attr('data-age', v.id).find('.tooth-age-name').html(v.name);
                    if(![null, undefined, ''].includes(v.image)){
                        tmp.find('.tooth-age-image').children('img').attr({
                            src: v.image,
                            alt: v.name
                        });
                    }
                    if(data.age == v.id){
                        tmp.addClass('active');
                        $('#ipt-tooth-age').val(v.id);
                    }
                    $('.tooth-age-container').append(tmp);
                });
                $('body').myUnloading();
                return false;
            }
            toastr.error('Không tải được dữ liệu');
            $('body').myUnloading();
            loadStep(2);
            return false;
        }).fail(function(f){
            toastr.error('Có lỗi xảy ra');
            $('body').myUnloading();
            loadStep(2);
        });
        $('.tooth-step').on('click tap', '.tooth-age', function(){
            var data_age = $(this).attr('data-age') || null;
            $('#ipt-tooth-age').val(data_age);
            $('.tooth-age').removeClass('active');
            $(this).addClass('active');
            $('.next-step').trigger('click');
        }).on('click tap', '.next-step', function(){
            var data_age = $('#ipt-tooth-age').val() || null;
            if(data_age !== null){
                if(data.age !== data_age){
                    data.choose = null;
                    data.service = null;
                }
                data.prevStep = data.step;
                data.age = data_age;
                localStorage.setItem('data', JSON.stringify(data));
                loadStep(4);
                return false;
            }
            toastr.error('Vui lòng chọn độ tuổi');
            return false;
        });
    }
JS;
$this->registerJs($script);
