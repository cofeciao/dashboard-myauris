<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\grid\MyGridView;
use backend\models\CustomerModel;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\CustomerOnlineRemindCall;

$css = <<< CSS
.my-modal.modal .modal-remind-call {
    width: 1200px;
    margin-left: auto;
    margin-right: auto;
}
CSS;
$this->registerCss($css);
?>
    <div class="my-modal modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-scrollable modal-xl modal-remind-call" role="document">
            <div class="modal-content">
                <div class="modal-header bg-blue-grey bg-lighten-2 white">
                    <div class="modal-title">Bạn có <?= $nhaclich->totalCount ?> khách hàng cần chăm sóc trong
                        ngày <?= date('d-m-Y') ?>.
                    </div>
                    <button type="button" class="close modal-close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php Pjax::begin(['id' => 'alert-ajax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'POST']]) ?>
                    <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                        <?= \yii\grid\GridView::widget([
                            'dataProvider' => $nhaclich,
                            'layout' => '{errors} <div class="pane-single-table table-responsive">{items}</div><div class="pager-wrap clearfix">{summary}' .
                                '{pager}</div>',
                            'options' => [
                                'class' => 'grid-view table-responsive cp-grid cp-widget pane-hScroll',
                                'data-pjax' => 1
                            ],
                            'rowOptions' => function ($model) {
                                return ['data-key' => $model->id];
                            },
                            'summaryOptions' => [
                                'class' => 'summary pull-right',
                            ],
                            /*'myOptions' => [
                                'class' => 'grid-content my-content pane-vScroll',
                            ],*/
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
                            'tableOptions' => [
                                'class' => 'table table-striped table-bordered mb-0'
                            ],
                            'columns' => [
                                [
                                    'class' => \yii\grid\SerialColumn::class,
                                    'header' => 'STT',
                                    'headerOptions' => [
                                        'width' => 20,
                                        'class' => 'text-center'
                                    ],
                                    'contentOptions' => [
                                        'class' => 'text-center'
                                    ]
                                ],
                                [
                                    'attribute' => 'customer_id',
                                    'format' => 'html',
                                    'value' => function ($model) {
                                        if ($model->customerHasOne == null) {
                                            return null;
                                        }
                                        return $model->customerHasOne->full_name != null ? $model->customerHasOne->full_name : $model->customerHasOne->name;
                                    },
                                    'headerOptions' => [
                                        'width' => 200
                                    ]
                                ],
                                [
                                    'attribute' => 'status',
                                    'format' => 'html',
                                    'value' => function ($model) {
                                        if ($model->status == CustomerModel::STATUS_FAIL) {
                                            return '<span class="status-fail">Fail</span>';
                                        }
                                        if ($model->status == CustomerModel::STATUS_KBM) {
                                            return '<span class="status-kobm">KBM</span>';
                                        }
                                        if ($model->status == CustomerModel::STATUS_DH && $model->dat_hen == Dep365CustomerOnline::DAT_HEN_KHONG_DEN) {
                                            return '<span class="khong-den">Đặt hẹn không đến</span>';
                                        }
                                        return null;
                                    },
                                    'headerOptions' => [
                                        'class' => 'text-center',
                                        'width' => 150
                                    ],
                                    'contentOptions' => [
                                        'class' => 'text-center'
                                    ]
                                ],
                                [
                                    'attribute' => 'reason',
                                    'format' => 'html',
                                    'value' => function ($model) {
                                        if ($model->status == CustomerModel::STATUS_FAIL && $model->customerOnlineFailStatus != null) {
                                            return $model->customerOnlineFailStatus->name;
                                        }
                                        if ($model->status == CustomerModel::STATUS_DH && $model->dat_hen == Dep365CustomerOnline::DAT_HEN_KHONG_DEN && $model->customerOnlineFailDatHen != null) {
                                            return $model->customerOnlineFailDatHen->name;
                                        }
                                        if ($model->customerOnlineHasOne != null && $model->customerOnlineHasOne->status == CustomerModel::STATUS_FAIL && $model->customerOnlineHasOne->failStatusCustomerOnlineHasOne != null) {
                                            return $model->customerOnlineHasOne->failStatusCustomerOnlineHasOne->name;
                                        }
                                        if ($model->customerOnlineHasOne != null && $model->customerOnlineHasOne->status == CustomerModel::STATUS_DH && $model->customerOnlineHasOne->dat_hen == Dep365CustomerOnline::DAT_HEN_KHONG_DEN && $model->customerOnlineHasOne->failDatHenCustomerOnlineHasOne != null) {
                                            return $model->customerOnlineHasOne->failDatHenCustomerOnlineHasOne->name;
                                        }
                                        return null;
                                    }
                                ],
                                [
                                    'attribute' => 'remind_call_time',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        if ($model->remind_call_time == null) {
                                            return null;
                                        }
                                        if (date('H:i', $model->remind_call_time) == '00:00') {
                                            return Html::tag('span', date('d-m-Y', $model->remind_call_time), ['class' => 'green f-w-bold']);
                                        }
                                        return Html::tag('span', date('d-m-Y H:i', $model->remind_call_time), ['class' => 'green f-w-bold']);
                                    },
                                    'headerOptions' => [
                                        'width' => 150,
                                        'class' => 'text-center'
                                    ],
                                    'contentOptions' => [
                                        'class' => 'text-center'
                                    ]
                                ],
                                [
                                    'attribute' => 'take_care',
                                    'header' => 'Tình trạng',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        if ($model->take_care > 0) {
                                            return '<span class="green">Đã chăm sóc</span>';
                                        }
                                        return '<span class="red">Chưa chăm sóc</span>';
                                    },
                                    'headerOptions' => [
                                        'class' => 'text-center',
                                        'width' => 140
                                    ],
                                    'contentOptions' => [
                                        'class' => 'text-center'
                                    ]
                                ],
                                [
                                    'attribute' => '',
                                    'header' => 'Nhân viên',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        if ($model->customerOnlineHasOne == null) return null;
                                        $sale_online = \backend\modules\user\models\User::getPermissionUser($model->customerOnlineHasOne->permission_user);
                                        if ($sale_online == null) return null;
                                        return $sale_online->fullname;
                                    }
                                ],
                                ['class' => 'yii\grid\ActionColumn',
                                    'header' => 'Actions',
                                    'template' => '<div class="btn-group" role="group">{call} {update}</div>',
                                    'buttons' => [
                                        'call' => function ($url, $model) {
                                            if ($model->customerHasOne == null || $model->customerHasOne->phone == null) {
                                                return null;
                                            }
                                            return Html::a('<i class="fa fa-phone"></i>', 'javascript:;', ['title' => 'Gọi lại', 'class' => 'btn btn-outline-success', 'onclick' => 'return (typeof mycall == "object" ? mycall.makeCall(\'' . $model->customerHasOne->phone . '\', getOptionsAlert(' . $model->primaryKey . ')) : false);']);
                                        },
                                        'update' => function ($url, $model) {
                                            if ($model->type == CustomerOnlineRemindCall::TYPE_CUSTOMER_ONLINE) {
                                                if ($model->status == Dep365CustomerOnline::STATUS_KBM && $model->customerHasOne != null && $model->customerHasOne->phone != null) {
                                                    /*
                                                     * Trạng thái KBM => chuyển link về trang edit thông tin khách hàng (tìm theo sđt)
                                                     * */
                                                    $link = Url::toRoute([
                                                        '/customer/customer-online/index',
                                                        'Dep365CustomerOnlineSearch[type_search_create]' => 'date',
                                                        'Dep365CustomerOnlineSearch[button]' => '1',
                                                        'Dep365CustomerOnlineSearch[keyword]' => $model->customerHasOne->phone,
                                                        'edit' => 'true',
                                                        'customer_id' => $model->customer_id
                                                    ]);
                                                } else {
                                                    $link = Url::toRoute(['/customer/customer-online-remind-call', 'edit' => 'true', 'remind_id' => $model->primaryKey]);
                                                }
                                            } else {
                                                $link = Url::toRoute(['/directsale/direct-sale-remind-call', 'edit' => 'true', 'remind_id' => $model->primaryKey]);
                                            }
//                                        $link = $model->type == CustomerOnlineRemindCall::TYPE_CUSTOMER_ONLINE ? Url::toRoute(['/customer/customer-online-remind-call', 'edit' => 'true', 'remind_id' => $model->primaryKey]) : Url::toRoute(['/directsale/direct-sale-remind-call', 'edit' => 'true', 'remind_id' => $model->primaryKey]);
                                            return Html::a('<i class="ft-edit"></i>', $link, [
                                                'class' => 'btn btn-outline-primary',
                                                'data-pjax' => '0'
                                            ]);
                                        }
                                    ],
                                    'headerOptions' => [
                                        'width' => 80,
                                    ],
                                ],
                            ]
                        ]);
                        ?>
                    </div>
                    <?php Pjax::end() ?>
                </div>
                <div class="modal-footer">
                    <a href="<?= $url ?>" data-pjax="0" class="btn btn-primary btn-submit">Xem danh sách</a>
                </div>
            </div>
        </div>
    </div>
<?php
$script = <<< JS
function getOptionsAlert(id){
    return {
        dataSendWithCallLog: {
            nhac_lich_id: id
        },
        callbackOnStartCall: function(){
            $('.my-modal').modal('hide');
        },
        callbackOnEndCall: function(){
            $.pjax.reload({url: window.location.href, method: 'POST', container: '#alert-ajax'});
            $('.my-modal').modal({
                backdrop: 'static',
                keyboard: false
            });
        }
    }
}
setTimeout(function(){
    $('.my-modal').modal({
        backdrop: 'static',
        keyboard: false
    });
}, 1000);
JS;
$this->registerJs($script, View::POS_END);
