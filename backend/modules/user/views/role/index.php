<?php

use yii\helpers\Html;
use common\grid\MyGridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\user\models\search\RbacAuthItemChildSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Phân Quyền');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'User'), 'url' => ['/user']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?= $this->title; ?></h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a class="block-page"
                                   onclick='window.location="<?= \Yii::$app->getRequest()->getUrl(); ?>"'><i
                                            class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                            <?php Pjax::begin(['id' => 'userRole', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
                            <?= MyGridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
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
                                    ['class' => 'yii\grid\SerialColumn',
                                        'headerOptions' => [
                                            'width' => 60,
                                            'rowspan' => 2
                                        ],
                                        'filterOptions' => [
                                            'class' => 'd-none'
                                        ],
                                    ],
                                    [
                                        'attribute' => 'description',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return Html::a($model->description, yii\helpers\Url::to(['view', 'id' => $model->name]), ['data-pjax' => 0]);
                                        },
                                        'headerOptions' => [
                                            'width' => 200,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'name',
                                        'headerOptions' => [
                                            'width' => 200,
                                        ],
                                    ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'ruleName',
                                        'filter' => ArrayHelper::map(Yii::$app->getAuthManager()->getRules(), 'name', 'name'),
                                        'filterInputOptions' => ['class' => 'form-control', 'prompt' => Yii::t('rbac', 'Select Rule')],
                                        'headerOptions' => [
                                            'width' => 60,
                                            'rowspan' => 2
                                        ],
                                        'filterOptions' => [
                                            'class' => 'd-none'
                                        ],
                                    ],
//                                [
//                                    'class' => \common\grid\EnumColumn::class,
//                                    'attribute' => 'type',
//                                    'enum' => RbacAuthItem::getRoleType(),
//                                    'filter' => RbacAuthItem::getRoleType(),
//                                ],
//                                [
//                                    'class' => \common\grid\EnumColumn::class,
//                                    'attribute' => 'rule_name',
//                                    'filter' => ArrayHelper::map(Yii::$app->getAuthManager()->getRules(), 'name', 'name'),
//                                    'filterInputOptions' => ['class' => 'form-control', 'prompt' => Yii::t('rbac', 'Select Rule')],
//                                ],

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
$url = \yii\helpers\Url::toRoute(['/modules/controllers/show-hide']);
$urlDelete = \yii\helpers\Url::toRoute(['/test/delete']);
$urlChangePageSize = \yii\helpers\Url::toRoute(['/user/role/perpage']);
$tit = Yii::t('backend', 'Notification');

$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', 'If delete, you will not be able to recover!');

$script = <<< JS
var userRole = new myGridView();
userRole.init({
    pjaxId: '#userRole',
    urlChangePageSize: '$urlChangePageSize',
});

$(document).ready(function () {
    $('.check-toggle').change(function () {
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
    $('.confirm-color').on('click', function (e) {
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
                        $.pjax.reload({url: currentUrl, method: 'POST', container: userRole.options.pjaxId});
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

