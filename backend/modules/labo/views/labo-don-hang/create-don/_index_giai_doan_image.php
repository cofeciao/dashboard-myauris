<?php

use common\grid\MyGridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\labo\models\search\SearchLaboGiaiDoanImage */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('backend', 'Labo Giai Doan Images');
//$this->params['breadcrumbs'][] = $this->title;
?>

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
                        'data-minus' => '{"0":250,"1":".header-navbar","2":".btn-add-campaign","3":".pager-wrap","4":".footer"}'
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
                            'template' => '<div class="btn-group" role="group">{delete}</div>',
                            'buttons' => [
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
//                                    [
//                                        'attribute' => 'labo_giai_doan_id',
//                                    ],
//                                    'image',
                        [
                            'attribute' => 'image',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Html::img('@web/uploads/tmp/' . $model->image, ['alt' => 'My logo', 'width' => '250px', 'value' => Url::to('@web/uploads/tmp/' . $model->image, true), 'class' => 'button-image rounded']);
                            },
                            'filterOptions' => [
                                'class' => 'd-none',
                            ],
                            'contentOptions' => [
                                'class' => 'text-center',
                            ]
                        ],

                        [
                            'attribute' => 'created_by',
                            'value' => function ($model) {
                                /*$user = new backend\modules\labo\models\LaboGiaiDoanImage();*/
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


<?php
$url = Url::toRoute(['show-hide']);
$urlDelete = Url::toRoute(['labo-giai-doan-image/delete']);
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
    $('body').on('click', '.button-image', function (e) {
        window.open($(this).attr('value'));
    });
});
JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>
