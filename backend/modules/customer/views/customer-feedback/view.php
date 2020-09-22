<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\customer\models\CustomerFeedback */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Customer Feedbacks', 'url' => ['index']];
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
                                [
                                    'attribute' => 'customer_id',
                                    'value' => function ($model) {
                                        if ($model->customerHasOne == null) {
                                            return null;
                                        }
                                        if ($model->customerHasOne->full_name != null) {
                                            return $model->customerHasOne->full_name;
                                        } elseif ($model->customerHasOne->forename != null) {
                                            return $model->customerHasOne->forename;
                                        } else {
                                            return $model->customerHasOne->name;
                                        }
                                    }
                                ],
                                [
                                    'attribute' => 'rating',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return \backend\helpers\BackendHelpers::getRatings($model->rating);
                                    }
                                ],
                                'feedback:ntext',
                                'created_at:datetime',
                                [
                                    'attribute' => 'status',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $model->status == 1 ? 'Đã duyệt' : 'Chưa duyệt';
                                    }
                                ],
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
