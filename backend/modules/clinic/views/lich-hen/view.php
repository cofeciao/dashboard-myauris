<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\ActiveForm;
use backend\modules\customer\models\Dep365CustomerOnline;

/* @var $this yii\web\View */
/* @var $model backend\modules\customer\models\Dep365CustomerOnline */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Khách hàng trực tuyến', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$user = new Dep365CustomerOnline();
$userCreatedInTimeLine = $user->getUserCreatedBy($model->getAttribute('created_by'))->fullname;

?>

    <div class="modal-header bg-blue-grey bg-lighten-2 white">
        <h4 class="modal-title">Khách hàng: <?= $this->title; ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <?= DetailView::widget([
            'model' => $model,
            'options' => ['class' => 'detail1-view table table-striped table-bordered detail-view'],
            'attributes' => [
                'name',
                'full_name',
                'forename',
                'phone',
                [
                    'attribute' => 'sex',
                    'value' => function ($model) {
                        switch ($model->sex) {
                            case 1:
                                $result = 'Nam Giới';
                                break;
                            case 0:
                                $result = 'Nữ Giới';
                                break;
                            case 2:
                                $result = 'Chưa xác định';
                                break;
                            default:
                                $result = 'Chưa xác định';
                                break;
                        }
                        return $result;
                    },
                ],
                'birthday',
                'nguonCustomerOnlineHasOne.name',
                'statusDatHenHasOne.name',
                'statusCustomerGotoAurisHasOne.name',
                'face_post_id',
                'face_customer',
                'provinceHasOne.name',
                'districtHasOne.name',
                'address',
                [
                    'attribute' => 'time_lichhen',
                    'format' => 'html',
                    'value' => function ($model) {
                        if ($model->time_lichhen == null) {
                            return null;
                        } else {
                            return Yii::$app->formatter->asDatetime($model->time_lichhen);
                        }
                    }
                ],
                'customer_come:datetime',
                [
                    'attribute' => 'co_so',
                    'value' => function ($model) {
                        if ($model->co_so == null) {
                            return null;
                        } else {
                            return 'Cơ sở số ' . $model->co_so;
                        }
                    }
                ],
                'tt_kh',
                [
                    'attribute' => 'note',
                    'label' => 'Ghi chú của tư vấn viên',
                    'format' => 'html',
                    'value' => function ($model) {
                        return $model->note;
                    }
                ],
                [
                    'attribute' => 'note_direct',
                    'label' => 'Ghi chú của direct sale',
                    'format' => 'html',
                    'value' => function ($model) {
                        return $model->note_direct;
                    }
                ],
                'customer_thamkham',
                'customer_mongmuon',
                [
                    'attribute' => 'permission_user',
                    'value' => function ($model) {
                        $userProfile = new Dep365CustomerOnline();
                        return $userProfile->getNhanVienTuVan($model->permission_user);
                    }
                ],
                'created_at:datetime',
                'updated_at:datetime',

                [
                    'attribute' => 'created_by',
                    'value' => function ($model) {
                        $user = new Dep365CustomerOnline();
                        $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                        return $userCreatedBy->fullname;
                    }
                ],
                [
                    'attribute' => 'updated_by',
                    'value' => function ($model) {
                        $user = new Dep365CustomerOnline();
                        $userCreatedBy = $user->getUserCreatedBy($model->updated_by);
                        return $userCreatedBy->fullname;
                    }
                ],

            ],
        ]) ?>
    </div>
    <div class="modal-footer p-0"></div>