<?php

use common\grid\MyGridView;
use dosamigos\datetimepicker\DateTimePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;


?>

<div class="content-header row p-2 customer-header">
    <div class="content-header-left col-md-6 col-12 ">
        <h4 class="content-header-title ">Khách hàng :<strong><?= $mCustomer->name ?></strong> - Mã KH :<strong><?= $mCustomer->customer_code ?></strong></h4>
    </div>
</div>


<div class="content-body">
    <div class=" row p-1">
        <div class="col-5">
            <?php $form = ActiveForm::begin([
                'id' => 'bao-hanh',
                'class' => 'form form-horizontal',
//                'enableAjaxValidation' => true,
                'enableClientValidation' => true,
                // Yii::$app->controller->action->id == 'create'
                'action' => Url::toRoute(['/clinic/clinic/bao-hanh', 'customer_id' => $customer_id]),
            ]); ?>

            <div class="card">
                <div class="card-header ">
                    <h4 class="card-title">Thông tin bảo hành</h4>
                </div>
                <div class="card-content ">
                    <div class="card-body">
                        <!--                        --><?php // $form->field($model, 'id')->hiddenInput()->label(false); ?>
                        <div class="row">
                            <div class="col-12 mb-1">
                                <?= $form->field($model, 'phong_kham_don_hang_id')->dropDownList($listDonHang, [
                                    'class' => 'dropdown ui form-control',
                                    'prompt' => 'Mã đơn hàng...',
                                    'style' => 'width: 100%',
                                ]); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-1">
                                <?= $form->field($model, 'so_luong_rang')->textInput(['type' => 'number']); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-1">
                                <?= $form->field($model, 'ly_do')->textArea(['rows' => 3]) ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-1">
                                <?php
                                if (empty($model->ngay_thuc_hien)) {
                                    $model->ngay_thuc_hien = date('d-m-Y H:i', time());
                                } else {
                                    $model->ngay_thuc_hien = date('d-m-Y H:i', $model->ngay_thuc_hien);
                                }
                                echo $form->field($model, 'ngay_thuc_hien')->widget(DateTimePicker::class, [
                                    'clientOptions' => [
                                        'format' => 'dd-mm-yyyy hh:ii',
                                        'autoclose' => true,
                                        'todayHighlight' => true
                                    ],
                                    'clientEvents' => [
                                    ],
                                    'options' => [
                                        'readonly' => 'readonly',
                                        'class' => 'form-control'
                                    ]
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer ">
                    <?= Html::resetButton('<i class="ft-x"></i> Close', ['class' =>
                        'btn btn-warning mr-1']) ?>
                    <?= Html::submitButton(
                        '<i class="fa fa-check-square-o"></i> Save',
                        ['class' => 'btn btn-primary']
                    ) ?>
                </div>
            </div>


            <?php ActiveForm::end(); ?>
        </div>

        <!--                                                                                                        Danh sách bảo hành                                                          -->
        <div class="col-7">
            <div class="card">
                <div class="card-header ">
                    <h4 class="card-title">Danh sách bảo hành</h4>
                </div>
                <div class="card-content ">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">

                                <?php Pjax::begin(['id' => 'customer-ajax', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>

                                <?= MyGridView::widget([
                                    'id' => 'customer-online-clinic',
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
                                        'data-minus' => '{"0":135,"1":".header-navbar","2":".btn-add-campaign","3":".pager-wrap","4":".footer","5":".form-search", "6":".customer-header"}'
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
                                                'width' => 50,
                                            ],
                                        ],
                                        [
                                            'class' => 'yii\grid\ActionColumn',
                                            'header' => 'Actions',
                                            'template' => '<div class="btn-group" role="group">{delete-bao-hanh} </div>',
                                            'buttons' => [
                                                'delete-bao-hanh' => function ($url, $modelBaoHanh) {
//                                                        return $modelBaoHanh->getMaHoaDon();$modelBaoHanh->id
//                                                    return $url;
                                                    return Html::a('<i class="ft-trash-2 red confirm-color" data-id = "' . $modelBaoHanh->id . '" ></i>', 'javascript:void(0)', ['class' => 'btn btn-default']);
                                                },
                                            ],
                                            'headerOptions' => [
                                                'width' => 60,
                                            ],
                                            'contentOptions' => [
                                                'style' => 'line-height:40px',
                                            ]
                                        ],
                                        [
                                            'attribute' => 'phong_kham_don_hang_id',
                                            'format' => 'raw',
                                            'value' => function ($modelBaoHanh) {
                                                return $modelBaoHanh->getMaHoaDon();
                                            },
                                            'headerOptions' => [
                                                'width' => '60%',
                                            ],
                                        ],
                                        [
                                            'attribute' => 'so_luong_rang',
                                            'contentOptions' => [
                                                'style' => 'text-align:center',
                                            ],
                                        ],
                                        [
                                            'attribute' => 'ly_do',
                                            'format' => 'ntext'
                                        ],
                                        [
                                            'attribute' => 'ngay_thuc_hien',
                                            'value' => function ($modelBaoHanh) {
                                                return $modelBaoHanh->getNgayThucHien();
                                            },
                                        ],
                                    ],
                                ]); ?>
                                <?php Pjax::end(); ?>

                            </div>


                        </div>
                    </div>
                </div>
                <div class="card-footer ">

                </div>
            </div>
        </div>

    </div>

</div>


<?php
$this->registerCssFile('/css/css_order.css', ['depends' => [\yii\bootstrap\BootstrapAsset::class]]);
$this->registerJsFile('/js/js_order.js', ['depends' => [\yii\web\JqueryAsset::class]]);

$tit = Yii::t('backend', 'Notification');

$urlChangeNumber = Url::toRoute('perpage');
$urlUpdateCustomer = Url::toRoute('render-and-update');
$urlDH = Url::toRoute('check-letan');
$urlChangePageSize = Url::toRoute(['perpage']);

$urlCustomField = Yii::$app->getUrlManager()->createUrl('config/custom-field-customer');
$urlDelete = \yii\helpers\Url::toRoute(['clinic/delete-bao-hanh']);
$deleteSuccess = Yii::$app->params['delete-success'];

$script = <<< JS
var clinic = new myGridView();
clinic.init({
    pjaxId: '#customer-ajax',
    urlChangePageSize: '$urlChangePageSize',
});
function saveOrder(){
    return new Promise(function(resolve, reject){
        var formData = $('#form-don-hang').serialize();
        $('#form-don-hang').myLoading({opacity: true});
        $.ajax({
            url: $('#form-don-hang').attr('action'),
            type: 'POST',
            data: formData,
            dataType: 'json',
        })
        .done(function(res) {
            if (res.status == 1) {
                resolve(res);
            } else {
                reject(res);
            }
        }).fail(function(err){
            console.log('update order error', err);
            reject(null);
        });
    });
}

$(document).ready(function () {
    $('body').on('click', '.confirm-color', function (e) {
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
                        $.pjax.reload({url: currentUrl, method: 'POST', container: clinic.options.pjaxId});
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
