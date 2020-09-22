<?php

use yii\helpers\Html;
use common\grid\MyGridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use backend\modules\clinic\models\PhongKhamDonHangWThanhToan;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\clinic\models\search\PhongKhamDonHangWThanhToanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Danh sách thanh toán');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Clinic'), 'url' => ['/clinic']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Order'), 'url' => ['/clinic/clinic-order']];
$this->params['breadcrumbs'][] = $this->title;
?>
    <section id="dom">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <div class="btn-add-campaign clearfix" style="margin-top:0px;position:relative">
                                <?php //Html::a('<i class="fa fa-plus"> Thêm mới</i>', ['create'], ['title' => 'Thêm mới', 'data-pjax' => 0, 'class' => 'btn btn-default pull-left'])?>
                            </div>
                            <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                            <?php Pjax::begin(['id' => 'custom-pjax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
                            <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                                <?= MyGridView::widget([
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
                                    'myOptions' => [
                                        'class' => 'grid-content my-content pane-vScroll',
                                        'data-minus' => '{"0":42,"1":".header-navbar","2":".form-search","3":".pager-wrap","4":".footer","5":".grid-footer"}'
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
                                            'footerOptions' => [
                                                'colspan' => 4,
                                                'class' => 'text-right font-weight-bold'
                                            ],
                                            'footer' => 'Tổng: '
                                        ],
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'header' => 'Actions',
                                            'template' => '<div class="btn-group" role="group">{update} {print} {delete}</div>',
                                            'buttons' => [
                                                'update' => function ($url, $model) {
                                                    return Html::a('<i class="ft-edit blue"></i>', $url, [
                                                        'title' => 'Cập nhật',
                                                        'class' => 'btn btn-default',
                                                        'data-pjax' => 0,
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop' => 'static',
                                                        'data-keyboard' => false,
                                                        'data-target' => '#custom-modal',
                                                        'onclick' => 'showModal($(this), "' . $url . '");return false;',
                                                    ]);
                                                },
                                                'print' => function ($url, $model) {
                                                    return '<a class="btn btn-default print-payment" data-href="' . Url::toRoute(['print-payment', 'id' => $model->primaryKey]) . '"><i class="ft-printer"></i></a>';
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
                                                'class' => 'd-none'
                                            ],
                                            'footerOptions' => [
                                                'class' => 'd-none'
                                            ]
                                        ],
                                        [
                                            'attribute' => 'customer_id',
                                            'value' => function ($model) {
                                                if ($model->customerHasOne == null) {
                                                    return null;
                                                }
                                                return $model->customerHasOne->full_name != null ? $model->customerHasOne->full_name : $model->customerHasOne->name;
                                            },
                                            'footerOptions' => [
                                                'class' => 'd-none'
                                            ]
                                        ],
                                        [
                                            'attribute' => 'phong_kham_don_hang_id',
                                            'value' => function ($model) {
                                                if ($model->donHangHasOne == null) {
                                                    return null;
                                                }
                                                return $model->donHangHasOne->order_code;
                                            },
                                            'footerOptions' => [
                                                'class' => 'd-none'
                                            ]
                                        ],
                                        [
                                            'attribute' => 'tien_thanh_toan',
                                            'format' => 'raw',
                                            'filter' => false,
                                            'value' => function ($model) {
                                                if ($model->tien_thanh_toan == null) {
                                                    return null;
                                                }
                                                if ($model->tam_ung == PhongKhamDonHangWThanhToan::HOAN_COC) {
                                                    return '<span class="red">-' . number_format($model->tien_thanh_toan, 0, '', '.') . '</span>';
                                                } else {
                                                    return '<span class="blue">+' . number_format($model->tien_thanh_toan, 0, '', '.') . '</span>';
                                                }
                                            },
                                            'footer' => '<span style="color: #0E7E12">' . number_format($so_tien_thanh_toan, 0, '', '.') . '</span>',
                                        ],
                                        [
                                            'class' => \common\grid\EnumColumn::class,
                                            'attribute' => 'loai_thanh_toan',
                                            'value' => function ($model) {
                                                return $model->loai_thanh_toan;
                                            },
                                            'enum' => \yii\helpers\ArrayHelper::map(\backend\modules\clinic\models\PhongKhamLoaiThanhToan::getClinicLoaiThanhToan(), 'id', 'name'),
                                            'filter' => \yii\helpers\ArrayHelper::map(\backend\modules\clinic\models\PhongKhamLoaiThanhToan::getClinicLoaiThanhToan(), 'id', 'name'),
                                            'filterInputOptions' => [
                                                'class' => 'ui dropdown form-control'
                                            ],
                                        ],
                                        [
                                            'class' => \common\grid\EnumColumn::class,
                                            'attribute' => 'tam_ung',
                                            'format' => 'raw',
                                            'value' => function ($model) {
                                                if ($model->tam_ung === null || !array_key_exists($model->tam_ung, \backend\models\doanhthu\ThanhToanModel::THANHTOAN_TYPE)) {
                                                    return null;
                                                }
                                                if ($model->tam_ung == PhongKhamDonHangWThanhToan::HOAN_COC) {
                                                    return '<span class="red">' . \backend\models\doanhthu\ThanhToanModel::THANHTOAN_TYPE[$model->tam_ung] . '</span>';
                                                } else {
                                                    return \backend\models\doanhthu\ThanhToanModel::THANHTOAN_TYPE[$model->tam_ung];
                                                }
                                            },
                                            'enum' => \backend\models\doanhthu\ThanhToanModel::THANHTOAN_TYPE,
                                            'filter' => \backend\models\doanhthu\ThanhToanModel::THANHTOAN_TYPE,
                                            'filterInputOptions' => [
                                                'class' => 'ui dropdown form-control'
                                            ],
                                        ],
                                        'ngay_tao:datetime',
                                        'created_at:datetime',
                                        [
                                            'attribute' => 'created_by',
                                            'filter' => false,
                                            'value' => function ($model) {
                                                $user = new backend\modules\clinic\models\PhongKhamDonHangWThanhToan();
                                                $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                                                return $userCreatedBy->fullname;
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
    <iframe class="data-load hidden" src="" frameborder="0" style="width: 100%;min-height: 500px;"></iframe>
<?php
$url = \yii\helpers\Url::toRoute(['/clinic/clinic-payment/show-hide']);
$urlDelete = \yii\helpers\Url::toRoute(['/clinic/clinic-payment/delete']);
$urlChangePageSize = \yii\helpers\Url::toRoute(['/clinic/clinic-payment/perpage']);

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
    }).on('beforeSubmit', '#form-clinic-payment', function(e) {
        e.preventDefault();
        var form = $(this),
            form_data = form.serialize();
        form.myLoading({
            opacity: true
        });
        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            dataType: 'json',
            data: form_data
        }).done(function(res) {
            if(res.code == 200){
                toastr.success(res.msg, 'Thông báo');
                $.when($.pjax.reload({url: window.location.href, method: 'POST', container: customPjax.options.pjaxId})).done(function(){
                    $('#custom-modal').find('.close').trigger('click');
                    form.myUnloading();
                });
            } else {
                toastr.error(res.msg, 'Thông báo');
                form.myUnloading();
            }
        }).fail(function(err) {
            console.log('submit form clinic payment fail', err);
            form.myUnloading();
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
});
JS;

$this->registerJs($script, \yii\web\View::POS_END);
