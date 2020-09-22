<?php

use yii\helpers\Html;
use common\grid\MyGridView;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\clinic\models\search\PhongKhamDonHangWHoanCocSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Danh sách hoàn cọc');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Clinic'), 'url' => ['/clinic']];
$this->params['breadcrumbs'][] = $this->title;
?>

<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                        <?php Pjax::begin(['id' => 'custom-pjax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                            <?= MyGridView::widget([
                                'dataProvider' => $dataProvider,
//                                'filterModel' => $searchModel,
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
                                    'data-minus' => '{"0":42,"1":".header-navbar","2":".btn-add-campaign","3":".pager-wrap","4":".footer","5":".form-search"}'
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
                                        'class' => 'yii\grid\ActionColumn',
                                        'header' => 'Actions',
                                        'template' => '<div class="btn-group" role="group">{print}</div>',
                                        'buttons' => [
                                            'print' => function ($url, $model) {
                                                return Html::a('<i class="ft-printer"></i>', 'javascript:void(0)', [
                                                    'class' => 'btn btn-default print-payment',
                                                    'data-href' => Url::toRoute(['/clinic/clinic-payment/print-payment', 'id' => $model->primaryKey]),
                                                    'style' => $model->accept_hoan_coc == \backend\modules\clinic\models\PhongKhamDonHangWThanhToan::UNVIEW_HOAN_COC ? 'cursor: not-allowed' : ''
                                                ]);
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
                                    [
                                        'attribute' => 'customer_id',
                                        'value' => function ($model) {
                                            if ($model->customerHasOne == null) {
                                                return null;
                                            }
                                            return $model->customerHasOne->full_name != null ? $model->customerHasOne->full_name : $model->customerHasOne->name;
                                        }
                                    ],
                                    [
                                        'attribute' => 'phong_kham_don_hang_id',
                                        'value' => function ($model) {
                                            if ($model->donHangHasOne == null) {
                                                return null;
                                            }
                                            return $model->donHangHasOne->order_code;
                                        }
                                    ],
                                    [
                                        'attribute' => 'dat_coc',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            if ($model->dat_coc == null || $model->dat_coc == '') {
                                                return null;
                                            }
                                            return '<span style="color: #0E7E12">' . number_format($model->dat_coc, 0, '', '.') . '</span>';
                                        },
                                        'contentOptions' => [
                                            'class' => ''
                                        ]
                                    ],
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'loai_thanh_toan',
                                        'value' => function ($model) {
                                            return $model->loai_thanh_toan;
                                        },
                                        'enum' => \yii\helpers\ArrayHelper::map(\backend\modules\clinic\models\PhongKhamLoaiThanhToan::getClinicLoaiThanhToan(), 'id', 'name'),
                                        'filter' => \yii\helpers\ArrayHelper::map(\backend\modules\clinic\models\PhongKhamLoaiThanhToan::getClinicLoaiThanhToan(), 'id', 'name'),
                                        'filterInputOptions' => [
                                            'class' => 'ui dropdown form-control'
                                        ],
                                    ],
                                    [
                                        'attribute' => 'accept_hoan_coc',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            if ($model->accept_hoan_coc === null || !array_key_exists($model->accept_hoan_coc, \backend\modules\clinic\models\PhongKhamDonHangWThanhToan::HOAN_COC_TYPE)) {
                                                $model->accept_hoan_coc = 0;
                                            }
                                            $color = 'transparent';
                                            if ($model->accept_hoan_coc == 0) {
                                                $color = 'red';
                                            }
                                            if ($model->accept_hoan_coc == 1) {
                                                $color = 'green';
                                            }
                                            if ($model->accept_hoan_coc == 2) {
                                                $color = '#856404';
                                            }
                                            $text = \backend\modules\clinic\models\PhongKhamDonHangWThanhToan::HOAN_COC_TYPE[$model->accept_hoan_coc];
                                            $result['type'] = 'select';
                                            $result['dataChoose'] = $model->accept_hoan_coc;
                                            $result['dataSelect'] = \backend\modules\clinic\models\PhongKhamDonHangWThanhToan::HOAN_COC_TYPE;
                                            return '<div style="text-decoration: underline; cursor: pointer; color: ' . $color . '" data-option="accept_hoan_coc" class="edittable-accept_hoan_coc" myedit-options=\'' . json_encode($result) . '\'>' . $text . '</div>';
                                        },

                                    ],
                                    'ngay_tao:datetime',
                                    'created_at:datetime',
                                    [
                                        'attribute' => 'created_by',
                                        'value' => function ($model) {
                                            $user = new backend\modules\clinic\models\PhongKhamDonHangWThanhToan();
                                            $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                                            return $userCreatedBy->fullname;
                                        }
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
$url = \yii\helpers\Url::toRoute(['show-hide']);
$urlDelete = \yii\helpers\Url::toRoute(['delete']);
$urlChangePageSize = \yii\helpers\Url::toRoute(['perpage']);
$urlAcceptHoanCoc = \yii\helpers\Url::toRoute(['accept-hoan-coc']);

$tit = Yii::t('backend', 'Notification');

$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', 'If delete, you will not be able to recover!');

$script = <<< JS
var customPjax = new myGridView({
    pjaxId: '#custom-pjax',
    urlChangePageSize: '$urlChangePageSize',
});

var myeditor = new myEditor();
myeditor.init({
    element: '.edittable-accept_hoan_coc',
    callbackBeforeSubmit: function(){
        var currentUrl = $(location).attr('href');
        var dataOption = myeditor.editor.find('.myEdit-data > select').val(),
            id = myeditor.editor.closest('tr').attr('data-key');
        var params = {
            id: id,
            dataOption: dataOption,
        };
        
        $.ajax({
            url: '$urlAcceptHoanCoc',
            type: 'POST',
            dataType: 'json',
            data: params
        })
        .done(function (data) {
            if (data.status == '200') {
                $.pjax.reload({url: currentUrl, method: 'POST', container: customPjax.options.pjaxId});
                toastr.success(data.mess, 'Thông báo');
            } else {
                toastr.error(data.mess, 'Thông báo');
            }
        })
        .fail(function (error) {
            toastr.error(error.mess, 'Thông báo');
        });
    }, /* callbackBeforeSubmit */
    callbackAfterSubmit: function(){}, /* callbackAfterSubmit */
    callbackBeforeCancel: function(){}, /* callbackBeforeCancel */
    callbackAfterCancel: function(){}, /* callbackAfterCancel */
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
    }).on('click', '.print-payment', function() {
        let printUrl = $(this).data('href');
        $('.data-load').attr('src', printUrl);
    }).on('click', '.confirm-color', function (e) {
        e.preventDefault();
        var id = JSON.parse($(this).attr("data-id"));
        var table = $(this).closest('tr');
        var currentUrl = $(location).attr('href');

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
                                $.pjax.reload({url: currentUrl, method: 'POST', container: customPjax.options.pjaxId});
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

