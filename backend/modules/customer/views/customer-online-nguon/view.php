<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\customer\models\Dep365CustomerOnlineNguon */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Khách hàng trực tuyến', 'url' => ['/customer']];
$this->params['breadcrumbs'][] = ['label' => 'Nguồn khách hàng', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="modal-header bg-blue-grey bg-lighten-2 white">
    <h4 class="modal-title">Gửi SMS: <?= $this->title; ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <?= DetailView::widget([
        'model' => $model,
        'options' => ['class' => 'detail1-view table table-striped table-bordered detail-view'],
        'attributes' => [
            'id',
            'name',
            'slug',
            'mota:ntext',
            'created_at:date',
            'updated_at:date',
            [
                'attribute' => 'agency_id',
                'value' => function ($model) {
                    $agency = json_encode($model->agency_id);
                    $name = \backend\modules\customer\models\Dep365Agency::getNameAgency($agency);
                    return $name;
                },
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->status == 1 ? 'Hiển thị' : 'Đang ẩn';
                }
            ],

            [
                'attribute' => 'created_by',
                'value' => function ($model) {
                    $user = new backend\modules\customer\models\Dep365CustomerOnlineNguon();
                    $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                    return $userCreatedBy->fullName;
                }
            ],
            [
                'attribute' => 'updated_by',
                'value' => function ($model) {
                    $user = new backend\modules\customer\models\Dep365CustomerOnlineNguon();
                    $userCreatedBy = $user->getUserCreatedBy($model->updated_by);
                    return $userCreatedBy->fullName;
                }
            ],

        ],
    ]) ?>
</div>
<div class="modal-footer p-0"></div>