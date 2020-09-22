<?php

use backend\modules\user\models\User;
use yii\helpers\Html;
use common\grid\MyGridView;
use yii\widgets\Pjax;
use backend\modules\baocao\models\BaocaoChayAdsFace;
use backend\modules\customer\models\Dep365CustomerOnlineFanpage;
use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\customer\models\Dep365CustomerOnlineDichVu;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\baocao\models\search\BaocaoChayAdsFaceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Báo cáo chạy Advertising');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Report'), 'url' => ['']];
$this->params['breadcrumbs'][] = $this->title;

//Check visible đơn vị chạy ads
$donvi = false;

$user = new User();
$roleUser = $user->getRoleName(Yii::$app->user->id);
$roleDev = User::USER_DEVELOP;

if ($roleUser == $roleDev || $roleUser == User::USER_DEVELOP) {
    $donvi = true;
}


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
            <div class="card">
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <div class="btn-add-campaign clearfix" style="margin-top:0px;position:relative">
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
                                    'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['/baocao/baocao-chay-ads-face/create']) . '");return false;',
                                ]
                            )
                            ?>
                            <?= Html::button(
                                '<i class="fa fa-refresh"></i> Đồng bộ',
                                [
                                    'title' => 'Đồng Bộ',
                                    'class' => 'btn btn-default pull-left dong-bo',
                                    'data-pjax' => 0,
                                ]
                            )
                            ?>
                            <?= Html::button(
                                '<i class="fa fa-upload"></i> Import',
                                [
                                    'title' => 'Import data',
                                    'class' => 'btn btn-default pull-left import',
                                    'data-pjax' => 0,
                                    'data-toggle' => 'modal',
                                    'data-backdrop' => 'static',
                                    'data-keyboard' => false,
                                    'data-target' => '#custom-modal',
                                    'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['/baocao/baocao-chay-ads-face/import']) . '");return false;',
                                ]
                            )
                            ?>
                        </div>
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                            <?php Pjax::begin(
                                ['id' => 'chay-face-ads-pjax', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]
                            ); ?>
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
                                    ],
                                    ['class' => 'yii\grid\ActionColumn',
                                        'header' => 'Actions',
                                        'template' => '<div class="btn-group" role="group">{update} {delete}</div>',
                                        'buttons' => [
                                            'update' => function ($url, $model) {
                                                return Html::button(
                                                    '<i class="ft-edit blue"></i>',
                                                    [
                                                        'class' => 'btn btn-default',
                                                        'data-pjax' => 0,
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop' => 'static',
                                                        'data-keyboard' => false,
                                                        'data-target' => '#custom-modal',
                                                        'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['/baocao/baocao-chay-ads-face/update', 'id' => $model->id]) . '");return false;',
                                                    ]
                                                );
                                            },
                                            'delete' => function ($url, $model) {
                                                return Html::a('<i class="ft-trash-2 red confirm-color" data-id = "' . $model->id . '" ></i>', 'javascript:void(0)', ['class' => 'btn btn-default']);
                                            },
                                        ],
                                        'headerOptions' => [
                                            'width' => 100,
                                            'rowspan' => 2
                                        ],
                                        'filterOptions' => [
                                            'class' => 'd-none',
                                        ],
                                    ],
                                    [
                                        'attribute' => 'ngay_chay',
                                        'format' => 'date',
                                        'value' => 'ngay_chay',
                                        'filter' => \dosamigos\datepicker\DatePicker::widget([
                                            'model' => $searchModel,
                                            'attribute' => 'ngay_chay',
                                            'template' => '{input}{addon}',
                                            'addon' => '<i class="fa fa-calendar"></i>',
                                            'language' => 'vi',
                                            'clientOptions' => [
                                                'autoclose' => true,
                                                'format' => 'dd-mm-yyyy',
                                            ],
                                            'options' => [
                                                'autocomplete' => 'off',
                                            ]
                                        ]),
                                        'headerOptions' => [
                                            'width' => 200,
                                        ],
                                    ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'don_vi',
                                        'visible' => $donvi,
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $name = \common\models\UserProfile::getFullNameChayAds($model->don_vi);
                                            if ($name == null) {
                                                return null;
                                            }
                                            return Html::a($name, ['#'], ['data-pjax' => 0]);
//                                        return Html::a($name, ['view', 'id' => $model->id], ['data-pjax' => 0]);
                                        },
                                        'enum' => BaocaoChayAdsFace::getDonViChayAdvertising(),
                                        'filter' => BaocaoChayAdsFace::getDonViChayAdvertising(),
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                        'headerOptions' => [
                                            'width' => 200,
                                        ],

                                    ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'location_id',
                                        'format' => 'raw',
                                        'value' => 'locationHasOne.name',
                                        'enum' => BaocaoLocation::getBaocaoLocationArray(),
                                        'filter' => BaocaoLocation::getBaocaoLocationArray(),
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                        'headerOptions' => [
                                            'width' => 220,
                                        ],
                                    ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'page_chay',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $page = Dep365CustomerOnlineFanpage::getNameFanpage($model->page_chay);
                                            return $page;
//                                        return Html::a($name, ['view', 'id' => $model->id], ['data-pjax' => 0]);
                                        },
                                        'enum' => Dep365CustomerOnlineFanpage::getListFanpageArray(),
                                        'filter' => Dep365CustomerOnlineFanpage::getListFanpageArray(),
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                        'headerOptions' => [
                                            'width' => 220,
                                        ],
                                    ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'san_pham',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            if ($model->san_pham == null) {
                                                return null;
                                            }

                                            return $model->sanPhamHasOne->name;
                                        },
                                        'enum' => Dep365CustomerOnlineDichVu::getSanPhamDichVuArray(),
                                        'filter' => Dep365CustomerOnlineDichVu::getSanPhamDichVuArray(),
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                        'headerOptions' => [
                                            'width' => 220,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'so_tien_chay',
                                        'filter' => false,
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return number_format($model->so_tien_chay, 0, ',', '.');
                                        }
                                    ],
                                    [
                                        'attribute' => 'hien_thi',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return number_format($model->hien_thi, 0, ',', '.');
                                        },
                                    ],
                                    [
                                        'attribute' => 'tiep_can',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return number_format($model->tiep_can, 0, ',', '.');
                                        },
                                    ],
                                    [
                                        'attribute' => 'binh_luan',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return number_format($model->binh_luan, 0, ',', '.');
                                        },
                                    ],
                                    [
                                        'attribute' => 'tin_nhan',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return number_format($model->tin_nhan, 0, ',', '.');
                                        },
                                    ],
                                    [
                                        'attribute' => 'tuong_tac',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return number_format($model->tuong_tac, 0, ',', '.');
                                        },
                                    ],
                                    [
                                        'attribute' => 'so_dien_thoai',
                                        'format' => 'raw',
                                        'label' => 'SDT',
                                        'value' => function ($model) {
                                            return number_format($model->so_dien_thoai, 0, ',', '.');
                                        },
                                    ],
                                    [
                                        'attribute' => 'goi_duoc',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return number_format($model->goi_duoc, 0, ',', '.');
                                        },
                                    ],
                                    [
                                        'attribute' => 'lich_hen',
                                        'format' => 'raw',
                                        'label' => 'Lịch mới',
                                        'value' => function ($model) {
                                            return number_format($model->lich_hen, 0, ',', '.');
                                        },
                                    ],
                                    [
                                        'attribute' => 'khach_den',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return number_format($model->khach_den, 0, ',', '.');
                                        },
                                    ],
                                    [
                                        'attribute' => 'money_hienthi',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return number_format($model->money_hienthi, 0, ',', '.');
                                        },
                                    ],
                                    [
                                        'attribute' => 'money_tiepcan',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return number_format($model->money_tiepcan, 0, ',', '.');
                                        },
                                    ],
//                                [
//                                    'attribute' => 'money_binhluan',
//                                    'format' => 'raw',
//                                    'value' => function ($model) {
//                                        return number_format($model->money_binhluan, 0, ',', '.');
//                                    },
//                                ],
//                                [
//                                    'attribute' => 'money_tinnhan',
//                                    'format' => 'raw',
//                                    'value' => function ($model) {
//                                        return number_format($model->money_tinnhan, 0, ',', '.');
//                                    },
//                                ],
                                    [
                                        'attribute' => 'money_tuongtac',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return number_format($model->money_tuongtac, 0, ',', '.');
                                        },
                                    ],
                                    [
                                        'attribute' => 'money_sodienthoai',
                                        'format' => 'raw',
                                        'label' => 'Giá SDT',
                                        'value' => function ($model) {
                                            return number_format($model->money_sodienthoai, 0, ',', '.');
                                        },
                                    ],
                                    [
                                        'attribute' => 'money_goiduoc',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return number_format($model->money_goiduoc, 0, ',', '.');
                                        },
                                    ],
                                    [
                                        'attribute' => 'money_lichhen',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return number_format($model->money_lichhen, 0, ',', '.');
                                        },
                                    ],
//                                [
//                                    'attribute' => 'money_khachden',
//                                    'format' => 'raw',
//                                    'value' => function ($model) {
//                                        return number_format($model->money_khachden, 0, ',', '.');
//                                    },
//                                ],
                                    [
                                        'attribute' => 'created_by',
                                        'value' => function ($model) {
                                            $user = new backend\modules\baocao\models\BaocaoChayAdsFace();
                                            $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                                            return $userCreatedBy->fullname;
                                        }
                                    ],
                                ],
                            ]); ?>
                            <?php Pjax::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$url = \yii\helpers\Url::toRoute(['/baocao/baocao-chay-ads-face/show-hide']);
$urlDelete = \yii\helpers\Url::toRoute(['/baocao/baocao-chay-ads-face/delete']);
$urlChangePageSize = \yii\helpers\Url::toRoute(['/baocao/baocao-chay-ads-face/perpage']);
$urlDongBo = \yii\helpers\Url::toRoute(['/baocao/baocao-chay-ads-face/dong-bo']);
$urlImport = \yii\helpers\Url::toRoute(['/baocao/baocao-chay-ads-face/import-success']);

$tit = Yii::t('backend', 'Notification');

$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', 'If delete, you will not be able to recover!');

$script = <<< JS
var adsFace = new myGridView();
adsFace.init({
    pjaxId: '#chay-face-ads-pjax',
    urlChangePageSize: '$urlChangePageSize'
});

$('body').on('click', '.dong-bo', function() {
    var currentUrl = $(location).attr('href');
    $('#chay-face-ads-pjax').myLoading({
        msg: 'Đang đồng bộ',
        opacity: true
    });
    $.ajax({
        type: 'POST',
        dataType: 'json',
        url: '$urlDongBo',
    }).done(function(res) {
        if(res.status == '200'){
            $.pjax.reload({url: currentUrl, method: 'POST', container: '#chay-face-ads-pjax'});
            toastr.success(res.msg, 'Thành công');
        }
        else if(res.status == '201') {
            $('#chay-face-ads-pjax').myUnloading();
            toastr.error(res.msg, 'Lỗi');
        }
    }).fail(function(err) {
        $('#chay-face-ads-pjax').myUnloading();
    });
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
                        $.pjax.reload({url: currentUrl, method: 'POST', container: adsFace.options.pjaxId});
                    }
                    if(data.status == 'failure' || data.status == 'exception')
                        toastr.error('Xoá không thành công', 'Thông báo');
                }
              });
          }
        });
    });
});
$('body').on('beforeSubmit', 'form#form-baocao-ads-import', function (event) {
    var currentUrl = $(location).attr('href');
    var dataFile = new FormData($('#form-baocao-ads-import')[0]);
    $('.import-ads').myLoading({
        opacity: true,
        size: 'sm',
    });
    $.ajax({
        type: "POST",
        url: "$urlImport",
        data: dataFile,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false
    }).done(function(res) {
        console.log(res);
        if(res.status == '200') {
            $('.import-ads').myUnloading();
            $.when($.pjax.reload({url: currentUrl, method: 'POST', container: adsFace.options.pjaxId})).done(function() {
                $('.card-content').find('.close-import').trigger('click');
                toastr.success(res.msg, 'Thông báo');
            });
        }
        if(res.status == '201')
            toastr.error(res.msg, 'Thông báo');
    }).fail(function(err) {
        console.log(err);
        $('.import-ads').myUnloading();
    });
    return false;
});
JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>

