<?php

use common\grid\MyGridView;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\clinic\models\search\PhongKhamChupHinhSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Chụp hình');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Clinic'), 'url' => ['/clinic']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content collapse show ">
                    <div class="card-body card-dashboard">
                        <?php Pjax::begin(['id' => 'clinic-chup-hinh', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
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
                                            'width' => 40,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'avatar',
                                        'header' => 'Avatar',
                                        'format' => 'raw',
                                        'headerOptions' => [
                                            'width' => 80,
                                        ],
                                        'value' => function ($model) {
                                            if ($model->avatar == null || !file_exists(Yii::$app->basePath . '/web/uploads/avatar/70x70/' . $model->avatar)) {
                                                $avatar = '/local/default/avatar-default.png';
                                            } else {
                                                $avatar = '/uploads/avatar/70x70/' . $model->avatar;
                                            }
                                            return Html::img($avatar);
                                        },
                                    ],
                                    [
                                        'attribute' => 'full_name',
                                        'format' => 'raw',
                                        'headerOptions' => [
                                            'width' => 180,
                                        ],
                                        'value' => function ($model) {
                                            $name = $model->full_name == null ? null : $model->full_name;
                                            return Html::a($name, ['upload', 'id' => $model->id], ['data-pjax' => 0, 'class' => 'name-customer']);
                                        },
                                    ],
                                    [
                                        'attribute' => 'customer_code',
                                        'label' => 'Mã khách hàng',
                                        'format' => 'html',
                                        'value' => 'customer_code',
                                        'headerOptions' => [
                                            'width' => 120,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'sex',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return $model->sex == 1 ? 'Nam' : 'Nữ';
                                        },
                                        'headerOptions' => [
                                            'width' => 90,
                                        ],
                                    ],
                                    'provinceHasOne.name',
                                    [
                                        'attribute' => 'co_so',
                                        'value' => 'co_so',
                                        'headerOptions' => [
                                            'width' => 80,
                                        ],
                                    ],
                                    [
                                        'attribute' => 'note',
                                        'format' => 'raw',
                                        'label' => 'Ghi chú Tư Vấn Online',
                                        'value' => function ($model) {
                                            return $model->note;
                                        }
                                    ],
                                    [
                                        'format' => 'raw',
                                        'label' => 'Direct Sale',
                                        'value' => 'directSaleHasOne.fullname',
                                        'headerOptions' => [
                                            'width' => 120,
                                        ],
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
$url = \yii\helpers\Url::toRoute(['show-hide']);
$urlDelete = \yii\helpers\Url::toRoute(['delete']);
$urlChangePageSize = \yii\helpers\Url::toRoute(['perpage']);
$tit = Yii::t('backend', 'Notification');

$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', 'If delete, you will not be able to recover!');

$script = <<< JS
var clinic = new myGridView();
clinic.init({
    pjaxId: '#clinic-chup-hinh',
    urlChangePageSize: '$urlChangePageSize'
})

$(document).ready(function () {
    $('body').on('click', '.name-customer', function() {
        $('#clinic-chup-hinh').myLoading({
            msg: 'Đang tải hình',
            opacity: true,
        });
    });
    
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

