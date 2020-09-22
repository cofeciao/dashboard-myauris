<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23-Mar-19
 * Time: 3:35 PM
 */

use backend\modules\clinic\models\PhongKhamKhuyenMai;
use common\grid\MyGridView;
use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('backend', 'Customer');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Direct Sale'), 'url' => ['']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="dom">
    <div class="row">
        <div class="col-12">
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
                                        'template' => '<div class="btn-group" role="group">{updateCustomer} {order} {list-order} {show-beaf}</div>',
                                        'buttons' => [
                                            'updateCustomer' => function ($url, $model) {
                                                return Html::button(
                                                    '<i class="p-icon ft-edit green"></i>',
                                                    [
                                                        'title' => 'Cập nhật',
                                                        'class' => 'btn btn-default',
                                                        'data-pjax' => 0,
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop' => 'static',
                                                        'data-keyboard' => false,
                                                        'data-target' => '#custom-modal',
                                                        'onclick' => 'showModal($(this), "' . Url::toRoute(['update', 'id' => $model->id]) . '");return false;',
                                                    ]
                                                );
                                            },
                                            'order' => function ($url, $model) {
                                                return Html::button(
                                                    '<i class="p-icon relative ft-clipboard success"><i class="fa fa-plus"></i></i>',
                                                    [
                                                        'title' => 'Tạo đơn hàng',
                                                        'class' => 'btn btn-default',
                                                        'data-pjax' => 0,
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop' => 'static',
                                                        'data-keyboard' => false,
                                                        'data-target' => '#custom-modal',
                                                        'onclick' => 'showModal($(this), "' . $url . '");return false;',
                                                    ]
                                                );
                                            },
                                            /*'vet' => function ($url, $model) {
                                                return Html::a('<i class="ft-calendar red"></i>', 'javascript:void(0)', ['data-pjax' => 0, 'title' => 'Lịch điều trị', 'class' => 'dieutri-direct', 'data-id' => $model->id]);
                                            },*/
                                            'list-order' => function ($url, $model) {
                                                return Html::a('<i class="p-icon relative ft-clipboard red"><i class="fa fa-search"></i></i>', ['/clinic/clinic-order', 'customer_id' => $model->primaryKey], [
                                                    'title' => 'Danh sách đơn hàng',
                                                    'class' => 'btn btn-default',
                                                    'data-pjax' => 0,
                                                ]);
                                            },
                                            'show-beaf' => function ($url, $model) {
                                                return Html::a(
                                                    '<i class="p-icon ft-eye blue"></i>',
                                                    ['#'],
                                                    [
                                                        'title' => 'Xem hình Before - After',
                                                        'class' => 'view-beaf btn btn-default',
                                                        'data-pjax' => 0,
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop' => 'static',
                                                        'data-keyboard' => false,
                                                        'data-target' => '#modalCenter',
                                                        'onclick' => 'showImgBeaf($(this), "' . $url . '");return false;',
                                                    ]
                                                );
                                            },
                                        ],
                                        'contentOptions' => [
                                        ],
                                        'headerOptions' => [
                                            'width' => 150,
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
                                            if ($model->avatar == null || !file_exists(Yii::$app->basePath . '/web/uploads/avatar/70x70/' . $model->avatar)) {
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
                                        'headerOptions' => [
                                            'class' => 'text-center'
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-center'
                                        ]
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
                                        'contentOptions' => [
                                            'class' => 'text-center',
                                            'style' => 'vertical-align:middle'
                                        ],
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
<div class="modal fade" id="modalCenter" tabindex="-1" role="dialog" aria-labelledby="modalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="min-height: 300px; overflow-x: hidden"></div>
    </div>
</div>
<?php
$this->registerCssFile('/css/css_order.css', ['depends' => [\yii\bootstrap\BootstrapAsset::class]]);
$this->registerJsFile('/js/js_order.js', ['depends' => [\yii\web\JqueryAsset::class]]);

$type_curency = PhongKhamKhuyenMai::TYPE_CURENCY;
$type_percent = PhongKhamKhuyenMai::TYPE_PERCENT;
$urlCurrent = Url::current();
$urlGetPriceKhuyenMai = Url::toRoute(['/clinic/phong-kham-khuyen-mai/get-price-khuyen-mai']);
$url = Url::toRoute(['update-field']);
$urlUpdateCustomer = Url::toRoute(['update']);
$urlOrderCustomer = Url::toRoute(['order']);
$urlGetPriceSP = Url::toRoute(['get-price-san-pham']);
$urlDieuTriCustomer = Url::toRoute(['dieu-tri']);
$urlCheckThamMy = Url::toRoute(['check-tham-my']);
$urlChangePageSize = Url::toRoute(['perpage']);
$urlCheckDichVu = Url::toRoute(['/clinic/lich-hen/check-dich-vu']);

$tit = Yii::t('backend', 'Notification');
$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];
$urlLoadSanPham = Url::toRoute(['/clinic/clinic-san-pham/danh-sach-san-pham']);
$urlPrintOrder = Url::toRoute(['/clinic/clinic-order/print-order', 'id' => '']);

$urlImageCompareJsMb = Url::to('@web/plugins') . '/image-comparison-slider/js/jquery.mobile.custom.min.js';
$script = <<< JS
var directSale = new myGridView(),
    type_curency = '$type_curency',
    type_percent = '$type_percent',
    urlGetPriceKhuyenMai = '$urlGetPriceKhuyenMai';
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

function loadModalImageBeaf(modal){
    return new Promise(function(resolve, reject){
        $(modal).find('.modal-content').myLoading({
            msg: 'Đang tải dữ liệu...',
            opacity: true
        });
        resolve();
    });
}
function showImgBeaf(el, url){
    let modalId = el.data('target');
    loadModalImageBeaf(modalId).then(function(){
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'html'
        })
        .done(function(data){
            setTimeout(function(){
                $(modalId).find('.modal-content').html(data);
            }, 3000);
        })
        .fail(function(error){
            console.log(error);
        });
    })
}
function saveOrder(){
    return new Promise(function(resolve, reject){
        var formData = $('#form-don-hang').serialize();
        $('#form-don-hang').myLoading({opacity: true});
        $.ajax({
            url: $('#form-don-hang').attr('action'),
            type: 'POST',
            data: formData,
            dataType: 'json',
        })
        .done(function(res) {
            console.log(res);
            if (res.status == 200) {
                resolve(res);
            } else {
                reject(res);
            }
        }).fail(function(err){
            console.log('update order error', err);
            reject(null);
        });
    });
}
    
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
}).on('click', '.close-dieutri', function() {
    dieuTri.close();
}).on('beforeSubmit', 'form#form-don-hang', function(e) {
    e.preventDefault();
    var btnHandle = $('#form-don-hang').find('#button-handle').val() || null;
    saveOrder().then(function(result){
        console.log(result);
        $.when($.pjax.reload({url: window.location.href, method: 'POST', container: directSale.options.pjaxId})).done(function(){
            if(btnHandle == 1){
                /* SUBMIT */
                var url_redirect = $('form#form-don-hang').attr('redirect-on-submit') || null;
                toastr.success(result.result, 'Thông báo');
                if(url_redirect != null && url_redirect != ''){
                    setTimeout(function(){
                        window.location.href = url_redirect;
                    }, 3000);
                } else {
                    $('#custom-modal').find('.close').trigger('click');
                }
            } else {
                /* PRINT */
                $.when(toastr.success(result.result, 'Thông báo')).then(function(){
                    if (result.order_id != null) {
                        var printUrl = '$urlPrintOrder' + result.order_id;
                        window.open(printUrl, '', 'width=800,height=600,left=0,top=0,toolbar=0,scrollbars=0,status=0');
                    }
                    $('#custom-modal').find('.close').trigger('click');
                });
            }
        });
    }, function(error){
        if(error == null){
            $('#form-don-hang').myUnloading();
        } else {
            $('#form-don-hang').myUnloading();
            toastr.error(error.result, 'Thông báo');
        }
    });
    return false;
}).on('click', '.close-order', function() {
    orderdirect.close();
}).on('change', '.sl-sp',function() {
    var tr = $(this).closest('tr');
    var idsp = tr.find('.san-pham-clinic').val() || 0;
    var sl = tr.find('.so-luong-clinic').val() || 1;
    if(sl < 1) {
        sl = 1;
        tr.find('.so-luong-clinic').val('1');
    }
    if(idsp == 0) {
        tr.find('.thanh-tien').val(idsp);
        checkChietKhauOrder(tr);
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
                checkChietKhauOrder(tr);
            }
        },
    });
}).on('change', '.check-toggle', function () {
    var id = $(this).val();
    $.post('$urlCheckThamMy', {id: id}, function (result) {
        if(result == 1) {
            toastr.success('$resultSuccess', '$tit');
        }
        if(result == 0) {
            toastr.error('$resultDanger', '$tit');
        }
    });
}).on('beforeSubmit', 'form#form-update', function(e) {
   e.preventDefault();
   var currentUrl = $(location).attr('href');
   var formData = $('#form-update').serialize();

    $('#form-update').myLoading({opacity: true});
   
    $.ajax({
        url: $('#form-update').attr('action'),
        type: 'POST',
        data: formData,
        dataType: 'json',
    })
    .done(function(res) {
        $('#form-update').myUnloading();
        console.log(res);
        if (res.status == 200) {
            $.when($.pjax.reload({url: currentUrl, method: 'POST', container: directSale.options.pjaxId})).done(function(){
                $('.modal-header').find('.close').trigger('click');
                toastr.success(res.mess, '$tit');
            });
        } else {
            toastr.error(res.mess, '$tit');
        }
    })
    .fail(function(err){
        $('#form-update').myUnloading();
        console.log(err);
    });
   
   return false;
}).on('change', '.san-pham-clinic', function(){
    let el = $(this),
        san_pham = $(this).val() || null;
    $('#form-don-hang').myLoading({
        opacity: true
    });
    el.closest('tr').find('.dich-vu').val('');
    $.post('$urlCheckDichVu', {sanpham: san_pham}, function(res){
        $('#form-don-hang').myUnloading();
        if(res.code === 200){
            el.closest('tr').find('.dich-vu').val(res.data);
        } else {
            toastr.error(res.msg, 'Thông báo');
        }
    }, 'json').fail(function(f){
        $('#form-don-hang').myUnloading();
        toastr.error('Lỗi load dữ liệu dịch vụ', 'Thông báo');
        console.log('load dich_vu fail', f);
    });
});
//End Đơn hàng
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
                $.pjax.reload({url: window.location.href, method: 'POST', container:'#direct-sale-ajax'});
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
JS;
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js', ['depends' => [yii\web\JqueryAsset::class]]);
$this->registerJs($script, \yii\web\View::POS_END);
$this->registerCss('
//.modal-content{max-height:900px}
.fade-scale{transform:scale(0);opacity:0;-webkit-transition:all .25s linear;-o-transition:all .25s linear;transition:all .25s linear}
.fade-scale.show{opacity:1;transform:scale(1)}
.ba-title{font-family: "Muli", sanse-rif}
');
?>
