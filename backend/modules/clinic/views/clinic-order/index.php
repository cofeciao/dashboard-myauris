<?php

use backend\modules\clinic\models\Clinic;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\PhongKhamDonHangWOrder;
use backend\modules\clinic\models\PhongKhamDonHangWThanhToan;
use backend\modules\clinic\models\PhongKhamKhuyenMai;
use backend\modules\setting\models\Dep365CoSo;
use backend\modules\user\models\User;
use common\grid\MyGridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\clinic\models\search\PhongKhamDonHangSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Đơn hàng');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Clinic'), 'url' => ['/clinic']];
$this->params['breadcrumbs'][] = $this->title;

$user = new User();
$roleUser = $user->getRoleName(Yii::$app->user->id);
$roleDev = User::USER_DEVELOP;

$idCustomer = false;
if ($roleUser == $roleDev) {
    $idCustomer = true;
}

$co_so = ArrayHelper::map(Dep365CoSo::getCoSo(), 'id', 'name');

//$a = 3;
$total = $dh_dat_coc + $dh_thanh_toan;
$conno = $dh_tong_tien - $dh_chiet_khau - $total;

$can_update = Yii::$app->user->can($roleDev) ||
    Yii::$app->user->can('clinic') ||
    Yii::$app->user->can('clinicClinic-order') ||
    Yii::$app->user->can('clinicClinic-orderUpdate');
$can_add_payment = Yii::$app->user->can($roleDev) ||
    Yii::$app->user->can('clinic') ||
    Yii::$app->user->can('clinicClinic-order') ||
    Yii::$app->user->can('clinicClinic-orderAdd-payment');
$can_list_payment = Yii::$app->user->can($roleDev) ||
    Yii::$app->user->can('clinic') ||
    Yii::$app->user->can('clinicClinic-order') ||
    Yii::$app->user->can('clinicClinic-orderList-payment');
$can_dieu_tri = Yii::$app->user->can($roleDev) ||
    Yii::$app->user->can('clinic') ||
    Yii::$app->user->can('clinicClinic-order') ||
    Yii::$app->user->can('clinicClinic-orderDieu-tri');
$can_list_dieu_tri = Yii::$app->user->can($roleDev) ||
    Yii::$app->user->can('clinic') ||
    Yii::$app->user->can('clinicClinic-order') ||
    Yii::$app->user->can('clinicClinic-orderList-dieu-tri');
$can_print = Yii::$app->user->can($roleDev) ||
    Yii::$app->user->can('clinic') ||
    Yii::$app->user->can('clinicClinic-order') ||
    Yii::$app->user->can('clinicClinic-orderPrint');
$can_don_labo = Yii::$app->user->can($roleDev) ||
    Yii::$app->user->can('clinic') ||
    Yii::$app->user->can('clinicClinic-order') ||
    Yii::$app->user->can('clinicClinic-orderDon-labo');
$can_actions = $can_update || $can_add_payment || $can_list_payment || $can_dieu_tri || $can_list_dieu_tri || $can_print || $can_don_labo;

$css = <<< CSS
.dropdown i {margin:0}
h5{margin-top:0;margin-bottom:4px;color:#6c757d;font-size:.875rem;}
.dropdown-divider {border-color:#dedede}
.dropdown-item:active,.dropdown-item:hover{background:none;color:inherit}
.dropdown-item a.btn:hover{background-color:#f5f5f5}
CSS;
$this->registerCss($css);
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
                                    $hoan_coc = PhongKhamDonHang::getThanhToanByType($model->id, PhongKhamDonHangWThanhToan::HOAN_COC);
                                    $return = ['data-key' => $model->id];
                                    if ($hoan_coc != null && $hoan_coc != '' && $hoan_coc > 0) {
                                        $return['class'] = 'table-danger';
                                    }
                                    return $return;
                                },
                                'myOptions' => [
                                    'class' => 'grid-content my-content pane-vScroll',
                                    'data-minus' => '{"0":42,"1":".header-navbar","2":".form-search","3":".pager-wrap","4":".footer","5":".grid-footer","6":".content-header"}'
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
                                        'header' => 'STT',
                                        'headerOptions' => [
                                            'width' => 60,
                                        ],
                                        'footerOptions' => [
                                            'colspan' => 3 + ($can_actions || $idCustomer ? 1 : 0) + ($idCustomer == true ? 1 : 0),
                                            'class' => 'text-right font-weight-bold'
                                        ],
                                        'footer' => 'Tổng: '
                                    ],
                                    [
                                        'attribute' => 'id',
                                        'label' => 'ID',
                                        'value' => 'id',
                                        'visible' => $idCustomer,
                                        'headerOptions' => [
                                            'width' => '60px'
                                        ],
                                        'footerOptions' => [
                                            'class' => 'd-none'
                                        ]
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'Actions',
                                        'visible' => $idCustomer || $can_actions,
                                        'template' => '<div class="btn-group dropright" role="group">{actions}</div>',
                                        'buttons' => [
                                            'actions' => function ($url, $model) use ($idCustomer, $can_update, $can_print, $can_don_labo, $can_add_payment, $can_list_payment, $can_dieu_tri, $can_list_dieu_tri) {
                                                $act = '
                                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <div class="dropdown-item">
                                                            <div class="btn-group">';
                                                if ($can_update) {
                                                    $act .= '<a class="btn btn-default" 
                                                                data-pjax="0" 
                                                                data-toggle="modal" 
                                                                data-backdrop="static" 
                                                                data-keyboard="false" 
                                                                data-target="#custom-modal" 
                                                                onclick="showModal($(this), \'' . Url::toRoute(['update', 'id' => $model->primaryKey]) . '\'); return false;">
                                                                    <i class="p-icon fa fa-pencil blue"></i></a>';
                                                }
                                                if ($idCustomer) {
                                                    $act .= '<a class="btn btn-default btn-delete-order" 
                                                                href="' . Url::toRoute(['delete', 'id' => $model->primaryKey]) . '" 
                                                                title="Xóa đơn">
                                                                    <i class="p-icon fa fa-trash red"></i></a>';
                                                }
                                                $act .= '</div><!--<div class="dropdown-divider"></div>
                                                            <h5>Thanh toán</h5>-->
                                                            <div class="btn-group">';
                                                if ($can_add_payment) {
                                                    $act .= '<a class="btn btn-default"
                                                                title="Thanh toán"
                                                                data-pjax="0" 
                                                                data-toggle="modal" 
                                                                data-backdrop="static" 
                                                                data-keyboard="false" 
                                                                data-target="#custom-modal" 
                                                                onclick="showModal($(this), \'' . Url::toRoute(['add-payment', 'id' => $model->primaryKey]) . '\'); return false;">
                                                                    <i class="p-icon fa fa-credit-card green"></i></a>';
                                                }
                                                if ($can_list_payment) {
                                                    $act .= '<a class="btn btn-default" 
                                                                data-pjax="0" 
                                                                href="' . Url::toRoute(['/clinic/clinic-payment', 'order_id' => $model->primaryKey]) . '" 
                                                                title="Lịch sử thanh toán">
                                                                    <i class="p-icon fa fa-credit-card red"></i></a>';
                                                }
                                                $act .= '</div><!--<div class="dropdown-divider"></div>
                                                            <h5>Điều trị</h5>-->
                                                            <div class="btn-group">';
                                                if ($can_dieu_tri) {
                                                    $act .= '<a class="btn btn-default"
                                                                title="Lịch điều trị"
                                                                data-pjax="0" 
                                                                data-toggle="modal" 
                                                                data-backdrop="static" 
                                                                data-keyboard="false" 
                                                                data-target="#custom-modal" 
                                                                onclick="showModal($(this), \'' . Url::toRoute(['dieu-tri', 'id' => $model->primaryKey]) . '\'); return false;">
                                                                    <i class="relative p-icon fa fa-calendar-o green"><i class="fa fa-plus"></i></i></a>';
                                                }
                                                if ($can_list_dieu_tri) {
                                                    $act .= '<a class="btn btn-default" 
                                                                data-pjax="0" 
                                                                href="' . Url::toRoute(['/clinic/clinic-dieu-tri', 'order_id' => $model->primaryKey]) . '" 
                                                                title="DS Lịch điều trị">
                                                                    <i class="relative p-icon fa fa-calendar-o red"><i class="fa fa-search"></i></i></a>';
                                                }
                                                $act .= '</div><!--<div class="dropdown-divider"></div>
                                                            <h5></h5>-->
                                                            <div class="btn-group">';
                                                if ($can_print) {
                                                    $act .= '<a class="btn btn-default print-order" 
                                                                href="javascript:void(0)" 
                                                                title="In đơn hàng"
                                                                data-id="' . $model->primaryKey . '"
                                                                data-href="' . Url::toRoute(['print-order', 'id' => $model->primaryKey]) . '">
                                                                    <i class="p-icon fa fa-print blue"></i></a>';
                                                }
                                                if ($can_don_labo) {
                                                    $act .= '<a class="btn btn-default print-order" 
                                                                href="' . Url::toRoute(['/labo/labo-don-hang/create-don', 'don_id' => $model->primaryKey]) . '" 
                                                                title="Tạo đơn Labo"
                                                                target="_blank"
                                                                data-pjax="0">
                                                                    <i class="p-icon fa fa-clipboard blue"></i></a>';
                                                }
                                                $act .= '
                                                            </div>
                                                        </div>
                                                    </div>
                                                ';
                                                return $act;
                                            },
                                            /*'delete' => function ($url, $model) {
                                                return Html::a('<i class="fa fa-trash"></i>', $url, ['class' => 'btn btn-default red btn-delete-order']);
                                            }*/
                                        ],
                                        'headerOptions' => [
                                            'width' => 80
                                        ],
                                        'footerOptions' => [
                                            'class' => 'd-none'
                                        ]
                                    ],
                                    /*[
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'Actions',
                                        'visible' => $can_actions,
                                        'template' => '<div class="btn-group dropright" role="group">{actions} {update} {add-payment} {list-payment} {dieu-tri} {list-dieu-tri} {print} {don-labo}</div>',
                                        'buttons' => [
                                            'update' => function ($url, $model) use ($can_update) {
                                                if (!$can_update) return null;
                                                return Html::button(
                                                    '<i class="ft-edit blue"></i>',
                                                    [
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
                                            'add-payment' => function ($url) use ($can_add_payment) {
                                                if (!$can_add_payment) return null;
                                                return Html::button('<i class="p-icon fa fa-credit-card green"></i>', [
                                                    'title' => 'Thanh toán',
                                                    'class' => 'btn btn-default',
                                                    'data-pjax' => 0,
                                                    'data-toggle' => 'modal',
                                                    'data-backdrop' => 'static',
                                                    'data-keyboard' => false,
                                                    'data-target' => '#custom-modal',
                                                    'onclick' => 'showModal($(this), "' . $url . '");return false;',
                                                ]);
                                            },
                                            'list-payment' => function ($url, $model) use ($can_list_payment) {
                                                if (!$can_list_payment) return null;
                                                return Html::a('<i class="p-icon fa fa-credit-card red"></i>', ['/clinic/clinic-payment', 'order_id' => $model->primaryKey], [
                                                    'title' => 'Lịch sử thanh toán',
                                                    'class' => 'btn btn-default',
                                                    'data-pjax' => 0,
                                                ]);
                                            },
                                            'dieu-tri' => function ($url) use ($can_dieu_tri) {
                                                if (!$can_dieu_tri) return null;
                                                return Html::button(
                                                    '<i class="relative p-icon fa fa-calendar-o green"><i class="fa fa-plus"></i></i>',
                                                    [
                                                        'title' => 'Lịch điều trị',
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
                                            'list-dieu-tri' => function ($url, $model) use ($can_list_dieu_tri) {
                                                if (!$can_list_dieu_tri) return null;
                                                return Html::a('<i class="relative p-icon fa fa-calendar-o red"><i class="fa fa-search"></i></i>', ['/clinic/clinic-dieu-tri', 'order_id' => $model->primaryKey], [
                                                    'title' => 'Danh sách lịch điều trị',
                                                    'class' => 'btn btn-default',
                                                    'data-pjax' => 0,
                                                ]);
                                            },
                                            'print' => function ($url, $model) use ($can_print) {
                                                if (!$can_print) return null;
                                                return Html::a('<i class="p-icon ft-printer blue"></i>', 'javascript:void(0)', [
                                                    'title' => 'In đơn hàng',
                                                    'class' => 'btn btn-default print-order',
                                                    'data-id' => $model->primaryKey,
                                                    'data-href' => Url::toRoute(['print-order', 'id' => $model->primaryKey])
                                                ]);
                                            },
                                            'don-labo' => function ($url, $model) use ($can_don_labo) {
                                                if (!$can_don_labo) return null;
                                                if (Yii::$app->user->can(USER::USER_LE_TAN) || Yii::$app->user->can(USER::USER_KE_TOAN)) {
                                                    return Html::a('<i class="p-icon relative ft-clipboard blue"></i>', ['/labo/labo-don-hang/create-don', 'don_id' => $model->primaryKey], [
                                                        'title' => 'Tạo đơn Labo',
                                                        'class' => 'btn btn-default',
                                                        'data-pjax' => 0,
                                                        'target' => "_blank",
                                                    ]);
                                                }
                                            }
                                        ],
                                        'headerOptions' => [
                                            'width' => 240
                                        ],
                                        'footerOptions' => [
                                            'class' => 'd-none'
                                        ]
                                    ],*/
                                    [
                                        'attribute' => 'name',
                                        'format' => 'raw',
                                        'headerOptions' => [
                                            'width' => 250,
                                        ],
                                        'value' => function ($model) use ($co_so) {
                                            if ($model->clinicHasOne == null) return null;
                                            $tit = $model->clinicHasOne->full_name == null ? $model->clinicHasOne->forename : $model->clinicHasOne->full_name;

                                            $nickName = Html::a($tit, 'javascript:void(0)', [
                                                'data-pjax' => 0,
                                                'data-toggle' => 'modal',
                                                'data-backdrop' => 'static',
                                                'data-keyboard' => false,
                                                'data-target' => '#custom-modal',
                                                'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['view', 'id' => $model->id]) . '");return false;',
                                            ]);

                                            if (Yii::$app->user->can(USER::USER_ADMINISTRATOR)) {
                                                $style = 'display:inline-block';
                                                $class = '';
                                                $aCoSo = ArrayHelper::map(Dep365CoSo::getCoSo(), 'id', 'name');
                                                if ($model->co_so !== null && array_key_exists($model->co_so, $aCoSo)) {
                                                    $text = $aCoSo[$model->co_so];
                                                } else {
                                                    $style = 'display:inline-block;color:red;font-weight:bold;';
                                                    $text = 'not-set';
                                                }
                                                $data = PhongKhamDonHang::getEdittableCoSo($model->co_so);
                                                $text = '<div style="' . $style . '" data-option="coso" class="edittable-co-so ' . $class . '" myedit-options=\'' . $data . '\'>' . $text . '</div>';
                                            } else {
                                                if ($model->coSoHasOne == null) {
                                                    $text = 'not-set';
                                                } else {
                                                    $text = $model->coSoHasOne->name;
                                                }
                                            }

                                            $user = new backend\modules\clinic\models\PhongKhamDonHang();
                                            $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                                            if ($userCreatedBy == false) {
                                                $userCreate = '';
                                            }
                                            $userCreate = $userCreatedBy->fullname;

                                            if ($model->created_at == null) {
                                                $createTime = '';
                                            }
                                            $createTime = date('d-m-Y H:i:s', $model->created_at);

                                            return $nickName . ' - ' . $text
                                                . '<br> Mã KH: <span style="font-weight:600">' . $model->clinicHasOne->customer_code . '</span>'
                                                . '<br> Mã hóa đơn: <span style="font-weight:600">' . $model->order_code . '</span>'
                                                . '<br> <i>Tạo bởi: ' . $userCreate . ' <br>(' . $createTime . ')</i>';
                                        },
                                        'footerOptions' => [
                                            'class' => 'd-none'
                                        ]
                                    ],
                                    [
                                        'attribute' => 'phone_number',
                                        'label' => 'SDT',
                                        'format' => 'raw',
                                        'headerOptions' => [
                                            'width' => 100,
                                        ],
                                        'value' => function ($model) {
                                            if ($model->clinicHasOne == null) {
                                                return null;
                                            }
                                            return '<div class="td-phone">' .
                                                Html::a(
                                                    '<button class="btn btn-success white btn-sm"><i class="fa fa-phone"></i></button>',
                                                    'javascript:void(0)',
                                                    [
                                                        'onclick' => 'return typeof mycall == \'object\' ? mycall.makeCall(\'' . $model->clinicHasOne->phone . '\') : toastr.warning("Không thể kết nối đến hệ thống gọi")',
                                                        'title' => 'Gọi'
                                                    ]
                                                ) .
                                                Html::button('<i class="fa fa-copy"></i>', [
                                                    'class' => 'btn btn-primary white btn-sm copy-data',
                                                    'data-phone' => $model->clinicHasOne->phone,
                                                    'data-copy' => '{"el_copy":"attr-copy","data_copy":"data-phone","success":"toastr.success(\"Đã copy số điện thoại!\")"}',
                                                    'data-pjax' => 0,
                                                    'title' => 'Copy số điện thoại'
                                                ]);
                                        },
                                        'footerOptions' => [
                                            'class' => 'd-none'
                                        ]
                                    ],

                                    // Nghia fix code
                                    // [
                                    //     'attribute' => 'clinic_code',
                                    //     'header' => 'Chi tiết đơn hàng',
                                    //     'format' => 'raw',
                                    //     'value' => function ($model) {
                                    //         $aWOrderHasMany = $model->phongKhamDonHangWOrderHasMany;
                                    //         $result = "";
                                    //         foreach ($aWOrderHasMany as $mWOrder) {

                                    //             $code = Html::a('Tạo code bảo hành', 'javascript:void(0)', [
                                    //                 'data-pjax' => 0,
                                    //                 'data-toggle' => 'modal',
                                    //                 'data-backdrop' => 'static',
                                    //                 'data-keyboard' => false,
                                    //                 'data-target' => '#custom-modal',
                                    //                 'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['create-code-bao-hanh', 'don_hang_id' => $model->id, 'worder_id' => $mWOrder->id]) . '");return false;',
                                    //             ]);

                                    //             $item = $mWOrder->sanPhamHasOne->name . $code . "<br>";
                                    //             $result .= $item;
                                    //         }

                                    //         return $result;
                                    //     },
                                    //     // 'footerOptions' => [
                                    //     //     'class' => 'd-none'
                                    //     // ],
                                    //     'headerOptions' => [
                                    //         'width' => "250px"
                                    //     ],
                                    //     'footer' => '',
                                    // ],


                                    /*[
                                        'attribute' => 'clinic_code',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            if ($model->clinicHasOne == null) {
                                                return null;
                                            }
                                            return $model->clinicHasOne->customer_code;
                                        },
                                        'footerOptions' => [
                                            'class' => 'd-none'
                                        ]
                                    ],*/
                                    /*[
                                        'attribute' => 'order_code',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            if ($model->order_code == null) {
                                                return null;
                                            }
                                            return $model->order_code;
                                        },
                                        'footerOptions' => [
                                            'class' => 'd-none'
                                        ]
                                    ],*/
                                    [
                                        'attribute' => 'total',
                                        'label' => 'Tổng tiền',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $dh = new PhongKhamDonHangWOrder();
                                            $thanhTien = $dh->getMoneyCustomer($model->id);
                                            return number_format($thanhTien, 0, ',', '.') . $model->viewInfoConfirm();
                                        },
                                        'headerOptions' => [
                                            'width' => "170px"
                                        ],
                                        'footer' => $dh_tong_tien <= 0 ? 0 : number_format($dh_tong_tien, 0, '', '.'),
                                    ],
                                    [
                                        'attribute' => 'chiet_khau',
                                        'value' => function ($model) {
                                            if ($model->chiet_khau == null || $model->chiet_khau == '') {
                                                return null;
                                            }
                                            return number_format($model->chiet_khau, 0, '', '.');
                                        },
                                        'contentOptions' => [
                                            'class' => ''
                                        ],
                                        'footer' => $dh_chiet_khau <= 0 ? 0 : number_format($dh_chiet_khau, 0, '', '.')
                                    ],
                                    [
                                        'attribute' => 'dh_thanh_tien',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $dh = new PhongKhamDonHangWOrder();
                                            $thanh_tien = $dh->getMoneyCustomer($model->id);
                                            return number_format($thanh_tien - $model->chiet_khau, 0, ',', '.');
                                        },
                                        'headerOptions' => [
                                            'title' => 'Thành tiền = TOTAL - Chiết khấu'
                                        ],
                                        'contentOptions' => [
                                            'class' => 'font-weight-bold'
                                        ],
                                        'footerOptions' => [
                                            'class' => 'font-weight-bold'
                                        ],
                                        'footer' => $dh_tong_tien - $dh_chiet_khau <= 0 ? 0 : number_format(($dh_tong_tien - $dh_chiet_khau), 0, '', '.')
                                    ],
                                    [
                                        'attribute' => 'dat_coc',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $tt = new PhongKhamDonHangWThanhToan();
                                            $dat_coc = $tt->getDatCocByOrder($model->id);
                                            if ($dat_coc == 0) {
                                                return null;
                                            }
                                            return number_format($dat_coc, 0, ',', '.');
                                        },
                                        'contentOptions' => [
                                            'class' => ''
                                        ],
                                        'footer' => number_format($dh_dat_coc, 0, '', '.')
                                    ],
                                    [
                                        'attribute' => 'thanh_toan',
                                        'label' => 'Thanh toán',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $tt = new PhongKhamDonHangWThanhToan();
                                            $thanh_toan = $tt->getThanhToanByOrder($model->id);
                                            if ($thanh_toan == null) {
                                                return null;
                                            }
                                            return number_format($thanh_toan, 0, ',', '.');
                                        },
                                        'contentOptions' => [
                                            'class' => ''
                                        ],
                                        'footer' => number_format($dh_thanh_toan, 0, '', '.')
                                    ],
                                    [
                                        'attribute' => 'tien_thu',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            $tt = new PhongKhamDonHangWThanhToan();
                                            $dat_coc = $tt->getDatCocByOrder($model->id);
                                            $thanh_toan = $tt->getThanhToanByOrder($model->id);
                                            if ($dat_coc + $thanh_toan == 0) {
                                                return null;
                                            }
                                            return '<span style="color: #0E7E12">' . number_format($dat_coc + $thanh_toan, 0, ',', '.') . '</span>';
                                        },
                                        'footer' => '<span style="color: #0E7E12">' . number_format($total, 0, '', '.') . '</span>'
                                    ],
                                    [
                                        'attribute' => 'con_no',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $dh = new PhongKhamDonHangWOrder();
                                            $tt = new PhongKhamDonHangWThanhToan();
                                            $thanh_tien = $dh->getMoneyCustomer($model->id);
                                            $dat_coc = $tt->getDatCocByOrder($model->id);
                                            $thanh_toan = $tt->getThanhToanByOrder($model->id);
                                            $con_no = $thanh_tien - $model->chiet_khau - ($dat_coc + $thanh_toan);
                                            if ($con_no == 0) {
                                                return '<span style="color: #0E7E12">' . $con_no . '</span>';
                                            }
                                            return '<span style="color: red">' . number_format($con_no, 0, ',', '.') . '</span>';
                                        },
                                        'contentOptions' => [
                                            'class' => ''
                                        ],
                                        'footer' => ($conno <= 0 ? '<span style="color: #0E7E12">' : '<span style="color: red">') . number_format(($conno <= 0 ? 0 : $conno), 0, '', '.') . '</span>'
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
                                            'colspan' => 2
                                        ]
                                    ],
                                    [
                                        'attribute' => 'permission_user',
                                        'label' => 'Online',
                                        'value' => function ($model) {
                                            if ($model->clinicHasOne == null || $model->clinicHasOne->is_customer_who == 2) {
                                                return null;
                                            }
                                            $userProfile = new Clinic();
                                            return $userProfile->getNhanVienTuVan($model->clinicHasOne->permission_user);
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
$urlValidatePrintOrder = Url::toRoute(['validate-print-order']);
$urlValidatePrintPayment = Url::toRoute(['validate-print-payment']);
$urlGetPriceSP = \yii\helpers\Url::toRoute('get-price-san-pham');
$urlLoadSanPham = Url::toRoute(['/clinic/clinic-san-pham/danh-sach-san-pham']);
$urlPrintOrder = Url::toRoute(['/clinic/clinic-order/print-order', 'id' => '']);
$urlPrintPayment = Url::toRoute(['/clinic/clinic-payment/print-payment', 'id' => '']);
$urlCheckDichVu = Url::toRoute(['/clinic/lich-hen/check-dich-vu']);

$urlGetPriceKhuyenMai = Url::toRoute(['/clinic/phong-kham-khuyen-mai/get-price-khuyen-mai']);

$urlDH = Url::toRoute('check-coso');


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
    type_percent = '$type_percent',
    urlGetPriceKhuyenMai = '$urlGetPriceKhuyenMai';
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
}).on('beforeSubmit', 'form#form-dieu-tri', function(e) {
    // Cập nhật lịch điều trị trong clinic
    e.preventDefault();
    let formData = $('#form-dieu-tri').serialize();
    
    $('#form-dieu-tri').myLoading({opacity: true});
    
    $.ajax({
        url: $('#form-dieu-tri').attr('action'),
        type: 'POST',
        dataType: 'json',
        data: formData,
    })
    .done(function(res) {
        if (res.status == 1) {
            $.when($.pjax.reload({url: window.location.href, method: 'POST', container: clinic.options.pjaxId})).done(function(){
                $('.modal-header').find('.close').trigger('click');
                toastr.success(res.result, '$tit');
            });
        } else {
            toastr.error(res.result, '$tit');
            $('#form-dieu-tri').myUnloading();
        }
    })
    .fail(function(err){
        $('#form-dieu-tri').myUnloading();
    });
    
    return false;
}).on('beforeSubmit', 'form#form-payment', function(e){
    e.preventDefault();
    let formData = $('#form-payment').serialize();
    $('#form-payment').myLoading({
        opacity: true
    });
    $.ajax({
        type: 'POST',
        url: $('#form-payment').attr('action'),
        dataType: 'json',
        data: formData
    }).done(function(res) {
        if(res.code == 200){
            $.when($.pjax.reload({url: window.location.href, method: 'POST', container: clinic.options.pjaxId})).done(function(){
//                if (res.order_id != null) {
//                    var printUrl = '$urlPrintPayment' + res.order_id;
//                    window.open(printUrl, '', 'width=800,height=600,left=0,top=0,toolbar=0,scrollbars=0,status=0');
//                }
                if (res.phong_kham_don_hang_id != null) {
                    var printUrl = '$urlPrintOrder' + res.phong_kham_don_hang_id;
                    window.open(printUrl, '', 'width=800,height=600,left=0,top=0,toolbar=0,scrollbars=0,status=0');
                }
                $('#custom-modal').find('.close').trigger('click');
                toastr.success(res.result, 'Thông báo');
            });
        } else {
            $('#form-payment').myUnloading();
            toastr.error(res.msg, 'Thông báo');
        }
    }).fail(function(err) {
        $('#form-payment').myUnloading();
        console.log('Submit form payment fail', err);
    })
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
}).on('click', '.print-order', function () {
    let id = $(this).data('id');
    let printUrl = $(this).data('href');
    $.ajax({
        url: '$urlValidatePrintOrder',
        type: 'GET',
        data: {id: id}
    })
    .done(function (data) {
        if (data == null) {
            window.open(printUrl, '', 'width=800,height=600,left=0,top=0,toolbar=0,scrollbars=0,status=0');
        } else if (data.status == 400) {
            toastr.error(data.mess, 'Thông báo');
            return false;
        }
    });
    
}).on('click', '.print-payment', function () {
    let id = $(this).data('id');
    let printUrl = $(this).data('href');
    $.ajax({
        url: '$urlValidatePrintPayment',
        type: 'GET',
        data: {id: id}
    })
    .done(function (data) {
        if (data == null) {
            window.open(printUrl, '', 'width=800,height=600,left=0,top=0,toolbar=0,scrollbars=0,status=0');
        } else if (data.status == 400) {
            toastr.error(data.mess, 'Thông báo');
            return false;
        }
        
    });
}).on('change', '.sl-sp', function() {
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
}).on('click', '.btn-delete-order', function(e){
    e.preventDefault();
    var url = $(this).attr('href') || null;
    if(url != null){
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
                $('#clinicOrder').myLoading({
                    opacity: true
                });
                $.ajax({
                    type: 'POST',
                    url: url,
                    dataType: 'json',
                    data: {}
                }).done(function(res){
                    if(res.status == 'success'){
                        toastr.success('$deleteSuccess');
                        $.pjax.reload({url: window.location.href, method: 'POST', container: clinic.options.pjaxId});
                    } else {
                        toastr.error('$deleteDanger');
                        $('#clinicOrder').myUnloading();
                    }
                }).fail(function(f){
                    console.log('delete fail');
                    toastr.error('Xoá thất bại');
                    $('#clinicOrder').myUnloading();
                });
            }
        });
    }
    return false;
});

$(document).ready(function () {
    $('.check-toggle').change(function () {
        let id = $(this).val();
        $.post('$url', {
            id: id
        }, function (result) {
            if (result == 1) {
                toastr.success('$resultSuccess', '$tit');
            }
            if (result == 0) {
                toastr.error('$resultDanger', '$tit');
            }
        });
    });
    
    $('.confirm-color').on('click', function (e) {
        e.preventDefault();
        let id = JSON.parse($(this).attr("data-id")),
            table = $(this).closest('tr'),
            currentUrl = $(location).attr('href');
        
        try {    
            swal({
                title: "$data_title",
                text: "$data_text",
                icon: "warning",
                showCancelButton: true,
                buttons: {
                    cancel: {
                        text: "No, cancel plx!",
                        value: null,
                        visible: true,
                        className: "btn-warning",
                        closeModal: true,
                    },
                    confirm: {
                        text: "Yes, delete it!",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true
                    }
                }
            }).then(isConfirm => {
                if (isConfirm) {
                    $.ajax({
                        type: "POST",
                        cache: false,
                        data: {
                            "id": id
                        },
                        url: "$urlDelete",
                        dataType: "json",
                        success: function (data) {
                            if (data.status == 'success') {
                                toastr.success('$deleteSuccess', '$tit');
                                table.slideUp("slow");
                                $.pjax.reload({url: currentUrl, method: 'POST', container: clinic.options.pjaxId});
                            }
                            if (data.status == 'failure')
                                swal("NotAllow", "$deleteDanger", "error");
                            if (data.status == 'exception')
                                swal("NotAllow", "$deleteDanger", "error");
                        }
                    });
    
                }
    
            });
        } catch (e) {
            alert(e); //check tosee any errors
        }
    });
});


// thay doi co so
var myeditor = new myEditor();
myeditor.init({
    element: '.edittable-co-so', /* id or class of element */
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