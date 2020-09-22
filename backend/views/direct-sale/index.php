<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23-Mar-19
 * Time: 3:35 PM
 */

use common\grid\MyGridView;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Direct Sale';

?>
<section id="dom">
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
                <div class="card-content collapse show customer-index">
                    <div class="card-body card-dashboard">
                        <?php Pjax::begin(
                ['id' => 'direct-sale-ajax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]
            ); ?>
                        <?php
                        echo $this->render('_search', ['model' => $searchModel]);
                        ?>
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                            <?= MyGridView::widget([
                                'id' => 'customer-online-clinic',
                                'dataProvider' => $dataProvider,
//                                    'filterModel' => $searchModel,
                                'layout' => '{errors} <div class="pane-single-table">{items}</div><div class="pager-wrap clearfix">{summary}' .
                                    Yii::$app->controller->renderPartial('@backend/views/layouts/my-gridview/_goToPage', ['totalPage' => $totalPage, 'currentPage' => Yii::$app->request->get($dataProvider->getPagination()->pageParam)]) .
                                    Yii::$app->controller->renderPartial('@backend/views/layouts/my-gridview/_pageSize') .
                                    '{pager}</div>',
                                'tableOptions' => [
                                    'id' => 'listCampaign',
                                    'class' => 'cp-grid cp-widget pane-hScroll',
                                ],
                                'options' => [
                                    'class' => 'grid-view',
                                    'data-pjax' => 1
                                ],
                                'rowOptions' => function ($model) {
                                    return ['data-key' => $model->id];
                                },
                                'myOptions' => [
                                    'class' => 'grid-content my-content pane-vScroll',
                                    'data-minus' => '{"0":42,"1":".header-navbar","2":".form-search","3":".pager-wrap","4":".footer"}'
                                ],
                                'summaryOptions' => [
                                    'class' => 'summary pull-right',
                                ],
                                'pager' => [
                                    'firstPageLabel' => Html::tag('span', 'skip_previous', ['class' => 'material-icons']),
                                    'lastPageLabel' => Html::tag('span', 'skip_next', ['class' => 'material-icons']),
                                    'prevPageLabel' => Html::tag('span', 'play_arrow', ['class' => 'material-icons']),
                                    'nextPageLabel' => Html::tag('span', 'play_arrow', ['class' => 'material-icons']),
                                    'maxButtonCount' => 5,

                                    'options' => [
                                        'tag' => 'ul',
                                        'class' => 'pagination pull-left',
                                    ],

                                    // Customzing CSS class for pager link
                                    'linkOptions' => ['class' => 'page-link'],
                                    'activePageCssClass' => 'active',
                                    'disabledPageCssClass' => 'disabled page-disabled',
                                    'pageCssClass' => 'page-item',

                                    // Customzing CSS class for navigating link
                                    'prevPageCssClass' => 'page-item prev',
                                    'nextPageCssClass' => 'page-item next',
                                    'firstPageCssClass' => 'page-item first',
                                    'lastPageCssClass' => 'page-item last',
                                ],
                                'columns' => [
                                    [
                                        'class' => 'yii\grid\SerialColumn',
                                        'header' => 'STT',
                                        'headerOptions' => [
                                            'width' => 60,
                                        ],
                                        'contentOptions' => [
                                            'style' => 'text-align: center'
                                        ]
                                    ],
                                    ['class' => 'yii\grid\ActionColumn',
                                        'header' => 'Actions',
                                        'template' => '{updateCustomer}{order}{vet}{beaf}',
                                        'buttons' => [
                                            'updateCustomer' => function ($url, $model) {
                                                return Html::a('<i class="ft-edit green"></i>', ['#'], ['data-pjax' => 0, 'title' => 'Cập nhật', 'class' => 'update-direct', 'data-id' => $model->id]);
                                            },
                                            'order' => function ($url, $model) {
                                                return Html::a('<i class="ft-clipboard blue"></i>', ['#'], ['data-pjax' => 0, 'title' => 'Đơn hàng', 'class' => 'order-direct', 'data-id' => $model->id]);
                                            },
                                            'vet' => function ($url, $model) {
                                                return Html::a('<i class="ft-calendar red"></i>', 'javascript:void(0)', ['data-pjax' => 0, 'title' => 'Lịch điều trị', 'class' => 'dieutri-direct', 'data-id' => $model->id]);
                                            },
                                            'beaf' => function ($url, $model) {
                                                return Html::a(
                                                    '<i class="ft-eye blue"></i>',
                                                    ['#'],
                                                    [
                                                        'title' => 'Xem hình Before - After',
                                                        'class' => 'view-beaf',
                                                        'data-pjax' => 0,
                                                        'data-toggle' => 'modal',
                                                        'data-target' => '#modalCenter',
                                                    ]
                                                );
                                            },
                                        ],
                                        'contentOptions' => [
                                            'class' => 'button-action'
                                        ],
                                        'headerOptions' => [
                                            'width' => 140,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'avatar',
                                        'header' => 'Avatar',
                                        'format' => 'raw',
                                        'headerOptions' => [
                                            'width' => 100,
                                            'style' => 'text-align: center'
                                        ],
                                        'contentOptions' => [
                                            'style' => 'text-align: center'
                                        ],
                                        'value' => function ($model) {
                                            if ($model->avatar == null || !file_exists(Yii::$app->basePath .'/web/uploads/avatar/70x70/'. $model->avatar)) {
                                                $avatar = '/local/default/avatar-default.png';
                                            } else {
                                                $avatar = '/uploads/avatar/70x70/' . $model->avatar;
                                            }
                                            return Html::img($avatar);
                                        }
                                    ],
                                    [
                                        'attribute' => 'full_name',
                                        'headerOptions' => [
                                            'width' => 250,
                                        ],
                                        'contentOptions' => [
                                            'class' => 'edittable-customer',
                                            'data-field' => 'fullname',
                                        ],
//                                    'value' => function ($model) {
//                                        return $model->full_name == null ? $model->name : $model->full_name;
//                                    },
                                    ],
                                    [
                                        'attribute' => 'customer_code',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->customer_code;
                                        },
                                    ],
                                    [
                                        'attribute' => 'province',
                                        'value' => 'provinceHasOne.name',
                                    ],
                                    [
                                        'attribute' => 'co_so',
                                        'value' => 'coSoHasOne.name',
                                        'headerOptions' => [
                                            'width' => 80,
                                        ],
                                        'contentOptions' => [
                                            'style' => 'text-align: center'
                                        ]
                                    ],
                                    [
                                        'attribute' => 'customer_come',
                                        'format' => 'datetime',
                                        'label' => 'TG khách đến',
                                        'value' => 'customer_come',
                                    ],
                                    [
                                        'attribute' => 'customer_come_time_to',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return \backend\modules\customer\models\Dep365CustomerOnlineCome::getNameTrangThaiKhachDen($model->customer_come_time_to);
                                        },
                                    ],
                                    [
                                        'attribute' => 'customer_mongmuon',
                                        'format' => 'raw',
                                        'label' => 'Mong muốn',
                                        'value' => 'customer_mongmuon',
                                        'contentOptions' => [
                                            'class' => 'edittable-customer',
                                            'data-field' => 'mongmuon',
                                        ]
                                    ],
                                    [
                                        'attribute' => 'note_direct',
                                        'format' => 'raw',
                                        'label' => 'Ghi chú',
                                        'value' => 'note_direct',
                                        'contentOptions' => [
                                            'class' => 'edittable-customer',
                                            'data-field' => 'notedirect',
                                        ]
                                    ],
                                    [
                                        'attribute' => 'direct_sale',
                                        'format' => 'raw',
                                        'value' => 'directSaleHasOne.fullname',
                                    ],
                                    [
                                        'attribute' => 'customer_direct_sale_checkthammy',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return \common\widgets\ModavaCheckbox::widget([
                                                'id' => $model->id,
                                                'value' => $model->customer_direct_sale_checkthammy,
                                            ]);
                                        },
                                        'contentOptions' => ['style' => 'vertical-align:middle'],
                                    ],
                                ],
                            ]); ?>
                        </div>
                        <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div id="order-direct"></div>
<div id="update-direct"></div>
<div id="dieutri-direct"></div>
<div class="modal fade-scale" id="modalCenter" tabindex="-1" role="dialog" aria-labelledby="modalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body border-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <div class="ba-button mb-1 hidden">
                    <button class="btn js-front-btn">Before</button>
                    <button class="btn js-back-btn">After</button>
                    <button class="btn js-toggle-btn">Before / After</button>
                </div>
                <div class="row ba-info mb-1">
                    <div class="col-md-6 d-flex align-items-center">
                        <div class="ba-title" style="font-size:24px;font-weight:600;">DIGITAL SMILE DESIGN</div>
                    </div>
                    <div class="col-md-6">
                        <div class="ba-copyright text-right">
                            <img src="/images/logo-1.png" alt="logo" height="45">
                        </div>
                    </div>
                </div>
                <div class="js-img-compare">
                    <div style="display: none;">
                        <span class="images-compare-label">Before</span>
                        <img src="/uploads/customer-before/nn-khanh-g-obmeh-1557713744.jpg" alt="Before">
                    </div>
                    <div>
                        <span class="images-compare-label">After</span>
                        <img src="/uploads/customer-after/nn-khanh-mo45p-1557713744.jpg" alt="After">
                    </div>
                </div>
            <div class="modal-footer p-0 border-0"></div>
        </div>
    </div>
</div>
<?php
$urlCurrent = Url::current();
$url = Url::toRoute('/direct-sale/update-field');
$urlUpdateCustomer = Url::toRoute('/direct-sale/update');
$urlOrderCustomer = Url::toRoute('/direct-sale/order');
$urlGetPriceSP = Url::toRoute('/direct-sale/get-price-san-pham');
$urlDieuTriCustomer = Url::toRoute('/direct-sale/dieu-tri');
$urlCheckThamMy = Url::toRoute('/direct-sale/check-tham-my');
$urlChangePageSize = Url::toRoute(['/direct-sale/perpage']);

$tit = Yii::t('backend', 'Notification');
$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];

$urlImageCompareJsMb = Url::to('@web/plugins') . '/image-comparison-slider/js/jquery.mobile.custom.min.js';
$script = <<< JS
var directSale = new myGridView();
directSale.init({
    pjaxId: '#direct-sale-ajax',
    urlChangePageSize: '$urlChangePageSize'
})

//Start Điều trị
var dieuTri = new myDiv();   
    dieuTri.init({
        div: "#dieutri-direct",
        button: ".dieutri-direct",
        minWidth: "500px",
        time: 500,
        callbackBeforeOpen: function() {
            var idCustomer = dieuTri.elementJustClick.attr('data-id');
            $.ajax({
                url: '$urlDieuTriCustomer',
                cache: false,
                method: "POST",
                dataType: "html",
                data: {'id': idCustomer},
                async: false,
                success: function (data) {
                    $('#dieutri-direct').append(data);
                    dieuTri.setDiv();
                },
            });
        },
        callbackAfterClose: function(){
            $('#dieutri-direct').empty();
        }
    });
    
$('body').on('beforeSubmit', 'form#form-dieu-tri', function(e) {
    e.preventDefault();
    var \$form = $(this);
    $.ajax({
        url: \$form.attr('action'),
        cache: false,
        method: "POST",
        dataType: "json",
        data: \$form.serialize(),
        async: false,
        success: function (data) {
            if(data.status == '200') {
                setTimeout(function(){
                    toastr.success(data.result, 'Thông báo');
                }, 1000);
                $('body').find('form#form-dieu-tri').unbind('beforeSubmit');
                dieuTri.close();
            } else {
                setTimeout(function(){
                    toastr.error(data.result, 'Thông báo');
                }, 1000);
            }
        }
    });
    return false;
});
$('body').on('click', '.close-dieutri', function() {
    dieuTri.close();
});
//End Điều trị

//Start đơn hàng
var orderdirect = new myDiv();
    orderdirect.init({
        div: "#order-direct",
        button: ".order-direct",
        minWidth: "40%",
        time: 500,
        callbackBeforeOpen: function() {
            var idCustomer = orderdirect.elementJustClick.attr('data-id');
            $.ajax({
                url: '$urlOrderCustomer',
                cache: false,
                method: "POST",
                dataType: "html",
                data: {'id': idCustomer},
                async: false,
                success: function (data) {
                    $('#order-direct').append(data);
                    orderdirect.setDiv();
                },
            });
        },
        callbackAfterClose: function(){
            $('#order-direct').empty();
        }
    });
$('body').on('beforeSubmit', 'form#form-don-hang', function(e) {
    e.preventDefault();
    var \$form = $(this);
    $.ajax({
        url: \$form.attr('action'),
        cache: false,
        method: "POST",
        dataType: "json",
        data: \$form.serialize(),
        async: false,
        success: function (data) {
            if(data.status == '200') {
                setTimeout(function(){
                    $.pjax.reload({url: '/direct-sale/index', method: 'POST', container: directSale.options.pjaxId});
                    toastr.success(data.result, 'Thông báo');
                }, 500);
                orderdirect.close();
            } else {
                setTimeout(function(){
                    toastr.error(data.result, 'Thông báo');
                }, 500);
            }
        }
    });
    return false;
});
$('body').on('click', '.close-order', function() {
    orderdirect.close();
});
$("body").on('keyup', '.on-keyup', function () {
    var order_discount  = $(this).val().replace(/[^0-9]/gi, '');
    order_discount = order_discount.replace(/\./g, '');
    if(order_discount.trim() == '')
        order_discount = 0;
    $(this).val(addCommas(parseInt(order_discount)));
    getMoney();
});
$('body').on('change', '.sl-sp',function() {
    var tr = $(this).closest('tr');
    var idsp = tr.find('.san-pham-clinic').val() || 0;
    var sl = tr.find('.so-luong-clinic').val() || 1;
    if(sl < 1) {
        sl = 1;
        tr.find('.so-luong-clinic').val('1');
    }
    if(idsp == 0) {
        tr.find('.thanh-tien').val(idsp);
        getMoney();
        return false;
    }
    
    $.ajax({
        url: '$urlGetPriceSP',
        cache: false,
        method: "POST",
        dataType: "json",
        data: {'id': idsp, 'sl':sl},
        async: false,
        success: function (data) {
            if(data.status == 1) {
                tr.find('.thanh-tien').val(data.result);
                getMoney();
            }
        },
    });
});
function getMoney() {
    var totalMoney = 0;
    chietkhau = $('#phongkhamdonhang-chiet_khau').val();
    $('body').find('.thanh-tien').each(function() {
        var tmp = $(this).val().replace(/\./g, '');
        if($.isNumeric(tmp))
            totalMoney += parseInt($(this).val().replace(/\./g, ''));
    });
    $('body').find('.on-keyup').each(function() {
        var tmp = $(this).val().replace(/\./g, '');
        if($.isNumeric(tmp))
            totalMoney -= parseInt($(this).val().replace(/\./g, ''));
    });
    $('#phongkhamdonhang-thanh_tien').val(addCommas(parseInt(totalMoney)));
}
//End Đơn hàng

//Start Update
var updatedirect = new myDiv();
    updatedirect.init({
        div: "#update-direct",
        button: ".update-direct",
        minWidth: "40%",
        time: 500,
        callbackBeforeOpen: function() {
            var idCustomer = updatedirect.elementJustClick.attr('data-id');
            $.ajax({
                url: '$urlUpdateCustomer',
                cache: false,
                method: "POST",
                dataType: "html",
                data: {'id': idCustomer},
                async: false,
                success: function (data) {
                    $('#update-direct').append(data);
                    updatedirect.setDiv();
                },
            });
        },
        callbackAfterClose: function(){
            $('#update-direct').empty();
        }
    });
$('body').on('beforeSubmit', 'form#form-update', function(e) {
    e.preventDefault();
    var \$form = $(this);
    
    $.ajax({
        url: \$form.attr('action'),
        cache: false,
        method: "POST",
        dataType: "json",
        data: \$form.serialize(),
        async: false,
        success: function (data) {
            console.log(data);
            if(data.status == '200') {
                setTimeout(function(){
                    $.pjax.reload({url: '$urlCurrent', method: 'POST', container: directSale.options.pjaxId});
                    toastr.success(data.result, 'Thông báo');
                }, 500);
                updatedirect.close();
            } else {
                setTimeout(function(){
                    toastr.error(data.result, 'Thông báo');
                }, 500);
            }
        }
    });
    return false;
});
$('body').on('click', '.close-update', function() {
    updatedirect.close();
});
//End Update

// editTable customer
var myeditor = new myEditor();
myeditor.init({
    element: '.edittable-customer', /* id or class of element */
    callbackBeforeSubmit: function(){
        var field = myeditor.editor.attr('data-field'),
        customerId = myeditor.editor.closest('tr').attr('data-key'),
        data = myeditor.editor.find('.myEdit-data > textarea').val();
        $.ajax({
            type: 'POST',
            url: '$url',
            dataType: 'json',
            data: {id: customerId, field:field, data:data},
        }).done(function(data) {
            if(data.status == '200') {
                $.pjax.reload({url: '/direct-sale/index', method: 'POST', container:'#direct-sale-ajax'});
                toastr.success('Cập nhật thành công.', 'Thông báo');
            } else {
                toastr.error('Cập nhật thất bại.', 'Thông báo');
            }
        })
    }, /* callbackBeforeSubmit */
    callbackAfterSubmit: function(){}, /* callbackAfterSubmit */
    callbackBeforeCancel: function(){}, /* callbackBeforeCancel */
    callbackAfterCancel: function(){}, /* callbackAfterCancel */
});

// Start check tham my
$('body').on('change', '.check-toggle', function () {
    var id = $(this).val();
    $.post('$urlCheckThamMy', {id: id}, function (result) {
        if(result == 1) {
            toastr.success('$resultSuccess', '$tit');
        }
        if(result == 0) {
            toastr.error('$resultDanger', '$tit');
        }
    });
});
// End check tham my

$('#modalCenter').on('show.bs.modal', function(){
    setTimeout(function(){
        $(window).resize();
    },180)
})
$(function () {

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
});

JS;
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js', ['depends' => [yii\web\JqueryAsset::class]]);
$this->registerJsFile(Url::to('@web/plugins') . '/jquery-images-compare/js/jquery.images-compare.js', ['depends' => [yii\web\JqueryAsset::class]]);
$this->registerCssFile(Url::to('@web/plugins') . '/jquery-images-compare/css/images-compare.css', [
    'depends' => [\yii\bootstrap\BootstrapAsset::class],
    'media' => 'screen',
    'type' => 'text/css',
], 'stylesheet');
$this->registerJs($script, \yii\web\View::POS_END);
$this->registerCss('
.modal-content {
    max-height: 900px;
}
.fade-scale {
  transform: scale(0);
  opacity: 0;
  -webkit-transition: all .25s linear;
  -o-transition: all .25s linear;
  transition: all .25s linear;
}
.fade-scale.show {
  opacity: 1;
  transform: scale(1);
}
');
?>
