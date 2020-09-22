<?php

use common\grid\MyGridView;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\clinic\models\search\PhongKhamHinhTkncSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Thiết kế nụ cười');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Clinic'), 'url' => ['/clinic']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content collapse show ">
                    <div class="card-body card-dashboard">
                        <?php Pjax::begin(['id' => 'clinic-tknc', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
                        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
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
                                    ],
                                    [
                                        'attribute' => 'full_name',
                                        'format' => 'raw',
                                        'headerOptions' => [
                                            'width' => 250,
                                        ],
                                        'value' => function ($model) {
                                            $name = $model->full_name == null ? $model->name : $model->full_name;
                                            return Html::a($name, ['upload', 'id' => $model->id], ['data-pjax' => 0, 'class' => 'name-customer']);
                                        }
                                    ],
                                    [
                                        'attribute' => 'customer_code',
                                        'label' => 'Mã khách hàng',
                                        'format' => 'html',
                                        'value' => 'customer_code'
                                    ],
                                    [
                                        'attribute' => 'sex',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->sex == 1 ? 'Nam' : 'Nữ';
                                        }
                                    ],
                                    'provinceHasOne.name',
                                    [
                                        'attribute' => 'dat_hen',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->dat_hen == 1 ? 'Đã đến' : 'Chưa đến';
                                        }
                                    ],
                                    'co_so',
                                    [
                                        'attribute' => 'note',
                                        'format' => 'raw',
                                        'headerOptions' => [
                                            'width' => 200,
                                        ],
                                        'label' => 'Ghi chú Tư Vấn Online',
                                        'value' => function ($model) {
                                            return $model->note;
                                        }
                                    ],
                                    [
                                        'format' => 'raw',
                                        'label' => 'Direct Sale',
                                        'value' => 'directSaleHasOne.fullname'
                                    ],
                                    'note_direct',
                                ],
                            ]); ?>
                        </div>
                        <?php Pjax::end() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$urlChangePageSize = \yii\helpers\Url::toRoute(['perpage']);

$script = <<< JS
var clinic = new myGridView();
clinic.init({
    pjaxId: '#clinic-tknc',
    urlChangePageSize: '$urlChangePageSize'
});
$(document).ready(function() {
    $('body').on('click', '.name-customer', function() {
        $('#clinic-tknc').myLoading({
            msg: 'Đang tải hình',
            opacity: true,
        });
    });
});
JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>