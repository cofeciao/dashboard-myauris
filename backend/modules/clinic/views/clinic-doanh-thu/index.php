<?php

use backend\components\MyComponent;
use backend\modules\clinic\models\PhongKhamLichDieuTri;
use backend\modules\clinic\models\search\CustomerDoanhThuSearch;
use yii\helpers\Html;
use common\grid\MyGridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use backend\modules\clinic\models\PhongKhamDonHangWThanhToan;
use backend\modules\user\models\User;


/* @var $this yii\web\View */
/* @var $searchModel backend\modules\clinic\models\search\PhongKhamDonHangWThanhToanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Báo Cáo Tổng Hợp');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Clinic'), 'url' => ['/clinic']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Order'), 'url' => ['/clinic/clinic-doanh-thu']];
$this->params['breadcrumbs'][] = $this->title;

$arr = CustomerDoanhThuSearch::getlistField();
foreach ($arr as $key => $val) {
    $$key = MyComponent::getCookies($key) !== false ? MyComponent::getCookies($key) : 1;
}

$user = new User();
$roleName = $user->getRoleName(Yii::$app->user->id);
?>
<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <div class="btn-add-campaign clearfix" style="margin-top:0px;position:relative">
                            <?php //Html::a('<i class="fa fa-plus"> Thêm mới</i>', ['create'], ['title' => 'Thêm mới', 'data-pjax' => 0, 'class' => 'btn btn-default pull-left'])
                            ?>
                        </div>
                        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                        <?php Pjax::begin(['id' => 'custom-pjax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                            <?= MyGridView::widget([
                                'id' => 'customer-online-clinic',
                                'showFooter' => true,
                                'placeFooterAfterBody' => true,
                                'dataProvider' => $dataProvider,
                                //                                    'filterModel' => $searchModel,
                                'showFooter' => true,
                                'placeFooterAfterBody' => true,
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
                                    'data-minus' => '{"0":52,"1":".header-navbar","2":".btn-add-campaign","3":".pager-wrap","4":".footer","5":".form-search", "6":".option-button"}'
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
                                            'class' => 'd-none',
                                        ],
                                        //                                            'footerOptions' => [
                                        //                                                'colspan' => 4,
                                        //                                                'class' => 'text-right font-weight-bold'
                                        //                                            ],
                                    ],

                                    [
                                        'header' => "Đã tính DS chỉnh nha",
                                        'headerOptions' => [
                                            'style' => 'font-size:11px; font-weight: 600',
                                            'width' => "135px"
                                        ],
                                        'format' => 'raw',
                                        'value' => function ($model) use ($roleName) {
                                            $data = json_encode([
                                                'id' => $model->id,
                                                'confirm' => $model->confirm,
                                            ]);
                                            $addClass = "hidden";
                                            if (in_array($roleName, [
                                                User::USER_KE_TOAN,
                                                User::USER_MANAGER_KE_TOAN
                                            ])) {
                                                $addClass = "";
                                            }
                                            if ($model->confirm == 1) {
                                                $userConfirmHasOneName = ($model->userConfirmHasOne !== null) ? $model->userConfirmHasOne->fullname : "";
                                                return Html::checkbox("Xác nhận", true, ['id' => $model->id, 'value' => $model->id, 'data' => $data, 'class' => "confirm-order " . $addClass]) .
                                                    "<br>" . $userConfirmHasOneName .
                                                    "<br><i style='font-size:13px'>" . $model->getConfirmTime() . "</i>";
                                            } else {
                                                return Html::checkbox("Xác nhận", false, ['id' => $model->id, 'value' => $model->id, 'data' => $data, 'class' => "confirm-order " . $addClass]);
                                            }
                                        },
                                        'contentOptions' => [
                                            'style' => 'font-size:13px;text-align: center'
                                        ],
                                    ],

                                    [
                                        'attribute' => 'customer_id',
                                        'label' => 'Khách hàng',
                                        'value' => function ($model) {
                                            if ($model->customerOnlineHasOne == null) {
                                                return null;
                                            }
                                            return $model->customerOnlineHasOne->full_name != null ? $model->customerOnlineHasOne->full_name : $model->customerOnlineHasOne->name;
                                        },
                                        //                                            'footerOptions' => [
                                        //                                                'class' => 'd-none'
                                        //                                            ],
                                        'headerOptions' => [
                                            'width' => "200px"
                                        ],
                                        'visible' => $dt_customer,
                                    ],

                                    [
                                        'attribute' => 'order_code',
                                        //                                            'footerOptions' => [
                                        //                                                'class' => 'd-none'
                                        //                                            ],
                                        'headerOptions' => [
                                            'width' => "110px"
                                        ],
                                        'contentOptions' => [
                                            'style' => 'font-size:13px'
                                        ],
                                        'visible' => $dt_order_code,

                                    ],



                                    [
                                        'label' => 'Trạng thái DV',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            return $model->showHoanThanh();
                                        },
                                        'headerOptions' => [
                                            'width' => "130px"
                                        ],
                                        'visible' => $dt_trang_thai,
                                    ],

                                    [
                                        'label' => 'Thanh toán',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            return $model->showHoanThanhThanhToan();
                                        },
                                        'headerOptions' => [
                                            'width' => "130px"
                                        ],
                                        'visible' => $dt_trang_thai_hoan_thanh,
                                    ],

                                    [
                                        'label' => 'Gói DV - SP : SL',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            return $model->getThongTinGoiDichVu();
                                        },
                                        'footerOptions' => [
                                            'class' => 'text-right font-weight-bold'
                                        ],
                                        'headerOptions' => [
                                            'width' => "260px"
                                        ],
                                        'contentOptions' => [
                                            'style' => 'font-size:13px'
                                        ],
                                        'visible' => $dt_dich_vu,
                                        'footer' => 'Tổng: '
                                    ],

                                    [
                                        'label' => 'TỔNG TIỀN TRƯỚC CK',
                                        'format' => 'raw',
                                        'filter' => false,
                                        'value' => function ($model) {
                                            if ($model->thanh_tien == null) {
                                                return null;
                                            }
                                            return '<span class="blue">' . number_format($model->thanh_tien, 0, '', '.') . '</span>';
                                        },
                                        'headerOptions' => [
                                            'width' => "180px"
                                        ],
                                        'footerOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'visible' => $dt_tong_tien_truoc_chiet_khau,

                                        'footer' => '<span class="blue">' . number_format($sum_tien_truoc_chiet_khau, 0, '', '.') . '</span>',
                                    ],

                                    [
                                        'label' => 'TIỀN CK',
                                        'format' => 'raw',
                                        'filter' => false,
                                        'value' => function ($model) {
                                            if ($model->thanh_tien == null) {
                                                return null;
                                            }
                                            return '<span class="blue">' . number_format($model->chiet_khau, 0, '', '.') . '</span>';
                                        },
                                        'headerOptions' => [
                                            'width' => "140px"
                                        ],
                                        'footerOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'visible' => $dt_tong_tien_chiet_khau,

                                        'footer' => '<span class="blue">' . number_format($sum_tien_chiet_khau, 0, '', '.') . '</span>',
                                    ],

                                    [
                                        'label' => 'CHI TIẾT CK',
                                        'format' => 'raw',
                                        'filter' => false,
                                        'value' => function ($model) {
                                            return $model->getChiTietChietKhau();
                                        },
                                        'headerOptions' => [
                                            'width' => "350px"
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-left ',
                                            'style' => 'font-size:13px'
                                        ],
                                        'visible' => $dt_chi_tiet_chiet_khau,

                                        //                                            'footer' => '<span class="blue">' . number_format($sum_don_hang, 0, '', '.') . '</span>',
                                    ],

                                    [
                                        'label' => 'TỔNG TIỀN HĐ',
                                        'format' => 'raw',
                                        'filter' => false,
                                        'value' => function ($model) {
                                            if ($model->thanh_tien == null) {
                                                return null;
                                            }
                                            return '<span class="blue">' . number_format($model->thanh_tien - $model->chiet_khau, 0, '', '.') . '</span>';
                                        },
                                        'headerOptions' => [
                                            'width' => "140px"
                                        ],
                                        'footerOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'visible' => $dt_tong_tien_hd,

                                        'footer' => '<span class="blue">' . number_format($sum_don_hang, 0, '', '.') . '</span>',
                                    ],

                                    [
                                        'label' => 'KHÁCH HÀNG NỢ',
                                        'format' => 'raw',
                                        'filter' => false,
                                        'value' => function ($model) {
                                            $tt = new PhongKhamDonHangWThanhToan();
                                            $dat_coc = $tt->getDatCocByOrder($model->id);
                                            $thanh_toan = $tt->getThanhToanByOrder($model->id);
                                            if ($model->thanh_tien == null) {
                                                return null;
                                            }
                                            return '<span style="color:#ff0000;">' . number_format($model->thanh_tien - $model->chiet_khau - $dat_coc - $thanh_toan, 0, '', '.') . '</span>';
                                        },
                                        'headerOptions' => [
                                            'width' => "140px"
                                        ],
                                        'footerOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'visible' => $dt_khach_hang_no,

                                        'footer' => '<span style="color:#ff0000;">' . number_format($sum_don_hang - $sum_thuc_thu, 0, '', '.') . '</span>',
                                    ],

                                    [
                                        'label' => 'TỔNG THỰC THU (1)',
                                        'format' => 'html',
                                        'headerOptions' => [
                                            'width' => "160px"
                                        ],
                                        'footerOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'value' => function ($model) {
                                            $tt = new PhongKhamDonHangWThanhToan();
                                            $dat_coc = $tt->getDatCocByOrder($model->id);
                                            $thanh_toan = $tt->getThanhToanByOrder($model->id);
                                            if ($dat_coc + $thanh_toan == 0) {
                                                return null;
                                            }
                                            return '<span style="color: #0E7E12">' . number_format($dat_coc + $thanh_toan, 0, ',', '.') . '</span>';
                                        },
                                        'visible' => $dt_tong_tien_thuc_thu,

                                        'footer' => '<span style="color: #0E7E12">' . number_format($sum_thuc_thu, 0, '', '.') . '</span>'
                                    ],

                                    [
                                        'label' => 'THỰC THU THẺ (1)',
                                        'format' => 'html',
                                        'headerOptions' => [
                                            'width' => "160px"
                                        ],
                                        'footerOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'value' => function ($model) {
                                            $tt = new PhongKhamDonHangWThanhToan();
                                            $dat_coc = $tt->getDatCocByOrderChiTiet($model->id, false);
                                            $thanh_toan = $tt->getThanhToanByOrderChiTiet($model->id, false);
                                            if ($dat_coc + $thanh_toan == 0) {
                                                return null;
                                            }
                                            return '<span style="color: #0E7E12">' . number_format($dat_coc + $thanh_toan, 0, ',', '.') . '</span>';
                                        },
                                        'visible' => $dt_thuc_thu_the,

                                        'footer' => '<span style="color: #0E7E12">' . number_format($sum_thuc_thu_the, 0, '', '.') . '</span>'
                                    ],

                                    [
                                        'label' => 'THỰC THU TM (1)',
                                        'format' => 'html',
                                        'headerOptions' => [
                                            'width' => "160px"
                                        ],
                                        'footerOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'value' => function ($model) {
                                            $tt = new PhongKhamDonHangWThanhToan();
                                            $dat_coc = $tt->getDatCocByOrderChiTiet($model->id, true);
                                            $thanh_toan = $tt->getThanhToanByOrderChiTiet($model->id, true);
                                            if ($dat_coc + $thanh_toan == 0) {
                                                return null;
                                            }
                                            return '<span style="color: #0E7E12">' . number_format($dat_coc + $thanh_toan, 0, ',', '.') . '</span>';
                                        },
                                        'visible' => $dt_thuc_thu_tien_mat,

                                        'footer' => '<span style="color: #0E7E12">' . number_format($sum_thuc_thu_tien_mat, 0, '', '.') . '</span>'
                                    ],

                                    [
                                        'label' => 'TỔNG THANH TOÁN (2)',
                                        'format' => 'html',
                                        'headerOptions' => [
                                            'width' => "170px"
                                        ],
                                        'footerOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'value' => function ($model) {
                                            $tt = new PhongKhamDonHangWThanhToan();
                                            $thanh_toan = $tt->getThanhToanByOrder($model->id);
                                            if ($thanh_toan == 0) {
                                                return null;
                                            }
                                            return '<span style="color: #d30a0a">' . number_format($thanh_toan, 0, ',', '.') . '</span>';
                                        },
                                        'visible' => $dt_tong_thanh_toan,

                                        'footer' => '<span style="color: #d30a0a">' . number_format($sum_thanh_toan_the + $sum_thanh_toan_tien_mat, 0, '', '.') . '</span>'
                                    ],

                                    [
                                        'label' => 'THANH TOÁN THẺ (2)',
                                        'format' => 'html',
                                        'headerOptions' => [
                                            'width' => "160px"
                                        ],
                                        'footerOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'value' => function ($model) {
                                            $tt = new PhongKhamDonHangWThanhToan();
                                            $thanh_toan = $tt->getThanhToanByOrderChiTiet($model->id, false);
                                            if ($thanh_toan == 0) {
                                                return null;
                                            }
                                            return '<span style="color: #d30a0a">' . number_format($thanh_toan, 0, ',', '.') . '</span>';
                                        },
                                        'visible' => $dt_thanh_toan_the,

                                        'footer' => '<span style="color: #d30a0a">' . number_format($sum_thanh_toan_the, 0, '', '.') . '</span>'
                                    ],

                                    [
                                        'label' => 'THANH TOÁN TM (2)',
                                        'format' => 'html',
                                        'headerOptions' => [
                                            'width' => "160px"
                                        ],
                                        'footerOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'value' => function ($model) {
                                            $tt = new PhongKhamDonHangWThanhToan();
                                            $thanh_toan = $tt->getThanhToanByOrderChiTiet($model->id, true);
                                            if ($thanh_toan == 0) {
                                                return null;
                                            }
                                            return '<span style="color: #d30a0a">' . number_format($thanh_toan, 0, ',', '.') . '</span>';
                                        },
                                        'visible' => $dt_thanh_toan_tien_mat,

                                        'footer' => '<span style="color: #d30a0a">' . number_format($sum_thanh_toan_tien_mat, 0, '', '.') . '</span>'
                                    ],

                                    [
                                        'label' => 'TỔNG ĐẶT CỌC (3)',
                                        'format' => 'html',
                                        'headerOptions' => [
                                            'width' => "160px"
                                        ],
                                        'footerOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'value' => function ($model) {
                                            $tt = new PhongKhamDonHangWThanhToan();
                                            $dat_coc = $tt->getDatCocByOrder($model->id);
                                            if ($dat_coc == 0) {
                                                return null;
                                            }
                                            return '<span style="color: #4953ae">' . number_format($dat_coc, 0, ',', '.') . '</span>';
                                        },
                                        'visible' => $dt_tong_dat_coc,

                                        'footer' => '<span style="color: #4953ae">' . number_format($sum_dat_coc_the + $sum_dat_coc_tien_mat, 0, '', '.') . '</span>'
                                    ],

                                    [
                                        'label' => 'ĐẶT CỌC THẺ (3)',
                                        'format' => 'html',
                                        'headerOptions' => [
                                            'width' => "160px"
                                        ],
                                        'footerOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'value' => function ($model) {
                                            $tt = new PhongKhamDonHangWThanhToan();
                                            $dat_coc = $tt->getDatCocByOrderChiTiet($model->id, false);
                                            if ($dat_coc == 0) {
                                                return null;
                                            }
                                            return '<span style="color: #4953ae">' . number_format($dat_coc, 0, ',', '.') . '</span>';
                                        },
                                        'visible' => $dt_dat_coc_the,

                                        'footer' => '<span style="color: #4953ae">' . number_format($sum_dat_coc_the, 0, '', '.') . '</span>'
                                    ],

                                    [
                                        'label' => 'ĐẶT CỌC TM(3)',
                                        'format' => 'html',
                                        'headerOptions' => [
                                            'width' => "160px"
                                        ],
                                        'footerOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'value' => function ($model) {
                                            $tt = new PhongKhamDonHangWThanhToan();
                                            $dat_coc = $tt->getDatCocByOrderChiTiet($model->id, true);
                                            if ($dat_coc == 0) {
                                                return null;
                                            }
                                            return '<span style="color: #4953ae">' . number_format($dat_coc, 0, ',', '.') . '</span>';
                                        },
                                        'visible' => $dt_dat_coc_tien_mat,

                                        'footer' => '<span style="color: #4953ae">' . number_format($sum_dat_coc_tien_mat, 0, '', '.') . '</span>'
                                    ],

                                    [
                                        'label' => 'TRẢ GÓP',
                                        'format' => 'html',
                                        'headerOptions' => [
                                            'width' => "160px"
                                        ],
                                        'footerOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'value' => function ($model) {
                                            $tt = new PhongKhamDonHangWThanhToan();
                                            $tra_gop = $tt->getTraGopByOrder($model->id);
                                            if ($tra_gop == 0) {
                                                return null;
                                            }
                                            return '<span style="color: #000000">' . number_format($tra_gop, 0, ',', '.') . '</span>';
                                        },
                                        'visible' => $dt_tra_gop,

                                        'footer' => '<span style="color: #000000">' . number_format($sum_tra_gop, 0, '', '.') . '</span>'
                                    ],

                                    [
                                        'label' => 'HOÀN CỌC (4)',
                                        'format' => 'html',
                                        'headerOptions' => [
                                            'width' => "160px"
                                        ],
                                        'footerOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'value' => function ($model) {
                                            $tt = new PhongKhamDonHangWThanhToan();
                                            $hoan_coc = $tt->getHoanCocByOrder($model->id);
                                            if ($hoan_coc == 0) {
                                                return null;
                                            }
                                            return '<span style="color: #000000">' . number_format($hoan_coc, 0, ',', '.') . '</span>';
                                        },
                                        'visible' => $dt_hoan_coc,

                                        'footer' => '<span style="color: #000000">' . number_format($sum_tien_hoan_coc, 0, '', '.') . '</span>'
                                    ],

                                    [
                                        'label' => 'HOÀN CỌC THẺ (4)',
                                        'format' => 'html',
                                        'headerOptions' => [
                                            'width' => "160px"
                                        ],
                                        'footerOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'value' => function ($model) {
                                            $tt = new PhongKhamDonHangWThanhToan();
                                            $hoan_coc = $tt->getHoanCocByOrderChiTiet($model->id, false);
                                            if ($hoan_coc == 0) {
                                                return null;
                                            }
                                            return '<span style="color: #000000">' . number_format($hoan_coc, 0, ',', '.') . '</span>';
                                        },
                                        'visible' => $dt_hoan_coc_the,

                                        'footer' => '<span style="color: #000000">' . number_format($sum_tien_hoan_coc_the, 0, '', '.') . '</span>'
                                    ],

                                    [
                                        'label' => 'HOÀN CỌC TM (4)',
                                        'format' => 'html',
                                        'headerOptions' => [
                                            'width' => "160px"
                                        ],
                                        'footerOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'contentOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'value' => function ($model) {
                                            $tt = new PhongKhamDonHangWThanhToan();
                                            $hoan_coc = $tt->getHoanCocByOrderChiTiet($model->id, true);
                                            if ($hoan_coc == 0) {
                                                return null;
                                            }
                                            return '<span style="color: #000000">' . number_format($hoan_coc, 0, ',', '.') . '</span>';
                                        },
                                        'visible' => $dt_hoan_coc_tien_mat,

                                        'footer' => '<span style="color: #000000">' . number_format($sum_tien_hoan_coc_tien_mat, 0, '', '.') . '</span>'
                                    ],

                                    [
                                        'label' => 'HỦY DỊCH VỤ',
                                        'format' => 'html',
                                        'headerOptions' => [
                                            'width' => "160px"
                                        ],
                                        'footerOptions' => [
                                            'class' => 'text-right '
                                        ],

                                        'value' => function ($model) {
                                            return $model->checkHuyDichVu();
                                        },
                                        'visible' => $dt_huy_dich_vu,

                                    ],

                                    [
                                        'label' => 'CHI TIẾT GIAO DỊCH',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            return $model->getChiTietThanhToan();
                                        },
                                        'footerOptions' => [
                                            'class' => 'text-right '
                                        ],
                                        'headerOptions' => [
                                            'width' => "370px"
                                        ],
                                        'contentOptions' => [
                                            'style' => 'font-size:14px'
                                        ],
                                        'visible' => $dt_chi_tiet_giao_dich,

                                        //                                            'footer' => '<span style="color: #0E7E12">' . number_format($so_tien_thanh_toan, 0, '', '.') . '</span>',

                                    ],

                                    [
                                        'label' => 'CƠ SỞ',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            //                                                if ($model->customerOnlineHasOne) {
                                            //                                                    return $model->customerOnlineHasOne->co_so;
                                            //                                                }
                                            if ($model->co_so) {
                                                return $model->co_so;
                                            } else {
                                                if ($model->customerOnlineHasOne) {
                                                    return $model->customerOnlineHasOne->co_so;
                                                }
                                            }
                                            return "";
                                        },

                                        'headerOptions' => [
                                            'width' => "60px"
                                        ],
                                        'contentOptions' => [
                                            'style' => 'font-size:14px ; text-align:center',
                                        ],
                                        'visible' => $dt_co_so,

                                    ],

                                    [
                                        'label' => 'Sale PK',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            $customer = $model->customerOnlineHasOne;
                                            if ($customer) {
                                                return $customer->getDirectSaleName();
                                            }
                                            return "";
                                        },

                                        'headerOptions' => [
                                            'width' => "120px"
                                        ],
                                        'visible' => $dt_sale_pk,

                                    ],
                                    //                                        [
                                    //                                            'label' => 'Bác sĩ',
                                    //                                            'format' => 'html',
                                    //                                            'value' => function ($model) {
                                    //                                                return  $model->getThongTinBacSiLichDieuTri();
                                    //                                            },
                                    //
                                    //                                            'headerOptions' => [
                                    //                                                'width' => "120px"
                                    //                                            ],
                                    //                                            'visible' => $dt_bac_si,
                                    //
                                    //                                        ],
                                    //
                                    [
                                        'label' => 'BS mài',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            return  $model->getThongTinBacSiLichDieuTri(false, PhongKhamLichDieuTri::THAO_TAC_MAI);
                                        },
                                        'headerOptions' => [
                                            'width' => "100px"
                                        ],
                                        'visible' => $dt_bac_si_mai,
                                    ],
                                    [
                                        'label' => 'BS lắp',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            return  $model->getThongTinBacSiLichDieuTri(false, PhongKhamLichDieuTri::THAO_TAC_LAP);
                                        },
                                        'headerOptions' => [
                                            'width' => "100px"
                                        ],
                                        'visible' => $dt_bac_si_lap,
                                    ],
                                    [
                                        'label' => 'BS lợi',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            return  $model->getThongTinBacSiLichDieuTri(false, PhongKhamLichDieuTri::THAO_TAC_LOI);
                                        },
                                        'headerOptions' => [
                                            'width' => "100px"
                                        ],
                                        'visible' => $dt_bac_si_loi,
                                    ],
                                    [
                                        'label' => 'BS thao tác khác',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            return  $model->getThongTinBacSiLichDieuTri(false, PhongKhamLichDieuTri::THAO_TAC_KHAC);
                                        },
                                        'headerOptions' => [
                                            'width' => "130px"
                                        ],
                                        'visible' => $dt_bac_si_khac,
                                    ],

                                    //
                                    //                                        [
                                    //                                            'label' => 'Trợ thủ',
                                    //                                            'format' => 'html',
                                    //                                            'value' => function ($model) {
                                    //                                                return  $model->getThongTinTroThuLichDieuTri();
                                    //                                            },
                                    //
                                    //                                            'headerOptions' => [
                                    //                                                'width' => "120px"
                                    //                                            ],
                                    //                                            'visible' => $dt_tro_thu,
                                    //                                        ],
                                    [
                                        'label' => 'TT mài',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            return  $model->getThongTinTroThuLichDieuTri(false, PhongKhamLichDieuTri::THAO_TAC_MAI);
                                        },
                                        'headerOptions' => [
                                            'width' => "100px"
                                        ],
                                        'visible' => $dt_tro_thu_mai,
                                    ],
                                    [
                                        'label' => 'TT lắp',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            return  $model->getThongTinTroThuLichDieuTri(false, PhongKhamLichDieuTri::THAO_TAC_LAP);
                                        },
                                        'headerOptions' => [
                                            'width' => "100px"
                                        ],
                                        'visible' => $dt_tro_thu_lap,
                                    ],
                                    [
                                        'label' => 'TT lợi',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            return  $model->getThongTinTroThuLichDieuTri(false, PhongKhamLichDieuTri::THAO_TAC_LOI);
                                        },
                                        'headerOptions' => [
                                            'width' => "100px"
                                        ],
                                        'visible' => $dt_tro_thu_loi,
                                    ],
                                    [
                                        'label' => 'TT thac tác khác',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            return  $model->getThongTinTroThuLichDieuTri(false, PhongKhamLichDieuTri::THAO_TAC_KHAC);
                                        },
                                        'headerOptions' => [
                                            'width' => "130px"
                                        ],
                                        'visible' => $dt_tro_thu_khac,
                                    ],

                                    [
                                        'attribute' => 'created_at',
                                        'label' => 'Ngày tạo đơn',
                                        'format' => 'raw',
                                        'filter' => false,
                                        'value' => function ($model) {
                                            return Yii::$app->formatter->format($model->created_at, 'datetime');
                                        },
                                        'headerOptions' => [
                                            'width' => "120px"
                                        ],
                                        'contentOptions' => [
                                            'style' => 'font-size:13px'
                                        ],
                                        'visible' => $dt_created_at,
                                    ],
                                    [
                                        'attribute' => 'created_by',
                                        'filter' => false,
                                        'value' => function ($model) {
                                            $user = new backend\modules\clinic\models\PhongKhamDonHangWThanhToan();
                                            $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                                            if ($userCreatedBy) {
                                                return $userCreatedBy->fullname;
                                            }
                                            return "";
                                        },
                                        'visible' => $dt_created_by,
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
<iframe class="data-load hidden" src="" frameborder="0" style="width: 100%;min-height: 500px;"></iframe>
<?php
$url = \yii\helpers\Url::toRoute(['/clinic/clinic-doanh-thu/show-hide']);
$urlDelete = \yii\helpers\Url::toRoute(['/clinic/clinic-doanh-thu/delete']);
$urlChangePageSize = \yii\helpers\Url::toRoute(['/clinic/clinic-doanh-thu/perpage']);
$url_confirm = \yii\helpers\Url::toRoute(['confirm']);

$tit = Yii::t('backend', 'Notification');

$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', 'If delete, you will not be able to recover!');

$urlCustomField = Yii::$app->getUrlManager()->createUrl('config/custom-field-customer');

$script = <<< JS
var customPjax = new myGridView();
customPjax.init({
    pjaxId: '#custom-pjax',
    urlChangePageSize: '$urlChangePageSize',
});

$(document).ready(function () {
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
    }).on('beforeSubmit', '#form-clinic-payment', function(e) {
        e.preventDefault();
        var form_data = $('#form-clinic-payment').serialize();
        $.ajax({
            type: 'POST',
            url: $('#form-clinic-payment').attr('action'),
            dataType: 'json',
            data: form_data
        }).done(function(res) {
            if(res.code == 200){
                toastr.success(res.msg, 'Thông báo');
                $.when($.pjax.reload({url: window.location.href, method: 'POST', container: customPjax.options.pjaxId})).done(function(){
                    $('#custom-modal').find('.close').trigger('click');
                });
            } else {
                toastr.error(res.msg, 'Thông báo');
                $('#form-clinic-payment').myUnloading();
            }
        }).fail(function(err) {
            console.log('submit form clinic payment fail', err);
            $('#form-clinic-payment').myUnloading();
        });
        return false;
    }).on('click', '.print-payment', function() {
        let printUrl = $(this).data('href');
            window.open(printUrl, '', 'width=800,height=600,left=0,top=0,toolbar=0,scrollbars=0,status=0');
    }).on('click', '.confirm-color', function (e) {
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
                        $.pjax.reload({url: currentUrl, method: 'POST', container: customPjax.options.pjaxId});
                    }
                    if(data.status == 'failure' || data.status == 'exception')
                        toastr.error('Xoá không thành công', 'Thông báo');
                }
              });
          }
        });
    });
    //
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
                $.pjax.reload({url:currentUrl, method: 'POST', container: '#custom-pjax'});
            }
            unLoading('.dropbox-content');
        }).fail(function(err) {
            console.log(err);
            unLoading('.dropbox-content');
        });
    }).on('change', '.confirm-order', function () {
        var data = $(this).attr('data');
        // on_off(data);
        // console.log(data);
        $.ajax({
        type: "POST",
        data: {"data":data},
        url: "$url_confirm",
        dataType: "json",
        success: function(res) {
            if(res.code == '200') {
                toastr.success('$resultSuccess', '$tit');
                // table.slideUp("slow");
                $.pjax.reload({url: currentUrl, method: 'POST', container: customPjax.options.pjaxId});
            }
            if(res.code == '400') 
                toastr.error('$resultDanger', '$tit');
        }
    });
    });
});
JS;

$this->registerJs($script, \yii\web\View::POS_END);
