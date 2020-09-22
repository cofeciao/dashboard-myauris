<?php

use yii\helpers\Html;
use common\grid\MyGridView;
use yii\widgets\Pjax;
use backend\modules\location\models\District;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\location\models\search\DistrictSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'District');
$this->params['breadcrumbs'][] = ['label' => 'Location', 'url' => ['/location']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <div class="btn-add-campaign clearfix" style="margin-top:0px;position:relative; margin-bottom: 10px">
                            <?= Html::button(
    '<i class="fa fa-plus"></i> Thêm mới',
    [
                                    'title' => 'Thêm mới',
                                    'class' => 'btn btn-default pull-left',
                                    'data-pjax' => 0,
                                    'data-toggle' => 'modal',
                                    'data-backdrop' => 'static',
                                    'data-keyboard' => false,
                                    'data-target' => '#custom-modal',
                                    'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['create']) . '");return false;',
                                ]
)
                            ?>
                        </div>
                        <div style="margin-top:5px;border:1px solid #ccc;border-radius:3px">
                            <?php Pjax::begin(['id' => 'locationDistrict', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
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
                                    'headerOptions' => [
                                        'width' => 60,
                                        'rowspan' => 2
                                    ],
                                    'filterOptions' => [
                                        'class' => 'd-none',
                                    ],
                                ],
                                [
                                    'attribute' => 'name',
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
                                                'onclick' => 'showModal($(this), "' . \yii\helpers\Url::toRoute(['view', 'id' => $model->id]) . '");return false;',
                                            ]
                                        );
                                    }
                                ],
                                [
                                    'class' => \common\grid\EnumColumn::class,
                                    'attribute' => 'Type',
                                    'enum' => District::getDistrictType(),
                                    'filter' => District::getDistrictType(),
                                ],
                                'LatiLongTude',
                                'province.name',
                                'SortOrder',
                                [
                                    'attribute' => 'status',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return \common\widgets\ModavaCheckbox::widget([
                                            'id' => $model->id,
                                            'value' => $model->status,
                                        ]);
                                    },
                                ],

                                [
                                    'attribute' => 'created_by',
                                    'value' => function ($model) {
                                        $user = new backend\modules\location\models\District();
                                        $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                                        return $userCreatedBy->fullname;
                                    }
                                ],

                                ['class' => 'yii\grid\ActionColumn',
                                    'header' => 'Actions',
                                    'template' => '<div class="btn-group" role="group">{update} {delete}</div>',
                                    'buttons' => [
                                        'update' => function ($url, $model) {
                                            return Html::button(
                                                '<i class="ft-edit blue"></i>',
                                                [
                                                    'class' => 'btn btn-default',
                                                    'data-pjax' => 0,
                                                    'data-toggle' => 'modal',
                                                    'data-backdrop' => 'static',
                                                    'data-keyboard' => false,
                                                    'data-target' => '#custom-modal',
                                                    'onclick' => 'showModal($(this), "' . $url . '");return false;',
                                                ]
                                            );
                                        },
                                        'delete' => function ($url, $model) {
                                            return Html::a('<i class="ft-trash-2 red confirm-color" data-id = "' . json_encode([$model->id]) . '" ></i>', 'javascript:void(0)', ['class' => 'btn btn-default']);
                                        },
                                    ],
                                    'headerOptions' => [
                                        'width' => 100,
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
$url = \yii\helpers\Url::toRoute(['/location/district/show-hide']);
$urlDelete = \yii\helpers\Url::toRoute(['/location/district/delete']);
$urlChangePageSize = \yii\helpers\Url::toRoute(['/location/district/perpage']);
$tit = Yii::t('backend', 'Notification');

$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', 'If delete, you will not be able to recover!');

$script = <<< JS
var locationDistrict = new myGridView({
    pjaxId: '#locationDistrict',
    urlChangePageSize: '$urlChangePageSize',
});
$('body').on('beforeSubmit', 'form#form-location-district', function(e) {
   e.preventDefault();
   var currentUrl = $(location).attr('href');
   var formData = $('#form-location-district').serialize();

    $('#form-location-district').myLoading({opacity: true});
   
    $.ajax({
        url: $('#form-location-district').attr('action'),
        type: 'POST',
        data: formData,
        dataType: 'json',
    })
    .done(function(res) {
        $('#form-location-district').myUnloading();
        if (res.status == 200) {
            $.when($.pjax.reload({url: currentUrl, method: 'POST', container: locationDistrict.options.pjaxId})).done(function(){
                $('.modal-header').find('.close').trigger('click');
                toastr.success(res.mess, '$tit');
            });
        } else {
            toastr.error(res.mess, '$tit');
        }
    })
    .fail(function(err){
        $('#form-location-district').myUnloading();
        console.log(err);
    });
   
   return false;
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
                               $.pjax.reload({url: currentUrl, method: 'POST', container: locationDistrict.options.pjaxId});
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

