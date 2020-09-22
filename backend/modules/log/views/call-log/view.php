<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\log\models\CallLog */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Call Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?= $this->title; ?></h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <?= Html::a('<i class="ft-plus"></i>', ['create'], ['title' => 'Thêm mới', 'data-pjax' => 0]) ?>
                            <?= Html::a('<i class="ft-edit"></i>', ['update'], ['title' => 'Cập nhật', 'data-pjax' => 0]) ?>
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

                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'detail1-view table table-striped table-bordered detail-view'],
                            'attributes' => [
                                'id',
                                'call_id',
                                'call_den_di',
                                'call_status',
                                'call_time:datetime',
                                'call_time_start:datetime',
                                'user_id',
                                'phone',
                                'customer_id',
                                'call_source',
                                'created_at',
                                'updated_at',

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
                                        $user = new backend\modules\log\models\CallLog();
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
                                        $user = new backend\modules\log\models\CallLog();
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
                </div>
            </div>
        </div>
</section>

