<?php

use yii\helpers\Url;

$css = <<< CSS
.loading-video-content{box-shadow:0 0 20px #919191}
.tooth-treatment .tooth-treatment-content {transition:all .3s ease-in-out}
.tooth-treatment:hover > .tooth-treatment-content,
.tooth-treatment.active > .tooth-treatment-content{background:#f54f89;color:#fff;border-radius: 50px;}
.tooth-treatment-inner {font-weight:600;font-size:18pt}
CSS;
$this->registerCss($css);
?>
    <div class="tooth-step step-2">
        <input type="hidden" id="ipt-tooth-treatment" value="">
        <div class="container">
            <div class="row align-items-center justify-content-center position-relative">
                <div class="logo"><img src="/images/logo-new.png" alt=""></div>
                <div class="step-title">Video giới thiệu</div>
            </div>
            <div class="row">
                <div class="col-12 loading-video">
                    <div class="loading-video-container">
                        <div class="loading-video-content embed-responsive embed-responsive-16by9"></div>
                    </div>
                </div>
            </div>
            <div class="row tooth-treatment-container">
            </div>
            <div class="step-2-button-group text-center mt-2 mb-4">
                <button type="button" class="btn btn-warning btn-come-back">Quay lại</button>
                <button type="button" class="btn btn-success btn-back-home">Home</button>
                <button type="button" class="btn btn-success next-step" style="display: none;">Tiếp theo</button>
            </div>
        </div>
    </div>
    <div class="col-4 tooth-treatment tmp" data-treatment=""
         data-iframe="">
        <div class="tooth-treatment-content">
            <div class="tooth-treatment-inner"></div>
        </div>
    </div>
<?php
$urlCheckHasChild = Url::toRoute(['check-has-child']);
$urlLoadKyThuatRang = Url::toRoute(['load-ky-thuat-rang']);
$script = <<< JS
    if([null, undefined, ''].includes(data.status)){
        loadStep(1);
    } else {
        data.step = 2;
        dataStep.push(2);
        localStorage.setItem('data', JSON.stringify(data));
        $('body').myLoading({
            opacity: true
        });
        $.post('$urlCheckHasChild', {status: data.status}, function(res){
            if(res === 'true'){
                $('.next-step').show();
            }
        }).fail(function(f){});
        $.ajax({
            type: 'POST',
            url: '$urlLoadKyThuatRang',
            dataType: 'json',
            data: {
                status: data.status
            },
        }).done(function(res){
            console.log(res);
            if(res.length > 0){
                var i = 0;
                $.each(res, function(k, v){
                    var tmp = $('.tooth-treatment.tmp').clone();
                    tmp.removeClass('tmp').attr({
                        'data-treatment': v.id,
                        'data-iframe': v.link_video
                    }).find('.tooth-treatment-inner').html(v.name);
                    console.log(data.treatment == v.id || ([null, undefined, ''].includes(data.treatment) && i == 0));
                    if(data.treatment == v.id || ([null, undefined, ''].includes(data.treatment) && i == 0)){
                        tmp.addClass('active');
                        $('#ipt-tooth-treatment').val(v.id);
                        if(![null, undefined, ''].includes(v.link_video)) $('.loading-video-content').append('<iframe class="embed-responsive-item" src="'+ v.link_video +'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
                    }
                    $('.tooth-treatment-container').append(tmp);
                    i++;
                });
                $('body').myUnloading();
                return false;
            }
            toastr.error('Không tải được dữ liệu');
            $('body').myUnloading();
            loadStep(1);
            return false;
        }).fail(function(f){
            toastr.error('Có lỗi xảy ra');
            $('body').myUnloading();
            loadStep(1);
        });
        $('.tooth-step').on('click tap', '.tooth-treatment', function(){
            var data_treatment = $(this).attr('data-treatment') || null;
            $('#ipt-tooth-treatment').val(data_treatment).trigger('change');
            $('.tooth-treatment').removeClass('active');
            $(this).addClass('active');
        }).on('click tap', '.next-step', function(){
            var data_treatment = $('#ipt-tooth-treatment').val() || null;
            if(data_treatment !== null) {
                if(data.treatment !== data_treatment){
                    data.age = null;
                    data.choose = null;
                    data.service = null;
                }
                data.prevStep = data.step;
                data.treatment = data_treatment;
                localStorage.setItem('data', JSON.stringify(data));
                loadStep(3);
                return false;
            }
            toastr.error('Vui lòng chọn tình trạng');
            return false;
        }).on('change', '#ipt-tooth-treatment', function(){
            $('.loading-video-container').myLoading({
                opacity: true,
                msg: 'Tải video...'
            });
            var data_treatment = $(this).val() || null,
                data_iframe = $('.tooth-treatment[data-treatment="'+ data_treatment +'"]').attr('data-iframe') || null;
            if(data_iframe !== null){
                $.when($('.loading-video-content').html('<iframe class="embed-responsive-item" src="'+data_iframe+'" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>')).done(function(){
                    $('.loading-video-container').myUnloading();
                });
            } else {
                $('.loading-video-container').myUnloading();
            }
        });
    }
JS;
$this->registerJs($script);
