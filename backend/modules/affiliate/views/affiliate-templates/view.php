<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\affiliate\models\AffiliateTemplates */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Affiliate'), 'url' => ['/affiliate']];
$this->params['breadcrumbs'][] = ['label' => 'Mẫu trang landing', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modal-header bg-blue-grey bg-lighten-2 white">
    <h4 class="modal-title"><?= $this->title . ': ' . $model->name; ?></h4>
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
            'mota:html',
            'created_at:date',
            'updated_at:date',

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
                    $user = new backend\modules\affiliate\models\AffiliateTemplates();
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
                    $user = new backend\modules\affiliate\models\AffiliateTemplates();
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