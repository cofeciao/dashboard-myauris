<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\testab\models\Campaign */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Campaigns', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
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

                <div class="card-content collapse show">
                    <div class="card-body card-dashboard">
                        <div class="row">
                            <div class="col-lg-2 col-xs-2 col-md-3 col-xs-4 col-4 campaign-left ">
                                <?php echo Html::a('<i class="ft-plus"></i>', ['create'], ['title' => 'Thêm mới chiến dich', 'data-pjax' => 0]) ?>
                                <?= $this->render('_listView', [
                                    'dataProviderCD' => $dataProviderCD,
                                    'id' => $id
                                ]);
                                ?>
                            </div>
                            <div class="col-lg-10 col-xs-10 col-md-9 col-xs-8 col-8">
                                <section id="dom">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="campaign-name">Chiến dịch: <span><?= $chienDich->name; ?></span></div>
                                            <div class="campaign-type">Loại chiến dịch: <span><?= $model->name; ?></span></div>
                                            <?= DetailView::widget([
                                                'model' => $model,
                                                'options' => ['class' => 'detail1-view table table-striped table-bordered detail-view', 'id' => 'campaign-detail'],
                                                'attributes' => [
                                                    'id',
                                                    'name',
                                                    'slug',
                                                    'kyThuatHasOne.name',
                                                    'link_test',
                                                    'content:html',
                                                    'chiphi_thucchay',
                                                    'comment',
                                                    'tin_nhan',
//                                'tong_tuong_tac',
//                                'hien_thi',
//                                'tiep_can',
//                                'nguoi_xem_1',
//                                'nguoi_xem_50',
//                                'tan_suat',
//                                'gia_tuong_tac',
//                                'gia_hien_thi',
//                                'gia_tiep_can',
//                                'gia_10s',
//                                'gia_50phantram',
                                                    'end_date:datetime',
                                                    'created_at:datetime',
                                                    'updated_at:datetime',
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
                                                            $user = new backend\modules\testab\models\AbCampaign();
                                                            $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                                                            return $userCreatedBy->fullname;
                                                        }
                                                    ],
                                                    [
                                                        'attribute' => 'updated_by',
                                                        'value' => function ($model) {
                                                            $user = new backend\modules\testab\models\AbCampaign();
                                                            $userCreatedBy = $user->getUserCreatedBy($model->updated_by);
                                                            return $userCreatedBy->fullname;
                                                        }
                                                    ],

                                                ],
                                            ]) ?>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


