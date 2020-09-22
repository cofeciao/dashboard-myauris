<?php

use backend\modules\clinic\models\PhongKhamKhuyenMai;
use backend\modules\user\models\User;
use common\grid\MyGridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use backend\modules\customer\models\Dep365CustomerOnlineDathenStatus;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\components\MyComponent;
use backend\modules\clinic\models\UploadAudio;
use backend\modules\setting\models\Dep365CoSo;
use yii\helpers\ArrayHelper;

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

$arr = Yii::$app->controller->module->params['customerField'];
foreach ($arr as $key => $val) {
    $$key = MyComponent::getCookies($key) !== false ? MyComponent::getCookies($key) : 1;
}
$directsale = [];
$co_so = ArrayHelper::map(Dep365CoSo::getCoSo(), 'id', 'name');

$dr = \common\models\User::getNhanVienTuDirectSale();
//var_dump($dr);die;
if ($dr != null) {
    foreach ($dr as $key => $item) {
        $directsale[$item->id] = $item->userProfile->fullname;
    }
}
?>

<section id="dom">
    <div class="row">
        <div class="col-12">
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
                                        'header' => 'STT',
                                        'headerOptions' => [
                                            'width' => 40,
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
                                        'template' => '<div class="btn-group" role="group">{render-and-update} {danh-gia}</div><div class="btn-group mt-1" role="group">{order-customer} {list-order}</div>',
                                        'buttons' => [
                                            'render-and-update' => function ($url) {
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
                                            'order-customer' => function ($url) {
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
                                            'list-order' => function ($url, $model) {
                                                return Html::a('<i class="p-icon relative ft-clipboard red"><i class="fa fa-search"></i></i>', ['/clinic/clinic-order', 'customer_id' => $model->primaryKey], [
                                                    'title' => 'Danh sách đơn hàng',
                                                    'class' => 'btn btn-default',
                                                    'data-pjax' => 0,
                                                ]);
                                            },
                                            'danh-gia' => function ($url, $model) {
                                                return Html::button(
                                                    '<i class="p-icon relative ft-check-square success"></i>',
                                                    [
                                                        'title' => 'Đánh giá',
                                                        'class' => 'btn btn-default danh-gia',
                                                        'data-pjax' => 0,
                                                        'data-id' => $model->id,
                                                    ]
                                                );
                                            }
                                        ],
                                        'headerOptions' => [
                                            'width' => 85,
                                        ],
                                        'contentOptions' => []
                                    ],
                                    [
                                        'attribute' => 'avatar',
                                        'header' => 'Avatar',
                                        'format' => 'raw',
                                        'visible' => $avatar,
                                        'headerOptions' => [
                                            'width' => 80,
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
                                    [
                                        'attribute' => 'name',
                                        'format' => 'raw',
                                        'visible' => $nickName,
                                        'value' => function ($model) {
                                            $str = Html::a($model->name, 'javascript:void(0)', [
                                                'title' => $model->name,
                                                'data-pjax' => 0,
                                                'data-toggle' => 'modal',
                                                'data-backdrop' => 'static',
                                                'data-keyboard' => false,
                                                'data-target' => '#custom-modal',
                                                'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['/clinic/clinic/view', 'id' => $model->id]) . '");return false;',
                                            ]);
                                            if ($model->time_lichhen != null) {
                                                $str .= '<br/>Lịch hẹn: <span style="font-weight: 600;">' . date('d-m-Y H:i', $model->time_lichhen) . '</span>';
                                            }
                                            if ($model->customer_come != null) {
                                                $str .= '<br/>Thời gian đến: <span style="font-weight: 600;">' . date('d-m-Y H:i', $model->customer_come) . '</span>';
                                            }
                                            return $str;
                                        },
                                        'headerOptions' => [
                                            'width' => 230,
                                        ],
                                    ],
                                    [
                                        // 'attribute' => 'full_name',
                                        'header' => 'Thông tin',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $provinceName = ($model->provinceHasOne) ? $model->provinceHasOne->name : "";
                                            return 'Họ tên: <span style="font-weight: 600;">' . $model->full_name . '</span>' .
                                                '<br>Tên : <span style="font-weight: 600;">' . $model->forename . " - <i>" . $provinceName . "</i> </span>" .
                                                '<br>Mã KH : <span style="font-weight: 600;">' . $model->customer_code . '</span>' .
                                                '<br>Mong muốn : <span style="font-size:13px; font-weight: 600"> ' . $model->note . "</span>";
                                        },
                                        'headerOptions' => [
                                            'width' => 300,
                                        ],
                                        // 'visible' => $fullName,
                                    ],
                                    // [
                                    //     'attribute' => 'full_name',
                                    //     'value' => 'full_name',
                                    //     'visible' => $fullName,
                                    // ],
                                    // [
                                    //     'attribute' => 'forename',
                                    //     'format' => 'raw',
                                    //     'visible' => $nameName,
                                    //     'value' => 'forename',
                                    //     'headerOptions' => [
                                    //         'width' => 80,
                                    //     ],
                                    // ],
                                    [
                                        // 'attribute' => 'phone',
                                        'header' => 'SĐT',
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
                                            'width' => 85,
                                            'min-width' => 85,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'dat_hen',
                                        'label' => 'Đặt hẹn',
                                        'visible' => $datHen,
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $dathen = new Dep365CustomerOnlineDathenStatus();
                                            $khachden = new Dep365CustomerOnlineCome();
                                            $data = $dathen->getOneDatHenStatus($model->dat_hen);

                                            $style = '';
                                            if ($data == null) {
                                                $style = 'color: red; font-weight: bold;';
                                                $text = 'not-set';
                                                $class = '';
                                            } else {
                                                $class = $model->dat_hen == 1 ? 'da-den' : 'khong-den';
                                                $text = $data->name;
                                            }
                                            $data = \backend\modules\clinic\models\Clinic::getEdittableDatHen($model->dat_hen);
                                            $audioUpload = '';

                                            if ($model->dat_hen == 1) {
                                                if (UploadAudio::exitCustomer($model->id)) {
                                                    $audioUpload = Html::a('Có sử dụng app', \yii\helpers\Url::to(['/quan-ly/customer-view', 'id' => $model->id]));
                                                    // $audioUpload = "Có sử dụng app";
                                                } else {
                                                    // $audioUpload = Html::a('Không sử dụng app', \yii\helpers\Url::to(['/quan-ly/customer-view', 'id' => $model->id]));
                                                    $audioUpload = "<span style='color:#ff5722;'>Không sử dụng app</span>";
                                                }
                                            }

                                            $trang_thai_khach = $khachden->getNameTrangThaiKhachDenNotStaticColor($model->customer_come_time_to);
                                            $ly_do_khong_lam = '';
                                            if ($model->customerOnlineComeHasOne != null && $model->customerOnlineComeHasOne->accept != Dep365CustomerOnlineCome::STATUS_ACCEPT && $model->ly_do_khong_lam != null && $model->ly_do_khong_lam != '') {
                                                $ly_do_khong_lam = '<br/>Lý do: <span style="font-weight: 600">' . Yii::$app->params['ly-do-khong-lam'][$model->ly_do_khong_lam] . '</span>';
                                            }

                                            return '<div style="' . $style . '" data-option="dathen" class="edittable-dat-hen ' . $class . '" myedit-options=\'' . $data . '\'>' . $text . '</div>' .
                                                $audioUpload . '<br>' . $trang_thai_khach . $ly_do_khong_lam;
                                        },
                                        'headerOptions' => [
                                            'width' => 180,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'directsale',
                                        'label' => 'Direct Sale',
                                        'visible' => $directSale,
                                        'format' => 'raw',
                                        'value' => function ($model) use ($directsale) {
                                            $data = \common\models\UserProfile::getFullNameDirectSale($model->directsale);
                                            $style = '';
                                            if ($data == null || $data == '') {
                                                $text = 'not-set';
                                                $style = 'color: red; font-weight: bold;';
                                            } else {
                                                $text = $data;
                                            }

                                            $result['type'] = 'select';
                                            $result['dataChoose'] = (string)$model->directsale;
                                            $result['dataSelect'] = $directsale;
                                            $str = '<div style="' . $style . '" class="edittable-dat-hen" data-option="direct" myedit-options=\'' . json_encode($result) . '\'>' . $text . '</div>';
                                            if ($model->note_direct != null) {
                                                $str .= '<br/>Ghi chú Direct: ' . $model->note_direct;
                                            }
                                            return $str;
                                        },
                                        'headerOptions' => [
                                            'width' => 120,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'id_dich_vu',
                                        'label' => 'Dịch vụ',
                                        'visible' => $directSale,
                                        'format' => 'raw',
                                        'value' => function ($model) use ($directsale) {
                                            $dichvu = \backend\modules\customer\models\Dep365CustomerOnlineDichVu::getById($model->id_dich_vu);
                                            $data = $dichvu == null ? null : $dichvu->name;
                                            $style = '';
                                            if ($data == null || $data == '') {
                                                $text = 'not-set';
                                                $style = 'color: red; font-weight: bold;';
                                            } else {
                                                $text = $data;
                                            }

                                            $result['type'] = 'select';
                                            $result['dataChoose'] = (string)$model->id_dich_vu;
                                            $result['dataSelect'] = \backend\modules\customer\models\Dep365CustomerOnlineDichVu::getSanPhamDichVuArray();
                                            $str = '<div style="' . $style . '" class="edittable-dat-hen" data-option="dichvu" myedit-options=\'' . json_encode($result) . '\'>' . $text . '</div>';
                                            return $str;
                                        },
                                        'headerOptions' => [
                                            'width' => 120,
                                        ],
                                    ],
                                    // [
                                    //     'attribute' => 'province',
                                    //     //                                        'value' => 'provinceHasOne.name',
                                    //     'value' => function ($model) {
                                    //         $province = new \backend\modules\location\models\Province();
                                    //         return $province->getProvinceOne($model->province) != null ? $province->getProvinceOne($model->province)->name : '';
                                    //     },
                                    //     'visible' => $province,
                                    // ],
                                    // [
                                    //     'attribute' => 'customer_come_time_to',
                                    //     'format' => 'raw',
                                    //     'visible' => $statusCustomer,
                                    //     'value' => function ($model) {
                                    //         $khachden = new Dep365CustomerOnlineCome();
                                    //         return $khachden->getNameTrangThaiKhachDenNotStaticColor($model->customer_come_time_to);
                                    //     },
                                    // ],
                                    //                                        [
                                    //                                            'attribute' => 'ly_do_khong_lam',
                                    //                                            'label' => 'Lý do',
                                    //                                            'format' => 'raw',
                                    //                                            'visible' => $statusCustomer,
                                    //                                            'value' => function ($model) {
                                    //                                                if ($model->ly_do_khong_lam == null || $model->ly_do_khong_lam == '') {
                                    //                                                    return null;
                                    //                                                }
                                    //                                                return Yii::$app->params['ly-do-khong-lam'][$model->ly_do_khong_lam];
                                    //                                            },
                                    //                                            'headerOptions' => [
                                    //                                                'width' => 100,
                                    //                                            ],
                                    //                                        ],
                                    [
                                        'attribute' => 'co_so',
                                        'visible' => $coSo,
                                        'format' => 'raw',
                                        //                                        'value' => 'coSoHasOne.name',
                                        'value' => function ($model) use ($roleUser, $co_so) {
                                            $coSo = new Dep365CoSo();
                                            $get_co_so = $coSo->getCoSoOne($model->co_so);
                                            if ($get_co_so == null) {
                                                $text = 'Not set';
                                                $style = 'color: red; font-weight: bold;';
                                            } else {
                                                $text = $get_co_so->name;
                                                $style = '';
                                            }
                                            $result['type'] = 'select';
                                            $result['dataChoose'] = (string)$model->co_so;
                                            $result['dataSelect'] = $co_so;
                                            if (in_array($roleUser, [
                                                User::USER_DEVELOP,
                                                User::USER_ADMINISTRATOR,
                                                User::USER_QUANLY_PHONGKHAM
                                            ])) {
                                                return '<div style="' . $style . '" class="edittable-dat-hen" data-option="co_so" myedit-options=\'' . json_encode($result) . '\'>' . $text . '</div>';
                                            } else {
                                                return $text;
                                            }
                                        },
                                        'headerOptions' => [
                                            'width' => 80,
                                        ],
                                    ],
                                    /*[
                                            'attribute' => 'danh_gia',
                                            'format' => 'raw',
                                            'value' => function ($model) {
                                                if (count($model->danhGiaHasOne) == 0) {
                                                    return null;
                                                }
                                                foreach ($model->danhGiaHasOne as $key => $item) {
    //                                                if (date('d-m-Y', $item->created_at) == date('d-m-Y')) {
                                                    return \backend\helpers\BackendHelpers::getRatings($item->danh_gia_thai_do);
    //                                                }
                                                }
                                            }
                                        ],*/
                                    // [
                                    //     'attribute' => 'note',
                                    //     'value' => 'note',
                                    //     'visible' => $note,
                                    //     'headerOptions' => [
                                    //         'width' => 300,
                                    //     ],
                                    // ],
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
$this->registerCssFile('/css/css_order.css', ['depends' => [\yii\bootstrap\BootstrapAsset::class]]);
$this->registerJsFile('/js/js_order.js', ['depends' => [\yii\web\JqueryAsset::class]]);

$tit = Yii::t('backend', 'Notification');

$type_curency = PhongKhamKhuyenMai::TYPE_CURENCY;
$type_percent = PhongKhamKhuyenMai::TYPE_PERCENT;
$urlGetPriceKhuyenMai = Url::toRoute(['/clinic/phong-kham-khuyen-mai/get-price-khuyen-mai']);

$urlChangeNumber = Url::toRoute('perpage');
$urlUpdateCustomer = Url::toRoute('render-and-update');
$urlCreateCustomer = Url::toRoute('create');
$urlOrderCustomer = Url::toRoute('order-customer');
$urlGetPriceSP = Url::toRoute(['/clinic/clinic/get-price-san-pham']);
$urlDieuTriCustomer = Url::toRoute('dieu-tri');
$urlDH = Url::toRoute('check-letan');
$urlChangePageSize = Url::toRoute(['perpage']);
$urlLoadSanPham = Url::toRoute(['/clinic/clinic-san-pham/danh-sach-san-pham']);
$urlCheckDichVu = Url::toRoute(['check-dich-vu']);
$urlPrintOrder = Url::toRoute(['/clinic/clinic-order/print-order', 'id' => '']);
$urlCustomField = Url::toRoute(['/config/custom-field-toggle']);
$urlDanhGia = Url::toRoute('danh-gia');
$script = <<< JS
var currentUrl = $(location).attr('href');

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

// editTable customer
var myeditor = new myEditor();
myeditor.init({
    element: '.edittable-dat-hen', /* id or class of element */
    callbackBeforeSubmit: function(){
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
                
                $.pjax.reload({url: window.location.href, method: 'POST', container: clinic.options.pjaxId});
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

$("body").on('change', '.sl-sp',function() {
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
    })
    .done(function(data){
        if(data.status == 1){
            tr.find('.thanh-tien').val(data.result);
            checkChietKhauOrder(tr);
        }
    });
}).on('beforeSubmit', 'form#clinic-update',function(e) {
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
}).on('beforeSubmit', 'form#form-don-hang', function(e) {
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
        } else {
            toastr.error(res.msg, 'Thông báo');
        }
    }, 'json').fail(function(f){
        $('#form-don-hang').myUnloading();
        toastr.error('Lỗi load dữ liệu dịch vụ', 'Thông báo');
        console.log('load dich_vu fail', f);
    });
}).on('click', '.danh-gia', function() {
    var id = $(this).attr('data-id');
    $('body').myLoading({msg: 'Đang xử lý...'});
    $.ajax({
        type: 'POST',
        dataType: 'json',
        data: {id: id},
        url: '$urlDanhGia'
    }).done(function(res) {
        console.log(res);
        if(res.status == '201' || res.status == '202') {
            toastr.error(res.msg, 'Lỗi');
        }
        if(res.status == '200') {
            $.when($.pjax.reload({url: currentUrl, method: 'POST', container: '#customer-ajax'})).done(function() {
                $('body').myUnloading();
                toastr.success(res.msg, 'Thành công');
            })
        }
    }).fail(function(err) {
        $('body').myUnloading();
    });
}).on('click', '.dropbox-submit', function(){
    let currentUrl = window.location.href,
        arrayField = [];
    $('.dropbox-content').find('.custom-field').each(function(){
        let tmp = {}, field =  $(this).attr('name'), fieldVal = $(this).is(':checked') ? 1 : 0;
        tmp[field] = fieldVal;
        arrayField.push(tmp);
    });
    $.ajax({
        url: '$urlCustomField',
        type: 'post',
        dataType: 'json',
        data: {arrayField: arrayField}
    }).done(function (data) {
        if(data.status == '200') {
            $.pjax.reload({url:currentUrl, method: 'POST', container: '#customer-ajax'});
        }
        unLoading('.dropbox-content');
    }).fail(function (error) {
        console.log(error);
        unLoading('.dropbox-content');
    });
});
JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>