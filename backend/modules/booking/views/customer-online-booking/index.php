<?php

use backend\modules\user\models\User;
use yii\helpers\Html;
use common\grid\MyGridView;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\booking\models\search\CustomerOnlineBookingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Customer Booking');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Booking'), 'url' => ['']];
$this->params['breadcrumbs'][] = $this->title;

$user = new User();
$roleUser = $user->getRoleName(Yii::$app->user->id);
$roleDev = User::USER_DEVELOP;
$roleAdmin = User::USER_ADMINISTRATOR;

$Action = false;
if ($roleUser == $roleDev || $roleUser == $roleAdmin) {
    $Action = true;
}
?>
<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <div class="btn-add-campaign clearfix" style="margin-top:0px;position:relative">
                            <?php // Html::a('<i class="fa fa-plus"> Thêm mới</i>', ['create'], ['title' => 'Thêm mới', 'data-pjax' => 0, 'class' => 'btn btn-default pull-left'])?>
                            <?php if (Yii::$app->user->id == 1 || Yii::$app->user->id == 102) { ?>
                                <?= Html::button('Tạo lịch ảo', ['class' => 'btn btn-primary', 'id' => 'render-virtual-booking']) ?>
                                <?= Html::button('Convert', ['class' => 'btn btn-success', 'id' => 'convert-data']) ?>
                            <?php } ?>
                        </div>
                        <?php // echo $this->render('_search', ['model' => $searchModel]);?>
                        <?php Pjax::begin(['id' => 'customer-online-booking', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                            <?= MyGridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'filterSelector' => 'select[name="per-page"]',
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
                                    'data-minus' => '{"0":42,"1":".header-navbar","2":".btn-add-campaign","3":".pager-wrap","4":".footer"}'
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
                                //'filterModel' => $searchModel,
                                'columns' => [
                                    [
                                        'class' => 'yii\grid\SerialColumn',
                                        'header' => 'STT',
                                        'headerOptions' => [
                                            'width' => 60,
                                            'rowspan' => 2
                                        ],
                                        'filterOptions' => [
                                            'class' => 'd-none'
                                        ],
                                    ],
                                    [
                                        'attribute' => 'user_register_id',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            if ($model->customer_type == null) {
                                                return null;
                                            }
                                            if ($model->customer_type == 1) {
                                                $user = new \backend\models\CustomerModel();
                                            } else {
                                                $user = new \backend\modules\booking\models\UserRegister();
                                            }
                                            $customer = $user->getById($model->user_register_id);
                                            if ($customer == null) {
                                                return null;
                                            }
                                            return Html::a($customer->name, '#', [
                                                'title' => $customer->name,
                                                'data-pjax' => 0,
                                                'data-toggle' => 'modal',
                                                'data-backdrop' => 'static',
                                                'data-keyboard' => false,
                                                'data-target' => '#custom-modal',
                                                'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['view', 'id' => $model->id]) . '"); return false;',
                                            ]);
                                        }
                                    ],
                                    [
                                        'attribute' => 'phone',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            if ($model->userRegisterHasOne == null) {
                                                return null;
                                            }
                                            return '<div class="td-phone">' .
                                                Html::a(
                                                    '<button class="btn btn-success white btn-sm"><i class="fa fa-phone"></i></button>',
                                                    'javascript:void(0)',
                                                    [
                                                        'onclick' => 'return typeof mycall == \'object\' ? mycall.makeCall(\'' . $model->userRegisterHasOne->phone . '\') : toastr.warning("Không thể kết nối đến hệ thống gọi")',
                                                        'title' => 'Gọi'
                                                    ]
                                                ) .
                                                Html::button('<i class="fa fa-copy"></i>', [
                                                    'class' => 'btn btn-primary white btn-sm copy-data',
                                                    'data-phone' => $model->userRegisterHasOne->phone,
                                                    'data-copy' => '{"el_copy":"attr-copy","data_copy":"data-phone","success":"toastr.success(\"Đã copy số điện thoại!\")"}',
                                                    'data-pjax' => 0,
                                                    'title' => 'Copy số điện thoại'
                                                ]);
                                        }
                                    ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'customer_type',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            if (!array_key_exists($model->customer_type, \backend\modules\booking\models\CustomerOnlineBooking::CUSTOMER_TYPE)) {
                                                return null;
                                            }
                                            return \backend\modules\booking\models\CustomerOnlineBooking::CUSTOMER_TYPE[$model->customer_type];
                                        },
                                        'enum' => \backend\modules\booking\models\CustomerOnlineBooking::CUSTOMER_TYPE,
                                        'filter' => \backend\modules\booking\models\CustomerOnlineBooking::CUSTOMER_TYPE,
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                        'headerOptions' => [
                                            'width' => 250,
                                        ],
                                    ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'time_id',
                                        'format' => 'raw',
                                        'value' => 'timeWorkHasOne.time',
                                        'enum' => \backend\modules\booking\models\TimeWork::getTimeWorkArray(),
                                        'filter' => \backend\modules\booking\models\TimeWork::getTimeWorkArray(),
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                    ],
                                    [
                                        'attribute' => 'booking_date',
                                        'format' => 'date',
                                        'value' => 'booking_date',
                                        'filter' => \dosamigos\datepicker\DatePicker::widget([
                                            'model' => $searchModel,
                                            'attribute' => 'booking_date',
                                            'template' => '{input}{addon}',
                                            'addon' => '<i class="fa fa-calendar"></i>',
                                            'language' => 'vi',
                                            'clientOptions' => [
                                                'autoclose' => true,
                                                'format' => 'dd-mm-yyyy',
                                                'todayHighlight' => true
                                            ],
                                            'options' => [
                                                'autocomplete' => 'off',
                                            ]
                                        ]),
                                        'headerOptions' => [
                                            'width' => 200,
                                        ],
                                        'contentOptions' => [
                                            'class' => 'mod-date'
                                        ],
                                    ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'coso_id',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            if ($model->coSoHasOne == null) {
                                                return null;
                                            }
                                            return 'Cơ sở ' . $model->coSoHasOne->name;
                                        },
                                        'enum' => \backend\modules\setting\models\Dep365CoSo::getCoSoArray(),
                                        'filter' => \backend\modules\setting\models\Dep365CoSo::getCoSoArray(),
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                    ],
                                    [
                                        'attribute' => 'status',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return '<label class="square-checkbox">' . Html::checkbox('', ($model->status == 1), ['class' => 'chk-status', 'url-chk' => \yii\helpers\Url::toRoute(['change-status', 'id' => $model->primaryKey])]) . '<span></span></label>';
                                        }
                                    ],
                                    'created_at:date',
                                    ['class' => 'yii\grid\ActionColumn',
                                        'header' => 'Actions',
                                        'visible' => $Action,
                                        'template' => '<div class="btn-group" role="group">{delete}</div>',
                                        'buttons' => [
                                            /*'update' => function ($url, $model) {
                                                return Html::button('<i class="ft-edit blue"></i>',
                                                    [
                                                        'class' => 'btn btn-default',
                                                        'data-pjax' => 0,
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop' => 'static',
                                                        'data-keyboard' => false,
                                                        'data-target' => '#custom-modal',
                                                        'onclick' => 'showModal($(this), "' . $url . '");return false;',
                                                    ]);
                                            },*/
                                            'delete' => function ($url, $model) {
                                                return Html::a('<i class="ft-trash-2 red confirm-color" data-id = "' . $model->id . '" ></i>', 'javascript:void(0)', ['class' => 'btn btn-default']);
                                            },
                                        ],
                                        'headerOptions' => [
                                            'width' => 100,
                                            'rowspan' => 2,
                                        ],
                                        'filterOptions' => [
                                            'class' => 'd-none'
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

<?php
$url = \yii\helpers\Url::toRoute(['show-hide']);
$urlDelete = \yii\helpers\Url::toRoute(['delete']);
$urlChangePageSize = \yii\helpers\Url::toRoute(['perpage']);
$urlRenderVirtualBooking = \yii\helpers\Url::toRoute(['render-virtual-booking']);
$urlOptionsRenderBooking = \yii\helpers\Url::toRoute(['choose-options-render-booking']);
$urlConvertData = \yii\helpers\Url::toRoute(['convert-data']);

$tit = Yii::t('backend', 'Notification');

$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', 'If delete, you will not be able to recover!');

$this->registerCssFile('https://cdn.myauris.vn/plugins/myModal/myModal/myModal.css', ['depends' => [\yii\bootstrap\BootstrapAsset::class]]);
$this->registerJsFile('https://cdn.myauris.vn/plugins/myModal/myModal/myModal.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$script = <<< JS
var onlineBooking = new myGridView(),
    mymodal = new myModal();
onlineBooking.init({
    pjaxId: '#customer-online-booking',
    urlChangePageSize: '$urlChangePageSize'
});

$('body').on('beforeSubmit', 'form#form-customer-booking', function(e){
    e.preventDefault();
   var currentUrl = $(location).attr('href');
   var formData = $('#form-customer-booking').serialize();
    
    $('#form-customer-booking').myLoading({
        opacity: true
    });
       
    $.ajax({
        url: $('#form-customer-booking').attr('action'),
        type: 'POST',
        data: formData,
        dataType: 'json',
    })
    .done(function(res) {
        if (res.status == 200) {
            $.when($.pjax.reload({url: currentUrl, method: 'POST', container: onlineBooking.options.pjaxId})).done(function(){
                toastr.success(res.mess, '$tit');
                $('.modal-header').find('.close').trigger('click');
            });
        } else {
            toastr.error(res.mess, '$tit');
        }
    });
   
   return false;
})

$(document).ready(function () {
    $('body').on('click', '#convert-data', function(){
        $('body').myLoading();
        $.ajax({
            type: 'POST',
            url: '$urlConvertData',
            dataType: 'json',
        }).done(function(res){
            $('body').myUnloading();
            if(res.code == 200){
                toastr.success(res.msg, 'Thông báo');
            } else {
                toastr.error(res.msg, 'Thông báo');
            }
            var currentUrl = $(location).attr('href');
            $.pjax.defaults.timeout = false;
            $.pjax.reload({url: currentUrl, method: 'POST', container:'#customer-online-booking'});
            console.log(res);
        }).fail(function(err){
            $('body').myUnloading();
            toastr.error('Lỗi!', 'Thông báo');
            console.log(err);
        });
    });
    $('body').on('click', '#render-virtual-booking', function(){
        $('body').myLoading({
            opacity: true,
            msg: 'Tạo lịch ảo...'
        });
        $.ajax({
            type: 'POST',
            url: '$urlRenderVirtualBooking',
            dataType: 'json',
        }).done(function(res){
            console.log(res);
            if(res.code == 200){
                if(res.return == 'choose-options'){
                    mymodal.setModal('modal-choose-options', 'large');
                    mymodal.setText('title', 'Phương thức tạo lịch ảo');
                    mymodal.myModal.find(mymodal.el['body']).load('$urlOptionsRenderBooking');
                    mymodal.setClassEl('add', 'header', 'bg-blue-grey bg-lighten-2 white');
                    mymodal.setCallback('submit', function(){
                        mymodal.myModal.find('#submit-choose-options-render').trigger('click');
                    });
                    mymodal.setCallbackOnHide(function(){
                        $.when($.pjax.reload({url: window.location.href, method: 'POST', container: onlineBooking.options.pjaxId})).done(function(){
                            $('body').myUnloading();
                        });
                    });
                    mymodal.visible('show');
                } else {
                    $.when($.pjax.reload({url: window.location.href, method: 'POST', container:'#customer-online-booking'})).done(function(){
                        toastr.success(res.msg, 'Thông báo');
                        $('body').myUnloading();
                    });
                }
            } else {
                toastr.error(res.msg, 'Thông báo');
                $('body').myUnloading();
            }
        }).fail(function(err){
            $('body').myUnloading();
            toastr.error('Lỗi!', 'Thông báo');
            console.log(err);
        });
    });
    $('body').on('change', '.check-toggle', function () {
        var id = $(this).val();
        $.post('$url', {id: id}, function (result) {
            if(result == 1) {
                toastr.success('$resultSuccess', '$tit');
            }
            if(result == 0) {
                toastr.error('$resultDanger', '$tit');
            }
        });
    });
    $('body').on('click', '.confirm-color', function (e) {
        e.preventDefault();
        var currentUrl = $(location).attr('href');
        var id = $(this).attr("data-id");
        Swal.fire({
          title: 'Bạn có chắc muốn xoá?',
          text: "Bạn sẽ không khôi phục lại được!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Vâng, xoá nó!'
        }).then((result) => {
          if (result.value) {
              $.ajax({
                type: "POST",
                cache: false,
                data:{"id":id},
                url: "$urlDelete",
                dataType: "json",
                success: function(data){
                    if(data.status == 'success') {
                        toastr.success('$deleteSuccess', '$tit');
                        $.pjax.reload({url: currentUrl, method: 'POST', container: onlineBooking.options.pjaxId});
                    }
                    if(data.status == 'failure' || data.status == 'exception')
                        toastr.error('Xoá không thành công', 'Thông báo');
                }
              });
          }
        });
    });
    $('body').on('change', '.chk-status', function(){
        var checkbox = $(this),
            url = checkbox.attr('url-chk') || null;
        if(url != null){
            Swal.fire({
                title: 'Cập nhật trạng thái?',
                text: "Bạn đã chăm sóc khách hàng này!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Vâng, cập nhật!'
            }).then((result) => {
                if (result.value) {
                    $('.modal-content').myLoading({
                        opacity: true
                    });
                    $.post(url, {status: $(this).is(':checked')}, function(res){
                        if(res.code == 200) {
                            toastr.success(res.msg, 'Thông báo');
                            $.pjax.reload({url: window.location.href, method: 'POST', container: onlineBooking.options.pjaxId});
                        } else {
                            toastr.error(res.msg, 'Thông báo');
                        }
                        $('.modal-content').myUnloading();
                    }, 'json').fail(function(f){
                        console.log('fail', f);
                        toastr.error('Có lỗi xảy ra', 'Thông báo');
                        $('.modal-content').myUnloading();
                    });
                } else {
                    checkbox.prop('checked', !checkbox.is(':checked'));
                }
            });
        }
    });
    if(typeof socket === 'object'){
        socket.on('dep365-alert', function (res) {
            var data = res.data || null,
                message = res.message || null;
            if (typeof data !== 'object' || message === null || data.length <= 0) return false;
            var act = data.act || null;
            if(act === 'customer-online-booking'){
                $.pjax.reload({url: window.location.href, method: 'POST', container: onlineBooking.options.pjaxId});
            }
        });
    }
});
JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>

