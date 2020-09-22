<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\modules\user\models\User;

/* @var $this yii\web\View */
/* @var $model backend\modules\user\models\User */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'User'), 'url' => ['index']];
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
        'attributes' => [
            'id',
            'username',
            'email:email',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    switch ($model->status) {
                        case 1:
                            $status = 'Chưa kích hoạt';
                            break;
                        case 2:
                            $status = 'Hoạt động';
                            break;
                        case 3:
                            $status = 'Đã xóa';
                            break;
                    }
                    return $status;
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function ($model) {
                    if ($model->created_at != null) {
                        return Yii::$app->formatter->asDate($model->created_at, 'd-M-Y');
                    }
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function ($model) {
                    if ($model->updated_at != null) {
                        return Yii::$app->formatter->asDate($model->updated_at, 'd-M-Y');
                    }
                }
            ],
            [
                'attribute' => 'logged_at',
                'value' => function ($model) {
                    if ($model->logged_at != null) {
                        return Yii::$app->formatter->asDate($model->logged_at, 'd-M-Y');
                    }
                }
            ],
            [
                'attribute' => 'created_by',
                'value' => function ($model) {
                    if ($model->id == 1) {
                        return '';
                    }
                    $user = new backend\modules\user\models\User();
                    $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                    return $userCreatedBy->fullname;
                }
            ],
            [
                'attribute' => 'updated_by',
                'value' => function ($model) {
                    if ($model->id == 1) {
                        return '';
                    }
                    $user = new backend\modules\user\models\User();
                    $userCreatedBy = $user->getUserCreatedBy($model->updated_by);
                    return $userCreatedBy->fullname;
                }
            ],

        ],
    ]) ?>
</div>
<div class="modal-footer p-0"></div>