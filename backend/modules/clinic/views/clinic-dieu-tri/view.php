<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use backend\helpers\BackendHelpers;

/* @var $this yii\web\View */
/* @var $model backend\modules\clinic\models\PhongKhamLichDieuTri */

$this->title = $model->clinicHasOne->full_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Phòng khám'), 'url' => ['/clinic/clinic']];
$this->params['breadcrumbs'][] = ['label' => 'Lịch điều trị', 'url' => ['index']];
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
            [
                'attribute' => 'name',
                'label' => 'Khách hàng',
                'value' => function ($model) {
                    return $model->clinicHasOne->full_name;
                }
            ],
            'customer_code',
            'order_code',
            [
                'attribute' => 'ekip',
                'value' => function ($model) {
                    $user = new \common\models\UserProfile();
                    $fullname = $user->getFullNameBacSi($model->ekip);
                    if ($fullname == false) {
                        return null;
                    }
                    return $fullname;
                }
            ],
            'time_dieu_tri:datetime',
            'time_start:datetime',
            'time_end:datetime',
//            [
//                'attribute' => 'order',
//                'format' => 'html',
//                'value' => function ($model) {
//                    return $this->render('_viewOderClinic', ['model' => $model->orderHasOne]);
//                }
//            ],
//            [
//                'attribute' => 'thanhtoan',
//                'format' => 'html',
//                'value' => function ($model) {
//                    return $this->render('_viewThanhToanClinic', ['model' => $model->orderHasOne]);
//                }
//            ],
            'huong_dieu_tri:html',
            'note:ntext',
            'created_at:date',
            'updated_at:date',
            [
                'attribute' => 'thai_do',
                'format' => 'raw',
                'value' => function ($model) {
                    return BackendHelpers::getRatings($model->thai_do);
                }
            ],
            [
                'attribute' => 'chuyen_mon',
                'format' => 'raw',
                'value' => function ($model) {
                    return BackendHelpers::getRatings($model->chuyen_mon);
                }
            ],
            [
                'attribute' => 'tham_my',
                'format' => 'raw',
                'value' => function ($model) {
                    return BackendHelpers::getRatings($model->tham_my);
                }
            ],
            [
                'attribute' => 'created_by',
                'value' => function ($model) {
                    $user = new backend\modules\clinic\models\PhongKhamLichDieuTri();
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
                    $user = new backend\modules\clinic\models\PhongKhamLichDieuTri();
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

