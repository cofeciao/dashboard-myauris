<?php

use common\grid\MyGridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

$this->title = 'Bác sĩ';
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
                        <?php
                        Pjax::begin(
                            ['id' => 'bacsi-ajax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]
                        );
                        ?>
                        <?php
                        echo $this->render('_search', ['model' => $searchModel]);
                        ?>
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                            <?= MyGridView::widget([
                                'dataProvider' => $dataProvider,
//                                    'filterModel' => $searchModel,
                                'layout' => '{errors} <div class="pane-single-table">{items}</div><div class="pager-wrap clearfix">{summary}' .
                                    Yii::$app->controller->renderPartial('@backend/views/layouts/my-gridview/_goToPage', ['totalPage' => $totalPage, 'currentPage' => Yii::$app->request->get($dataProvider->getPagination()->pageParam)]) .
                                    Yii::$app->controller->renderPartial('@backend/views/layouts/my-gridview/_pageSize') .
                                    '{pager}</div>',
                                'tableOptions' => [
                                    'id' => 'listCampaign',
                                    'class' => 'cp-grid cp-widget pane-hScroll',
                                ],
                                'options' => [
                                    'class' => 'grid-view',
                                    'data-pjax' => 1]
                                ,
                                'rowOptions' => function ($model) {
                                    return ['data-key' => $model->id];
                                },
                                'myOptions' => [
                                    'class' => 'grid-content my-content pane-vScroll',
                                    'data-minus' => '{"0":42,"1":".header-navbar","2":".form-search","3":".pager-wrap","4":".footer"}'
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
                                        'contentOptions' => [
                                            'style' => 'text-align: center'
                                        ]
                                    ],
                                    [
                                        'attribute' => 'avatar',
                                        'header' => 'Avatar',
                                        'format' => 'raw',
                                        'headerOptions' => [
                                            'style' => 'text-align: center',
                                            'width' => 100,
                                        ],
                                        'value' => function ($model) {
                                            if ($model->avatar == null || !file_exists(Yii::$app->basePath . '/web/uploads/avatar/70x70/' . $model->avatar)) {
                                                $avatar = '/local/default/avatar-default.png';
                                            } else {
                                                $avatar = '/uploads/avatar/70x70/' . $model->avatar;
                                            }
                                            return Html::img($avatar);
                                        }
                                    ],
                                    [
                                        'attribute' => 'full_name',
                                        'headerOptions' => [
                                            'width' => 300,
                                        ],
                                        'contentOptions' => [
                                            'class' => 'edittable-customer',
                                            'data-field' => 'fullname',
                                        ],
//                                            'value' => function ($model) {
//                                                return $model->full_name == null ? $model->name : $model->full_name;
//                                            },
                                    ],
                                    [
                                        'attribute' => 'customer_code',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->customer_code;
                                        },
                                        'headerOptions' => [
                                            'width' => 170,
                                        ]
                                    ],
                                    [
                                        'attribute' => 'province',
                                        'value' => 'provinceHasOne.name',
                                        'headerOptions' => [
                                            'width' => 170,
                                        ]
                                    ],
                                    [
                                        'attribute' => 'co_so',
                                        'value' => 'coSoHasOne.name',
                                        'headerOptions' => [
                                            'width' => 100,
                                        ],
                                        'contentOptions' => [
                                            'style' => 'text-align: center'
                                        ]
                                    ],

                                    [
                                        'attribute' => 'customer_come',
                                        'format' => 'datetime',
                                        'label' => 'TG khách đến',
                                        'value' => 'customer_come',
                                        'headerOptions' => [
                                            'width' => 170,
                                        ]
                                    ],
                                    [
                                        'attribute' => 'customer_come_time_to',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return \backend\modules\customer\models\Dep365CustomerOnlineCome::getNameTrangThaiKhachDen($model->customer_come_time_to);
                                        },
                                        'headerOptions' => [
                                            'width' => 200,
                                        ]
                                    ],
                                    [
                                        'attribute' => 'direct_sale',
                                        'format' => 'raw',
                                        'value' => 'directSaleHasOne.fullname',
                                        'headerOptions' => [
                                            'width' => 170,
                                        ],
                                    ],
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'Actions',
                                        'template' => '{update} {check_final}',
                                        'buttons' => [
                                            'update' => function ($url, $model) {
                                                return Html::a('<i class="ft-clipboard blue"></i>', $url, ['title' => 'Cập nhật']);
                                            },
                                            'check_final' => function ($url, $model) {
//                                                return Html::a('<i class="ft-check"></i>', 'javascript:void(0)',
//                                                    [
//                                                        'class' => 'check-final' . ($model->customer_bacsi_check_final == 1 ? ' success' : ''),
//                                                        'title' => $model->customer_bacsi_check_final == 1 ? 'Hoàn thành' : 'Chưa hoàn thành',
//                                                        'onclick' => 'checkFinal(this, ' . $model->id . ')'
//                                                    ]
//                                                );
                                            }
                                        ],
                                        'contentOptions' => [
                                            'class' => 'button-action'
                                        ],
                                        'headerOptions' => [
                                            'width' => 100
                                        ]
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
$urlCheckFinal = Url::toRoute('/bacsi/check-final');
$urlChangePageSize = \yii\helpers\Url::toRoute(['/bacsi/perpage']);

$tit = Yii::t('backend', 'Notification');
$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];

$script = <<< JS
var bacsi = new myGridView();
bacsi.init({
    pjaxId: '#bacsi-ajax',
    urlChangePageSize: '$urlChangePageSize'
});

function checkFinal(e, id) {
    if (!id) return false;
    
    $.post('$urlCheckFinal', {id: id}, function(data){
        if (data == 1) {
            $(e).toggleClass('success');
            toastr.success('$resultSuccess', '$tit');
        } else {
            toastr.error('$resultDanger', '$tit');
        }
    });    
}
JS;
$this->registerJs($script, \yii\web\View::POS_END);
?>
