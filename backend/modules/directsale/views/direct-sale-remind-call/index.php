<?php

use yii\helpers\Html;
use common\grid\MyGridView;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\customer\models\search\CustomerOnlineRemindCallSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Dep365 Customer Online Remind Calls');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Direct Sale'), 'url' => ['']];
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
                        <div class="btn-add-campaign clearfix" style="margin-top:0px;position:relative">
                        </div>
                        <?php Pjax::begin(['id' => 'custom-pjax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
                        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                            <?= MyGridView::widget([
                                'dataProvider' => $dataProvider,
//                                'filterModel' => $searchModel,
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
                                    'data-minus' => '{"0":42,"1":".header-navbar","2":".btn-add-campaign","3":".pager-wrap","4":".footer","5":"#form-search-clinic"}'
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

                                    ],
                                    [
                                        'attribute' => 'remind_call_time',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            if ($model->remind_call_time == null) {
                                                return null;
                                            }
                                            $option = [];
                                            if (date('d-m-Y', $model->remind_call_time) == date('d-m-Y')) {
                                                $option['style'] = 'color: red';
                                            }
                                            return Html::tag('span', date('d-m-Y', $model->remind_call_time), $option);
                                        }
                                    ],
                                    [
                                        'attribute' => 'customer_id',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            if ($model->customerHasOne == null) {
                                                return null;
                                            }
                                            return $model->customerHasOne->name;
                                        }
                                    ],
                                    [
                                        'attribute' => 'customerHasOne.phone',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            if ($model->customerHasOne == null || $model->customerHasOne->phone == null) {
                                                return null;
                                            }
                                            return '<div class="td-phone">' .
                                                Html::a(
                                                    '<button class="btn btn-success white btn-sm"><i class="fa fa-phone"></i></button>',
                                                    'javascript:void(0)',
                                                    [
                                                        'onclick' => 'return typeof mycall == \'object\' ? mycall.makeCall(\'' . $model->customerHasOne->phone . '\') : toastr.warning("Không thể kết nối đến hệ thống gọi")',
                                                        'title' => 'Gọi'
                                                    ]
                                                ) .
                                                Html::button('<i class="fa fa-copy"></i>', [
                                                    'class' => 'btn btn-primary white btn-sm copy-data',
                                                    'data-phone' => $model->customerHasOne->phone,
                                                    'data-copy' => '{"el_copy":"attr-copy","data_copy":"data-phone","success":"toastr.success(\"Đã copy số điện thoại!\")"}',
                                                    'data-pjax' => 0,
                                                    'title' => 'Copy số điện thoại'
                                                ]);
                                        }
                                    ],
                                    [
                                        'attribute' => 'customerOnlineCome.name',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            if ($model->customerOnlineCome == null) {
                                                return null;
                                            }
                                            return $model->customerOnlineCome->name;
                                        }
                                    ],
                                    [
                                        'attribute' => 'type',
                                        'value' => function ($model) {
                                            return \backend\models\Dep365CustomerOnlineRemindCall::NAME_TYPE[$model->type];
                                        }
                                    ],
                                    [
                                        'attribute' => 'note',
                                        'header' => 'Ghi chú',
                                    ],
                                    [
                                        'attribute' => 'created_by',
                                        'value' => function ($model) {
                                            $user = new \backend\models\Dep365CustomerOnlineRemindCall();
                                            $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                                            return $userCreatedBy->fullname;
                                        }
                                    ],

                                    ['class' => 'yii\grid\ActionColumn',
                                        'header' => 'Actions',
                                        'template' => '<div class="btn-group" role="group">{call}</div>',
                                        'buttons' => [
                                            'call' => function ($url, $model) {
                                                return Html::a('<i class="fa fa-phone"></i>', 'javascript:;', ['title' => 'Gọi lại', 'class' => 'btn btn-outline-success', 'onclick' => ($model->customerHasOne == null || $model->customerHasOne->phone == null ? 'return false;' : 'return (typeof mycall == "object" ? mycall.makeCall(\'' . $model->customerHasOne->phone . '\') : false);')]);
                                            },
                                        ],
                                        'headerOptions' => [
                                            'width' => 100,
                                            'rowspan' => 2
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
$url = \yii\helpers\Url::toRoute(['/customer/customer-online-remind-call/show-hide']);
$urlDelete = \yii\helpers\Url::toRoute(['/customer/customer-online-remind-call/delete']);
$urlChangePageSize = \yii\helpers\Url::toRoute(['/customer/customer-online-remind-call/perpage']);


$tit = Yii::t('backend', 'Notification');

$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', 'If delete, you will not be able to recover!');

$urlChangePageSize = \yii\helpers\Url::toRoute(['perpage']);
$script = <<< JS
var mygridview = new myGridView({
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
        var table = $(this).parent().parent();
        try{

            swal({
                title: "$data_title",
                text: "$data_text",
                icon: "warning",
                showCancelButton: true,
                buttons: {
                    cancel: {
                        text: "No, cancel plx!",
                        value: null,
                        visible: true,
                        className: "btn-warning",
                        closeModal: true,
                    },
                    confirm: {
                        text: "Yes, delete it!",
                        value: true,
                        visible: true,
                        className: "",
                        closeModal: true
                    }
                }
            }).then(isConfirm => {
                if (isConfirm) {
                    $.ajax({
                        type: "POST",
                        cache: false,
                        data:{"id":id},
                        url: "$urlDelete",
                        dataType: "json",
                        success: function(data){
                           if(data.status == 'success') {
                               toastr.success('$deleteSuccess', '$tit');
                               table.slideUp("slow");
                           }
                           if(data.status == 'failure')
                               swal("NotAllow", "$deleteDanger", "error");
                           if(data.status == 'exception')
                              swal("NotAllow", "$deleteDanger", "error");
                        }
                    });

                }

            });
        } catch(e)
        {
            alert(e); //check tosee any errors
        }
    });
});
JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>

