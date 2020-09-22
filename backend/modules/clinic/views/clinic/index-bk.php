<?php

use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\user\models\User;
use common\grid\MyGridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use backend\modules\customer\models\Dep365CustomerOnlineDathenStatus;
use backend\modules\customer\models\Dep365CustomerOnlineCome;

$this->title = Yii::t('backend', 'Khách hàng');
$this->params['breadcrumbs'][] = $this->title;

$user = new User();
$roleUser = $user->getRoleName(Yii::$app->user->id);
$roleDev = User::USER_DEVELOP;

?>
<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content collapse show customer-index">
                    <div class="card-body card-dashboard">
                        <div class="btn-add-campaign clearfix" style="margin-top:0px;position:relative; margin-bottom: 10px">
                            <?= Html::button(
    '<i class="fa fa-plus"></i> Thêm mới',
    [
                                    'title' => 'Thêm mới',
                                    'class' => 'btn btn-default pull-left',
                                    'data-pjax' => 0,
                                    'data-toggle' => 'modal',
                                    'data-backdrop' => 'static',
                                    'data-keyboard' => false,
                                    'data-target' => '#custom-modal',
                                    'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['/clinic/clinic/create']) . '");return false;',
                                ]
)
                            ?>
                        </div>
                        <?php Pjax::begin(['id' => 'customer-ajax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
                        <?php
                        echo $this->render('_search', ['model' => $searchModel]);
                        ?>
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                            <?= MyGridView::widget([
                                'id' => 'customer-online-clinic',
                                'dataProvider' => $dataProvider,
                                'layout' => '{errors} <div class="pane-single-table">{items}</div><div class="pager-wrap clearfix">{summary}' .
                                    Yii::$app->controller->renderPartial('@backend/views/layouts/my-gridview/_goToPage', ['totalPage' => $totalPage, 'currentPage' => Yii::$app->request->get($dataProvider->getPagination()->pageParam)]) .
                                    Yii::$app->controller->renderPartial('@backend/views/layouts/my-gridview/_pageSize') .
                                    '{pager}</div>',
                                'tableOptions' => [
                                    'id' => 'listCampaign',
                                    'class' => 'cp-grid cp-widget pane-hScroll',
                                ],
                                'myOptions' => [
                                    'class' => 'grid-content my-content pane-vScroll',
                                    'data-minus' => '{"0":52,"1":".header-navbar","2":".btn-add-campaign","3":".pager-wrap","4":".footer","5":".form-search"}'
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
                                            'style' => 'line-height:70px',
                                        ]
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'Actions',
                                        'template' => '<div class="btn-group" role="group">{render-and-update} {order-customer} {dieu-tri}</div>',
                                        'buttons' => [
                                            'render-and-update' => function ($url) {
                                                return Html::button(
                                                    '<i class="ft-edit blue"></i>',
                                                    [
                                                        'title' => 'Cập nhật',
                                                        'class' => 'btn btn-default',
                                                        'data-pjax' => 0,
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop' => 'static',
                                                        'data-keyboard' => false,
                                                        'data-target' => '#custom-modal',
                                                        'onclick' => 'showModal($(this), "'.$url.'");return false;',
                                                    ]
                                                );
                                            },
                                            'order-customer' => function ($url) {
                                                return Html::button(
                                                    '<i class="ft-clipboard blue"></i>',
                                                    [
                                                        'title' => 'Đơn hàng',
                                                        'class' => 'btn btn-default',
                                                        'data-pjax' => 0,
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop' => 'static',
                                                        'data-keyboard' => false,
                                                        'data-target' => '#custom-modal',
                                                        'onclick' => 'showModal($(this), "'.$url.'");return false;',
                                                    ]
                                                );
                                            },
                                            'dieu-tri' => function ($url) {
                                                return Html::button(
                                                    '<i class="ft-clipboard red"></i>',
                                                    [
                                                        'title' => 'Lịch điều trị',
                                                        'class' => 'btn btn-default',
                                                        'data-pjax' => 0,
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop' => 'static',
                                                        'data-keyboard' => false,
                                                        'data-target' => '#custom-modal',
                                                        'onclick' => 'showModal($(this), "'.$url.'");return false;',
                                                    ]
                                                );
                                            },
                                        ],
                                        'headerOptions' => [
                                            'width' => 110,
                                        ],
                                        'contentOptions' => [
                                            'style' => 'line-height:65px',
                                        ]
                                    ],
                                    [
                                        'attribute' => 'avatar',
                                        'header' => 'Avatar',
                                        'format' => 'raw',
                                        'headerOptions' => [
                                            'width' => 90,
                                        ],
                                        'value' => function ($model) {
                                            if ($model->avatar == null || !file_exists(Yii::$app->basePath .'/web/uploads/avatar/70x70/'. $model->avatar)) {
                                                $avatar = '/local/default/avatar-default.png';
                                            } else {
                                                $avatar = '/uploads/avatar/70x70/' . $model->avatar;
                                            }
                                            return Html::img($avatar);
                                        },
                                    ],
                                    [
                                        'attribute' => 'name',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return Html::a($model->name, 'javascript:void(0)', [
                                                'title' => $model->name,
                                                'data-pjax' => 0,
                                                'data-toggle' => 'modal',
                                                'data-backdrop' => 'static',
                                                'data-keyboard' => false,
                                                'data-target' => '#custom-modal',
                                                'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['/clinic/clinic/view', 'id' => $model->id]) . '");return false;',
                                            ]);
                                        }
                                    ],
                                    'full_name',
                                    [
                                        'attribute' => 'forename',
                                        'format' => 'raw',
                                        'value' => 'forename',
                                        'headerOptions' => [
                                            'width' => 80,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'phone',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->phone;
                                        },
                                    ],
                                    [
                                        'attribute' => 'province',
                                        'value' => 'provinceHasOne.name',
                                    ],
                                    [
                                        'attribute' => 'dat_hen',
                                        'label' => 'Đặt hẹn',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $dathen = new Dep365CustomerOnlineDathenStatus();
                                            $data = $dathen->getOneDatHenStatus($model->dat_hen);
                                            if ($data == null) {
                                                $text = 'not-set';
                                                $class = '';
                                            } else {
                                                $class = $model->dat_hen == 1 ? 'da-den' : 'khong-den';
                                                $text = $data->name;
                                            }
                                            $data = \backend\modules\clinic\models\Clinic::getEdittableDatHen($model->dat_hen);
                                            return '<div data-option="dathen" class="edittable-dat-hen ' . $class . '" myedit-options=\'' . $data . '\'>' . $text . '</div>';
                                        },
                                        'headerOptions' => [
                                            'width' => 100,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'customer_come_time_to',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $khachden = new Dep365CustomerOnlineCome();
                                            return $khachden->getNameTrangThaiKhachDenNotStatic($model->customer_come_time_to);
                                        },
                                    ],

                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'co_so',
                                        'value' => 'coSoHasOne.name',
                                        'enum' => Dep365CustomerOnline::getCoSoDep365(),
                                        'filter' => Dep365CustomerOnline::getCoSoDep365(),
                                        'headerOptions' => [
                                            'width' => 80,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'time_lichhen',
                                        'format' => 'raw',
                                        'label' => 'Lịch hẹn',
                                        'value' => function ($model) {
                                            if ($model->time_lichhen == null) {
                                                return null;
                                            }
                                            return date('d-m-Y H:i', $model->time_lichhen);
                                        },
                                        'filter' => \dosamigos\datepicker\DatePicker::widget([
                                            'model' => $searchModel,
                                            'attribute' => 'time_lichhen',
                                            'language' => 'vi',
                                            'clientOptions' => [
                                                'autoclose' => true,
                                                'format' => 'dd-mm-yyyy',
                                            ]
                                        ]),
                                    ],
                                    [
                                        'attribute' => 'customer_come',
                                        'format' => 'datetime',
                                        'value' => 'customer_come',
                                        'filter' => \dosamigos\datepicker\DatePicker::widget([
                                            'model' => $searchModel,
                                            'attribute' => 'customer_come',
                                            'language' => 'vi',
                                            'clientOptions' => [
                                                'autoclose' => true,
                                                'format' => 'dd-mm-yyyy hh:ii',
                                            ]
                                        ]),
                                        'headerOptions' => [
                                            'width' => 180,
                                        ],
                                    ],
                                    'note',
                                    [
                                        'attribute' => 'permission_user',
                                        'label' => 'Online',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $user = User::getPermissionUser($model->permission_user);
                                            if ($user == null) {
                                                return '';
                                            }
                                            return $user->fullname;
                                        },
                                        'headerOptions' => [
                                            'width' => 120,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'directsale',
                                        'label' => 'Direct Sale',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $data = \common\models\UserProfile::getFullNameDirectSale($model->directsale);
                                            if ($data == null || $data == '') {
                                                $text = 'not-set';
                                            } else {
                                                $text = $data;
                                            }

                                            $directsale = [];
                                            $dr = \common\models\User::getNhanVienTuDirectSale();
                                            if ($dr != null) {
                                                foreach ($dr as $key => $item) {
                                                    $directsale[$item->id] = $item->userProfile->fullname;
                                                }
                                            }
                                            $result['type'] = 'select';
                                            $result['dataChoose'] = (string)$model->directsale;
                                            $result['dataSelect'] = $directsale;
                                            return '<div class="edittable-dat-hen" data-option="direct" myedit-options=\'' . json_encode($result) . '\'>' . $text . '</div>';
                                        },
                                        'headerOptions' => [
                                            'width' => 120,
                                        ],
                                    ],
                                    'note_direct',
                                ],
                            ]); ?>
                        </div>
                        <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>
</section>
<div id="order-customer"></div>
<div id="dieutri-customer"></div>

<?php
$tit = Yii::t('backend', 'Notification');

$urlChangeNumber = Url::toRoute('perpage');
$urlUpdateCustomer = Url::toRoute('render-and-update');
$urlCreateCustomer = Url::toRoute('create');
$urlOrderCustomer = Url::toRoute('order-customer');
$urlGetPriceSP = Url::toRoute('get-price-san-pham');
$urlDieuTriCustomer = Url::toRoute('dieu-tri');
$urlDH = Url::toRoute('check-letan');
$urlChangePageSize = Url::toRoute(['perpage']);
$script = <<< JS
var clinic = new myGridView();
clinic.init({
    pjaxId: '#customer-ajax',
    urlChangePageSize: '$urlChangePageSize',
});

$("body").on('keyup', '.on-keyup', function () {
    var order_discount  = $(this).val().replace(/[^0-9]/gi, '');
    order_discount = order_discount.replace(/\./g, '');
    if(order_discount.trim() == '')
        order_discount = 0;
    $(this).val(addCommas(parseInt(order_discount)));
    getMoney();
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
        type: 'POST',
        dataType: 'json',
        data: {'id': idsp, 'sl':sl},
    })
    .done(function(data){
        if(data.status == 1){
            tr.find('.thanh-tien').val(data.result);
            getMoney();
        }
    });
});
    
// Tạo mới khách hàng trong clinic
$('body').on('beforeSubmit', 'form#clinic-create',function(e) {
    e.preventDefault();
    var currentUrl = $(location).attr('href');
    var formData = $('#clinic-create').serialize();
    
    $('#clinic-create').myLoading({opacity: true});
    
    $.ajax({
        url: $('#clinic-create').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: formData,
    })
    .done(function(res) {
        if (res.status == 1) {
            $.when($.pjax.reload({url: currentUrl, method: 'POST', container: clinic.options.pjaxId})).done(function(){
                $('.modal-header').find('.close').trigger('click');
                toastr.success(res.result, '$tit');
            });
        } else {
            $('#clinic-create').myUnloading();
            toastr.error(res.result, '$tit');
        }
    }).fail(function(err) {
        $('#clinic-create').myUnloading();
        console.log('create fail', err);
    });
    return false;
});

// Cập nhật khách hàng trong clinic
$('body').on('beforeSubmit', 'form#clinic-update',function(e) {
    e.preventDefault();
    var currentUrl = $(location).attr('href');
    var formData = $('#clinic-update').serialize();
    
    $('#clinic-update').myLoading({opacity: true});
    
    $.ajax({
        url: $('#clinic-update').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: formData,
    })
    .done(function(res) {
        if (res.status == 1) {
            $.when($.pjax.reload({url: currentUrl, method: 'POST', container: clinic.options.pjaxId})).done(function(){
                $('.modal-header').find('.close').trigger('click');
                toastr.success(res.result, '$tit');
            });
        } else {
            $('#clinic-update').myUnloading();
            toastr.error(res.result, '$tit');
        }
    }).fail(function(err) {
        $('#clinic-update').myUnloading();
        console.log('update fail', err);
    });
    return false;
});

// Cập nhật đơn hàng trong clinic
$('body').on('beforeSubmit', 'form#form-don-hang', function(e) {
    e.preventDefault();
    var currentUrl = $(location).attr('href');
    var formData = $('#form-don-hang').serialize();
    
    $('#form-don-hang').myLoading({opacity: true});
   
    $.ajax({
        url: $('#form-don-hang').attr('action'),
        type: 'POST',
        data: formData,
        dataType: 'json',
    })
    .done(function(res) {
        if (res.status == 1) {
            $.when($.pjax.reload({url: currentUrl, method: 'POST', container: clinic.options.pjaxId})).done(function(){
                $('.modal-header').find('.close').trigger('click');
                toastr.success(res.result, '$tit');
            });
        } else {
            $('#form-don-hang').myUnloading();
            toastr.error(res.result, '$tit');
        }
    }).fail(function(err){
        $('#form-don-hang').myUnloading();
        console.log('update order error', err);
    });
   
   return false;
});

// Cập nhật lịch điều trị trong clinic
$('body').on('beforeSubmit', 'form#form-dieu-tri', function(e) {
    e.preventDefault();
    var currentUrl = $(location).attr('href');
    var formData = $('#form-dieu-tri').serialize();
    
    $('#form-dieu-tri').myLoading({opacity: true});
    
    $.ajax({
        url: $('#form-dieu-tri').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: formData,
    })
    .done(function(res) {
        if (res.status == 1) {
            $.when($.pjax.reload({url: currentUrl, method: 'POST', container: clinic.options.pjaxId})).done(function(){
                $('.modal-header').find('.close').trigger('click');
                toastr.success(res.result, '$tit');
            });
        } else {
            toastr.error(res.result, '$tit');
        }
    });
    
    return false;
});

// editTable customer
var myeditor = new myEditor();
myeditor.init({
    element: '.edittable-dat-hen', /* id or class of element */
    callbackBeforeSubmit: function(){        
        var currentUrl = $(location).attr('href');
        var dataOption = myeditor.editor.find('.myEdit-data > select').val(),
            id = myeditor.editor.closest('tr').attr('data-key'),
            option = myeditor.editor.attr('data-option');
        $.ajax({
            url: '$urlDH',
            type: 'POST',
            dataType: 'json',
            data: {id: id, dataOption:dataOption, option:option},
        })
        .done(function(data) {
            if(data.status == '200') {
                if(dataOption == 1 && option == 'dathen') 
                    myeditor.editor.removeClass('khong-den').addClass('da-den');
                else if(dataOption == 2 && option == 'dathen') 
                    myeditor.editor.removeClass('da-den').addClass('khong-den');
                
                $.pjax.reload({url: currentUrl, method: 'POST', container: clinic.options.pjaxId});
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

$this->registerJs($script, \yii\web\View::POS_END);
?>

