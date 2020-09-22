<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\clinic\models\Clinic */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Clinics', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="dom">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><?=  $this->title; ?></h4>
                    <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a class="block-page"
                                   onclick='window.location="<?=  \Yii::$app->getRequest()->getUrl(); ?>"'><i
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
            'customer_code',
            'full_name',
            'forename',
            'name',
            'avatar',
            'slug',
            'phone:ntext',
            'sex',
            'birthday',
            'status',
            'agency_id',
            'nguon_online',
            'address',
            'province',
            'district',
            'face_fanpage',
            'face_post_id',
            'face_customer',
            'note:ntext',
            'directsale',
            'note_direct:ntext',
            'permission_user',
            'per_inactivity',
            'permission_old',
            'tt_kh:ntext',
            'ngaythang',
            'date_lichhen',
            'time_lichhen:datetime',
            'co_so',
            'dat_hen',
            'customer_come',
            'customer_come_date',
            'customer_come_time_to:datetime',
            'customer_gen',
            'customer_mongmuon:ntext',
            'customer_thamkham:ntext',
            'customer_huong_dieu_tri',
            'customer_ghichu_bacsi',
            'status_fail',
            'is_customer_who',
            'is_affiliate_created',
            'customer_direct_sale_checkthammy',
            'customer_bacsi_check_final',
            'customer_old',
            'ngay_tao',
            'created_at',
            'updated_at',
            'created_by',
            'updated_by',
            'dat_hen_fail',
            'reason_reject:ntext',

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
                                $user = new backend\modules\clinic\models\Clinic();
                                $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                                return $userCreatedBy->fullname;
                            }
                        ],
                        [
                            'attribute' => 'updated_by',
                            'value' => function ($model) {
                                $user = new backend\modules\clinic\models\Clinic();
                                $userCreatedBy = $user->getUserCreatedBy($model->updated_by);
                                return $userCreatedBy->fullname;
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

