<?php

use backend\modules\labo\models\LaboDonHang;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\labo\models\LaboDonHang */

$this->title = "Phiếu Labo " . $mDonHang->order_code;
//$this->params['breadcrumbs'][] = ['label' => 'Phiếu Labo', 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
$array_vi_tri_rang = ($model->vi_tri_rang) ? $model->vi_tri_rang : [];
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

                        <div class="btn-add-campaign clearfix" style="margin-top:0px;position:relative">
                            <?= Html::a('<i class="fa fa-wrench"> Quản lý công đoạn</i>', ['/labo/labo-don-hang/quan-ly-cong-doan', 'id' => $model->id], ['title' => 'Quản lý công đoạn', 'data-pjax' => 0, 'class' => 'btn btn-default pull-left']) ?>
                            <?= Html::a('<i class="fa fa-user-o"> Hình ảnh KH</i>', ['/labo/labo-don-hang/hinh-anh-khach-hang', 'id' => $mDonHang->customer_id], ['title' => 'Hình ảnh KH', 'data-pjax' => 0, 'class' => 'btn btn-default pull-left']) ?>
                        </div>
                        <br>
                        <?= DetailView::widget([
                            'model' => $model,
                            'options' => ['class' => 'detail1-view table table-striped table-bordered detail-view'],
                            'attributes' => [
                                [
                                    'attribute' => 'bac_si_id',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        $user = new \common\models\UserProfile();
                                        $fullname = $user->getFullNameBacSi($model->bac_si_id);
                                        return $fullname;
                                    }
                                ],
                                [
                                    'attribute' => 'phong_kham_don_hang_id',
                                    'format' => 'raw',
                                    'value' => function () use ($mDonHang) {
                                        return $mDonHang->order_code;
                                    }
                                ],
                                [
                                    'attribute' => 'ngay_nhan',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return date('d-m-Y', $model->ngay_nhan);
                                    }
                                ],
                                [
                                    'attribute' => 'ngay_giao',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return date('d-m-Y', $model->ngay_giao);
                                    }
                                ],
                                [
                                    'attribute' => 'loai_phuc_hinh',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $model->getLoaiPhucHinh();
                                    }
                                ],
                                'yeu_cau:ntext',
                                [
                                    'attribute' => 'trang_thai',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $model->getTrangThai();
                                    }
                                ],

//            'status',
                                'created_at:datetime',
//            'created_by',
//            'updated_by',
//            'updated_at',

//                        [
//                            'attribute' => 'status',
//                            'format' => 'raw',
//                            'value' => function ($model) {
//                                return $model->status == 1 ? 'Hiển thị' : 'Đang ẩn';
//                            }
//                        ],

                                [
                                    'attribute' => 'created_by',
                                    'value' => function ($model) {
                                        /*$user = new backend\modules\labo\models\LaboDonHang();*/
                                        $user = new \backend\modules\user\models\User();
                                        $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                                        if ($userCreatedBy == null) return null;
                                        return $userCreatedBy->fullname;
                                    }
                                ],
//                        [
//                            'attribute' => 'updated_by',
//                            'value' => function ($model) {
//                                /*$user = new backend\modules\labo\models\LaboDonHang();*/
//                                $user = new \backend\modules\user\models\User();
//                                $userCreatedBy = $user->getUserCreatedBy($model->updated_by);
//                                if($userCreatedBy == null) return null;
//                                return $userCreatedBy->fullname;
//                            }
//                        ],

                            ],
                        ]) ?>

                        <h4 class="card-title">Vị trị răng</h4>
                        <div class="row">
                            <!--                            <div class="col-2"></div>-->
                            <div class="col-12">
                                <div style="overflow-x:auto;">
                                    <table class="table ">
                                        <tbody>
                                        <tr>
                                            <?php
                                            $listTren = LaboDonHang::getListRangTren();
                                            foreach ($listTren as $key => $value):
                                                $checked = isset($array_vi_tri_rang[$key]) ? "checked" : " ";
                                                ?>
                                                <td>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input"
                                                               name="LaboDonHang[vi_tri_rang][<?= $key ?>]"
                                                               id="checkbox<?= $key ?>" <?= $checked ?> disabled>
                                                        <label class="custom-control-label"
                                                               for="checkbox<?= $key ?>"><?= $value ?></label>
                                                    </div>
                                                </td>
                                            <?php
                                            endforeach;
                                            ?>
                                        </tr>

                                        <tr>
                                            <?php
                                            $listDuoi = LaboDonHang::getListRangDuoi();
                                            foreach ($listDuoi as $key => $value):
                                                $checked = isset($array_vi_tri_rang[$key]) ? "checked" : " ";
                                                ?>
                                                <td>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input"
                                                               name="LaboDonHang[vi_tri_rang][<?= $key ?>]"
                                                               id="checkbox<?= $key ?>" <?= $checked ?> disabled>
                                                        <label class="custom-control-label"
                                                               for="checkbox<?= $key ?>"><?= $value ?></label>
                                                    </div>
                                                </td>
                                            <?php
                                            endforeach;
                                            ?>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--                            <div class="col-2"></div>-->
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>


</section>

