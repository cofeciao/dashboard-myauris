<?php
$css = <<< CSS
.js-img-compare,
.images-compare-before,
.images-compare-after {
    height: 100%;
}
.images-compare-before {
    width: 100%;
}
CSS;
$this->registerCss($css);
?>
<div class="js-img-compare">
    <div style="display: none;">
        <span class="images-compare-label">Before</span>
        <img src="<?= $before ?>" class="img-before" alt="Before">
    </div>
    <div>
        <span class="images-compare-label">After</span>
        <img src="<?= $after ?>" class="img-after" alt="After">
    </div>
</div>
<?php
$script = <<< JS
$('#modalCenter').on('show.bs.modal', function(){
    setTimeout(function(){
        $(window).resize();
    },180);
});
function myCompare1(){
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
    $('#modalCenter').find('.modal-inner').removeClass('loading').addClass('loaded');
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