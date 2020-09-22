<?php

use yii\helpers\Url;

?>
<?php
$css = <<< CSS
@font-face {
    font-family: 'UTM Duepuntozero';
    font-style: normal;
    font-weight: 400;
    font-display: swap;
    src: url('/fonts/utm-duepuntozero/UTM-Duepuntozero.ttf') format('truetype');
}
@font-face {
    font-family: 'UTM Duepuntozero';
    font-style: normal;
    font-weight: 700;
    font-display: swap;
    src: url('/fonts/utm-duepuntozero/UTM-DuepuntozeroBold.ttf') format('truetype');
}
html body{height: 100%;overflow: hidden;}
body{font-family: "UTM Duepuntozero"}
div[class*=col-]{padding-left:12.5px;padding-right:12.5px;}
.row{margin-left:-12.5px;margin-right:-12.5px;}
.btn-pink{background-color:#f54f89!important;border-color:#f54f89!important;-webkit-border-radius:50px;-moz-border-radius:50px;border-radius:50px}
button.btn.btn-success.btn-back-home{min-width:64px;height:84px;border-radius:100%;background:#93268f!important;font-weight:600!important;font-size:18pt;padding:10px;border:6px double #fff!important}
.logo{position:absolute;left:15px;top:50%;-webkit-transform: translateY(-50%);-moz-transform: translateY(-50%);-ms-transform: translateY(-50%);-o-transform: translateY(-50%);transform: translateY(-50%);}
.tooth-step:not(.step-1) .btn-come-back,.tooth-step:not(.step-1) .next-step{position:relative;background:url(/images/btn-2.png) repeat-x left center;border:none;height:64px;min-width:100px;font-size:18pt;font-weight:600!important}
.tooth-step:not(.step-1) .btn-come-back{margin-right:15px}
.tooth-step:not(.step-1) .next-step{margin-left:15px}
.tooth-step:not(.step-1) .btn-come-back:before{content:'';position:absolute;top:0;left:-48px;width:50px;height:100%;background:url(/images/btn-1.png) no-repeat left center}
.tooth-step:not(.step-1) .btn-come-back:after{content:'';position:absolute;top:0;right:-43px;width:50px;height:100%;background:url(/images/btn-3.png) no-repeat left center}
.tooth-step:not(.step-1) .next-step:before{content:'';position:absolute;top:0;left:-43px;width:50px;height:100%;background:url(/images/btn-3.png) no-repeat left center;transform:rotateY(180deg)}
.tooth-step:not(.step-1) .next-step:after{content:'';position:absolute;top:0;right:-48px;width:50px;height:100%;background:url(/images/btn-1.png) no-repeat left center;transform:rotateY(180deg)}
.tooth-step:not(.step-1) .btn-come-back:active,
.tooth-step:not(.step-1) .btn-come-back:focus,
.tooth-step:not(.step-1) .btn-come-back:hover,
 .tooth-step:not(.step-1) .next-step:active,
.tooth-step:not(.step-1) .next-step:focus,
.tooth-step:not(.step-1) .next-step:hover{background: transparent url(/images/btn-2.png) repeat-x left center !important;}
#topbar{display:flex;padding:16px;background:silver;flex-wrap:nowrap;justify-content:center;align-items:center}
#topbar .input{width:250px;height:23px;background:#fff;border-radius:50px}
#tooth-status{height:100%;background:url(/images/backgrounds/layout-ipad-1.png) no-repeat top center;background-size:cover}
.step-title{min-width:235px;font-weight:600;font-size:20pt;margin-bottom:25px;padding:55px 15px 20px;background:linear-gradient(180deg,#feae7c,#fc50b1);border-radius:0 0 25px 25px;color:#fff;text-align:center;line-height:1}
.tooth-service-name,.tooth-service-price{margin-bottom:10px}
.tooth-treatment,.tooth-age,.loading-video,.tooth-service{margin-bottom:30px}
.tooth-treatment .tooth-treatment-content,.tooth-age-name,.tooth-choose-content,.tooth-age-image,.tooth-service-content{display:flex;align-items:center;justify-content:center;text-align:center;border-radius:5px;cursor:pointer}
.tooth-treatment .tooth-treatment-content{height:100%;min-height:50px}
.loading-video-content,.tooth-treatment > .tooth-treatment-content:hover,.tooth-treatment.active > .tooth-treatment-content{background:#eee;box-shadow:1px 1px 2px #333}
.tooth-treatment-inner,.tooth-age-inner{padding:10px 0}
.tooth-age-inner{background:#fff;border:1px solid #84077f;border-radius:10px;box-shadow:6px 6px 10px #919191;-webkit-transition: all .3s ease-in-out;-moz-transition: all .3s ease-in-out;-ms-transition: all .3s ease-in-out;-o-transition: all .3s ease-in-out;transition: all .3s ease-in-out;}
.tooth-age-inner:hover{box-shadow:6px 6px 10px #616161}
.tooth-age:hover .tooth-age-name,.tooth-age.active .tooth-age-name{font-weight:700}
.tooth-age-name,.tooth-choose-content{font-weight:600!important;font-size:16pt;padding:10px 18px;margin:0 10px 15px;border-radius:50px;}
.tooth-age-image,.tooth-service-content{padding:10px}
.tooth-age:hover .tooth-age-name,.tooth-age.active .tooth-age-name,.tooth-age:hover .tooth-age-image,.tooth-age.active .tooth-age-image,.tooth-choose:hover .tooth-choose-content,.tooth-choose.active .tooth-choose-content,.tooth-service:hover .tooth-service-content,.tooth-service.active .tooth-service-content{/*box-shadow:1px 1px 2px #333*/}
.tooth-choose-container{max-width:400px;margin:auto!important;justify-content:center}
.tooth-service-image > div{padding:5px;border:solid 1px #ccc;border-radius:3px}
.tooth-service-name,.tooth-service-price > span{font-weight:700}
.tooth-service-price > span{color:red}
.tooth-service-content{height:100%}
.tmp{display:none}
#back-home{position:fixed;bottom:10px;right:10px;width:50px;height:50px;display:flex;align-items:center;justify-content:center;border-radius:50%;background:#f00;cursor:pointer;box-shadow:0px 0px 4px #333;opacity:.75}
#back-home a{color:#fff}
#back-home:active,div#back-home:hover{box-shadow:1px 1px 4px #000;opacity:1}
#back-home i{font-size:28px}
.logo img {max-width: 82px}
@media (min-width: 768px) {
    .container{max-width:698px}
}
@media (max-width: 1024px) {
    .status-name{font-size:12pt!important}
}
@media (max-width: 767px) {
    .step-title {padding-top:45px}
}
@media (max-width: 480px) {
    
}
CSS;

$this->registerCss($css);
?>
    <div id="topbar">
        <div class="input"></div>
    </div>
    <div id="tooth-status"></div>
    <div id="back-home"><a href="<?= Url::toRoute(['/site/index']) ?>"><i class="fa fa-home"></i></a></div>
<?php
$urlLoad = Url::toRoute(['load-step', 'step' => '']);
$urlImage = Yii::getAlias('@frontendUrl') . '/images/ico/favicon.png';
$script = <<< JS
function loadStep(step = 1, data = null) {
    $('body').myLoading({
        opacity: true
    });
    $.when($('#tooth-status').load('$urlLoad' + step, data)).done(function(){
        $('body').myUnloading();
    });
}
var data = localStorage.getItem('data') || null,
    dataStep = [];
if(data === null){
    data = {
        prevStep: 1,
        step: 1,
        status: null,
        treatment: null,
        age: null,
        choose: null,
        service: null,
    };
    localStorage.setItem('data', JSON.stringify(data));
} else {
    data = JSON.parse(data);
}
loadStep(data.step);
$('body').on('click tap', '.btn-come-back', function(){
    var prevStep = null;
    if(dataStep.length > 0){
        prevStep = dataStep.slice(dataStep.length - 2, dataStep.length - 1);
        dataStep.splice(dataStep.length - 2, dataStep.length);
    }
    if([null, undefined, '[]'].includes(JSON.stringify(prevStep))){
        prevStep = data.prevStep || 1;
        if(prevStep >= data.step) prevStep = data.step - 1;
    }
    loadStep(prevStep);
}).on('click tap', '.btn-back-home', function(){
    data = {
        step: 1,
        status: null,
        treatment: null,
        age: null,
        choose: null,
        service: null,
    };
    dataStep = [];
    localStorage.removeItem('data');
    loadStep(1);
});
JS;
$this->registerJs($script, \yii\web\View::POS_END);
