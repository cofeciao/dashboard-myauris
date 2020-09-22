<?php

use common\grid\MyGridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use backend\modules\customer\models\Dep365CustomerOnlineDichVu;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\clinic\models\search\PhongKhamKpiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', Yii::t('backend', 'Phòng Khám Kpi - Danh sách'));
$this->params['breadcrumbs'][] = $this->title;
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
                                    'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['create']) . '");return false;',
                                ]
                            )
                            ?>
                        </div>
                        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
                        <?php Pjax::begin(['id' => 'custom-pjax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                            <?= MyGridView::widget([
                                'dataProvider' => $dataProvider,
//                                'filterModel' => $searchModel,
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
                                            'width' => 60,
                                            'rowspan' => 2
                                        ],
                                        'filterOptions' => [
                                            'class' => 'd-none',
                                        ],
                                    ],

                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'Actions',
                                        'template' => '<div class="btn-group" role="group">{update} {delete}</div>',
                                        'buttons' => [
                                            'update' => function ($url, $model) {
                                                return Html::button(
                                                    '<i class="ft-edit blue"></i>',
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
                                            'delete' => function ($url, $model) {
                                                return Html::a('<i class="ft-trash-2 red confirm-color" data-id = "' . json_encode([$model->id]) . '" ></i>', 'javascript:void(0)', ['class' => 'btn btn-default']);
                                            },
                                        ],
                                        'headerOptions' => [
                                            'width' => 100,
                                            'rowspan' => 2
                                        ],
                                        'filterOptions' => [
                                            'class' => 'd-none'
                                        ],
                                    ],
                                    /*[
                                        'attribute' => 'name',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return Html::a($model->name, ['view', 'id' => $model->id], ['data-pjax' => 0]);
                                        }
                                    ],*/
                                    [
                                        'attribute' => 'kpi_time',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return date('m-Y', $model->kpi_time);
                                        },
                                        'headerOptions' => [
                                            'width' => 200,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'id_dich_vu',
                                        'value' => function ($model) {
                                            if ($model->id_dich_vu == null) {
                                                return null;
                                            }
                                            if ($model->dichVuOnlineHasOne != null) {
                                                return $model->dichVuOnlineHasOne->name;
                                            }

                                            return null;
                                        }
                                    ],
                                    [
                                        'attribute' => 'kpi_tuong_tac',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->kpi_tuong_tac;
                                        }
                                    ],
                                    [
                                        'attribute' => 'kpi_lich_hen',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->kpi_lich_hen;
                                        }
                                    ],
                                    [
                                        'attribute' => 'kpi_lich_moi',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->kpi_lich_moi;
                                        }
                                    ],
                                    [
                                        'attribute' => 'kpi_khach_den',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->kpi_khach_den;
                                        }
                                    ],
                                    [
                                        'attribute' => 'kpi_khach_lam',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->kpi_khach_lam;
                                        }
                                    ],
                                    [
                                        'attribute' => 'status',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return \common\widgets\ModavaCheckbox::widget([
                                                'id' => $model->id,
                                                'value' => $model->status,
                                            ]);
                                        },
                                    ],
                                    [
                                        'attribute' => 'created_at',
                                        'format' => 'date',
                                        'value' => 'created_at',
                                        'headerOptions' => [
                                            'width' => 200,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'created_by',
                                        'value' => function ($model) {
                                            /*$user = new backend\modules\clinic\models\PhongKhamKpi();*/
                                            $user = new \backend\modules\user\models\User();
                                            $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                                            if ($userCreatedBy == null) return null;
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

