<?php

use yii\helpers\Html;
use common\grid\MyGridView;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\user\models\search\RbacAuthItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Permission');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'User'), 'url' => ['/user']];
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
                            <?php Pjax::begin(['id' => 'userPermission', 'timeout' => false, 'enablePushState' => true, 'clientOptions' => ['method' => 'GET']]); ?>
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
                                            'class' => 'd-none'
                                        ],
                                    ],
                                    [
                                        'attribute' => 'description',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            return Html::a($model->description, ['view', 'id' => $model->name], ['data-pjax' => 0]);
                                        }
                                    ],
                                    'name',
                                    [
                                        'class' => \common\grid\EnumColumn::class,
                                        'attribute' => 'ruleName',
                                        'filter' => ArrayHelper::map(Yii::$app->getAuthManager()->getRules(), 'name', 'name'),
                                        'filterInputOptions' => ['class' => 'form-control', 'prompt' => Yii::t('rbac', 'Select Rule')],


                                    ],
                                    'data',
                                    ['class' => 'yii\grid\ActionColumn',
                                        'header' => 'Actions',
                                        'template' => '<div class="btn-group" role="group">{update} {delete}</div>',
                                        'buttons' => [
                                            'update' => function ($url, $model) {
                                                return Html::button(
                                                    '<i class="ft-edit blue"></i>',
                                                    [
                                                        'title' => 'Cập nhật',
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
                                                return Html::a("<i class='ft-trash-2 red confirm-color' data-name='" . $model->name . "'></i>", "javascript:void(0)", ["class" => "btn btn-default"]);
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
                            <?php Pjax::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
$urlDelete = \yii\helpers\Url::toRoute(['/user/permission/delete']);
$urlChangePageSize = \yii\helpers\Url::toRoute(['/user/permission/perpage']);
$tit = Yii::t('backend', 'Notification');


$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', 'If delete, you will not be able to recover!');

$script = <<< JS
var userPermission = new myGridView();
userPermission.init({
    pjaxId: '#userPermission',
    urlChangePageSize: '$urlChangePageSize',
});
$('body').on('beforeSubmit', 'form#form-create-permission', function(e){
    e.preventDefault();
    var form = $(this),
    currentUrl = $(location).attr('href'),
    formData = form.serialize();
    
    form.myLoading({opacity: true});
       
    $.ajax({
        url: $('#form-create-permission').attr('action'),
        type: 'POST',
        data: formData,
        dataType: 'json',
    })
    .done(function(res) {
        if (res.status == 200) {
            toastr.success(res.mess, '$tit');
            $.when($.pjax.reload({url: currentUrl, method: 'POST', container: userPermission.options.pjaxId})).done(function(){
                $('.modal-header').find('.close').trigger('click');
                form.myUnloading();
            });
        } else {
            toastr.error(res.mess, '$tit');
            form.myUnloading();
        }
    })
    .fail(function(err){
        form.myUnloading();
        console.log(err);
    });
   
   return false;
})
$(document).ready(function () {

    $('body').on('click', '.confirm-color',function (e) {
        e.preventDefault();
        
        var name = $(this).attr("data-name");
        var currentUrl = $(location).attr('href');
        
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
                data:{"name":name},
                url: "$urlDelete",
                dataType: "text",
                success: function(data){
                    if(data == 'success') {
                        toastr.success('$deleteSuccess', '$tit');
                        $.pjax.reload({url: currentUrl, method: 'POST', container: userPermission.options.pjaxId});
                    }
                    if(data == 'failure' || data == 'exception')
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

