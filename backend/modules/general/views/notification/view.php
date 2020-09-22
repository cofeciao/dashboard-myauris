<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\general\models\Dep365Notification */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'General', 'url' => ['/general']];
$this->params['breadcrumbs'][] = ['label' => 'Thông báo', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="modal-header bg-blue-grey bg-lighten-2 white">
    <h4 class="modal-title"><?= $this->title; ?></h4>
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
            ['attribute' => 'name', 'format' => 'html'],
            'slug',
            'icon',
            'description:html',
            'content:html',
            'created_at:date',
            'updated_at:date',
            [
                'attribute' => 'is_new',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->is_new == 1 ? 'Tin mới' : 'Tin cũ';
                }
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
                    $user = new backend\modules\general\models\Dep365Notification();
                    $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                    if ($userCreatedBy == false) {
                        return null;
                    }
                    return $userCreatedBy->fullname;
                }
            ],
            [
                'attribute' => 'updated_by',
                'value' => function ($model) {
                    $user = new backend\modules\general\models\Dep365Notification();
                    $userCreatedBy = $user->getUserCreatedBy($model->updated_by);
                    if ($userCreatedBy == false) {
                        return null;
                    }
                    return $userCreatedBy->fullname;
                }
            ],

        ],
    ]) ?>
</div>
<div class="modal-footer p-0"></div>