<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 16-May-19
 * Time: 10:13 AM
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use common\grid\MyGridView;

$this->title = 'Báo cáo doanh thu';
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Report'), 'url' => ['']];
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
                    ['id' => 'baocao-doanhthu-ajax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]
                ); ?>
                            <?php
                            echo $this->render('_search', ['model' => $searchModel]);
                            ?>
                            <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                                <?= MyGridView::widget([
                                    'id' => 'bao-cao-doanh-thu',
                                    'dataProvider' => $dataProvider,
//                                    'filterModel' => $searchModel,
                                    'layout' => '{errors} <div class="pane-single-table">{items}</div><div class="pager-wrap clearfix">{summary}' .
                                        Yii::$app->controller->renderPartial('@backend/views/layouts/my-gridview/_goToPage', [
                                            'totalPage' => $totalPage,
                                            'currentPage' => Yii::$app->request->get($dataProvider->getPagination()->pageParam)]) .
                                        Yii::$app->controller->renderPartial('@backend/views/layouts/my-gridview/_pageSize') .
                                        '{pager}</div>',
                                    'tableOptions' => [
                                        'id' => 'listCampaign',
                                        'class' => 'cp-grid cp-widget pane-hScroll',
                                    ],
                                    'options' => [
                                        'class' => 'grid-view',
                                        'data-pjax' => 1
                                    ],
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
                                            ],
                                            'contentOptions' => [
                                                'style' => 'text-align: center'
                                            ]
                                        ],
                                        [
                                            'attribute' => 'order_code',
                                            'header' => 'Mã đơn hàng',
                                            'format' => 'raw',
                                            'headerOptions' => [
                                                'width' => 100,
                                                'style' => 'text-align: center'
                                            ],
                                            'contentOptions' => [
                                                'style' => 'text-align: center'
                                            ],
                                            'value' => 'order_code'
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
$urlChangePageSize = Url::toRoute(['/bao-cao-doanhthu/perpage']);
$script = <<< JS
var directSale = new myGridView();
directSale.init({
    pjaxId: '#baocao-doanhthu-ajax',
    urlChangePageSize: '$urlChangePageSize'
});
JS;

$this->registerJs($script, \yii\web\View::POS_END);
