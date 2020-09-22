<?php

use yii\helpers\Html;
use common\grid\MyGridView;
use yii\widgets\Pjax;
use backend\modules\testab\models\AbAddKythuat;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\testab\models\search\CampaignSeach */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Chiến dịch');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Test A/B'), 'url' => ['']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php Pjax::begin(
    ['id' => 'campaign-ajx', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]
); ?>
    <section id="dom">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <div class="row">
                                <div class="col-lg-2 col-xs-2 col-md-3 col-xs-4 col-4 campaign-left">
                                    <?= Html::a('<i class="ft-plus"></i>', 'javascript:void(0)', [
                                        'title' => 'Thêm mới chiến dich',
                                        'data-pjax' => 0,
                                        'data-toggle' => 'modal',
                                        'data-backdrop' => 'static',
                                        'data-keyboard' => false,
                                        'data-target' => '#custom-modal',
                                        'onclick' => 'showModal($(this), "'.\yii\helpers\Url::toRoute(['/testab/chien-dich/create-ajax']).'");return false;',
                                    ])?>
                                    <?= $this->render('_listView', [
                                        'dataProviderCD' => $dataProviderCD,
                                        'id' => $id
                                    ]);
                                    ?>
                                </div>
                                <div class="col-lg-10 col-xs-10 col-md-9 col-xs-8 col-8 campaign-right">
                                    <div class="campaign-content">
                                        <div class="campaign-content-wrap">
                                            <div class="btn-add-campaign clearfix"
                                                 style="margin-top:0px;position:relative">
                                                <?= Html::button(
                                        '<i class="fa fa-plus"></i> Thêm mới chiến dịch con',
                                        [
                                                        'title' => 'Thêm mới chiến dịch con',
                                                        'class' => 'btn btn-default pull-left',
                                                        'data-pjax' => 0,
                                                        'data-toggle' => 'modal',
                                                        'data-backdrop' => 'static',
                                                        'data-keyboard' => false,
                                                        'data-target' => '#custom-modal',
                                                        'onclick' => 'showModal($(this), "'.\yii\helpers\Url::toRoute(['/testab/chien-dich/create-campaign-ajax', 'cid' => $id]).'");return false;',
                                                    ]
                                    )
                                                ?>
                                            </div>
                                            <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                                                <?php
                                                Pjax::begin(
                                                    ['id' => 'campaign-ajax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]
                                                );
                                                ?>
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
                                                    'rowOptions' => function ($model) {
                                                        if ($model->end_date == null && time() - $model->created_at > 3 * 86400) {
                                                            return ['class' => 'campaign-danger'];
                                                        }
                                                        if ($model->end_date != null) {
                                                            return ['class' => 'campaign-ended'];
                                                        }
                                                    },
                                                    'myOptions' => [
                                                        'class' => 'grid-content pane-vScroll',
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
                                                                'class' => 'd-none'
                                                            ],
                                                        ],
                                                        [
                                                            'attribute' => 'created_at',
                                                            'format' => 'date',
                                                            'value' => 'created_at',
                                                            'filter' => \dosamigos\datepicker\DatePicker::widget([
                                                                'model' => $searchModel,
                                                                'attribute' => 'created_at',
                                                                'template' => '{input}{addon}',
//                                                                'addon' => '<i class="fa fa-calendar"></i>',
                                                                'language' => 'vi',
                                                                'clientOptions' => [
                                                                    'autoclose' => true,
                                                                    'format' => 'dd-mm-yyyy',
                                                                ],
                                                                'options' => [
                                                                    'autocomplete' => 'off',
                                                                ]
                                                            ]),
                                                            'contentOptions' => [
                                                                'class' => 'mod-date'
                                                            ],
                                                        ],
                                                        [
                                                            'attribute' => 'name',
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                return Html::a($model->name, ['view', 'id' => $model->id], ['data-pjax' => 0]);
                                                            },
                                                            'headerOptions' => [
                                                                'width' => 300,
                                                            ],
                                                            'contentOptions' => [
                                                                'class' => 'text-left'
                                                            ],
                                                        ],
                                                        [
                                                            'class' => \common\grid\EnumColumn::class,
                                                            'attribute' => 'ky_thuat',
                                                            'format' => 'raw',
                                                            'value' => 'kyThuatHasOne.name',
                                                            'enum' => AbAddKythuat::getKyThuatArray(),
                                                            'filter' => AbAddKythuat::getKyThuatArray(),
                                                            'filterInputOptions' => [
                                                                'class' => 'ui dropdown form-control'
                                                            ],
                                                            'headerOptions' => [
                                                                'width' => 250,
                                                            ],
                                                        ],
                                                        [
                                                            'attribute' => 'link_test',
                                                            'headerOptions' => [
                                                                'width' => 250,
                                                            ],
                                                            'contentOptions' => [
                                                                'class' => 'text-left'
                                                            ],
                                                        ],
                                                        //'content:ntext',
                                                        [
                                                            'attribute' => 'chiphi_thucchay',
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                if ($model->chiphi_thucchay == '') {
                                                                    return null;
                                                                }
                                                                return number_format($model->chiphi_thucchay, 0, '', '.');
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'comment',
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                if ($model->comment == '') {
                                                                    return null;
                                                                }
                                                                return number_format($model->comment, 0, '', '.');
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'tin_nhan',
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                if ($model->tin_nhan == '') {
                                                                    return null;
                                                                }
                                                                return number_format($model->tin_nhan, 0, '', '.');
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'tong_tuong_tac',
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                if ($model->tong_tuong_tac == '') {
                                                                    return null;
                                                                }
                                                                return number_format($model->tong_tuong_tac, 0, '', '.');
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'hien_thi',
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                if ($model->hien_thi == '') {
                                                                    return null;
                                                                }
                                                                return number_format($model->hien_thi, 0, '', '.');
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'tiep_can',
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                if ($model->tiep_can == '') {
                                                                    return null;
                                                                }
                                                                return number_format($model->tiep_can, 0, '', '.');
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'nguoi_xem_1',
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                if ($model->nguoi_xem_1 == '') {
                                                                    return null;
                                                                }
                                                                return number_format($model->nguoi_xem_1, 0, '', '.');
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'nguoi_xem_50',
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                if ($model->nguoi_xem_50 == '') {
                                                                    return null;
                                                                }
                                                                return number_format($model->nguoi_xem_50, 0, '', '.');
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'tan_suat',
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                if ($model->tan_suat == null) {
                                                                    return null;
                                                                }
                                                                return number_format($model->tan_suat, 2, ',', '.');
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'gia_tuong_tac',
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                if ($model->gia_tuong_tac == '' || $model->gia_tuong_tac == null) {
                                                                    return null;
                                                                }
                                                                return number_format($model->gia_tuong_tac, 0, '', '.');
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'gia_hien_thi',
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                if ($model->gia_hien_thi == '' || $model->gia_hien_thi == null) {
                                                                    return null;
                                                                }
                                                                return number_format($model->gia_hien_thi, 0, '', '.');
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'gia_tiep_can',
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                if ($model->gia_tiep_can == '' || $model->gia_tiep_can == null) {
                                                                    return null;
                                                                }
                                                                return number_format($model->gia_tiep_can, 0, '', '.');
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'gia_10s',
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                if ($model->gia_10s == '' || $model->gia_10s == null) {
                                                                    return null;
                                                                }
                                                                return number_format($model->gia_10s, 0, '', '.');
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'gia_50phantram',
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                if ($model->gia_50phantram == '' || $model->gia_50phantram == null) {
                                                                    return null;
                                                                }
                                                                return number_format($model->gia_50phantram, 0, '', '.');
                                                            }
                                                        ],
                                                        //                                [
                                                        //                                    'attribute' => 'status',
                                                        //                                    'format' => 'raw',
                                                        //                                    'value' => function ($model) {
                                                        //                                        return \common\widgets\ModavaCheckbox::widget([
                                                        //                                            'id' => $model->id,
                                                        //                                            'value' => $model->status,
                                                        //                                        ]);
                                                        //                                    },
                                                        //                                ],

                                                        [
                                                            'attribute' => 'created_by',
                                                            'value' => function ($model) {
                                                                $user = new backend\modules\testab\models\AbCampaign();
                                                                $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                                                                if ($userCreatedBy == null) {
                                                                    return null;
                                                                }
                                                                return $userCreatedBy->fullname;
                                                            }
                                                        ],

                                                        [
                                                            'class' => 'yii\grid\ActionColumn',
                                                            'header' => 'Actions',
                                                            'template' => '<div class="btn-group" role="group">{update} {delete}</div>',
                                                            'buttons' => [
                                                                'update' => function ($url, $model) {
                                                                    //return Html::a('<i class="ft-edit blue"></i>', ['/testab/chien-dich/update-campaign', 'id' => $model->id], ['class' => 'btn btn-default', 'data-pjax' => 0]);
                                                                    return Html::button(
                                                                        '<i class="ft-edit blue"></i>',
                                                                        [
                                                                                'class' => 'btn btn-default',
                                                                                'data-pjax' => 0,
                                                                                'data-toggle' => 'modal',
                                                                                'data-backdrop' => 'static',
                                                                                'data-keyboard' => false,
                                                                                'data-target' => '#custom-modal',
                                                                                'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['update-campaign-ajax', 'id' => $model->id]) . '");return false;',
                                                                            ]
                                                                    );
                                                                },
                                                                'delete' => function ($url, $model) {
                                                                    $user = new \backend\modules\user\models\User();
                                                                    $roleUser = $user->getRoleName(Yii::$app->user->id);
                                                                    if ($roleUser == \common\models\User::USER_ADMINISTRATOR ||
                                                                        $roleUser == \common\models\User::USER_DEVELOP) {
                                                                        return Html::a('<i class="ft-trash-2 red confirm-color-campaign" data-id = "' . $model->id . '" ></i>', 'javascript:void(0)', ['class' => 'btn btn-default']);
                                                                    }
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
                                                    ],
                                                ]); ?>
                                                <?php Pjax::end(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php Pjax::end(); ?>

<?php
$urlDelete = \yii\helpers\Url::toRoute(['delete-campaign']);
$urlChangePageSize = \yii\helpers\Url::toRoute(['perpage']);

$tit = Yii::t('backend', 'Notification');

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', 'If delete, you will not be able to recover!');

$script = <<< JS
var campaign = new myGridView();
campaign.init({
    pageParam: 'dp-1-page',
    urlChangePageSize: '$urlChangePageSize'
});

$(document).ready(function () {    
    $('body').on('click', '.confirm-color-campaign', function (e) {
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
                        $.pjax.reload({url: currentUrl, method: 'POST', container: campaign.options.pjaxId});
                    }
                    if(data.status == 'failure' || data.status == 'exception')
                        toastr.error('Xoá không thành công', 'Thông báo');
                }
              });
          }
        });
    });
});

$(document).on('pjax:success', function(e){
    if ("#" + e.target.id == '#campaign-ajx') {
        campaign.setHeightContent();
        campaign.setWidthCol();
        campaign.setEventScroll();
    }
    $(".ui.dropdown").dropdown();
})
JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>
