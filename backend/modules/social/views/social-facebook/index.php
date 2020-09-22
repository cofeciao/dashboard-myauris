<?php

use kartik\export\ExportMenu;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Social Facebook';
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Social'), 'url' => ['']];
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
                    <div class="card-content collapse show customer-index">
                        <div class="card-body card-dashboard">
                            <?php Pjax::begin(
                    ['id' => 'social-facebook',
                                    'timeout' => false, 'enablePushState' => true,
                                    'clientOptions' => ['method' => 'GET']]
                );
                            $gridColumns = [
                                ['class' => 'yii\grid\SerialColumn',
                                    'header' => 'STT',
                                    'headerOptions' => [
                                        'width' => 60
                                    ],
                                    'contentOptions' => [
                                        'style' => 'text-align: center'
                                    ]
                                ],
                                [
                                    'attribute' => 'name',
                                    'format' => 'raw',
                                ],
                                'forename',
                                'full_name',
                                [
                                    'attribute' => 'sex',
                                    'value' => function ($model) {
                                        return $model->sex == \backend\models\CustomerModel::SEX_MAN ? 'Nam' : 'Nữ';
                                    },
                                    'headerOptions' => [
                                        'width' => 100
                                    ],
                                    'contentOptions' => [
                                        'style' => 'text-align: center',
                                    ]
                                ],
                                [
                                    'attribute' => 'status',
                                    'value' => function ($model) {
                                        return $model->statusCustomerHasOne != null ? $model->statusCustomerHasOne->name : '-';
                                    },
                                ],
                                [
                                    'attribute' => 'dat_hen',
                                    'value' => function ($model) {
                                        return $model->statusDatHenHasOne != null ? $model->statusDatHenHasOne->name : '-';
                                    },
                                ],
                                [
                                    'attribute' => 'customer_come_to',
                                    'label' => 'Khách đến',
                                    'value' => function ($model) {
                                        return $model->statusCustomerGotoAurisHasOne != null ? $model->statusCustomerGotoAurisHasOne->name : '-';
                                    },
                                ],
                                [
                                    'attribute' => 'province',
                                    'value' => function ($model) {
                                        return $model->provinceHasOne != null ? $model->provinceHasOne->name : '-';
                                    },
                                ],
                                [
                                    'attribute' => 'face_customer',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return Html::a(
                                            $model->face_customer,
                                            $model->face_customer,
                                            ['target' => 'blank']
                                        );
                                    },
                                    'headerOptions' => [
                                        'style' => 'text-align: center',
                                        'width' => 320
                                    ],
                                ],
                                [
                                    'attribute' => 'created_at',
                                    'content' => function ($model) {
                                        return date('d-m-Y', $model->created_at);
                                    }
                                ]
                            ];
                            echo $this->render('_search', [
                                'model' => $data_filter,
                                'filter' => $filter,
                                'dataProvider' => $dataProvider,
                                'gridColumns' => $gridColumns,
                            ]);
                            ?>
                            <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                                <?= \common\grid\MyGridView::widget([
                                    'id' => 'customer-online-clinic',
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
                                        'data-minus' => '{"0":52,"1":".header-navbar","2":".pager-wrap","3":".footer","4":".form-search","5":".export-all"}'
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
                                    'columns' => $gridColumns,
                                ]);
                                ?>
                            </div>
                            <?php Pjax::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
$urlChangePageSize = Url::toRoute(['/social/social-facebook/perpage']);
$script = <<< JS
var social = new myGridView();
social.init({
    pjaxId: '#social-facebook',
    urlChangePageSize: '$urlChangePageSize',
});
JS;
$this->registerJs($script, \yii\web\View::POS_END);
?>