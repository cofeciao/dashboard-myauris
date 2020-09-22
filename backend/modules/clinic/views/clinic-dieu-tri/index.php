<?php

use common\grid\MyGridView;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\helpers\BackendHelpers;
use backend\modules\user\models\User;
use backend\modules\clinic\models\PhongKhamLichDieuTri;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\clinic\models\search\PhongKhamLichDieuTriSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Lịch điều trị');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Clinic'), 'url' => ['/clinic']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Order'), 'url' => ['/clinic/clinic-order']];
$this->params['breadcrumbs'][] = $this->title;

$user = new User();
$roleName = $user->getRoleName(Yii::$app->user->id);

$css = <<< CSS
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
CSS;
$this->registerCss($css);
$can_update = Yii::$app->user->can(User::USER_DEVELOP) ||
    Yii::$app->user->can('clinic') ||
    Yii::$app->user->can('clinicClinic-dieu-tri') ||
    Yii::$app->user->can('clinicClinic-dieu-triUpdate');
$can_update_tai_kham = Yii::$app->user->can(User::USER_DEVELOP) ||
    Yii::$app->user->can('clinic') ||
    Yii::$app->user->can('clinicClinic-dieu-tri') ||
    Yii::$app->user->can('clinicClinic-dieu-triUpdate-tai-kham');
$can_create_tai_kham = Yii::$app->user->can(User::USER_DEVELOP) ||
    Yii::$app->user->can('clinic') ||
    Yii::$app->user->can('clinicClinic-dieu-tri') ||
    Yii::$app->user->can('clinicClinic-dieu-triCreate-tai-kham');
$can_delete = Yii::$app->user->can(User::USER_DEVELOP) ||
    Yii::$app->user->can('clinic') ||
    Yii::$app->user->can('clinicClinic-dieu-tri') ||
    Yii::$app->user->can('clinicClinic-dieu-triDelete');
$can_actions = $can_update || $can_create_tai_kham || $can_update_tai_kham || $can_delete;
?>

<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content collapse show ">
                    <div class="card-body card-dashboard">
                        <?php Pjax::begin(['id' => 'clinic-dieu-tri', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
                        <?php echo $this->render('_search', ['model' => $searchModel, 'order' => $order, 'listCoSo' => $listCoSo]); ?>
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                            <?= MyGridView::widget([
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
                                    'data-minus' => '{"0":10,"1":".header-navbar","2":"#form-search-dieutri","3":".pager-wrap","4":".footer","5":".content-header"}'
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
                                        'header' => '#',
                                        'headerOptions' => [
                                            'width' => 30,
                                        ],
                                    ],

                                    //                                    doan code column nay co 2 vi tri neu muon cap nhat lai code
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'Actions',
                                        'visible' => $can_actions,
                                        'template' => '{update} {update-tai-kham} {create-tai-kham} {delete}',
                                        'buttons' => [
                                            'update' => function ($url, $model) use ($can_update, $roleName) {
                                                if (!$can_update) return null;
                                                return Html::button(
                                                    '<i class="ft-edit blue"></i>',
                                                    [
                                                        'alt' => 'Thông tin',
                                                        'title' => 'Thông tin',
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
                                            'update-tai-kham' => function ($url, $model) use ($can_update_tai_kham, $roleName) {
                                                if (
                                                    !$can_update_tai_kham ||
                                                    $model->tai_kham == null ||
                                                    (!Yii::$app->user->can(User::USER_DEVELOP) && Yii::$app->user->id != $model->created_by) ||
                                                    $model->last_dieu_tri == PhongKhamLichDieuTri::STATUS_PUBLISHED
                                                ) return null;
                                                return Html::button(
                                                    '<i class="ft-edit red"></i>',
                                                    [
                                                        'alt' => 'Thông tin tái khám',
                                                        'title' => 'Thông tin tái khám',
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
                                            'create-tai-kham' => function ($url, $model) use ($can_create_tai_kham) {
                                                if (
                                                    !$can_create_tai_kham ||
                                                    $model->last_dieu_tri != PhongKhamLichDieuTri::STATUS_PUBLISHED /*||
                                                        ($model->last_dieu_tri == PhongKhamLichDieuTri::STATUS_PUBLISHED && $model->time_end == null)*/
                                                ) return null;
                                                return Html::button(
                                                    '<i class="relative p-icon fa fa-calendar-o green"><i class="fa fa-plus"></i></i>',
                                                    [
                                                        'alt' => 'Tạo lịch tái khám',
                                                        'title' => 'Tạo lịch tái khám',
                                                        'class' => 'btn btn-default',
                                                        'data-pjax' => 0,
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop' => 'static',
                                                        'data-keyboard' => false,
                                                        'data-target' => '#custom-modal',
                                                        'onclick' => 'showModal($(this), "' . Url::toRoute(['create-tai-kham', 'customer_id' => $model->clinicHasOne->id, 'lich_dieu_tri_id' => $model->id]) . '");return false;',
                                                    ]
                                                );
                                            },
                                            'delete' => function ($url, $model) use ($can_delete, $roleName) {
                                                if (!$can_delete || !in_array($roleName, [User::USER_DEVELOP, User::USER_LE_TAN, User::USER_MANAGER_LE_TAN]) || $model->tai_kham == null) return null;
                                                return Html::a('<i class="fa fa-trash"></i>', $url, ['class' => 'btn btn-default red btn-delete']);
                                            },
                                        ],
                                        //                                        'visibleButtons' => [
                                        //                                            'update' => function ($model) {
                                        //                                                return ($model->time_end != null) ? false : true;
                                        //                                            }
                                        //                                        ]
                                        'headerOptions' => [
                                            'width' => 90,
                                        ],
                                    ],

                                    [
                                        'header' => 'Lịch điều trị',
                                        'format' => 'raw',
                                        'headerOptions' => [
                                            'width' => 150,
                                        ],
                                        'value' => function ($model) {
                                            return $model->getThongTinCoBan();
                                        },
                                    ],
                                    // [
                                    //     'attribute' => 'name',
                                    //     'format' => 'raw',
                                    //     'headerOptions' => [
                                    //         'width' => 200,
                                    //     ],
                                    //     'value' => function ($model) {
                                    //         $name = $model->clinicHasOne != null && $model->clinicHasOne->full_name != null ? $model->clinicHasOne->full_name : '';
                                    //         $option = [];
                                    //         if (date('d-m-Y', $model->time_dieu_tri) == date('d-m-Y')) {
                                    //             $option['style'] = 'color: red';
                                    //         }
                                    //         return Html::a($name, '#', array_merge($option, [
                                    //             'data-pjax' => 0,
                                    //             'data-toggle' => 'modal',
                                    //             'data-backdrop' => 'static',
                                    //             'data-keyboard' => false,
                                    //             'data-target' => '#custom-modal',
                                    //             'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['view', 'id' => $model->id]) . '");return false;',
                                    //         ]));
                                    //     },
                                    //     'visible' => in_array($roleName, [
                                    //         \common\models\User::USER_DEVELOP,
                                    //         \common\models\User::USER_ADMINISTRATOR,
                                    //         \common\models\User::USER_MANAGER_LE_TAN,
                                    //         \common\models\User::USER_LE_TAN,
                                    //     ]),
                                    // ],
                                    [
                                        'attribute' => 'phone',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $phone = ($model->clinicHasOne !== null && $model->clinicHasOne->phone !== null) ? $model->clinicHasOne->phone : " ";
                                            return '<div class="td-phone">' .
                                                Html::a(
                                                    '<button class="btn btn-success white btn-sm"><i class="fa fa-phone"></i></button>',
                                                    'javascript:void(0)',
                                                    [
                                                        'onclick' => 'return typeof mycall == \'object\' ? mycall.makeCall(\'' . $phone . '\') : toastr.warning("Không thể kết nối đến hệ thống gọi")',
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
                                        'headerOptions' => [
                                            'width' => 90
                                        ],
                                        'visible' => in_array($roleName, [
                                            \common\models\User::USER_DEVELOP,
                                            \common\models\User::USER_ADMINISTRATOR,
                                            \common\models\User::USER_MANAGER_LE_TAN,
                                            \common\models\User::USER_LE_TAN,
                                        ]),
                                        'headerOptions' => [
                                            'width' => 100,
                                        ],
                                    ],
                                    [
                                        'header' => 'Khách hàng',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            if ($model->clinicHasOne == null) {
                                                return null;
                                            }
                                            $name = $model->clinicHasOne != null && $model->clinicHasOne->full_name != null ? $model->clinicHasOne->full_name : '';
                                            $option = [];
                                            if (date('d-m-Y', $model->time_dieu_tri) == date('d-m-Y')) {
                                                $option['style'] = 'color: red';
                                            }
                                            $tit = Html::a($name, '#', array_merge($option, [
                                                'data-pjax' => 0,
                                                'data-toggle' => 'modal',
                                                'data-backdrop' => 'static',
                                                'data-keyboard' => false,
                                                'data-target' => '#custom-modal',
                                                'onclick' => 'showModal($(this), "' . Url::toRoute(['view', 'id' => $model->id]) . '");return false;',
                                            ]));

                                            return $tit . "<br><span style='font-weight: 600;' >" .
                                                $model->clinicHasOne->customer_code . "</span><br>" .
                                                "Đến: " . date('d-m-Y H:i', $model->clinicHasOne->customer_come);
                                        },
                                        'headerOptions' => [
                                            'width' => 190,
                                        ],
                                        'footerOptions' => [
                                            'class' => 'd-none'
                                        ],
                                        'visible' => in_array($roleName, [
                                            \common\models\User::USER_DEVELOP,
                                            \common\models\User::USER_ADMINISTRATOR,
                                            \common\models\User::USER_MANAGER_LE_TAN,
                                            \common\models\User::USER_LE_TAN,
                                            \common\models\User::USER_MANAGER_BAC_SI,
                                            \common\models\User::USER_BAC_SI,
                                        ]),
                                    ],
                                    [
                                        'header' => 'Đơn hàng',
                                        'format' => 'raw',
                                        'value' => function ($mLichDieuTri) {
                                            $model = $mLichDieuTri->orderHasOne;
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
                                            $mDonHang = $mLichDieuTri->orderHasOne;

                                            return "<span style='font-weight: 600;' >Mã HĐ : " . $order_code . " </span>" .
                                                "<i>(" . date('d-m-Y', $model->created_at) . ")</i><br>" .
                                                $model->getThongTinGoiDichVu() .
                                                "Tạo bởi : <i>" . $userCreate . "</i><br>" .
                                                $mDonHang->showHoanThanh(true) . '<br/>' .
                                                ($mLichDieuTri->tai_kham == null ? '' : ($mLichDieuTri->ekip != null ? '<span class="badge badge-success badge-pill ">Hoàn thành Tái Khám</span>' : '<span class="badge badge-warning badge-pill ">Chưa hoàn thành Tái Khám</span>')) .
                                                $model->viewInfoConfirm();
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
                                    // [
                                    //     'header' => "Dịch vụ",
                                    //     'format' => 'raw',
                                    //     'value' => function ($lichDieuTri) {
                                    //         $model = $lichDieuTri->orderHasOne;
                                    //         return $model->showHoanThanh(true);
                                    //     },
                                    //     'visible' => in_array($roleName, [
                                    //         \common\models\User::USER_DEVELOP,
                                    //         \common\models\User::USER_ADMINISTRATOR,
                                    //         \common\models\User::USER_MANAGER_LE_TAN,
                                    //         \common\models\User::USER_LE_TAN,
                                    //     ]),
                                    // ],
                                    [
                                        'attribute' => 'thao_tac',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->getThaoTac();
                                        }
                                    ],
                                    [
                                        'attribute' => 'ekip',
                                        'value' => function ($model) {
                                            $user = new \common\models\UserProfile();
                                            $fullname = $user->getFullNameBacSi($model->ekip);
                                            if ($fullname == false) {
                                                return null;
                                            }
                                            return $fullname;
                                        },
                                        'visible' => in_array($roleName, [
                                            \common\models\User::USER_DEVELOP,
                                            \common\models\User::USER_ADMINISTRATOR,
                                            \common\models\User::USER_MANAGER_LE_TAN,
                                            \common\models\User::USER_LE_TAN,
                                            \common\models\User::USER_MANAGER_BAC_SI,
                                            \common\models\User::USER_BAC_SI,
                                        ]),
                                    ],
                                    [
                                        'attribute' => 'tro_thu',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->getTroThu();
                                        },
                                    ],

                                    [
                                        'attribute' => 'room_id',
                                        'value' => function ($model) {
                                            if ($model->roomHasOne == null) {
                                                return null;
                                            }
                                            return $model->roomHasOne->fullname;
                                        },
                                        'visible' => in_array($roleName, [
                                            \common\models\User::USER_DEVELOP,
                                            \common\models\User::USER_ADMINISTRATOR,
                                            \common\models\User::USER_MANAGER_LE_TAN,
                                            \common\models\User::USER_LE_TAN,
                                        ]),
                                    ],
                                    //                                    [
                                    //                                        'attribute' => 'co_so',
                                    //                                        'value' => function ($model) {
                                    //                                            if ($model->coSoHasOne == null) {
                                    //                                                return null;
                                    //                                            }
                                    //                                            return 'Cơ sở ' . $model->coSoHasOne->name;
                                    //                                        }
                                    //                                    ],
                                    //                                    [
                                    //                                        'attribute' => 'direct_sale',
                                    //                                        'value' => function ($model) {
                                    //                                            if ($model->clinicHasOne->directsale == null) {
                                    //                                                return null;
                                    //                                            }
                                    //                                            return $model->clinicHasOne->directSaleHasOne->fullname;
                                    //                                        }
                                    //                                    ],
                                    [
                                        'header' => 'Thời gian thực hiện',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $time_start = '';
                                            $time_end = '';
                                            if ($model->time_start != null) {
                                                $time_start = 'BĐ : ' . date('d-m-Y H:i', $model->time_start);
                                            }

                                            if ($model->time_end != null) {
                                                $time_end = 'KT : ' . date('d-m-Y H:i', $model->time_end);
                                            }
                                            return $time_start . '<br>' . $time_end;
                                        },
                                        'visible' => in_array($roleName, [
                                            \common\models\User::USER_DEVELOP,
                                            \common\models\User::USER_ADMINISTRATOR,
                                            \common\models\User::USER_MANAGER_LE_TAN,
                                            \common\models\User::USER_LE_TAN,
                                        ]),
                                        'headerOptions' => [
                                            'width' => "180px"
                                        ],
                                    ],
                                    // [
                                    //     'attribute' => 'time_start',
                                    //     'format' => 'raw',
                                    //     'value' => function ($model) {
                                    //         if ($model->time_start != null) {
                                    //             return date('d-m-Y H:i', $model->time_start);
                                    //         } else {
                                    //             return null;
                                    //             /*return Html::a('Bắt đầu', '#', ['class' => 'btn mr-1 btn-primary btn-sm time-start', 'data-id' => $model->id]);*/
                                    //         }
                                    //     },
                                    //     'visible' => in_array($roleName, [
                                    //         \common\models\User::USER_DEVELOP,
                                    //         \common\models\User::USER_ADMINISTRATOR,
                                    //         \common\models\User::USER_MANAGER_LE_TAN,
                                    //         \common\models\User::USER_LE_TAN,
                                    //     ]),
                                    // ],
                                    // [
                                    //     'attribute' => 'time_end',
                                    //     'format' => 'raw',
                                    //     'value' => function ($model) {
                                    //         if ($model->time_end != null) {
                                    //             return date('d-m-Y H:i', $model->time_end);
                                    //         } else {
                                    //             return null;
                                    //         }
                                    //     },
                                    //     'visible' => in_array($roleName, [
                                    //         \common\models\User::USER_DEVELOP,
                                    //         \common\models\User::USER_ADMINISTRATOR,
                                    //         \common\models\User::USER_MANAGER_LE_TAN,
                                    //         \common\models\User::USER_LE_TAN,
                                    //     ]),
                                    // ],
                                    [
                                        'attribute' => 'danh_gia',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            switch ($model->danh_gia) {
                                                    /*case 0:
                                                    if ($model->time_end == null)
                                                        return Html::a('Đánh giá', Url::to('#'), ['class' => 'btn btn-secondary btn-sm', 'data-pjax' => 0]);
                                                    else
                                                        return Html::a('Đánh giá', Url::to('#'), ['class' => 'btn btn-primary btn-sm danh-gia', 'data-pjax' => 0, 'data-id' => $model->id]);
                                                    break;
                                                case 1:
                                                    return 'Đang đánh giá';
                                                    break;*/
                                                case 2:
                                                    return 'Đã đánh giá';
                                                    break;
                                                default:
                                                    return null;
                                            }
                                        },
                                        'visible' => in_array($roleName, [
                                            \common\models\User::USER_DEVELOP,
                                            \common\models\User::USER_ADMINISTRATOR,
                                            \common\models\User::USER_MANAGER_LE_TAN,
                                            \common\models\User::USER_LE_TAN,
                                        ]),
                                    ],
                                    // [
                                    //     'attribute' => 'thai_do',
                                    //     'format' => 'raw',
                                    //     'value' => function ($model) {
                                    //         return BackendHelpers::getRatings($model->thai_do);
                                    //     },
                                    //     'visible' => in_array($roleName, [
                                    //         \common\models\User::USER_DEVELOP,
                                    //         \common\models\User::USER_ADMINISTRATOR,
                                    //         \common\models\User::USER_MANAGER_LE_TAN,
                                    //         \common\models\User::USER_LE_TAN,
                                    //     ]),
                                    // ],
                                    // [
                                    //     'attribute' => 'chuyen_mon',
                                    //     'format' => 'raw',
                                    //     'value' => function ($model) {
                                    //         return BackendHelpers::getRatings($model->chuyen_mon);
                                    //     },
                                    //     'visible' => in_array($roleName, [
                                    //         \common\models\User::USER_DEVELOP,
                                    //         \common\models\User::USER_ADMINISTRATOR,
                                    //         \common\models\User::USER_MANAGER_LE_TAN,
                                    //         \common\models\User::USER_LE_TAN,
                                    //     ]),
                                    // ],
                                    // [
                                    //     'attribute' => 'tham_my',
                                    //     'format' => 'raw',
                                    //     'value' => function ($model) {
                                    //         return BackendHelpers::getRatings($model->tham_my);
                                    //     },
                                    //     'visible' => in_array($roleName, [
                                    //         \common\models\User::USER_DEVELOP,
                                    //         \common\models\User::USER_ADMINISTRATOR,
                                    //         \common\models\User::USER_MANAGER_LE_TAN,
                                    //         \common\models\User::USER_LE_TAN,
                                    //     ]),
                                    // ],
                                    // 'created_at:date',
                                    // [
                                    //     'attribute' => 'created_by',
                                    //     'value' => function ($model) {
                                    //         $user = new PhongKhamLichDieuTri();
                                    //         $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                                    //         if ($userCreatedBy == false) {
                                    //             return null;
                                    //         }
                                    //         return $userCreatedBy->fullname;
                                    //     }
                                    // ],

                                    // [
                                    //     'class' => 'yii\grid\ActionColumn',
                                    //     'header' => 'Actions',
                                    //     'visible' => in_array($roleName, [
                                    //         \common\models\User::USER_DEVELOP,
                                    //         \common\models\User::USER_ADMINISTRATOR,
                                    //         \common\models\User::USER_MANAGER_LE_TAN,
                                    //         \common\models\User::USER_LE_TAN,
                                    //     ]),
                                    //     'template' => '{update}',
                                    //     'buttons' => [
                                    //         'update' => function ($url, $model) {
                                    //             return Html::button(
                                    //                 '<i class="ft-edit blue"></i>',
                                    //                 [
                                    //                     'class' => 'btn btn-default',
                                    //                     'data-pjax' => 0,
                                    //                     'data-toggle' => 'modal',
                                    //                     'data-backdrop' => 'static',
                                    //                     'data-keyboard' => false,
                                    //                     'data-target' => '#custom-modal',
                                    //                     'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['update', 'id' => $model->id]) . '");return false;',
                                    //                 ]
                                    //             );
                                    //         },
                                    //     ],
                                    // ],
                                    // 'ngay_tao:datetime',
                                    // 'created_at:datetime',
                                    [
                                        'header' => 'Cập nhật gần nhất',
                                        'format' => 'raw',
                                        'attribute' => 'updated_by',
                                        'value' => function ($model) {
                                            $user = new backend\modules\clinic\models\PhongKhamDonHangWThanhToan();
                                            $userCreatedBy = $user->getUserCreatedBy($model->updated_by);
                                            $text = $userCreatedBy->fullname;
                                            return $text . "<br>" . date('d-m-Y h:i', $model->updated_at);
                                        },
                                        'visible' => in_array($roleName, [
                                            \common\models\User::USER_DEVELOP,
                                            \common\models\User::USER_ADMINISTRATOR,
                                            \common\models\User::USER_MANAGER_LE_TAN,
                                            \common\models\User::USER_LE_TAN,
                                            \common\models\User::USER_KE_TOAN,
                                            \common\models\User::USER_MANAGER_KE_TOAN,
                                        ]),
                                    ],
                                    // [
                                    //     'attribute' => 'updated_at',
                                    //     'value' => function ($model) {
                                    //         return $model->updated_at;
                                    //     }
                                    // ],
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

<?php
$url = Url::toRoute(['show-hide']);
$urlDelete = Url::toRoute(['delete']);
$urlChangePageSize = Url::toRoute(['perpage']);
$tit = Yii::t('backend', 'Notification');

$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', 'If delete, you will not be able to recover!');

$urlTimeStart = Url::toRoute('start-time');
$urlTimeEnd = Url::toRoute('end-time');
$urlDanhGia = Url::toRoute('danh-gia');

$script = <<< JS
var clinic = new myGridView();
clinic.init({
    pjaxId: '#clinic-dieu-tri',
    urlChangePageSize: '$urlChangePageSize'
})

var currentUrl = $(location).attr('href');

$('body').on('click', '.danh-gia', function() {
    if (confirm('Bạn muốn đánh giá lịch điều trị này?')) {
        var id = $(this).attr('data-id');
        $.ajax({
            type: "POST",
            cache: false,
            data:{"id":id},
            url: "$urlDanhGia",
            dataType: "json",
            success: function(data) {
                if(data.status == '200') {
                    toastr.success(data.result, 'Thông báo');
                    $.pjax.reload({url: currentUrl, method: 'POST', container: clinic.options.pjaxId});
                } else {
                    toastr.error(data.result, 'Thông báo');
                }
            }
        });
    };
});
$('body').on('click', '.time-end', function() {
    if (confirm('Bạn muốn kết thúc lịch điều trị?')) {
        var id = $(this).attr('data-id');
        $.ajax({
            type: "POST",
            cache: false,
            data:{"id":id},
            url: "$urlTimeEnd",
            dataType: "json",
            success: function(data) {
                if(data.status == 1) {
                    $.when($.pjax.reload({url: currentUrl, method: 'POST', container: clinic.options.pjaxId})).done(function(){
                        toastr.success(data.result, 'Thông báo');
                    });
                } else {
                    toastr.error(data.result, 'Thông báo');
                }
            }
        });
    };
});
$('body').on('click', '.time-start', function() {
    if (confirm('Bạn muốn bắt đầu lịch điều trị?')) {
        var id = $(this).attr('data-id');
        $.ajax({
            type: "POST",
            cache: false,
            data:{"id":id},
            url: "$urlTimeStart",
            dataType: "json",
            success: function(data) {
                if(data.status == true) {
                    $.when($.pjax.reload({url: currentUrl, method: 'POST', container: clinic.options.pjaxId})).done(function(){
                        toastr.success(data.result, 'Thông báo');
                    });
                } else {
                    toastr.error(data.result, 'Thông báo');
                }
            }
        });
    };
});
$('body').on('beforeSubmit', '#clinicDieuTriAjax', function(e) {
   e.preventDefault();
   var currentUrl = $(location).attr('href');
   var formData = $('#clinicDieuTriAjax').serialize();
    
    $('#clinicDieuTriAjax').myLoading({opacity: true});
   
    $.ajax({
        url: $('#clinicDieuTriAjax').attr('action'),
        type: 'POST',
        data: formData,
        dataType: 'json',
    })
    .done(function(res) {
        if (res.status == 200) {
            $.when($.pjax.reload({url: currentUrl, method: 'POST', container: clinic.options.pjaxId})).done(function(){
                $('.modal-header').find('.close').trigger('click');
                toastr.success(res.mess, '$tit');
                $('#clinicDieuTriAjax').myUnloading();
            });
        } else {
            toastr.error(res.mess, '$tit');
            $('#clinicDieuTriAjax').myUnloading();
        }
    })
    .fail(function(err){
        $('#clinicDieuTriAjax').myUnloading();
        console.log(err);
    });
   
   return false;
}).on('click', '.btn-delete', function(e){
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
        var id = $(this).val();
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
        var id = JSON.parse($(this).attr("data-id"));
        var table = $(this).parent().parent();
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
JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>