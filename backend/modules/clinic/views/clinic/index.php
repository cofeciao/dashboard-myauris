<?php

use backend\components\MyComponent;
use backend\modules\appmyauris\models\AppMyaurisCustomerLog;
use backend\modules\clinic\models\PhongKhamKhuyenMai;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\modules\user\models\User;
use common\grid\MyGridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use backend\modules\setting\models\Dep365CoSo;

$this->title = Yii::t('backend', 'Customer');
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

$arr = Yii::$app->controller->module->params['customerField'];
foreach ($arr as $key => $val) {
    $$key = MyComponent::getCookies($key) !== false ? MyComponent::getCookies($key) : 1;
}

$aCustomeComeAccept = Dep365CustomerOnlineCome::getCustomerOnlineComeArrayAccept();

$can_render_and_update = Yii::$app->user->can($roleDev) ||
    Yii::$app->user->can('clinic') ||
    Yii::$app->user->can('clinicClinic') ||
    Yii::$app->user->can('clinicClinicRender-and-update');
$can_order_customer = Yii::$app->user->can($roleDev) ||
    Yii::$app->user->can('clinic') ||
    Yii::$app->user->can('clinicClinic') ||
    Yii::$app->user->can('clinicClinicOrder-customer');
$can_list_order = Yii::$app->user->can($roleDev) ||
    Yii::$app->user->can('clinic') ||
    Yii::$app->user->can('clinicClinic') ||
    Yii::$app->user->can('clinicClinicList-order');
$can_bao_hanh = Yii::$app->user->can($roleDev) ||
    Yii::$app->user->can('clinic') ||
    Yii::$app->user->can('clinicClinic') ||
    Yii::$app->user->can('clinicClinicBao-hanh');
$can_editable = Yii::$app->user->can($roleDev) ||
    Yii::$app->user->can('clinic') ||
    Yii::$app->user->can('clinicClinic') ||
    Yii::$app->user->can('clinicClinicCheck-letan');
$can_tai_kham = Yii::$app->user->can($roleDev) ||
    Yii::$app->user->can('clinic') ||
    Yii::$app->user->can('clinicClinic-dieu-tri') ||
    Yii::$app->user->can('clinicClinic-dieu-triCreate-tai-kham');
$can_actions = $can_render_and_update || $can_order_customer || $can_list_order || $can_bao_hanh || $can_tai_kham;
?>

<section id="dom">
    <div class="row">
        <div class="col-12">

            <?php
            if (Yii::$app->session->hasFlash('alert-bao-hanh')) {
            ?>
                <div class="alert <?= Yii::$app->session->getFlash('alert-bao-hanh')['class']; ?> alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <?= Yii::$app->session->getFlash('alert-bao-hanh')['body']; ?>
                </div>
            <?php
            }
            ?>

            <div class="card">
                <div class="card-content collapse show customer-index">
                    <div class="card-body card-dashboard">
                        <div class="btn-add-campaign clearfix d-none" style="margin-top:0px;position:relative; margin-bottom: 10px">
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
                                    'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['create']) . '");return false;',
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
                                    Yii::$app->controller->renderPartial('_hiddenField') .
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
                                        'header' => '#',
                                        'headerOptions' => [
                                            'width' => 35,
                                        ],
                                        'contentOptions' => [
                                            'style' => 'line-height:70px',
                                        ]
                                    ],
                                    [
                                        'attribute' => 'id',
                                        'visible' => $idCustomer,
                                        'value' => 'id',
                                        'headerOptions' => [
                                            'width' => 70,
                                        ],
                                        'contentOptions' => [
                                            'style' => 'line-height:70px',
                                        ]
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'Actions',
                                        'visible' => $can_actions,
                                        'template' => '<div class="btn-group" role="group">{render-and-update} {order-customer} {list-order} {tai-kham} {bao-hanh}</div>',
                                        'buttons' => [
                                            'render-and-update' => function ($url) use ($can_render_and_update) {
                                                if (!$can_render_and_update) return null;
                                                return Html::button(
                                                    '<i class="p-icon ft-edit blue"></i>',
                                                    [
                                                        'title' => 'Cập nhật',
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
                                            'order-customer' => function ($url) use ($can_order_customer) {
                                                if (!$can_order_customer) return null;
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
                                            'list-order' => function ($url, $model) use ($can_list_order) {
                                                if (!$can_list_order) return null;
                                                return Html::a('<i class="p-icon relative ft-clipboard red"><i class="fa fa-search"></i></i>', ['/clinic/clinic-order', 'customer_id' => $model->primaryKey], [
                                                    'title' => 'Danh sách đơn hàng',
                                                    'class' => 'btn btn-default',
                                                    'data-pjax' => 0,
                                                ]);
                                            },
                                            'tai-kham' => function ($url, $model) use ($can_tai_kham) {
                                                if (!$can_tai_kham || count($model->lastDieuTriHasMany) <= 0) return null;
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
                                                        'onclick' => 'showModal($(this), "' . Url::toRoute(['/clinic/clinic-dieu-tri/create-tai-kham', 'customer_id' => $model->id]) . '");return false;',
                                                    ]
                                                );
                                            }
                                            // 'bao-hanh' => function ($url, $model) use ($aCustomeComeAccept, $can_bao_hanh) {
                                            //     if (!$can_bao_hanh || !array_key_exists($model->customer_come_time_to, $aCustomeComeAccept)) return null;
                                            //     return Html::a('<i class="p-icon relative ft-clipboard blue"><i class="fa fa-wrench"></i></i>', ['/clinic/clinic/bao-hanh', 'customer_id' => $model->primaryKey], [
                                            //         'title' => 'Bảo hành',
                                            //         'class' => 'btn btn-default',
                                            //         'data-pjax' => 0,
                                            //         'target' => "_blank",
                                            //     ]);
                                            // }
                                            // 'cham-soc' => function ($url, $model) {
                                            //     return Html::button(
                                            //         '<i class="p-icon relative ft-user-check blue"></i>',
                                            //         [
                                            //             'title' => 'Chăm sóc',
                                            //             'class' => 'btn btn-default',
                                            //             'data-pjax' => 0,
                                            //             'data-toggle' => 'modal',
                                            //             'data-backdrop' => 'static',
                                            //             'data-keyboard' => false,
                                            //             'data-target' => '#custom-modal',
                                            //             'onclick' => 'showModal($(this), "' . $url . '");return false;',
                                            //         ]
                                            //     );
                                            // }
                                        ],
                                        'headerOptions' => [
                                            'width' => 140,
                                        ],
                                        'contentOptions' => [
                                            'style' => 'line-height:65px',
                                        ]
                                    ],


                                    // chuc nang cham soc khach hang
                                    // [
                                    //     'class' => 'yii\grid\ActionColumn',
                                    //     'header' => 'CSKH',
                                    //     'visible' => $can_render_and_update || $can_order_customer || $can_list_order || $can_bao_hanh,
                                    //     'template' => '<div class="btn-group" role="group"> {cham-soc}</div>',
                                    //     'buttons' => [
                                    //         'cham-soc' => function ($url, $model) {
                                    //             // $cskhQuanLy = $model->cskhQuanLyHasOne;
                                    //             // $userCskhHasOne = ($cskhQuanLy !== null) ? (($cskhQuanLy->userCskhHasOne !== null) ? $cskhQuanLy->userCskhHasOne->fullname  : '') : '';
                                    //             return  Html::button(
                                    //                 '<i class="p-icon ft-user-check blue"></i>',
                                    //                 [
                                    //                     'title' => 'Chăm sóc',
                                    //                     'class' => 'btn btn-default',
                                    //                     'data-pjax' => 0,
                                    //                     'data-toggle' => 'modal',
                                    //                     'data-backdrop' => 'static',
                                    //                     'data-keyboard' => false,
                                    //                     'data-target' => '#custom-modal',
                                    //                     'onclick' => 'showModal($(this), "' . $url . '");return false;',
                                    //                 ]
                                    //             );
                                    //         }
                                    //     ],
                                    //     'headerOptions' => [
                                    //         'width' => 50,
                                    //     ],
                                    //     'contentOptions' => [
                                    //         // 'style' => 'line-height:65px',
                                    //     ],
                                    //     'visible' => in_array($roleUser, [
                                    //         \common\models\User::USER_DEVELOP,
                                    //         \common\models\User::USER_MANAGER,
                                    //         \common\models\User::USER_DATHEN,
                                    //     ]),
                                    // ],


                                    [
                                        'attribute' => 'avatar',
                                        'header' => 'Avatar',
                                        'format' => 'raw',
                                        'visible' => $avatar,
                                        'headerOptions' => [
                                            'width' => 90,
                                        ],
                                        'value' => function ($model) {
                                            if ($model->avatar == null || !file_exists(Yii::$app->basePath . '/web/uploads/avatar/70x70/' . $model->avatar)) {
                                                $avatar = '/local/default/avatar-default.png';
                                            } else {
                                                $avatar = '/uploads/avatar/70x70/' . $model->avatar;
                                            }
                                            return Html::img($avatar);
                                        },
                                    ],
                                    // [
                                    //     'attribute' => 'name',
                                    //     'format' => 'raw',
                                    //     'visible' => $nickName,
                                    //     'value' => function ($model) {
                                    //         return Html::a($model->name, 'javascript:void(0)', [
                                    //             'title' => $model->name,
                                    //             'data-pjax' => 0,
                                    //             'data-toggle' => 'modal',
                                    //             'data-backdrop' => 'static',
                                    //             'data-keyboard' => false,
                                    //             'data-target' => '#custom-modal',
                                    //             'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['/clinic/clinic/view', 'id' => $model->id]) . '");return false;',
                                    //         ]) . "<br>" . AppMyaurisCustomerLog::showButtonViewDonHang($model->id);
                                    //     }
                                    // ],
                                    // [
                                    //     'attribute' => 'full_name',
                                    //     'value' => 'full_name',
                                    //     'visible' => $fullName,
                                    // ],
                                    [
                                        'attribute' => 'full_name',
                                        'format' => 'raw',
                                        'value' => function ($model) use ($roleUser, $co_so) {
                                            $provinceName = ($model->provinceHasOne) ? $model->provinceHasOne->name : "";
                                            // $model->full_name
                                            $textCoSo = '';
                                            // Ten khach
                                            $nickName = Html::a($model->name, 'javascript:void(0)', [
                                                'title' => $model->name,
                                                'data-pjax' => 0,
                                                'data-toggle' => 'modal',
                                                'data-backdrop' => 'static',
                                                'data-keyboard' => false,
                                                'data-target' => '#custom-modal',
                                                'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['/clinic/clinic/view', 'id' => $model->id]) . '");return false;',
                                            ]);

                                            // CO SO
                                            $coSo = new Dep365CoSo();
                                            $get_co_so = $coSo->getCoSoOne($model->co_so);
                                            if ($get_co_so == null) {
                                                $textCoSo = 'Not set';
                                                $style = 'color: red; font-weight: bold;';
                                            } else {
                                                $textCoSo = $get_co_so->name;
                                                $style = '';
                                            }
                                            if (in_array($roleUser, [
                                                User::USER_DEVELOP,
                                                User::USER_ADMINISTRATOR,
                                                User::USER_QUANLY_PHONGKHAM
                                            ])) {
                                                $result['type'] = 'select';
                                                $result['dataChoose'] = (string)$model->co_so;
                                                $result['dataSelect'] = $co_so;
                                                $textCoSo = '<span style="' . $style . '" class="edittable-dat-hen" data-option="co_so" myedit-options=\'' . json_encode($result) . '\'>' . $textCoSo . '</span>';
                                            }

                                            /**
                                             * Thong tin chăm sóc khach hang
                                             */
                                            // $cskhQuanLy = $model->cskhQuanLyHasOne;
                                            // $userCskhHasOne = ($cskhQuanLy !== null) ? (($cskhQuanLy->userCskhHasOne !== null) ? $cskhQuanLy->userCskhHasOne->fullname  : null) : null;
                                            // $textCskh = ($userCskhHasOne !== null) ? '<br> <i>Chăm sóc : ' . $userCskhHasOne . "</i>" : '';
                                            return $nickName . " - <i>" . $provinceName . "</i> - " . $textCoSo .
                                                '<br>Mã KH : <span style="font-weight: 600;">' . $model->customer_code . '</span>' .
                                                '<br>Mong muốn : <span style="font-size:13px; font-weight: 600"> ' . $model->note . '</span>' .
                                                '<br>' . AppMyaurisCustomerLog::showButtonViewDonHang($model->id);
                                        },
                                        'headerOptions' => [
                                            'width' => 330,
                                        ],
                                        // 'visible' => $fullName,
                                    ],

                                    
                                    [
                                        'attribute' => 'phone',
                                        'header' => 'Điện thoại',
                                        'format' => 'raw',
                                        'visible' => $phoneNumber,
                                        'value' => function ($model) {
                                            return '<div class="td-phone">' .
                                                Html::a(
                                                    '<button class="btn btn-success white btn-sm"><i class="fa fa-phone"></i></button>',
                                                    'javascript:void(0)',
                                                    [
                                                        'onclick' => 'return typeof mycall == \'object\' ? mycall.makeCall(\'' . $model->phone . '\') : toastr.warning("Không thể kết nối đến hệ thống gọi")',
                                                        'title' => 'Gọi'
                                                    ]
                                                ) .
                                                Html::button('<i class="fa fa-copy"></i>', [
                                                    'class' => 'btn btn-primary white btn-sm copy-data',
                                                    'data-phone' => $model->phone,
                                                    'data-copy' => '{"el_copy":"attr-copy","data_copy":"data-phone","success":"toastr.success(\"Đã copy số điện thoại!\")"}',
                                                    'data-pjax' => 0,
                                                    'title' => 'Copy số điện thoại'
                                                ]);
                                        },
                                        'headerOptions' => [
                                            'width' => 80,
                                        ],
                                    ],
                                    /*[
                                                'attribute' => 'dat_hen',
                                                'label' => 'Đặt hẹn',
                                                'visible' => $datHen,
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
                                            ],*/
                                    [
                                        'attribute' => 'time_lichhen',
                                        'format' => 'raw',
                                        'visible' => $lichHen,
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
                                        'headerOptions' => [
                                            'width' => 135,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'customer_come',
                                        'format' => 'raw',
                                        'visible' => $timeGoAuris,
                                        //                                        'value' => 'customer_come',
                                        'value' => function ($model) {
                                            if ($model->customer_come == null) {
                                                return null;
                                            }
                                            return date('d-m-Y H:i', $model->customer_come);
                                        },
                                        'filter' => \dosamigos\datepicker\DatePicker::widget([
                                            'model' => $searchModel,
                                            'attribute' => 'customer_come',
                                            'language' => 'vi',
                                            'clientOptions' => [
                                                'autoclose' => true,
                                                'format' => 'dd-mm-yyyy',
                                            ]
                                        ]),
                                        'headerOptions' => [
                                            'width' => 135,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'directsale',
                                        'label' => 'Direct Sale',
                                        'visible' => $directSale,
                                        'format' => 'raw',
                                        'value' => function ($model) use ($roleUser) {
                                            $data = \common\models\UserProfile::getFullNameDirectSale($model->directsale);
                                            $khachden = new Dep365CustomerOnlineCome();
                                            $style = '';
                                            if ($data == null || $data == '') {
                                                $text = 'not-set';
                                                $style = 'color: red; font-weight: bold';
                                            } else {
                                                $text = $data;
                                            }
                                            if (in_array($roleUser, [
                                                User::USER_DEVELOP,
                                                User::USER_ADMINISTRATOR,
                                                User::USER_QUANLY_PHONGKHAM
                                            ])) {

                                                $directsale = [];
                                                $dr = \common\models\User::getNhanVienTuDirectSale();
                                                if ($dr != null) {
                                                    foreach ($dr as $key => $item) {
                                                        $directsale[$item->id] = $item->userProfile != null ? $item->userProfile->fullname : $item->username;
                                                    }
                                                }
                                                $result['type'] = 'select';
                                                $result['dataChoose'] = (string)$model->directsale;
                                                $result['dataSelect'] = $directsale;
                                                $text = '<div style="' . $style . '" class="edittable-dat-hen" data-option="direct" myedit-options=\'' . json_encode($result) . '\'>' . $text . '</div>';
                                            } else {
                                                $text = '<i>' . $text . '</i>';
                                            }


                                            $ly_do_khong_lam = '';
                                            if ($model->ly_do_khong_lam == null || $model->ly_do_khong_lam == '') {
                                            } else {
                                                $ly_do_khong_lam = 'Lý do: <span style="font-size:13px; font-weight: 600">' .
                                                    Yii::$app->params['ly-do-khong-lam'][$model->ly_do_khong_lam] . '</span>';
                                            }

                                            return $text . '<br>' .
                                                $khachden->getNameTrangThaiKhachDenNotStaticColor($model->customer_come_time_to) . '<br>' .
                                                $ly_do_khong_lam;
                                        },
                                        'headerOptions' => [
                                            'width' => 195,
                                        ],
                                    ],
                                    
                                    [
                                        'attribute' => 'permission_user',
                                        'label' => 'Online',
                                        'visible' => $permissionUser,
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
                                        'attribute' => 'note_direct',
                                        'value' => 'note_direct',
                                        'visible' => $noteDirect,
                                    ],
                                ],
                            ]); ?>
                        </div>
                        <?php Pjax::end(); ?>
                    </div>
                </div>
            </div>
        </div>
</section>
<div id="order-customer" class=""></div>
<div id="dieutri-customer"></div>

<?php
$this->registerCssFile('/css/css_order.css', ['depends' => [\yii\bootstrap\BootstrapAsset::class]]);
$this->registerJsFile('/js/js_order.js', ['depends' => [\yii\web\JqueryAsset::class]]);

$type_curency = PhongKhamKhuyenMai::TYPE_CURENCY;
$type_percent = PhongKhamKhuyenMai::TYPE_PERCENT;
$tit = Yii::t('backend', 'Notification');
$urlGetPriceKhuyenMai = Url::toRoute(['/clinic/phong-kham-khuyen-mai/get-price-khuyen-mai']);

$urlChangeNumber = Url::toRoute('perpage');
$urlUpdateCustomer = Url::toRoute('render-and-update');
$urlCreateCustomer = Url::toRoute('create');
$urlOrderCustomer = Url::toRoute('order-customer');
$urlGetPriceSP = Url::toRoute('get-price-san-pham');
$urlDieuTriCustomer = Url::toRoute('dieu-tri');
$urlCheckDichVu = Url::toRoute(['/clinic/lich-hen/check-dich-vu']);
$urlDH = Url::toRoute('check-letan');
$urlChangePageSize = Url::toRoute(['perpage']);

$urlCustomField = Yii::$app->getUrlManager()->createUrl('config/custom-field-customer');
$urlLoadSanPham = Url::toRoute(['/clinic/clinic-san-pham/danh-sach-san-pham']);
$script = <<< JS
var clinic = new myGridView(),
    type_curency = '$type_curency',
    type_percent = '$type_percent',
    urlGetPriceKhuyenMai = '$urlGetPriceKhuyenMai';
clinic.init({
    pjaxId: '#customer-ajax',
    urlChangePageSize: '$urlChangePageSize',
});
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
            if (res.status == 1) {
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

$("body").on('click', '.custom-field', function() {
    // Tùy chỉnh field
    var currentUrl = $(location).attr('href');
    $('.dropbox-content').myLoading({
        opacity: true,
        size: 'sm'
    });
    var field = $(this).attr('name'),
        fieldVal = $(this).is(':checked') ? 1 : 0;
    $.ajax({
       type: "POST",
       dataType: 'json',
       url:  '$urlCustomField',
       data: {field:field, fieldVal:fieldVal}
    }).done(function(data) {
        if(data.status == '200') {
            $.pjax.reload({url:currentUrl, method: 'POST', container: '#customer-ajax'});
        }
        unLoading('.dropbox-content');
    }).fail(function(err) {
        console.log(err);
        unLoading('.dropbox-content');
    });
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
        type: 'POST',
        dataType: 'json',
        data: {'id': idsp, 'sl':sl},
    }).done(function(data){
        if(data.status == 1){
            tr.find('.thanh-tien').val(data.result);
            checkChietKhauOrder(tr);
        }
    });
}).on('beforeSubmit', 'form#form-don-hang', function(e) {
    // Cập nhật đơn hàng trong clinic
    e.preventDefault();
    var btnHandle = $('#form-don-hang').find('#button-handle').val() || null;
    saveOrder().then(function(result){
        $.when($.pjax.reload({url: window.location.href, method: 'POST', container: clinic.options.pjaxId})).done(function(){
            if(btnHandle == 1){
                /* SUBMIT */
                var url_redirect = $('form#form-don-hang').attr('redirect-on-submit') || null;
                toastr.success(result.result, '$tit');
                if(url_redirect != null && url_redirect != ''){
                    setTimeout(function(){
                        window.location.href = url_redirect;
                    }, 3000);
                } else {
                    $('#custom-modal').find('.close').trigger('click');
                }
            } else {
                /* PRINT */
                $.when(toastr.success(result.result, '$tit')).then(function(){
                    $('#custom-modal').find('.close').trigger('click');
                });
            }
        });
    }, function(error){
        if(error == null){
            $('#form-don-hang').myUnloading();
        } else {
            $('#form-don-hang').myUnloading();
            toastr.error(error.result, '$tit');
        }
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
            el.closest('tr').find('.don-gia').val(res.don_gia);
        } else {
            toastr.error(res.msg, 'Thông báo');
        }
    }, 'json').fail(function(f){
        $('#form-don-hang').myUnloading();
        toastr.error('Lỗi load dữ liệu dịch vụ', 'Thông báo');
        console.log('load dich_vu fail', f);
    });
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