<?php

use yii\helpers\Html;
use common\grid\MyGridView;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\toothstatus\models\search\DichVuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Dich Vu');
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
                            <?=
                            Html::a('<i class="fa fa-plus"> Thêm mới</i>', ['create'], ['title' => 'Thêm mới', 'data-pjax' => 0, 'class' => 'btn btn-default pull-left']) ?>
                        </div>
                        <?php // echo $this->render('_search', ['model' => $searchModel]);?>
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
                                    'data-minus' => '{"0":42,"1":".header-navbar","2":".btn-add-campaign","3":".form-search","4":".pager-wrap","5":".grid-footer","6":".footer"}'
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
                                        'attribute' => 'name',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return Html::a($model->name, ['view', 'id' => $model->id], ['data-pjax' => 0]);
                                        }
                                    ],
                                    [
                                        'attribute' => 'tinh_trang_rang',
                                        'format' => 'raw',
                                        'header' => 'Tình trạng răng',
                                        'value' => function ($model) {
                                            if ($model->tinhTrangRangHasMany == null) {
                                                return null;
                                            }
                                            $tinh_trang_rangs = [];
                                            foreach ($model->tinhTrangRangHasMany as $tinh_trang_rang) {
                                                $tinh_trang_rangs[] = $tinh_trang_rang->name;
                                            }
                                            return '- '.implode('<br/>- ', $tinh_trang_rangs);
                                        }
                                    ],
                                    [
                                        'attribute' => 'do_tuoi',
                                        'format' => 'raw',
                                        'header' => 'Độ tuổi',
                                        'value' => function ($model) {
                                            if ($model->doTuoiHasMany == null) {
                                                return null;
                                            }
                                            $do_tuois = [];
                                            foreach ($model->doTuoiHasMany as $do_tuoi) {
                                                $do_tuois[] = $do_tuoi->name;
                                            }
                                            return '- '.implode('<br/>- ', $do_tuois);
                                        }
                                    ],
                                    [
                                        'attribute' => 'lua_chon',
                                        'format' => 'raw',
                                        'header' => 'Lựa chọn',
                                        'value' => function ($model) {
                                            if ($model->luaChonHasMany == null) {
                                                return null;
                                            }
                                            $lua_chons = [];
                                            foreach ($model->luaChonHasMany as $lua_chon) {
                                                $lua_chons[] = $lua_chon->name;
                                            }
                                            return '- '.implode('<br/>- ', $lua_chons);
                                        }
                                    ],
                                    [
                                        'attribute' => 'price',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            if ($model->price == null) {
                                                return null;
                                            }
                                            return number_format($model->price, 0, '', '.');
                                        }
                                    ],
                                    'description:ntext',
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
                                        'attribute' => 'created_by',
                                        'value' => function ($model) {
                                            $user = new backend\modules\toothstatus\models\DichVu();
                                            $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                                            return $userCreatedBy->fullname;
                                        }
                                    ],

                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'Actions',
                                        'template' => '<div class="btn-group" role="group">{update} {delete}</div>',
                                        'buttons' => [
                                            'update' => function ($url, $model) {
                                                return Html::a('<i class="ft-edit blue"></i>', $url, ['data-pjax' => 0, 'class' => 'btn btn-default']);
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
$url = \yii\helpers\Url::toRoute(['show-hide']);
$urlDelete = \yii\helpers\Url::toRoute(['delete']);
$urlChangePageSize = \yii\helpers\Url::toRoute(['perpage']);

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
?>

