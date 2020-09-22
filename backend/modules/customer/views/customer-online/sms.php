<?php

use common\grid\MyGridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\user\models\User;
use backend\modules\customer\models\Dep365SendSms;
use backend\modules\customer\models\Dep365CustomerOnlineDathenTime;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\customer\models\search\Dep365CustomerOnlineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Gửi SMS');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Online Support'), 'url' => ['/customer']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Customer'), 'url' => ['/customer/customer-online']];
$this->params['breadcrumbs'][] = $this->title;

$user = new User();
$roleUser = $user->getRoleName(Yii::$app->user->id);
$roleDev = User::USER_DEVELOP;

?>

<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content collapse show customer-index ">
                    <div class="card-body card-dashboard">
                        <?php Pjax::begin(
    ['id' => 'customer-online-ajax', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]
); ?>
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                            <?= MyGridView::widget([
                                'id' => 'customer-online',
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
                                            'width' => 60
                                        ],
                                    ],
                                    [
                                        'attribute' => 'name',
//                                    'label' => 'Họ tên',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return Html::a(
                                                $model->name,
                                                'javascript:void(0)',
                                                [
                                                    'data-pjax' => 0,
                                                    'data-toggle' => 'modal',
                                                    'data-backdrop' => 'static',
                                                    'data-keyboard' => false,
                                                    'data-target' => '#custom-modal',
                                                    'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['/customer/customer-online/view-send-sms', 'id' => $model->id]) . '");return false;',
                                                ]
                                            );
                                        },
                                        'headerOptions' => [
                                            'width' => 200
                                        ],
                                    ],
                                    [
                                        'attribute' => 'forename',
                                        'format' => 'raw',
                                        'headerOptions' => [
                                            'width' => 200
                                        ],
                                    ],
                                    [
                                        'attribute' => 'phone',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            $result = '';
                                            $createDay = date('d-m-Y');

                                            $timeLH = $model->time_lichhen;
                                            $timeLHday = date('d-m-Y', $timeLH);

                                            $datetime = new \DateTime();
                                            $datetime->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));


                                            $oneDay = $datetime->modify('+1 day')->format('d-m-Y');
                                            $threeDay = $datetime->modify('+2 day')->format('d-m-Y');
                                            $sevenDay = $datetime->modify('+4 day')->format('d-m-Y');

                                            $query = Dep365SendSms::find()->where(['customer_id' => $model->id, 'dep365_send_sms.status' => 0]);
                                            $query->andWhere(['between', 'created_at', strtotime($createDay), strtotime($createDay) + 86399]);

                                            if ($oneDay == $timeLHday) {
                                                $smsOne = $query->andWhere(['sms_lanthu' => 1])->all();
                                                if ($smsOne) {
                                                    $temp = true;
                                                    foreach ($smsOne as $key => $item) {
                                                        $result .= Html::img(Url::to('@web/images/number/1.png'), []);
                                                        $dateSms = date('d-m-Y', $item->created_at);
                                                        if ($dateSms == date('d-m-Y')) {
                                                            $temp = false;
                                                        }
                                                    }
                                                    if ($temp) {
                                                        $result .= Html::img(Url::to('@web/images/number/1-red.png'), []);
                                                    }
                                                } else {
                                                    $result .= Html::img(Url::to('@web/images/number/1-red.png'), []);
                                                }
                                            }

                                            if ($threeDay == $timeLHday) {
                                                $smsthree = $query->andWhere(['sms_lanthu' => 3])->all();
                                                if ($smsthree) {
                                                    $temp = true;
                                                    foreach ($smsthree as $key => $item) {
                                                        $result .= Html::img(Url::to('@web/images/number/3.png'), []);
                                                        $dateSms = date('d-m-Y', $item->created_at);
                                                        if ($dateSms == date('d-m-Y')) {
                                                            $temp = false;
                                                        }
                                                    }
                                                    if ($temp) {
                                                        $result .= Html::img(Url::to('@web/images/number/3-red.png'), []);
                                                    }
                                                } else {
                                                    $result .= Html::img(Url::to('@web/images/number/3-red.png'), []);
                                                }
                                            }

                                            if ($sevenDay == $timeLHday) {
                                                $smsSeven = $query->andWhere(['sms_lanthu' => 7])->all();
                                                if ($smsSeven) {
                                                    $temp = true;
                                                    foreach ($smsSeven as $key => $item) {
                                                        $result .= Html::img(Url::to('@web/images/number/7.png'), []);
                                                        $dateSms = date('d-m-Y', $item->created_at);
                                                        if ($dateSms == date('d-m-Y')) {
                                                            $temp = false;
                                                        }
                                                    }
                                                    if ($temp) {
                                                        $result .= Html::img(Url::to('@web/images/number/7-red.png'), []);
                                                    }
                                                } else {
                                                    $result .= Html::img(Url::to('@web/images/number/7-red.png'), []);
                                                }
                                            }

                                            if ($timeLHday == $createDay && date('d-m-Y', $model->created_at) == $createDay) {
                                                $smsOnetoDay = Dep365SendSms::find()->where(['customer_id' => $model->id, 'dep365_send_sms.status' => 0, 'sms_lanthu' => 1])->all();
                                                if ($smsOnetoDay) {
                                                    foreach ($smsOnetoDay as $key => $item) {
                                                        $result .= Html::img(Url::to('@web/images/number/1.png'), []);
                                                    }
                                                } else {
                                                    $result .= Html::img(Url::to('@web/images/number/1-red.png'), []);
                                                }
                                            }

                                            $smsOther = $query->andWhere(['sms_lanthu' => 0])->all();
                                            if ($smsOther) {
                                                foreach ($smsOther as $key => $item) {
                                                    $result .= Html::img(Url::to('@web/images/number/s.png'), []);
                                                }
                                            }
                                            return $result . '<br />' . Html::a($model->phone, 'javascript:void(0)', ['onclick' => 'mycall.makeCall(\'' . $model->phone . '\')']);
                                            ;
                                        }
                                    ],

                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'nguon_online',
                                        'value' => 'nguonCustomerOnlineHasOne.name',
                                        'enum' => Dep365CustomerOnline::getNguonCustomerOnline(),
                                        'filter' => Dep365CustomerOnline::getNguonCustomerOnline(),
                                    ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'province',
                                        'value' => 'provinceHasOne.name',
                                        'enum' => Dep365CustomerOnline::getProvince(),
                                        'filter' => \kartik\select2\Select2::widget([
                                            'model' => $searchModel,
                                            'attribute' => 'province',
                                            'data' => Dep365CustomerOnline::getProvince(),
                                            'theme' => \kartik\select2\Select2::THEME_DEFAULT,
                                            'hideSearch' => false,
                                            'options' => [
                                                'placeholder' => 'Tỉnh/Thành phố',
                                            ],
                                            'pluginOptions' => [
                                                'allowClear' => true,
                                                'data-pjax' => false,
                                            ],
                                        ]),
                                    ],
                                    [
                                        'attribute' => 'created_at',
                                        'format' => 'date',
                                        'value' => 'created_at',
                                        'filter' => \dosamigos\datepicker\DatePicker::widget([
                                            'model' => $searchModel,
                                            'attribute' => 'created_at',
                                            'template' => '{input}{addon}',
                                            'language' => 'vi',
                                            'clientOptions' => [
                                                'autoclose' => true,
                                                'format' => 'dd-mm-yyyy',
                                            ]
                                        ]),
                                    ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'status',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            if ($model->status == '1') {
                                                return '<span class="dat-hen">Đặt hẹn</span>';
                                            }
                                            if ($model->status == '6') {
                                                return '<span class="status-tiem-nang">Tiềm năng</span>';
                                            }
                                            if ($model->status == '2') {
                                                return '<span class="status-fail">Fail</span>';
                                            }
                                            if ($model->status == '3') {
                                                return '<span class="status-kobm">KBM</span>';
                                            }
                                            if ($model->status == '4') {
                                                return '<span class="status-so-ao">Số ảo</span>';
                                            }
                                            if ($model->status == '5') {
                                                return '<span class="status-underfine">Chưa xác định</span>';
                                            }
                                        },
                                        'enum' => Dep365CustomerOnline::getStatusCustomerOnline(),
                                        'filter' => Dep365CustomerOnline::getStatusCustomerOnline(),
                                    ],
//                                [
//                                    'class' => \common\grid\EnumColumn::class,
//                                    'attribute' => 'dat_hen',
//                                    'format' => 'raw',
////                                    'value' => 'dat_hen',
//                                    'value' => function ($model) {
//                                        if ($model->dat_hen == '1') {
//                                            return '<span class="da-den">Đã đến</span>';
//                                        }
//                                        if ($model->dat_hen == '2') {
//                                            return '<span class="khong-den">Không đến</span>';
//                                        }
//                                        if ($model->dat_hen == '3') {
//                                            return '<span class="doi-lich">Dời lịch</span>';
//                                        }
//
//                                    },
//                                    'enum' => Dep365CustomerOnline::getStatusDatHen(),
//                                    'filter' => Dep365CustomerOnline::getStatusDatHen(),
//                                ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'co_so',
                                        'value' => 'coSoHasOne.name',
                                        'enum' => Dep365CustomerOnline::getCoSoDep365(),
                                        'filter' => Dep365CustomerOnline::getCoSoDep365(),
                                    ],
                                    [
                                        'attribute' => 'time_lichhen',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            if ($model->time_lichhen == null) {
                                                return null;
                                            }
                                            return date('d-m-Y H:i', $model->time_lichhen);
                                        },
                                        'filter' => \dosamigos\datepicker\DatePicker::widget([
                                            'model' => $searchModel,
                                            'attribute' => 'time_lichhen',
                                            'language' => 'vi',
                                            'clientOptions' => [
                                                'autoclose' => true,
                                                'format' => 'dd-mm-yyyy',
                                            ]
                                        ]),
                                    ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'permission_user',
                                        'value' => function ($model) {
                                            $userProfile = new Dep365CustomerOnline();
                                            return $userProfile->getNhanVienTuVan($model->permission_user);
                                        },
                                        'enum' => Dep365CustomerOnline::getNhanVienTuVanFilter(),
                                        'filter' => \kartik\select2\Select2::widget([
                                            'model' => $searchModel,
                                            'attribute' => 'permission_user',
                                            'data' => Dep365CustomerOnline::getNhanVienTuVanFilter(),
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
$url = \yii\helpers\Url::toRoute(['/customer/customer-online/show-hide']);
if (Yii::$app->user->can('customerCustomer-onlineDelete') || Yii::$app->user->can(User::USER_DEVELOP)) {
    $urlDelete = \yii\helpers\Url::toRoute(['/customer/customer-online/delete']);
} else {
    $urlDelete = '';
}
$urlChangePageSize = \yii\helpers\Url::toRoute(['/customer/customer-online/perpage']);
$tit = Yii::t('backend', 'Notification');

$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', 'If delete, you will not be able to recover!');

$urlChangeNumber = Url::toRoute(['/customer/customer-online/perpage']);
$script = <<< JS
var customerOnline = new myGridView();
customerOnline.init({
    pjaxId: '#customer-online-ajax',
    urlChangePageSize: '$urlChangePageSize'
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
                    if("$urlDelete" == '') {
                        swal("NotAllow", "$deleteDanger", "error");
                    } else
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
                                   // $.pjax.reload({container:'#customer-online'});
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

