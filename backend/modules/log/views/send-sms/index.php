<?php

use yii\helpers\Html;
use common\grid\MyGridView;
use yii\widgets\Pjax;
use backend\components\MtSmsComponent;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\log\models\search\SendSmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Sms log');
$this->params['breadcrumbs'][] = $this->title;
$mtSms = new MtSmsComponent();
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
                            <?= Html::a('<i class="fa fa-plus"> Thêm mới</i>', ['create'], ['title' => 'Thêm mới', 'data-pjax' => 0, 'class' => 'btn btn-default pull-left']) ?>
                        </div>
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                            <?php Pjax::begin(['id' => 'sendsms', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]); ?>
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
                                'columns' => [
                                    [
                                        'class' => 'yii\grid\SerialColumn',
                                        'header' => 'STT',
                                        'headerOptions' => [
                                            'width' => 40,
                                            'rowspan' => 2
                                        ],
                                    ],
//                                [
////                                    'attribute' => 'name',
////                                    'format' => 'raw',
////                                    'value' => function ($model) {
////                                        return Html::a($model->name, ['view', 'id' => $model->id], ['data-pjax' => 0]);
////                                    }
////                                ],
//                                    '',
                                    [
                                        'attribute' => 'name',
                                        'value' => 'customerHasOne.name',
                                        'format' => 'html',
                                        'headerOptions' => [
                                            'width' => 100
                                        ],
                                    ],
//                                'sms_uuid:ntext',
//                                    'sms_text:html',
                                    [
                                        'attribute' => 'sms_text',
                                        'format' => 'html',
                                        'headerOptions' => [
                                            'width' => 300
                                        ],
                                    ],
                                    [
                                        'attribute' => 'sms_to',
                                        'headerOptions' => [
                                            'width' => 100
                                        ],
                                    ],
                                    [
                                        'attribute' => 'created_at',
                                        'label' => 'Ngày giờ gửi',
                                        'format' => 'datetime',
                                        'value' => function ($model) {
                                            return $model->created_at;
                                        },
                                        'headerOptions' => [
                                            'width' => 120
                                        ],
                                    ],
                                    [
                                        'attribute' => 'sms_lanthu',
                                        'headerOptions' => [
                                            'width' => 100
                                        ],
                                        'value' => function ($model) {
                                            if ($model->sms_lanthu == 100) {
                                                return 'Online';
                                            }
                                            return $model->sms_lanthu;
                                        }
                                    ],
                                    [
                                        'attribute' => 'status',
                                        'format' => 'raw',
                                        'value' => function ($model) use ($mtSms) {
                                            if ($model->type == 'mtsms_vht') {
                                                return $mtSms->getMessage($model->status);
                                            }
                                            return \backend\modules\log\models\Dep365SendSms::smsErrorStatus($model->status);
                                        },
                                        'headerOptions' => [
                                            'width' => 100
                                        ],
                                    ],

                                    [
                                        'attribute' => 'created_by',
                                        'label' => 'Người gửi',
                                        'value' => function ($model) {
                                            $user = new backend\modules\log\models\Dep365SendSms();
                                            $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                                            if ($userCreatedBy == false) {
                                                return null;
                                            }
                                            return $userCreatedBy->fullname;
                                        },
                                        'headerOptions' => [
                                            'width' => 120,
                                            'rowspan' => 2
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
$url = \yii\helpers\Url::toRoute(['/modules/controllers/show-hide']);
$urlDelete = \yii\helpers\Url::toRoute(['/test/delete']);
$urlChangePageSize = \yii\helpers\Url::toRoute(['/log/send-sms/perpage']);
$tit = Yii::t('backend', 'Notification');

$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', 'If delete, you will not be able to recover!');

$script = <<< JS
var logSendSms = new myGridView();
logSendSms.init({
    pjaxId: '#sendsms',
    urlChangePageSize: '$urlChangePageSize'
});

$(document).ready(function () {
    $('body').on('change', '.check-toggle', function () {
        var id = $(this).val();
        $.post('$url', {
            id: id
        }, function (result) {
            if (result == 1) {
                toastr.success('$resultSuccess', '$tit');
            }
            if (result == 0) {
                toastr.error('$resultDanger', '$tit');
            }
        });
    });
    $('body').on('click', '.confirm-color', function (e) {
        e.preventDefault();
        var id = JSON.parse($(this).attr("data-id"));
        var table = $(this).parent().parent();
        try {

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
                        data: {
                            "id": id
                        },
                        url: "$urlDelete",
                        dataType: "json",
                        success: function (data) {
                            if (data.status == 'success') {
                                toastr.success('$deleteSuccess', '$tit');
                                table.slideUp("slow");
                            }
                            if (data.status == 'failure')
                                swal("NotAllow", "$deleteDanger", "error");
                            if (data.status == 'exception')
                                swal("NotAllow", "$deleteDanger", "error");
                        }
                    });

                }

            });
        } catch (e) {
            alert(e); //check tosee any errors
        }
    });
});
JS;

$this->registerJs($script, \yii\web\View::POS_END);
?>

