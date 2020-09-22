<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\quytac\models\search\SupportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('backend', 'Bài viết hỗ trợ');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(); ?>
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
                <div class="card-header">
                    <h4 class="card-title"><?= $this->title; ?></h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <?= Html::a('<i class="ft-plus"></i>', ['create'], ['title' => 'Thêm mới', 'data-pjax' => 0]) ?>
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a class="block-page"
                                   onclick='window.location="<?= \Yii::$app->getRequest()->getUrl(); ?>"'><i
                                            class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <?php // echo $this->render('_search', ['model' => $searchModel]);?>

                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
//                            'filterModel' => $searchModel,
                            'layout' => '{errors}
                            <div class="horizontal-scroll scroll-example">{items}</div> {summary}{pager}',
                            'tableOptions' => [
                                'class' => 'table table-striped table-bordered dom-jQuery-events',
                            ],
                            'pager' => [
                                'firstPageLabel' => 'First',
                                'lastPageLabel' => 'Last',
                                'prevPageLabel' => 'Prev',
                                'nextPageLabel' => 'Next',
                                'maxButtonCount' => 5,

                                'options' => [
                                    'tag' => 'ul',
                                    'class' => 'pagination pull-right',
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
                                ],
                                [
                                    'attribute' => 'name',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return Html::a($model->name, ['view', 'id' => $model->id], ['data-pjax' => 0]);
                                    }
                                ],
//                                        'id',
//            'catagory_id',
//            'name',
//            'slug',
                                'desription',
                                //'content',
                                //'status',
                                //'created_at',
                                //'updated_at',
                                //'created_by',
                                //'updated_by',
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
                                        $user = new backend\modules\quytac\models\Support();
                                        $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                                        return $userCreatedBy->fullname;
                                    }
                                ],

                                ['class' => 'yii\grid\ActionColumn',
                                    'header' => 'Actions',
                                    'template' => '{update} {delete}',
                                    'buttons' => [
                                        'update' => function ($url, $model) {
                                            return Html::a('<i class="ft-edit blue"></i>', $url, ['data-pjax' => 0]);
                                        },
                                        'delete' => function ($url, $model) {
                                            return '<i class="ft-trash-2 red confirm-color" data-id = "' . $model->id . '" ></i>';
                                        },
                                    ]
                                ],
                            ],
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php Pjax::end(); ?>

<?php
$url = \yii\helpers\Url::toRoute(['/modules/controllers/show-hide']);
$urlDelete = \yii\helpers\Url::toRoute(['/test/delete']);
$tit = Yii::t('backend', 'Notification');

$resultSuccess = Yii::$app->params['update-success'];
$resultDanger = Yii::$app->params['update-danger'];

$deleteSuccess = Yii::$app->params['delete-success'];
$deleteDanger = Yii::$app->params['delete-danger'];

$data_title = Yii::t('backend', 'Are you sure?');
$data_text = Yii::t('backend', 'If delete, you will not be able to recover!');

$script = <<< JS
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
                        $.pjax.reload({url: currentUrl, method: 'POST', container: onlineBooking.options.pjaxId});
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

