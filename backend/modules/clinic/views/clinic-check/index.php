<?php

use backend\modules\clinic\models\Clinic;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\PhongKhamDonHangWOrder;
use backend\modules\clinic\models\PhongKhamDonHangWThanhToan;
use backend\modules\clinic\models\PhongKhamKhuyenMai;
use backend\modules\setting\models\Dep365CoSo;
use backend\modules\clinic\models\PhongKhamLichDieuTri;
use backend\modules\user\models\User;
use common\grid\MyGridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\clinic\models\search\PhongKhamDonHangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Check Doanh số Khách đã làm');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Clinic'), 'url' => ['/clinic']];
$this->params['breadcrumbs'][] = $this->title;

$user = new User();
$roleUser = $user->getRoleName(Yii::$app->user->id);
$roleDev = User::USER_DEVELOP;

$idCustomer = false;
if ($roleUser == $roleDev) {
    $idCustomer = true;
}

$a = 3;
?>
<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <?php Pjax::begin(['id' => 'clinic-order', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
                        <?php echo $this->render('_search', ['model' => $searchModel]); ?>

                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                            <?= MyGridView::widget([
                                'id' => 'clinicOrder',
                                'dataProvider' => $dataProvider,
                                //                                    'filterModel' => $searchModel,
                                'showFooter' => true,
                                'placeFooterAfterBody' => true,
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
                                    // $hoan_coc = PhongKhamDonHang::getThanhToanByType($model->id, PhongKhamDonHangWThanhToan::HOAN_COC);
                                    $return = ['data-key' => $model->id];
                                    // if ($hoan_coc != null && $hoan_coc != '' && $hoan_coc > 0) {
                                    //     $return['class'] = 'table-danger';
                                    // }
                                    // return $return;
                                },
                                'myOptions' => [
                                    'class' => 'grid-content my-content pane-vScroll',
                                    'data-minus' => '{"0":0,"1":".header-navbar","2":".form-search","3":".pager-wrap","4":".footer","5":".grid-footer"}'
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
                                'headerRowOptions' => [
                                    'class' => ''
                                ],
                                'columns' => [
                                    [
                                        'class' => 'yii\grid\SerialColumn',
                                        'header' => '#',
                                        'headerOptions' => [
                                            'width' => 30,
                                        ],
                                        'footerOptions' => [
                                            'class' => 'd-none'
                                        ]
                                    ],
                                    // [
                                    //     'attribute' => 'id',
                                    //     'label' => 'ID',
                                    //     'value' => 'id',
                                    //     'visible' => $idCustomer,
                                    //     'headerOptions' => [
                                    //         'width' => '60px'
                                    //     ],
                                    //     'footerOptions' => [
                                    //         'class' => 'd-none'
                                    //     ]
                                    // ],

                                    [
                                        'attribute' => 'name',
                                        'format' => 'raw',
                                        'headerOptions' => [
                                            'width' => 190,
                                        ],
                                        'value' => function ($model) {
                                            if ($model->clinicHasOne == null) {
                                                return null;
                                            }
                                            $tit = $model->clinicHasOne->full_name == null ? $model->clinicHasOne->forename : $model->clinicHasOne->full_name;

                                            return $tit . "<br><p style='font-weight: 600;' >" .
                                                $model->clinicHasOne->customer_code . "</p>" .
                                                "Đến: " . date('d-m-Y H:i',  $model->clinicHasOne->customer_come);
                                        },
                                        'footerOptions' => [
                                            'class' => 'd-none'
                                        ]
                                    ],


                                    [
                                        'label' => 'Đơn hàng',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            if ($model->order_code == null) {
                                                $order_code = null;
                                            }
                                            $order_code = $model->order_code;
                                            $user = new backend\modules\clinic\models\PhongKhamDonHang();
                                            $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                                            $userCreate = '';
                                            if (!$userCreatedBy == false) {
                                                $userCreate = $userCreatedBy->fullname;
                                            }
                                            return "<span style='font-weight: 600;' >Mã HĐ : " . $order_code . " </span>" .
                                                "<i>(" . date('d-m-Y', $model->created_at) . ")</i><br>" .
                                                $model->getThongTinGoiDichVu() .
                                                "Tạo bởi : <i>" . $userCreate . "</i><br>" .
                                                "Thanh toán : " . $model->showHoanThanhThanhToan() . "<br>" .
                                                $model->showHoanThanh(true);
                                        },

                                        'headerOptions' => [
                                            'width' => "260px"
                                        ],
                                        'contentOptions' => [
                                            'style' => 'font-size:13px'
                                        ],
                                        'footerOptions' => [
                                            'class' => 'd-none'
                                        ]
                                    ],
                                    [
                                        'label' => 'Trạng thái',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            $text = "DV : " . $model->showHoanThanh() . "<br>" .
                                                "Thanh toán : " . $model->showHoanThanhThanhToan();
                                            return $text;
                                        },
                                        'headerOptions' => [
                                            'width' => "180px"
                                        ],
                                        'contentOptions' => [
                                            'style' => 'font-size:13px'
                                        ],
                                        'footerOptions' => [
                                            'class' => 'd-none'
                                        ],
                                        'visible' => 0,
                                    ],
                                    [
                                        'header' => '<span class="blue text-right">' . number_format($sum_don_hang, 0, '', '.') . '</span>',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            if ($model->thanh_tien == null) {
                                                return null;
                                            }
                                            return '<span class="blue">' . number_format($model->thanh_tien - $model->chiet_khau, 0, '', '.') . '</span>';
                                        },
                                        'footerOptions' => [
                                            'class' => 'd-none'
                                        ],
                                        'headerOptions' => [
                                            'width' => "120px"
                                        ],
                                    ],

                                    
                                    [
                                        'attribute' => 'co_so',
                                        'header' => 'Cơ sở',
                                        'headerOptions' => [
                                            'width' => '60px'
                                        ],
                                        'format' => 'raw',
                                        'value' => function ($model) {

                                            if (Yii::$app->user->can(USER::USER_ADMINISTRATOR)) {
                                                $style = '';
                                                $class = '';
                                                $aCoSo = ArrayHelper::map(Dep365CoSo::getCoSo(), 'id', 'name');
                                                if ($model->co_so !== null && array_key_exists($model->co_so, $aCoSo)) {
                                                    $text = $aCoSo[$model->co_so];
                                                } else {
                                                    $style = 'color: red; font-weight: bold;';
                                                    $text = 'not-set';
                                                }
                                                $data = PhongKhamDonHang::getEdittableCoSo($model->co_so);
                                                return '<div style="' . $style . '"  myedit-options=\'' . $data . '\'>' . $text . '</div>';
                                            } else {
                                                if ($model->coSoHasOne == null) {
                                                    return null;
                                                }
                                                return $model->coSoHasOne->name;
                                            }
                                        },
                                        'footerOptions' => [
                                            'class' => 'd-none'
                                        ]
                                    ],
                                    [
                                        'attribute' => 'direct_sale_id',
                                        'format' => 'raw',
                                        'label' => 'Direct',
                                        'value' => function ($model) {
                                            $data = \common\models\UserProfile::getFullNameDirectSale($model->direct_sale_id);
                                            if ($data == null || $data == '') {
                                                $text = null;
                                            } else {
                                                $text = $data;
                                            }
                                            return $text;
                                        },
                                        'footerOptions' => [
                                            'class' => 'd-none'
                                        ]
                                    ],
                                    
                                ],
                            ]); ?>
                        </div>
                        <?php Pjax::end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<iframe class="data-load hidden" src="" frameborder="0" style="width: 100%;min-height: 500px;"></iframe>
<?php
$this->registerCssFile('/css/css_order.css', ['depends' => [\yii\bootstrap\BootstrapAsset::class]]);
$this->registerJsFile('/js/js_order.js', ['depends' => [\yii\web\JqueryAsset::class]]);

$type_curency = PhongKhamKhuyenMai::TYPE_CURENCY;
$type_percent = PhongKhamKhuyenMai::TYPE_PERCENT;
$url = \yii\helpers\Url::toRoute(['show-hide']);
$urlDelete = \yii\helpers\Url::toRoute(['delete']);
$urlChangePageSize = \yii\helpers\Url::toRoute(['perpage']);


$tit = Yii::t('backend', 'Notification');
$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', 'If delete, you will not be able to recover!');
$script = <<< JS
var clinic = new myGridView(),
    type_curency = '$type_curency',
    type_percent = '$type_percent';
clinic.init({
    pjaxId: '#clinic-order',
    urlChangePageSize: '$urlChangePageSize'
})
$('body').on('beforeSubmit', 'form#form-don-hang', function(e) {
    // Cập nhật đơn hàng trong clinic
    e.preventDefault();
    let currentUrl = $(location).attr('href'),
        formData = $('#form-don-hang').serialize();
    
    $('#form-don-hang').myLoading({opacity: true});
   
    $.ajax({
        url: $('#form-don-hang').attr('action'),
        type: 'POST',
        data: formData,
        dataType: 'json',
    })
    .done(function(res) {
        $('#form-don-hang').myUnloading();
        if (res.status == 200) {
            $.when($.pjax.reload({url: currentUrl, method: 'POST', container: clinic.options.pjaxId})).done(function(){
                $('.modal-header').find('.close').trigger('click');
                toastr.success(res.mess, '$tit');
            });
        } else {
            toastr.error(res.mess, '$tit');
        }
    })
    .fail(function(err){
        $('#form-don-hang').myUnloading();
        console.log(err);
    });
   
   return false;
});




JS;
$this->registerJs($script, \yii\web\View::POS_END);
$this->registerCss('
.btn-group .dropdown-menu{padding:0}
.btn-group .dropdown-menu .dropdown-item{padding:.25rem .5rem}
.btn-group .dropdown-menu .dropdown-item.active, .btn-group .dropdown-menu .dropdown-item:active{background-color:#f5f5f5}
.field-phongkhamlichdieutri-tro_thu .dropdown.ui {
    padding: 0.3em 2.1em 0.3em 1em;
}
.field-phongkhamlichdieutri-tro_thu .dropdown.ui input.search {
    margin-top: 0 !important;
    margin-bottom: 0 !important;
    line-height: 1em !important;
    padding-top: 5px !important;
    padding-bottom: 5px !important;
}
')
?>