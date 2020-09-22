<?php

use backend\modules\clinic\models\Clinic;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\labo\models\LaboDonHang;
use common\grid\MyGridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\labo\models\search\SearchLaboDonHang */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Phiếu Đơn Labo');
$this->params['breadcrumbs'][] = $this->title;
$coutLaboDonHangTrangThaiNew = LaboDonHang::coutLaboDonHangTrangThaiNew();
?>
<section id="dom">
    <div class="row">
        <div class="col-12">


            <?php
            if (Yii::$app->session->hasFlash('alert')) {
                ?>
                <div class="alert <?= Yii::$app->session->getFlash('alert')['class']; ?> alert-dismissible"
                     role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <?= Yii::$app->session->getFlash('alert')['body']; ?>
                </div>
                <?php
            }
            ?>

            <?php
            if ($coutLaboDonHangTrangThaiNew > 0):
                ?>
                <div class="alert alert-primary alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    Có <?= $coutLaboDonHangTrangThaiNew ?> phiếu Labo mới
                </div>
            <?php
            endif;
            ?>

            <div class="card">
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">

                        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                        <?php Pjax::begin(['id' => 'custom-pjax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                            <?= MyGridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
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
                                            'width' => 40,
                                            'rowspan' => 2
                                        ],
                                        'filterOptions' => [
                                            'class' => 'd-none',
                                        ],
                                    ],

                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => '#',
                                        'template' => '<div class="btn-group" role="group">{view} </div>',
                                        'buttons' => [
//                                            'update' => function ($url, $model) {
//                                                return Html::a('<i class="ft-edit blue"></i>', $url, ['data-pjax' => 0, 'class' => 'btn btn-default']);
//                                            },
//                                            'delete' => function ($url, $model) {
//                                                if (Yii::$app->user->can(\common\models\User::USER_ADMINISTRATOR)) {
//                                                    return Html::a('<i class="ft-trash-2 red confirm-color" data-id = "' . json_encode([$model->id]) . '" ></i>', 'javascript:void(0)', ['class' => 'btn btn-default']);
//                                                }
//                                                return "";
//                                            },
                                            'view' => function ($url, $model) {
                                                return Html::a(
                                                    '<i class="ft-eye green"></i>',
                                                    Url::toRoute(['/labo/labo-don-hang/view', 'id' => $model->primaryKey]),
                                                    [
                                                        'class' => 'btn btn-default',
                                                    ]
                                                );
                                            },
                                        ],
                                        'headerOptions' => [
                                            'width' => 50,
                                            'rowspan' => 2
                                        ],
                                        'filterOptions' => [
                                            'class' => 'd-none'
                                        ],
                                        'visible' => Yii::$app->user->can(\common\models\User::USER_KY_THUAT_LABO) ? true : false,
                                    ],

                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'Actions ',
                                        'template' => '<div class="btn-group" role="group">{delete} {create-don} </div>',
                                        'buttons' => [
                                            'create-don' => function ($url, $model) {
                                                return Html::a(
                                                    '<i class="ft-edit green"></i>',
                                                    Url::toRoute(['/labo/labo-don-hang/create-don', 'don_id' => $model->phong_kham_don_hang_id]),
                                                    [
                                                        'class' => 'btn btn-default',
                                                    ]
                                                );
//                                                return Html::a('<i class="ft-edit blue"></i>', $url, ['data-pjax' => 0, 'class' => 'btn btn-default']);
                                            },
                                            'delete' => function ($url, $model) {
                                                if (Yii::$app->user->can(User::USER_LE_TAN) || Yii::$app->user->can(User::USER_KE_TOAN )) {
                                                    return Html::a('<i class="ft-trash-2 red confirm-color" data-id = "' . json_encode([$model->id]) . '" ></i>', 'javascript:void(0)', ['class' => 'btn btn-default']);
                                                }
                                                return "";
                                            },
//                                            'view' => function ($url, $model) {
//                                                return Html::a(
//                                                    '<i class="ft-eye green"></i>',
//                                                    Url::toRoute(['/labo/labo-don-hang/view', 'id' => $model->primaryKey]),
//                                                    [
//                                                        'class' => 'btn btn-default',
//                                                    ]
//                                                );
//                                            },
                                        ],
                                        'headerOptions' => [
                                            'width' => 90,
                                            'rowspan' => 2
                                        ],
                                        'filterOptions' => [
                                            'class' => 'd-none'
                                        ],
                                        'visible' => (Yii::$app->user->can(User::USER_LE_TAN) || Yii::$app->user->can(User::USER_KE_TOAN )) ? true : false,
                                    ],

                                    [
                                        'header' => 'Phiếu Labo',
                                        'attribute' => 'keyword',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->getPhieuLabo();
                                        },
                                        'headerOptions' => [
                                            'width' => 210,
                                        ],
                                    ],

                                    [
                                        'attribute' => 'trang_thai',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->getTrangThai();
                                        },
                                        //'visible' => Yii::$app->user->can(\common\models\User::USER_ADMINISTRATOR) ? true : false,
                                        'filter' => LaboDonHang::getListTrangThai(),
                                    ],

                                    [
                                        'attribute' => 'bac_si_id',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $user = new \common\models\UserProfile();
                                            $fullname = $user->getFullNameBacSi($model->bac_si_id);
                                            return $fullname;
                                        },
                                        'visible' => (Yii::$app->user->can(User::USER_LE_TAN) || Yii::$app->user->can(User::USER_KE_TOAN )) ? true : false,

                                        'filter' => Clinic::getEkipbacsi(),
                                    ],

                                    [
                                        'attribute' => 'user_labo',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->getUserLabo();
                                        },
                                        'visible' => (Yii::$app->user->can(User::USER_LE_TAN) || Yii::$app->user->can(User::USER_KE_TOAN )) ? true : false,
//                                        'filter' => Clinic::getEkipbacsi(),
                                    ],

                                    [
                                        'attribute' => 'phong_kham_don_hang_id',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $donhang = PhongKhamDonHang::findOne($model->phong_kham_don_hang_id);
                                            return ($donhang) ? $donhang->order_code : "";
                                        },
                                        'visible' => (Yii::$app->user->can(User::USER_LE_TAN) || Yii::$app->user->can(User::USER_KE_TOAN )) ? true : false,
                                    ],
                                    [
                                        'attribute' => 'ngay_nhan',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return date('d-m-Y', $model->ngay_nhan);
                                        },
                                        'visible' => (Yii::$app->user->can(User::USER_LE_TAN) || Yii::$app->user->can(User::USER_KE_TOAN )) ? true : false,
                                    ],
                                    [
                                        'attribute' => 'ngay_giao',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return date('d-m-Y', $model->ngay_giao);
                                        },
                                        'visible' => (Yii::$app->user->can(User::USER_LE_TAN) || Yii::$app->user->can(User::USER_KE_TOAN )) ? true : false,
                                    ],
                                    [
                                        'attribute' => 'loai_phuc_hinh',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->getLoaiPhucHinh();
                                        },
                                        'visible' => (Yii::$app->user->can(User::USER_LE_TAN) || Yii::$app->user->can(User::USER_KE_TOAN )) ? true : false,
                                    ],

//                                    'bac_si_id',
//                                    'phong_kham_don_hang_id',
//                                    'ngay_nhan',
//                                    'ngay_giao',
//                                    'loai_phuc_hinh',
//                                    'loai_su',
                                    //'yeu_cau:ntext',
                                    //'trang_thai',
//                                    [
//                                        'attribute' => 'status',
//                                        'format' => 'raw',
//                                        'value' => function ($model) {
//                                            return \common\widgets\ModavaCheckbox::widget([
//                                                'id' => $model->id,
//                                                'value' => $model->status,
//                                            ]);
//                                        },
//                                    ],

                                    [
                                        'attribute' => 'created_by',
                                        'value' => function ($model) {
                                            /*$user = new backend\modules\labo\models\LaboDonHang();*/
                                            $user = new \backend\modules\user\models\User();
                                            $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                                            if ($userCreatedBy == null) return null;
                                            return $userCreatedBy->fullname;
                                        }
                                    ],
                                    [
                                        'attribute' => 'created_at',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return date('d-m-Y H:s', $model->created_at);
                                        }
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
    });
    $('body').on('click', '.confirm-color', function (e) {
        e.preventDefault();
        var id = JSON.parse($(this).attr("data-id"));
        var table = $(this).closest('tr');
        var currentUrl = $(location).attr('href');
        Swal.fire({
            title: "$data_title",
            text: "$data_text",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    cache: false,
                    data: {
                        "id": id
                    },
                    url: "$urlDelete",
                    dataType: "json",
                    success: function(data) {
                        if (data.status == 'success') {
                            toastr.success('$deleteSuccess', '$tit');
                            table.slideUp("slow");
                            $.pjax.reload({
                                url: currentUrl,
                                method: 'POST',
                                container: customPjax.options.pjaxId
                            });
                        }
                        if (data.status == 'failure' || data.status == 'exception')
                            toastr.error('Xoá không thành công', 'Thông báo');
                    }
                });
            }
        });
    });
});
JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>

