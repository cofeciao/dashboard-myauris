<?php

use yii\helpers\Html;
use common\grid\MyGridView;
use yii\widgets\Pjax;
use backend\modules\log\models\CallLog;
use dosamigos\datepicker\DatePicker;
use common\models\User;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\log\models\search\CallLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Call Logs');
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
                            <?php Pjax::begin(['id' => 'LogCallLog', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
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
                                    'call_id',
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'format' => 'html',
                                        'attribute' => 'call_den_di',
                                        'value' => function ($model) {
                                            if ($model->call_den_di == 1) {
                                                return '<div class="badge badge-success">Gọi đi</div>';
                                            }
                                            return '<div class="badge badge-warning">Gọi đến</div>';
                                        },
                                        'enum' => CallLog::getCallIncomingOrAway(),
                                        'filter' => CallLog::getCallIncomingOrAway()
                                    ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'call_status',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            return CallLog::getStatusCall($model->call_status);
                                        },
                                        'enum' => CallLog::getCallStatus(),
                                        'filter' => CallLog::getCallStatus()
                                    ],
                                    [
                                        'attribute' => 'call_time',
                                        'value' => function ($model) {
                                            if ($model->call_time == 0) {
                                                return null;
                                            }
                                            return \common\helpers\MyHelper::SecondsToTime($model->call_time);
                                        }
                                    ],
                                    [
                                        'attribute' => 'call_time_start',
                                        'value' => function ($model) {
                                            if ($model->call_time_start == null) {
                                                return null;
                                            }
                                            return date('d-m-Y H:i:s', $model->call_time_start);
                                        },
                                    ],
                                    'phone',
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'user_id',
                                        'value' => function ($model) {
                                            $userProfile = \common\models\UserProfile::getFullName($model->user_id);
                                            if (!$userProfile) {
                                                return null;
                                            }
                                            return $userProfile;
                                        },
                                        'enum' => User::getNhanVienOnline(),
                                        'filter' => \kartik\select2\Select2::widget([
                                            'model' => $searchModel,
                                            'attribute' => 'user_id',
                                            'data' => User::getNhanVienOnline(),
                                            'theme' => \kartik\select2\Select2::THEME_DEFAULT,
                                            'hideSearch' => false,
                                            'options' => [
                                                'placeholder' => 'Tìm nhân viên',
                                            ],
                                            'pluginOptions' => [
                                                'allowClear' => true,
                                                'data-pjax' => false,
                                            ],
                                        ])
                                    ],
                                    [
                                        'attribute' => 'customer_id',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $customer = new \backend\models\CustomerModel();
                                            $nameCustomer = $customer->getOneCustomer($model->customer_id);
                                            if ($nameCustomer == null) {
                                                return '<label class="btn btn-primary btn-sm">Mới</label>';
                                            }
                                            return $customer->getOneCustomer($model->customer_id);
                                        }
                                    ],
                                    [
                                        'attribute' => 'call_source',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $callLog = new CallLog();
                                            $hrf = json_decode($callLog->getDetailCall($model->call_id));
                                            return '
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="popover" 
                                        data-content="<iframe src =\'' . $hrf->key_index . '\'></iframe>" 
                                        data-html="true" data-placement="bottom">
											<i class="fa fa-play-circle"></i>
										</button>
                                        ';
                                        },
                                        'headerOptions' => [
                                            'width' => 80,
                                            'rowspan' => 2
                                        ],
                                        'filterOptions' => [
                                            'class' => 'd-none',
                                        ],

                                    ],

                                    [
                                        'attribute' => 'created_at',
                                        'format' => 'date',
                                        'value' => 'created_at',
                                        'filter' => DatePicker::widget([
                                            'model' => $searchModel,
                                            'attribute' => 'created_at',
                                            'template' => '{input}{addon}',
                                            'language' => 'vi',
                                            'clientOptions' => [
                                                'autoclose' => true,
                                                'format' => 'dd-mm-yyyy',
                                            ],
                                            'options' => [
                                                'autocomplete' => 'off',
                                            ]
                                        ]),
                                        'headerOptions' => [
                                            'width' => 120,
                                            'rowspan' => 2
                                        ],
                                        'filterOptions' => [
                                            'class' => 'd-none',
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
$urlChangePageSize = \yii\helpers\Url::toRoute(['/log/call-log/perpage']);
$tit = Yii::t('backend', 'Notification');

$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', 'If delete, you will not be able to recover!');

$script = <<< JS
var callLog = new myGridView();
callLog.init({
    pjaxId: '#LogCallLog',
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
$this->registerJsFile('/js/scripts/popover/popover.min.js', ['depends' => 'yii\web\JqueryAsset']);
?>

