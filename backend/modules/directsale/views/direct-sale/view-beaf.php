<div class="modal-body border-0" style="min-height: 300px;">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>

    <div class="row mb-1 js-img-compare-title">
        <div class="col-md-6 d-flex align-items-center">
            <div class="ba-title" style="font-size:24px;font-weight:600;">DIGITAL SMILE DESIGN</div>
        </div>
        <div class="col-md-6">
            <div class="ba-copyright text-right">
                <img src="/images/logo.png" alt="logo" height="45">
            </div>
        </div>
    </div>
    <div class="js-img-compare">
        <div style="display: none;">
            <span class="images-compare-label">Before</span>
            <img src="<?= $model['before'] ?>" class="img-before" alt="Before">
        </div>
        <div>
            <span class="images-compare-label">After</span>
            <img src="<?= $model['after'] ?>" class="img-after" alt="After">
        </div>
    </div>
</div>
<div class="modal-footer p-0 border-0"></div>

<?php
$script = <<< JS
$('#modalCenter').on('show.bs.modal', function(){
    setTimeout(function(){
        $(window).resize();
    },180);
});
function myCompare1(){
    $('.js-img-compare').myLoading();
    var imagesCompareElement = $('.js-img-compare').imagesCompare();
    var imagesCompare = imagesCompareElement.data('imagesCompare');
    var events = imagesCompare.events();

    imagesCompare.on(events.changed, function (event) {});

    $('.js-front-btn').on('click', function (event) {
        event.preventDefault();
        imagesCompare.setValue(1, true);
    });

    $('.js-back-btn').on('click', function (event) {
        event.preventDefault();
        imagesCompare.setValue(0, true);
    });

    $('.js-toggle-btn').on('click', function (event) {
        event.preventDefault();
        if (imagesCompare.getValue() >= 0 && imagesCompare.getValue() < 1) {
            imagesCompare.setValue(1, true);
        } else {
            imagesCompare.setValue(0, true);
        }
    });
}
function setUnloading(){
    $('.js-img-compare').myUnloading();
}
async function myCompare(){
    let a = await myCompare1();
    let b = await setUnloading();
}
$(function () {
    /*myCompare().then(function() {
        $('.js-img-compare').myUnloading();
    });*/
    myCompare();
});
JS;
$this->registerJsFile(\yii\helpers\Url::to('@web/plugins') . '/jquery-images-compare/js/jquery.images-compare.js');
$this->registerCssFile(\yii\helpers\Url::to('@web/plugins') . '/jquery-images-compare/css/images-compare.css');
$this->registerJs($script);
?>