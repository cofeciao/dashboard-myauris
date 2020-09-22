<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 17-04-2019
 * Time: 03:19 PM
 */

/* @var $customer \backend\models\Customer */
/* @var $listChupHinh array */
/* @var $listChupBanhMoi array */
/* @var $listChupCui array */
/* @var $listChupFinal array */

/* @var $listHinhTknc array */

use backend\modules\clinic\controllers\ChupBanhMoiController;
use backend\modules\clinic\controllers\ChupCuiController;
use backend\modules\clinic\controllers\ChupFinalController;
use backend\modules\clinic\controllers\ChupHinhController;
use backend\modules\clinic\controllers\DentalFormController;
use backend\modules\clinic\controllers\HinhFinalController;
use backend\modules\clinic\controllers\TkncController;
use backend\modules\clinic\controllers\UomRang1Controller;
use backend\modules\clinic\controllers\UomRang2Controller;
use yii\helpers\Html;
use yii\helpers\Url;

$customer_name = $customer->full_name == null ? $customer->name : $customer->full_name;
$customer_avatar = $customer->avatar == null ? '/local/default/avatar-default.png' : '/uploads/avatar/70x70/' . $customer->avatar;

$this->title = $customer_name;

$css = <<< CSS
#customer-view-tab li.nav-item a.nav-link {
    border-radius: 0.25rem;
}
CSS;
$this->registerCss($css);
?>
    <section id="dom">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content collapse show customer-index ">
                        <div class="card-content collapse show search-clinic">
                            <div class="customer-container">
                                <div class="customer-header">
                                    <div class="ch-col">
                                        <div class="c-avatar">
                                            <?= Html::img($customer_avatar, []) ?>
                                        </div>
                                    </div>
                                    <div class="ch-col">
                                        <div class="c-name">Khách hàng: <span><?= $customer_name ?></span></div>
                                        <div class="c-email">Id: <span><?= $customer->id ?></span></div>
                                    </div>
                                    <div class="ch-col">
                                        <div class="c-phone">Điện thoại: <span><?= $customer->phone ?: '-' ?></span>
                                        </div>
                                        <div class="c-code">Mã KH:
                                            <span><?= $customer->customer_code ?: '-' ?></span></div>
                                    </div>
                                    <div class="ch-col" style="margin-left: auto">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" row" id="myTabContent">
                            <div class="col-12 col-lg-7  ">
                                <div class=" " id="c-info" role="tabpanel"
                                     aria-labelledby="customer-info">
                                    <div class="card-body card-dashboard">
                                        <div class="customer-content">
                                            <div class="cc-block">
                                                <div class="ccb-header">
                                                    <div class="ccbh-title">Thông tin khách hàng</div>
                                                    <div class="ccbh-edit">
                                                        <!--<button class="btn btn-sm btn-outline-primary btn-ccbh-edit">Thay đổi
                                                        </button>-->
                                                    </div>
                                                </div>
                                                <div class="ccb-content load-data"
                                                     url-load="<?= Url::toRoute(['customer-info', 'id' => $customer->id]) ?>"
                                                     url-edit="<?= Url::toRoute(['customer-info-edit', 'id' => $customer->id]) ?>">
                                                </div>
                                            </div>
                                            <div class="cc-block">
                                                <div class="ccb-header">
                                                    <div class="ccbh-title">Đơn hàng</div>
                                                    <div class="ccbh-edit">
                                                        <!--<button class="btn btn-sm btn-outline-primary btn-ccbh-edit">Thay đổi
                                                        </button>-->
                                                    </div>
                                                </div>
                                                <?php
                                                foreach ($orderData as $order):
                                                    ?>
                                                    <div class="ccb-content load-data"
                                                         url-load="<?= Url::toRoute(['don-hang-info', 'id' => $customer->id, 'order_id' => $order->id]) ?>">
                                                    </div>
                                                <?php
                                                endforeach;
                                                ?>
                                            </div>


                                            <div class="cc-block">
                                                <div class="ccb-header">
                                                    <div class="ccbh-title">Thông tin lịch điều trị</div>
                                                    <div class="ccbh-edit">
                                                        <!--<button class="btn btn-sm btn-outline-primary btn-ccbh-edit">Thay đổi
                                                        </button>-->
                                                    </div>
                                                </div>
                                                <div class="ccb-content load-data"
                                                     url-load="<?= Url::toRoute(['lich-dieu-tri-info', 'id' => $customer->id]) ?>">
                                                </div>
                                            </div>
                                            <div class="cc-block">
                                                <div class="ccb-header">
                                                    <div class="ccbh-title">Hình chụp thăm khám</div>
                                                </div>
                                                <div class="ccb-content load-data"
                                                     url-load="<?= Url::toRoute(['show-image-google-drive', 'customer_id' => $customer->id, 'slug' => $customer->slug, 'folder' => ChupHinhController::FOLDER]) ?>">
                                                    <?php
                                                    //                                                echo $this->render('_chup_hinh_info', [
                                                    //                                                    'customer' => $customer,
                                                    //                                                    'listChupHinh' => $listChupHinh,
                                                    //                                                    'folder' => ChupHinhController::FOLDER
                                                    //                                                ]);
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="cc-block">
                                                <div class="ccb-header">
                                                    <div class="ccbh-title">Chụp banh môi</div>
                                                </div>
                                                <div class="ccb-content load-data"
                                                     url-load="<?= Url::toRoute(['show-image-google-drive', 'customer_id' => $customer->id, 'slug' => $customer->slug, 'folder' => ChupBanhMoiController::FOLDER]) ?>">
                                                </div>
                                            </div>
                                            <div class="cc-block">
                                                <div class="ccb-header">
                                                    <div class="ccbh-title">Chụp cùi</div>
                                                </div>
                                                <div class="ccb-content load-data"
                                                     url-load="<?= Url::toRoute(['show-image-google-drive', 'customer_id' => $customer->id, 'slug' => $customer->slug, 'folder' => ChupCuiController::FOLDER]) ?>">
                                                </div>
                                            </div>

                                            <div class="cc-block">
                                                <div class="ccb-header">
                                                    <div class="ccbh-title">Chụp kết thúc</div>
                                                </div>
                                                <div class="ccb-content load-data"
                                                     url-load="<?= Url::toRoute(['show-image-google-drive', 'customer_id' => $customer->id, 'slug' => $customer->slug, 'folder' => ChupFinalController::FOLDER]) ?>">
                                                </div>
                                            </div>

                                            <div class="cc-block">
                                                <div class="ccb-header">
                                                    <div class="ccbh-title">Hình thiết kế nụ cười</div>
                                                </div>
                                                <div class="ccb-content load-data"
                                                     url-load="<?= Url::toRoute(['show-image-google-drive', 'customer_id' => $customer->id, 'slug' => $customer->slug, 'folder' => TkncController::FOLDER]) ?>">
                                                </div>
                                            </div>

                                            <div class="cc-block">
                                                <div class="ccb-header">
                                                    <div class="ccbh-title">Ảnh chụp Ướm răng lần 1</div>
                                                </div>
                                                <div class="ccb-content load-data"
                                                     url-load="<?= Url::toRoute(['show-image-google-drive', 'customer_id' => $customer->id, 'slug' => $customer->slug, 'folder' => UomRang1Controller::FOLDER]) ?>">
                                                </div>
                                            </div>

                                            <div class="cc-block">
                                                <div class="ccb-header">
                                                    <div class="ccbh-title">Ảnh chụp Ướm răng lần 2</div>
                                                </div>
                                                <div class="ccb-content load-data"
                                                     url-load="<?= Url::toRoute(['show-image-google-drive', 'customer_id' => $customer->id, 'slug' => $customer->slug, 'folder' => UomRang2Controller::FOLDER]) ?>">
                                                </div>
                                            </div>

                                            <div class="cc-block">
                                                <div class="ccb-header">
                                                    <div class="ccbh-title">Ảnh chụp Final</div>
                                                </div>
                                                <div class="ccb-content load-data"
                                                     url-load="<?= Url::toRoute(['show-image-google-drive', 'customer_id' => $customer->id, 'slug' => $customer->slug, 'folder' => HinhFinalController::FOLDER]) ?>">
                                                </div>
                                            </div>

                                            <div class="cc-block">
                                                <div class="ccb-header">
                                                    <div class="ccbh-title">Ảnh chụp Dental Form</div>
                                                </div>
                                                <div class="ccb-content load-data"
                                                     url-load="<?= Url::toRoute(['show-image-google-drive', 'customer_id' => $customer->id, 'slug' => $customer->slug, 'folder' => DentalFormController::FOLDER]) ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-5  ">
                                <div class="" id="c-timeline" role="tabpanel"
                                     aria-labelledby="customer-timeline">
                                    <div class="card-body card-dashboard">
                                        <div class="customer-content">
                                            <div class="cc-block">
                                                <div class="ccb-header">
                                                    <div class="ccbh-title">Timeline khách hàng</div>
                                                </div>
                                                <div class="ccb-content load-data"
                                                     url-load="<?= Url::toRoute(['customer-timeline', 'id' => $customer->id]) ?>"></div>
                                            </div>
                                        </div>
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
$this->registerCssFile(Url::to('@web/modules') . '/customer/timeline/timeline.css', ['depends' => [\yii\bootstrap\BootstrapAsset::class]]);
$this->registerCssFile(Url::to('@web/plugins') . '/owl-carousel/owl.theme.default.min.css', ['depends' => [\yii\bootstrap\BootstrapAsset::class]]);
$this->registerCssFile(Url::to('@web/plugins') . '/owl-carousel/owl.carousel.css', ['depends' => [\yii\bootstrap\BootstrapAsset::class]]);
$this->registerJsFile(Url::to('@web/plugins') . '/owl-carousel/owl.carousel.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile(Url::to('@web/modules') . '/customer/timeline/timeline.js', ['depends' => \yii\web\JqueryAsset::class]);
$this->registerJsFile('/vendors/plugins/fancybox/dist/jquery.fancybox.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$script = <<< JS


function loadElement(el, url, callback = function(){}){
    el.myLoading().load(url, {}, function(){
        el.myUnloading();
        if(typeof callback == "function") callback();
    });
}
$('body').on('click', '.btn-ccbh-edit', function(){
    var btn = $(this),
        el = btn.closest('.cc-block').find('.load-data'),
        url_edit = el.attr('url-edit') || null;
    if(url_edit != null){
        loadElement(el, url_edit, function(){
            btn.hide();
        });
    }
}).on('click', '.btn-cancel-edit', function(){
    var btn = $(this).closest('.cc-block').find('.btn-ccbh-edit'),
        el = $(this).closest('.load-data'),
        url_load = el.attr('url-load') || null;
    if(url_load != null){
        loadElement(el, url_load, function(){
            btn.show();
        });
    }
}).on('click', '.btn-submit-edit', function(e){
    e.preventDefault();
    var btn = $(this).closest('.cc-block').find('.btn-ccbh-edit'),
        el = $(this).closest('.load-data'),
        url_edit = el.attr('url-edit') || null,
        url_load = el.attr('url-load') || null,
        form_id = $(this).closest('form').attr('id');
    if(url_edit != null){
        var form = document.getElementById(form_id),
            form_data = new FormData(form);
        $.ajax({
            type: 'POST',
            url: url_edit,
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
        }).done(function(res){
            if(res.code == 200){
                loadElement(el, url_load, function(){
                    btn.show();
                });
                toastr.success(res.msg, 'Thông báo!');
            } else {
                toastr.error(res.msg, 'Thông báo!');
            }
        });
    }
    return false;
});
$(document).ready(function(){
    $('div[data-carousel="owl-carousel"]').each(function(){
        $(this).owlCarousel({
            loop:true,
            margin:10,
            dots: false,
            responsiveClass:true,
            responsive:{
                0:{
                    items:2,
                    nav:true
                },
                600:{
                    items:4,
                    nav:false
                },
                1000:{
                    items:6,
                    nav:true,
                    loop:false
                }
            }
        });
    });
    $(window).ready(function(){
        $('.load-data').each(function(){
            var el = $(this),
                url_load = el.attr('url-load') || null;
            if(url_load != null){
                loadElement(el, url_load);
            }
        });
        $('div[data-carousel="owl-carousel"]').each(function(){
            // $(this)
        });
    });
});

// Pham Thanh Nghia
// 19-3-2020
var listImageFancy = [];
$('body').on('click', '.g-view', function(){
    let form = $(this).closest('.form-upload');
        form_id = form.attr('id');
        
        listImageFancy[form_id] = [];
        form.find('.g-file').each(function(){
            let image = $(this).find('.g-image').attr('data-image'),
                thumb = $(this).find('.g-thumb').attr('src');
            listImageFancy[form_id].push({
                src: image,
                opts: {
                    thumb: thumb
                }
            });
        });
         i = $(this).closest('.g-file').index();
         a = listImageFancy[form_id].slice(i, listImageFancy[form_id].length);
         b = listImageFancy[form_id].slice(0, i);
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
JS;
$this->registerJs($script, \yii\web\View::POS_END);
$this->registerCss('
.sp-content .owl-carousel button.owl-next, .sp-content .owl-carousel button.owl-prev {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 9;
    margin: 0;
    height: 40px;
    line-height: 35px;
    width: 40px;
}
.sp-content .owl-carousel .owl-prev {
    left: -15px;
}
.sp-content .owl-carousel .owl-next {
    right: -15px;
}
.sp-content .owl-carousel .owl-next span, .sp-content .owl-carousel .owl-prev span {
    font-size: 50px;
}
.sp-content .owl-theme .owl-nav [class*=owl-] {
    background: #869791;
    color: #FFF;
    opacity: 0.5;
}
.sp-content .owl-theme .owl-nav [class*=owl-]:not(.disabled):hover {
    opacity: 1;
}
');
