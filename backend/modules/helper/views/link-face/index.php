<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 17-Apr-19
 * Time: 2:58 PM
 */

use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Facebook khách hàng';
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Help'), 'url' => ['']];
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
            <?php Pjax::begin(); ?>
            <div class="card">
                <div class="card-content collapse show customer-index ">
                    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                    <div class="card-body card-dashboard">
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'layout' => '{errors}
                            <div class="both-side-scroll scroll-example my-content" data-minus=\'{"0":42,"1":".col-lg-3","2":".summary","3":".pagination","4":".header-navbar","5":".footer"}\'>{items}</div> {summary}{pager}',
                            'tableOptions' => [
                                'class' => 'table table-striped table-bordered dom-jQuery-events',
                            ],
                            'pager' => [
                                'firstPageLabel' => 'First',
                                'lastPageLabel' => 'Last',
                                'prevPageLabel' => 'Prev',
                                'nextPageLabel' => 'Next',
                                'maxButtonCount' => 5,

                                    'options' => [
                                    'tag' => 'ul',
                                    'class' => 'pagination pull-right',
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
                                ['class' => 'yii\grid\SerialColumn',
                                    'header' => 'STT',
                                    'headerOptions' => [
                                        'width' => '30px',
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
                                    ],
                                ],
                                [
                                    'attribute' => 'created_at',
                                    'content' => function ($model) {
                                        return date('d-m-Y', $model->created_at);
                                    }
                                ],
                                [
                                    'attribute' => 'updated_at',
                                    'content' => function ($model) {
                                        return date('d-m-Y', $model->updated_at);
                                    }
                                ],
                            ],
                        ]); ?>
                    </div>

                </div>
            </div>
            <?php Pjax::end(); ?>
        </div>
    </div>
</section>
