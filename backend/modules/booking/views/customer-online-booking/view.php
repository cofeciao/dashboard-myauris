<?php

use backend\modules\booking\models\CustomerOnlineBooking;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\booking\models\CustomerOnlineBooking */

$this->title = CustomerOnlineBooking::CUSTOMER_TYPE[$model->customer_type];
$this->params['breadcrumbs'][] = ['label' => 'Customer Online Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="modal-header bg-blue-grey bg-lighten-2 white">
    <h4 class="modal-title"><?= $this->title ?></h4>
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
                'attribute' => 'user_register_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->customer_type == CustomerOnlineBooking::CUSTOMER_FROM_ONLINE ? $model->customerOnlineHasOne->name : $model->userRegisterHasOne->name;
                }
            ],
            [
                'attribute' => 'customer_type',
                'format' => 'raw',
                'value' => function ($model) {
                    return CustomerOnlineBooking::CUSTOMER_TYPE[$model->customer_type];
                }
            ],
            [
                'attribute' => 'time_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->timeWorkHasOne->time;
                }
            ],
            [
                'attribute' => 'coso_id',
                'format' => 'raw',
                'value' => function ($model) {
                    return 'Cơ sở ' . $model->coSoHasOne->name;
                }
            ],
            'booking_date:date',
            [
                'attribute' => 'created_by',
                'value' => function ($model) {
                    $user = new backend\modules\booking\models\CustomerOnlineBooking();
                    $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                    return $userCreatedBy->fullname;
                }
            ],
            [
                'attribute' => 'updated_by',
                'value' => function ($model) {
                    $user = new backend\modules\booking\models\CustomerOnlineBooking();
                    $userCreatedBy = $user->getUserCreatedBy($model->updated_by);
                    return $userCreatedBy->fullname;
                }
            ],
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    return '<label class="square-checkbox mb-0">' . Html::checkbox('', $model->status == 1, ['class' => 'chk-status', 'url-chk' => \yii\helpers\Url::toRoute(['change-status', 'id' => $model->primaryKey])]) . '<span></span></label>';
                }
            ],
        ],
    ]) ?>
</div>
<div class="modal-footer p-0"></div>