<?php

use yii\helpers\Html;
use common\grid\MyGridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
use backend\modules\user\models\User;

$this->title = Yii::t('backend', 'Clinics');
$this->params['breadcrumbs'][] = $this->title;

$user = new User();
$roleName = $user->getRoleName(Yii::$app->user->id);
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
                                        'attribute' => 'id',
                                        'filter' => false,
                                        'visible' => in_array($roleName, [
                                            User::USER_DEVELOP,
                                            User::USER_ADMINISTRATOR
                                        ]),
                                        'value' => 'id',
                                        'headerOptions' => [
                                            'width' => 60
                                        ]
                                    ],
                                    [
                                        'attribute' => 'avatar',
                                        'filter' => false,
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            if ($model->avatar == null || !file_exists(Yii::$app->basePath . '/web/uploads/avatar/70x70/' . $model->avatar)) {
                                                $avatar = '/local/default/avatar-default.png';
                                            } else {
                                                $avatar = '/uploads/avatar/70x70/' . $model->avatar;
                                            }
                                            return Html::img($avatar);
                                        },
                                        'headerOptions' => [
                                            'width' => 100
                                        ]
                                    ],
                                    [
                                        'attribute' => 'name',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return Html::a($model->name, ['view', 'id' => $model->id], ['data-pjax' => 0]);
                                        }
                                    ],
                                    [
                                        'attribute' => 'customer_code',
                                        'format' => 'raw',
                                        'value' => 'customer_code',
                                        'headerOptions' => [
                                            'width' => 140
                                        ]
                                    ],
                                    [
                                        'attribute' => 'customer_come',
                                        'filter' => false,
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->customerOnlineComeHasOne == null || $model->customerOnlineComeHasOne->name == null ? null : $model->customerOnlineComeHasOne->name;
                                        }
                                    ],
                                    [
                                        'attribute' => 'created_by',
                                        'filter' => false,
                                        'value' => function ($model) {
                                            $user = new backend\modules\clinic\models\Clinic();
                                            $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                                            return $userCreatedBy->fullname;
                                        },
                                        'headerOptions' => [
                                            'width' => 140
                                        ]
                                    ],

                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'Affiliate',
                                        'template' => '<div class="btn-group" role="group">{affiliate}</div>',
                                        'buttons' => [
                                            'affiliate' => function ($url, $model) {
                                                return Html::a(($model->is_affiliate_created == 1 ? 'Cập nhật' : 'Tạo'), 'javascript:;', ['data-pjax' => 0, 'class' => 'btn btn-default btn-create-affiliate', 'data-customer' => $model->primaryKey]);
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
$urlChangePageSize = \yii\helpers\Url::toRoute(['perpage']);

$tit = Yii::t('backend', 'Notification');

$url_post = yii\helpers\Url::toRoute('/customer/customer-online/create-affiliate');
$script = <<< JS
var customPjax = new myGridView();
customPjax.init({
    pjaxId: '#custom-pjax',
    urlChangePageSize: '$urlChangePageSize',
});
$('body').on('click', '.btn-create-affiliate', function(e){
    e.preventDefault();
    var customer = $(this).attr('data-customer') || null;
    if(customer != null){
        $('body').myLoading({
            opacity: true
        });
        $.ajax({
            url: '$url_post',
            type: 'POST',
            dataType: 'json',
            data: {
                customer: customer
            },
            success: function(data) {
                if(data.status == 200){
                    $.when($.pjax.reload({url: window.location.href, method: 'POST', container: customPjax.options.pjaxId})).done(function(){
                        toastr.success(data.mess, 'Affiliate thông báo');
                        $('body').myUnloading();
                    })
                } else {
                    toastr.error(data.mess, 'Affiliate thông báo');
                    $('body').myUnloading();
                }
            }
        }).fail(function(f){
            toastr.error('Có lỗi khi cập nhật', 'Affiliate thông báo');
            $('body').myUnloading();
        });
    }
    return false;
})
JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>

