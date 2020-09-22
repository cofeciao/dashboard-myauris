<?php

use common\grid\MyGridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\labo\models\search\SearchLaboGiaiDoan */
/* @var $dataProvider yii\data\ActiveDataProvider */

//$this->title = Yii::t('backend', 'Labo Giai Doans');
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">
    <div class="card-content collapse show">
        <div class="card-body card-dashboard">
            <div class="btn-add-campaign clearfix" style="margin-top:0px;position:relative">
                <?php // Html::a('<i class="fa fa-plus"> Thêm mới</i>', ['create-giai-doan','labo_don_hang_id' => $labo_don_hang_id], ['title' => 'Thêm mới', 'data-pjax' => 0, 'class' => 'btn btn-default pull-left']) ?>
            </div>
            <?php Pjax::begin(['id' => 'custom-pjax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
            <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                <?= MyGridView::widget([
                    'dataProvider' => $dataProvider,
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
                            'header' => 'Actions',
                            'template' => '<div class="btn-group" role="group">{update-giai-doan} {delete}</div>',
                            'buttons' => [
                                'update-giai-doan' => function ($url, $model) {
                                    return Html::a('<i class="ft-edit blue"></i>', $url, ['data-pjax' => 0, 'class' => 'btn btn-default']);
                                },
                                'delete' => function ($url, $model) {
                                    if(Yii::$app->user->can(\common\models\User::USER_DEVELOP)){
                                        return Html::a('<i class="ft-trash-2 red confirm-color" data-id = "' . json_encode([$model->id]) . '" ></i>', 'javascript:void(0)', ['class' => 'btn btn-default']);
                                    }
                                },
                            ],
                            'headerOptions' => [
                                'width' => '40px',
                                'rowspan' => 2
                            ],
                            'filterOptions' => [
                                'class' => 'd-none'
                            ],
                        ],

                        [
                            'attribute' => 'giai_doan',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->getGiaiDoan();
                            },
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->getStatusView();
                            },
                        ],
                        [
                            'header' => 'Hình',
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->countLaboGiaiDoanImageView();
                            },
                        ],
//                        [
//                            'attribute' => 'status',
//                            'format' => 'raw',
//                            'value' => function ($model) {
//                                return \common\widgets\ModavaCheckbox::widget([
//                                    'id' => $model->id,
//                                    'value' => $model->status,
//                                ]);
//                            },
//                        ],
                        'note:ntext',
                        [
                            'attribute' => 'updated_by',
                            'value' => function ($model) {
                                /*$user = new backend\modules\labo\models\LaboGiaiDoan();*/
                                $user = new \backend\modules\user\models\User();
                                $userCreatedBy = $user->getUserCreatedBy($model->updated_by);
                                if ($userCreatedBy == null) return null;
                                return $userCreatedBy->fullname;
                            }
                        ],
                        'updated_at:datetime',
                    ],
                ]); ?>
            </div>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

<!--xu ly js create-don/create-->

