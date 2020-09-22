<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\clinic\models\PhongKhamSanPham */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Phòng khám'), 'url' => ['/clinic/clinic']];
$this->params['breadcrumbs'][] = ['label' => 'Sản Phẩm', 'url' => ['index']];
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
                                'name',
                                'slug',
                                [
                                    'attribute' => 'don_gia',
                                    'value' => function ($model) {
                                        return number_format($model->don_gia, 0, ',', '.') . '₫';
                                    }
                                ],
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
                                        $user = new backend\modules\clinic\models\PhongKhamSanPham();
                                        $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                                        return $userCreatedBy->fullname;
                                    }
                                ],
                                [
                                    'attribute' => 'updated_by',
                                    'value' => function ($model) {
                                        $user = new backend\modules\clinic\models\PhongKhamSanPham();
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
</section>

