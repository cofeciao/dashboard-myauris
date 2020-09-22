<?php

use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineDathenTime;
use backend\modules\customer\models\Dep365SendSms;
use backend\modules\user\models\User;
use common\grid\MyGridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use backend\modules\customer\models\Dep365CustomerOnlineDathenStatus;
use backend\components\MyComponent;
use backend\modules\clinic\models\UploadAudio;

$this->title = Yii::t('backend', 'Customer');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Online Support'), 'url' => ['/customer']];
$this->params['breadcrumbs'][] = $this->title;

$user = new User();
$roleUser = $user->getRoleName(Yii::$app->user->id);
$roleDev = User::USER_DEVELOP;

$idCustomer = false;
if ($roleUser == $roleDev) {
    $idCustomer = true;
}

/*
 * Custom Field
 */

$arr = Yii::$app->controller->module->params['customerField'];
foreach ($arr as $key => $val) {
    $$key = MyComponent::getCookies($key) !== false ? MyComponent::getCookies($key) : 1;
}
?>

<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content collapse show customer-index ">
                    <div class="card-body card-dashboard">
                        <div class="btn-add-campaign clearfix d-none" style="margin-top:0px;position:relative">
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
                            <?php if (
                                Yii::$app->user->can(\common\models\User::USER_DEVELOP) ||
                                Yii::$app->user->can('customerCustomer-onlineCreate-customer-facebook')
                            ) { ?>
                                <?= Html::button(
                                    '<i class="fa fa-plus"></i> Nhập facebook khách hàng',
                                    [
                                        'title' => 'Nhập facebook khách hàng',
                                        'class' => 'btn btn-default pull-left',
                                        'data-pjax' => 0,
                                        'data-toggle' => 'modal',
                                        'data-backdrop' => 'static',
                                        'data-keyboard' => false,
                                        'data-target' => '#custom-modal',
                                        'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['create-customer-facebook']) . '");return false;',
                                    ]
                                )
                                ?>
                            <?php } ?>
                            <?= Html::button(
                                '<i class="fa fa-download"></i> Tạo mới với danh sách',
                                [
                                    'title' => 'Tạo mới với danh sách',
                                    'class' => 'btn btn-primary pull-left ml-1 d-none',
                                    'data-pjax' => 0,
                                    'data-toggle' => 'modal',
                                    'data-backdrop' => 'static',
                                    'data-keyboard' => false,
                                    'data-target' => '#custom-modal',
                                    'onclick' => 'showModal($(this), "' . \yii\helpers\Url::to(['import-customer-online']) . '");return false;',
                                ]
                            )
                            ?>
                            <div class="dropbox-widget float-left ml-1 d-none">
                                <button type="button" class="dropbox-button btn btn-blue-grey btn-block px-2">
                                    <i class="fa fa-cogs"></i> Tùy chỉnh Filed
                                </button>
                                <ul class="dropbox-content">
                                    <?php
                                    foreach ($arr as $key => $item) {
                                    ?>
                                        <li class="form-group">
                                            <label>
                                                <input type="checkbox" class="custom-field" name="<?= $key; ?>" <?= $$key == 1 ? 'checked' : '' ?>>
                                                <?= $item; ?>
                                            </label>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                    <li class="text-center">
                                        <button class="btn btn-danger btn-sm dropbox-submit">Close</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <?php Pjax::begin(
                            [
                                'id' => 'customer-online-ajax',
                                'timeout' => false,
                                'enablePushState' => true,
                                'clientOptions' => ['method' => 'GET']
                            ]
                        ); ?>
                        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                            <?= MyGridView::widget([
                                'id' => 'customer-online',
                                'dataProvider' => $dataProvider,
                                //                                'filterModel' => $searchModel,
                                'layout' => '{errors} <div class="pane-single-table">{items}</div><div class="pager-wrap clearfix">{summary}' .
                                    Yii::$app->controller->renderPartial(
                                        '@backend/views/layouts/my-gridview/_goToPage',
                                        [
                                            'totalPage' => $totalPage,
                                            'currentPage' => Yii::$app->request->get($dataProvider->getPagination()->pageParam)
                                        ]
                                    ) .
                                    Yii::$app->controller->renderPartial('@backend/views/layouts/my-gridview/_pageSize') .
                                    Yii::$app->controller->renderPartial('_hiddenField') .
                                    '{pager}</div>',
                                'tableOptions' => [
                                    'id' => 'listCampaign',
                                    'class' => 'cp-grid cp-widget pane-hScroll',
                                ],
                                'myOptions' => [
                                    'class' => 'grid-content my-content pane-vScroll',
                                    'data-minus' => '{"0":42,"1":".header-navbar","2":".btn-add-campaign","3":".pager-wrap","4":".footer","5":".form-search"}'
                                ],
                                'summaryOptions' => [
                                    'class' => 'summary pull-right',
                                ],
                                'pager' => [
                                    'firstPageLabel' => Html::tag(
                                        'span',
                                        'skip_previous',
                                        ['class' => 'material-icons']
                                    ),
                                    'lastPageLabel' => Html::tag(
                                        'span',
                                        'skip_next',
                                        ['class' => 'material-icons']
                                    ),
                                    'prevPageLabel' => Html::tag(
                                        'span',
                                        'play_arrow',
                                        ['class' => 'material-icons']
                                    ),
                                    'nextPageLabel' => Html::tag(
                                        'span',
                                        'play_arrow',
                                        ['class' => 'material-icons']
                                    ),
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
                                            'width' => 40,
                                            'rowspan' => 2,
                                        ],
                                        'filterOptions' => [
                                            'class' => 'd-none',
                                        ],
                                    ],
                                    [
                                        'attribute' => 'id',
                                        'visible' => $idCustomer,
                                        'value' => 'id',
                                        'headerOptions' => [
                                            'width' => 70,
                                        ],
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'Actions',
                                        'template' => '<div class="btn-group" role="group">{getlink} {view-send-sms} {update} {delete} {list-call-log}</div>',
                                        'buttons' => [
                                            'getlink' => function ($url, $model) {
                                                $url = 'javascript:;';
                                                $style = 'cursor:not-allowed';
                                                if ($model->status == \backend\models\CustomerModel::STATUS_DH && $model->dat_hen == null) {
                                                    $url = 'http://booking.myauris.vn?user=' . $model->slug;
                                                    $style = '';
                                                }

                                                return Html::a('<i class="ft-link green"></i>', Url::to($url), [
                                                    'data-pjax' => 0,
                                                    'class' => 'btn btn-default copy-data',
                                                    'style' => $style,
                                                    'data-copy' => '{"el_copy":"attr-copy","data_copy":"href","success":"toastr.success(\"Đã copy link!\")"}',
                                                    'target' => '_blank',
                                                    'title' => 'Copy link cho khách hàng'
                                                ]);
                                            },
                                            'view-send-sms' => function ($url, $model) {
//                                                if (CONSOLE_HOST != 3) return null;
                                                return Html::a(
                                                    '<i class="fa fa-envelope-o blue"></i>',
                                                    'javascript:void(0)',
                                                    [
                                                        'title' => 'Gửi SMS',
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
                                            'update' => function ($url) {
                                                return Html::button(
                                                    '<i class="ft-edit blue"></i>',
                                                    [
                                                        'title' => 'Cập nhật',
                                                        'class' => 'btn btn-default btn-edit',
                                                        'data-pjax' => 0,
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop' => 'static',
                                                        'data-keyboard' => false,
                                                        'data-target' => '#custom-modal',
                                                        'onclick' => 'showModal($(this), "' . $url . '");return false;',
                                                    ]
                                                );
                                            },
                                            'delete' => function ($url, $model) {
                                                return Html::a(
                                                    '<i class="ft-trash-2 red confirm-color" data-id = "' . $model->id . '" ></i>',
                                                    'javascript:void(0)',
                                                    ['class' => 'btn btn-default']
                                                );
                                            },
                                            'list-call-log' => function ($url) {
                                                return Html::button('<i class="fa fa-history green"></i>', [
                                                    'title' => 'Danh sách cuộc gọi',
                                                    'class' => 'btn btn-default',
                                                    'data-pjax' => 0,
                                                    //                                                    'data-toggle' => 'modal',
                                                    //                                                    'data-backdrop' => false,
                                                    //                                                    'data-target' => '#custom-modal',
                                                    //                                                    'onclick' => 'showModal($(this), "' . $url . '");return false;',
                                                    'id' => 'load-call-log',
                                                    'url-load' => $url
                                                ]);
                                            }
                                        ],
                                        'headerOptions' => [
                                            'width' => 160,
                                            'rowspan' => 2
                                        ],
                                        'filterOptions' => [
                                            'class' => 'd-none',
                                        ],
                                    ],
                                    [
                                        'attribute' => 'name',
                                        'format' => 'raw',
                                        'visible' => $nickName,
                                        'value' => function ($model) {
                                            $gender = '';
                                            switch ($model->sex) {
                                                case 1:
                                                    $gender = 'Nam';
                                                    break;
                                                case 0:
                                                    $gender = 'Nữ';
                                                    break;
                                                case 2:
                                                    $gender = 'Chưa xác định';
                                                    break;
                                                default:
                                                    $gender = 'Chưa xác định';
                                                    break;
                                            }

                                            $user = new backend\modules\customer\models\Dep365CustomerOnline();
                                            $userCreatedBy = $user->getUserCreatedBy($model->created_by);

                                            // return $userCreatedBy->fullname;
                                            if ($model->co_so == null) {
                                                $nameCoSo = null;
                                            }
                                            $coSo = new \backend\modules\setting\models\Dep365CoSo();
                                            $nameCoSo = $coSo->getCoSoOne($model->co_so) != null ? $coSo->getCoSoOne($model->co_so)->name : null;

                                            return Html::a(
                                                $model->name,
                                                'javascript:void(0)',
                                                [
                                                    'data-pjax' => 0,
                                                    'data-toggle' => 'modal',
                                                    'data-backdrop' => 'static',
                                                    'data-keyboard' => false,
                                                    'data-target' => '#custom-modal',
                                                    'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute([
                                                        '/customer/customer-online/view',
                                                        'id' => $model->id
                                                    ]) . '");return false;',
                                                ]
                                            ) . '<i style="font-size:13px"> (' . $gender . ') - ' . $nameCoSo . '</i>' .
                                                '<br> <i style="font-size:13px">Tạo bởi: ' . $userCreatedBy->fullname . " (" . date("d-m-Y H:i", $model->created_at) . ")</i>" .
                                                // '<br> <i style="font-size:12px"> (' . date("d-m-Y H:i", $model->created_at) . ')</i>' .
                                                '<br> <span style="font-size:13px; font-weight: 600">Mong muốn : ' . $model->note . "</span>";
                                        },
                                        'headerOptions' => [
                                            'width' => 300,
                                        ],
                                    ],
                                    // [
                                    //     'attribute' => 'forename',
                                    //     'format' => 'raw',
                                    //     'visible' => $nameName,
                                    //     'value' => 'forename',
                                    //     'headerOptions' => [
                                    //         'width' => 200,
                                    //     ],
                                    // ],
                                    // [
                                    //     'attribute' => 'phone',
                                    //     'format' => 'raw',
                                    //     'visible' => $phoneNumber,
                                    //     'value' => function ($model) {
                                    //         return '<div class="td-phone">' .
                                    //             Html::a(
                                    //                 '<button class="btn btn-success white btn-sm"><i class="fa fa-phone"></i></button>',
                                    //                 'javascript:void(0)',
                                    //                 [
                                    //                     'onclick' => 'return typeof mycall == \'object\' ? mycall.makeCall(\'' . $model->phone . '\') : toastr.warning("Không thể kết nối đến hệ thống gọi")',
                                    //                     'title' => 'Gọi'
                                    //                 ]
                                    //             ) .
                                    //             Html::button('<i class="fa fa-copy"></i>', [
                                    //                 'class' => 'btn btn-primary white btn-sm copy-data',
                                    //                 'data-phone' => $model->phone,
                                    //                 'data-copy' => '{"el_copy":"attr-copy","data_copy":"data-phone","success":"toastr.success(\"Đã copy số điện thoại!\")"}',
                                    //                 'data-pjax' => 0,
                                    //                 'title' => 'Copy số điện thoại'
                                    //             ]);
                                    //     },
                                    //     'headerOptions' => [
                                    //         'width' => 120,
                                    //     ],
                                    // ],
                                    // [
                                    //     'attribute' => 'sex',
                                    //     'visible' => $sexSex,
                                    //     'value' => function ($model) {
                                    //         switch ($model->sex) {
                                    //             case 1:
                                    //                 $result = 'Nam Giới';
                                    //                 break;
                                    //             case 0:
                                    //                 $result = 'Nữ Giới';
                                    //                 break;
                                    //             case 2:
                                    //                 $result = 'Chưa xác định';
                                    //                 break;
                                    //             default:
                                    //                 $result = 'Chưa xác định';
                                    //                 break;
                                    //         }

                                    //         return $result;
                                    //     },
                                    //     'filterInputOptions' => [
                                    //         'class' => 'ui dropdown form-control'
                                    //     ],
                                    //     'headerOptions' => [
                                    //         'width' => 120,
                                    //     ],
                                    // ],
                                    [
                                        'attribute' => 'status',
                                        'visible' => $status,
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $string = '';
                                            if ($model->status == '1') {
                                                $string = '<span class="dat-hen">Đặt hẹn</span>';
                                            }
                                            if ($model->status == '6') {
                                                $string = '<span class="status-tiem-nang">Tiềm năng</span>';
                                            }
                                            if ($model->status == '2') {
                                                $string = '<span class="status-fail">Fail</span>';
                                            }
                                            if ($model->status == '3') {
                                                $string = '<span class="status-kobm">KBM</span>';
                                            }
                                            if ($model->status == '4') {
                                                $string = '<span class="status-so-ao">Số ảo</span>';
                                            }
                                            if ($model->status == '5') {
                                                $string = '<span class="status-underfine">Chưa xác định</span>';
                                            }
                                            return $string . '<div class="td-phone" style="margin-top: 10px"> Điện thoại' .
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
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                        'headerOptions' => [
                                            'width' => 110,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'dat_hen',
                                        'visible' => $datHen,
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $dathen = new Dep365CustomerOnlineDathenStatus();
                                            $data = $dathen->getOneDatHenStatus($model->dat_hen);
                                            if ($data == null) {
                                                return null;
                                            }
                                            $audioUpload = '';
                                            $directsaleName = '';
                                            if ($model->dat_hen == 1) {
                                                if (UploadAudio::exitCustomer($model->id)) {
                                                    if (Yii::$app->user->can('quan-lyCustomer-view')) {
                                                        $audioUpload = Html::a('Có sử dụng app', \yii\helpers\Url::to(['/quan-ly/customer-view', 'id' => $model->id]));
                                                    } else {
                                                        $audioUpload = "Có sử dụng app";
                                                    }
                                                } else {
                                                    // $audioUpload = Html::a('Không sử dụng app', \yii\helpers\Url::to(['/quan-ly/customer-view', 'id' => $model->id]));
                                                    $audioUpload = "<span style='color:#ff5722;'>Không sử dụng app</span>";
                                                }
                                            }

                                            if ($model->directSaleHasOne != null) {
                                                $directsaleName = '<i style="font-size:13px" >Direct : ' . $model->directSaleHasOne->fullname . '</i>';
                                            }

                                            if ($data->id == 1) {
                                                return '<span class="da-den">' . $data->name . '</span> <br>' . $audioUpload . '<br>' . $directsaleName;
                                            } elseif ($data->id == 2) {
                                                return '<span class="khong-den">' . $data->name . '</span>';
                                            } else {
                                                return $data->name;
                                            }
                                        },
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                        'headerOptions' => [
                                            'width' => 150,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'time_lichhen',
                                        'format' => 'raw',
                                        'visible' => $timeLichhen,
                                        'value' => function ($model) {
                                            if ($model->time_lichhen == null) {
                                                return null;
                                            }

                                            return date('d-m-Y H:i', $model->time_lichhen);
                                        },
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                        'headerOptions' => [
                                            'width' => 150,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'permission_user',
                                        'visible' => $permissionUser,
                                        'value' => function ($model) {
                                            $userProfile = new Dep365CustomerOnline();

                                            return $userProfile->getNhanVienTuVan($model->permission_user);
                                        },
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                        'headerOptions' => [
                                            'width' => 130,
                                        ],
                                    ],
                                    // [
                                    //     'attribute' => 'co_so',
                                    //     'visible' => $coSo,
                                    //     'value' => function ($model) {
                                    //         if ($model->co_so == null)
                                    //             return null;
                                    //         $coSo = new \backend\modules\setting\models\Dep365CoSo();
                                    //         return $coSo->getCoSoOne($model->co_so) != null ? $coSo->getCoSoOne($model->co_so)->name : null;
                                    //     },
                                    //     'filterInputOptions' => [
                                    //         'class' => 'ui dropdown form-control'
                                    //     ],
                                    //     'headerOptions' => [
                                    //         'width' => 100,
                                    //     ],
                                    // ],
                                    [
                                        'attribute' => 'id_dich_vu',
                                        'visible' => $idDichVu,
                                        'value' => function ($model) {
                                            if ($model->id_dich_vu == null) {
                                                return null;
                                            }
                                            if ($model->dichVuOnlineHasOne != null) {
                                                return $model->dichVuOnlineHasOne->name;
                                            }
                                            return null;
                                        },
                                        'headerOptions' => [
                                            'width' => 130,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'nguon_online',
                                        'visible' => $nguonTrucTuyen,
                                        'format' => 'raw',
                                        //                                            'value' => 'nguonCustomerOnlineHasOne.name',
                                        'value' => function ($model) {
                                            $face_fanpage = '';
                                            if (!empty($model->face_fanpage)) {
                                                $face_fanpage = "<br><i style='color: #1777f2;'>" . \backend\modules\customer\models\Dep365CustomerOnlineFanpage::getNameFanpage($model->face_fanpage)."</i>";
                                            }
                                            $nguonCustomerOnline = \backend\modules\customer\models\Dep365CustomerOnlineNguon::getNguonCustomerOnlineOne($model->nguon_online);
                                            if ($nguonCustomerOnline == null)
                                                return null;
                                            return $nguonCustomerOnline->name . $face_fanpage;
                                        },
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                        'headerOptions' => [
                                            'width' => 220,
                                        ],
                                    ],
                                    /*[
                                            'class'              => \common\grid\EnumColumn::class,
                                            'attribute'          => 'province',
                                            'visible'            => $province,
                                            'value'              => 'provinceHasOne.name',
                                            'enum'               => Dep365CustomerOnline::getProvince(),
                                            'filter'             => \kartik\select2\Select2::widget([
                                                'model'         => $searchModel,
                                                'attribute'     => 'province',
                                                'data'          => Dep365CustomerOnline::getProvince(),
                                                'theme'         => \kartik\select2\Select2::THEME_DEFAULT,
                                                'hideSearch'    => false,
                                                'options'       => [
                                                    'placeholder' => 'Tỉnh/Thành phố',
                                                ],
                                                'pluginOptions' => [
                                                    'allowClear' => true,
                                                    'data-pjax'  => false,
                                                ],
                                            ]),
                                            'filterInputOptions' => [
                                                'class' => 'ui dropdown form-control'
                                            ],
                                            'headerOptions'      => [
                                                'width' => 180,
                                            ],
                                        ],*/
                                    // [
                                    //     'attribute' => 'face_fanpage',
                                    //     'visible' => $faceFanpage,
                                    //     //                                            'value' => 'fanpageFacebookHasOne.name',
                                    //     'value' => function ($model) {
                                    //         return \backend\modules\customer\models\Dep365CustomerOnlineFanpage::getNameFanpage($model->face_fanpage);
                                    //     },
                                    //     'headerOptions' => [
                                    //         'width' => 220,
                                    //     ],
                                    // ],
                                    // [
                                    //     'attribute' => 'created_at',
                                    //     'format' => 'date',
                                    //     'visible' => $createdAt,
                                    //     'value' => 'created_at',
                                    //     'filterInputOptions' => [
                                    //         'class' => 'ui dropdown form-control'
                                    //     ],
                                    //     'headerOptions' => [
                                    //         'width' => 180,
                                    //     ],
                                    // ],
                                    [
                                        'attribute' => 'customer_come_time_to',
                                        'visible' => $customerComeTimeTo,
                                        'value' => function ($model) {
                                            if ($model->customer_come_time_to == null)
                                                return null;
                                            return \backend\modules\customer\models\Dep365CustomerOnlineCome::getNameTrangThaiKhachDen($model->customer_come_time_to);
                                        },
                                        'format' => 'raw',
                                    ],
                                    // [
                                    //     'attribute' => 'directSale',
                                    //     'visible' => $directSale,
                                    //     'value' => function ($model) {
                                    //         if ($model->directsale == null) {
                                    //             return null;
                                    //         }
                                    //         if ($model->directSaleHasOne != null) {
                                    //             return $model->directSaleHasOne->fullname;
                                    //         }
                                    //         return null;
                                    //     }
                                    // ],
                                    [
                                        'attribute' => 'status_fail',
                                        'visible' => $statusFail,
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $status_fail = $fail_dat_hen = '';
                                            if ($model->status_fail == '1') {
                                                $status_fail =  '<span class="status-tiem-nang">Tiềm năng</span>';
                                            }
                                            if ($model->failStatusCustomerOnlineHasOne !== null) {
                                                $status_fail = $model->failStatusCustomerOnlineHasOne->name;
                                            }

                                            if ($model->failDatHenCustomerOnlineHasOne !== null) {
                                                $fail_dat_hen = '<br>Direct ghi chú: ' . $model->failDatHenCustomerOnlineHasOne->name;
                                            }
                                            return $status_fail . $fail_dat_hen;
                                        },
                                        // 'filterInputOptions' => [
                                        //     'class' => 'ui dropdown form-control'
                                        // ],
                                        'headerOptions' => [
                                            'width' => 150,
                                            // 'text-align' => 'inherit'
                                        ],
                                    ],
                                    // [
                                    //     'attribute' => 'dat_hen_fail', // ly do khong den
                                    //     'visible' => $datHenFail,
                                    //     'format' => 'raw',
                                    //     'value' => function ($model) {
                                    //         if ($model->failDatHenCustomerOnlineHasOne !== null) {
                                    //             return $model->failDatHenCustomerOnlineHasOne->name;
                                    //         }
                                    //         return null;
                                    //     },
                                    //     'filterInputOptions' => [
                                    //         'class' => 'ui dropdown form-control'
                                    //     ],
                                    //     'headerOptions' => [
                                    //         'width' => 250,
                                    //     ],
                                    // ],
                                    // [
                                    //     'attribute' => 'note',
                                    //     'format' => 'html',
                                    //     'visible' => $note,
                                    //     'value' => 'note',
                                    //     'headerOptions' => [
                                    //         'width' => 400,
                                    //     ],
                                    // ],
                                    // [
                                    //     'attribute' => 'created_by',
                                    //     'format' => 'raw',
                                    //     'visible' => $createdBy,
                                    //     'value' => function ($model) {
                                    //         $user = new backend\modules\customer\models\Dep365CustomerOnline();
                                    //         $userCreatedBy = $user->getUserCreatedBy($model->created_by);

                                    //         return $userCreatedBy->fullname;
                                    //     },
                                    // ],
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
$this->registerCssFile(
    'https://cdn.myauris.vn/plugins/myModal/myModal/myModal.css',
    ['depends' => [\yii\bootstrap\BootstrapAsset::class]]
);
$this->registerJsFile(
    'https://cdn.myauris.vn/plugins/myModal/myModal/myModal.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);
$url = \yii\helpers\Url::toRoute(['/customer/customer-online/show-hide']);
if (Yii::$app->user->can('customerCustomer-onlineDelete') || Yii::$app->user->can(User::USER_DEVELOP)) {
    $urlDelete = \yii\helpers\Url::toRoute(['/customer/customer-online/delete']);
} else {
    $urlDelete = '';
}
$urlChangePageSize = \yii\helpers\Url::toRoute(['/customer/customer-online/perpage']);

$tit = Yii::t('backend', 'Notification');

$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', 'If delete, you will not be able to recover!');

$urlCustomField = Yii::$app->getUrlManager()->createUrl('config/custom-field-customer');

if (!isset($edit) || $edit == null) {
    $edit = 'false';
}
if (!isset($customer_id) || $customer_id == null) {
    $customer_id = 'null';
}

$script = <<< JS
// Tùy chỉnh field
$('body').on('click', '.custom-field', function() {
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
            $.pjax.reload({url:currentUrl, method: 'POST', container: '#customer-online-ajax'});
        }
        unLoading('.dropbox-content');
    }).fail(function(err) {
        console.log(err);
        unLoading('.dropbox-content');
    });
});
var customerOnline = new myGridView(),
    mymodal = new myModal();
customerOnline.init({
    pjaxId: '#customer-online-ajax',
    urlChangePageSize: '$urlChangePageSize'
});

$('.dropbox-button').unbind('click').bind('click', function(){
   $(this).closest('.dropbox-widget').children('.dropbox-content').slideToggle();
});
$('.dropbox-submit, .dropbox-default').unbind('click').bind('click', function(){
   $(this).closest('.dropbox-widget').children('.dropbox-content').slideUp();
});

$(document).ready(function () {
    $(window).ready(function(){
        if('$edit' != "false" && ![null, undefined].includes($customer_id) && ![null, undefined].includes($('tr[data-key="$customer_id"]')) && $('tr[data-key="$customer_id"]').length > 0) {
            setTimeout(function(){
                $('tr[data-key="$customer_id"]').find('.btn-edit').trigger('click');
            }, 1000);
        }
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
                        $.pjax.reload({url: currentUrl, method: 'POST', container: customerOnline.options.pjaxId});
                    }
                    if(data.status == 'failure' || data.status == 'exception')
                        toastr.error('Xoá không thành công', 'Thông báo');
                }
              });
          }
        });
    });
    
    /*$('body').on('beforeSubmit', 'form#create-customer-online', function(e) {
        e.preventDefault();
        $('#create-customer-online').myLoading({
            fixed: true,
            opacity: true
        });
        var currentUrl = $(location).attr('href');
        var formData = $('#create-customer-online').serialize();
        
        $.ajax({
            url: $('#create-customer-online').attr('action'),
            type: 'POST',
            dataType: 'json',
            data: formData,
        })
        .done(function(res) {
            console.log('submit done', res);
            if (res.status == 200) {
                $.when($.pjax.reload({url: currentUrl, method: 'POST', container: customerOnline.options.pjaxId})).done(function() {
                    toastr.success(res.mess, '$tit');
                    $('#create-customer-online').myUnloading();
                    $('.modal-header').find('.close').trigger('click');
                    $('.modal-content').html('');
                });
            } else {
                $('#create-customer-online').myUnloading();
                toastr.error(res.mess, '$tit');
            }
        }).fail(function(err){
            $('#create-customer-online').myUnloading();
            console.log('error submit form #create-customer-online', err);
            toastr.error('Có lỗi xảy ra!', 'Thông báo');
        });
        
        return false;
    });*/
    function loadModalCallLog(){
        return new Promise(function(resolve, reject){
            mymodal.setModal('modal-call-log', 'large');
            mymodal.setText('title', 'Danh sách cuộc gọi');
            mymodal.setText('body', '<div class="load-wait-call-log" style="min-height: 300px"></div>');
            mymodal.setText('cancel', '<i class="ft-x"></i> Close');
            mymodal.setClassEl('add', 'header', 'bg-blue-grey bg-lighten-2 white');
            mymodal.setClassEl('add', 'cancel', 'btn btn-warning');
            mymodal.setClassEl('remove', 'cancel', 'btn-default');
            mymodal.setVisible('submit', 'hide');
            mymodal.currentModal().find('.load-wait-call-log').myLoading({
                msg: 'Đang tải dữ liệu...',
                opacity: true
            });
            mymodal.visible('show');
            resolve();
        });
    }
    $('body').on('click', '#load-call-log', function(e){
        let urlLoad = $(this).attr('url-load');
        loadModalCallLog().then(function(res){
            $.ajax({
                type: 'POST',
                url: urlLoad,
            }).done(function(res){
                mymodal.currentModal().find('.load-wait-call-log').html(res);
            }).fail(function(err) {
                console.log('load call log fail', err);
                mymodal.visible('hide');
            });
        });
    });
});
JS;

$this->registerJs($script, \yii\web\View::POS_END);
$this->registerJsFile('/js/scripts/popover/popover.min.js', ['depends' => 'yii\web\JqueryAsset']);
