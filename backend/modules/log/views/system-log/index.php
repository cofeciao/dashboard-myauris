<?php

use common\grid\MyGridView;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\log\models\search\SystemLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Lỗi hệ thống');
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
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                        <?php Pjax::begin(['id' => 'pjaxId', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
                        <?php // echo $this->render('_search', ['model' => $searchModel]);?>
                        <?= MyGridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterSelector' => 'select[name="per-page"]',
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
                                'data-minus' => '{"0":42,"1":".header-navbar","2":".btn-add-campaign","3":".pager-wrap","4":".footer","5":".card-header"}'
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
//                                [
//                                    'class' => 'yii\grid\SerialColumn',
//                                    'header' => 'STT',
//                                ],
                                [
                                    'attribute' => 'level',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $model->level;
                                    },
                                    'headerOptions' => [
                                        'width' => 60,
                                    ]
                                ],
                                [
                                    'attribute' => 'category',
                                    'format' => 'raw',
                                    'headerOptions' => [
                                        'width' => 250
                                    ],
                                ],
                                [
                                    'attribute' => 'log_time',
                                    'format' => 'datetime',
                                    'headerOptions' => [
                                        'width' => 150
                                    ],
                                ],
                                [
                                    'attribute' => 'message',
                                    'format' => 'html',
                                    'headerOptions' => [
                                        'width' => 500,
                                    ],
                                    'contentOptions' => [
                                        'style' => 'white-space:pre-wrap'
                                    ],
                                ],
                                [
                                    'attribute' => 'prefix',
                                    'format' => 'html',
                                    'value' => function ($model) {
                                        return $model->prefix;
                                    },
                                    'headerOptions' => [
                                        'width' => 150
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
</section>
<?php
$urlChangePageSize = \yii\helpers\Url::toRoute(['perpage']);

$script = <<< JS

var systemLog = new myGridView();
systemLog.init({
    pjaxId: '#pjaxId',
    urlChangePageSize: '$urlChangePageSize'
});

JS;

$this->registerJs($script, \yii\web\View::POS_END);
