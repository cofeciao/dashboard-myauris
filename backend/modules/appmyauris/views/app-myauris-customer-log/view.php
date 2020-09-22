<?php

/* @var $this yii\web\View */
/* @var $model backend\modules\appmyauris\models\AppMyaurisCustomerLog */

$this->title = 'Đơn nháp';//$model->id;
$this->params['breadcrumbs'][] = ['label' => 'App Myauris Customer Logs', 'url' => ['index']];
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

                        <?php
                        //                        echo DetailView::widget([
                        //                            'model' => $model,
                        //                            'options' => ['class' => 'detail1-view table table-striped table-bordered detail-view'],
                        //                            'attributes' => [
                        //                                'id',
                        //                                'customer_id',
                        //                                'tu_van',
                        //                                'don_hang',
                        //                                'status',
                        //                                'created_at',
                        //                                'created_by',
                        //                                'updated_by',
                        //                                'updated_at',
                        //
                        //                                [
                        //                                    'attribute' => 'status',
                        //                                    'format' => 'raw',
                        //                                    'value' => function ($model) {
                        //                                        return $model->status == 1 ? 'Hiển thị' : 'Đang ẩn';
                        //                                    }
                        //                                ],
                        //
                        //                                [
                        //                                    'attribute' => 'created_by',
                        //                                    'value' => function ($model) {
                        //                                        /*$user = new backend\modules\appmyauris\models\AppMyaurisCustomerLog();*/
                        //                                        $user = new \backend\modules\user\models\User();
                        //                                        $userCreatedBy = $user->getUserCreatedBy($model->created_by);
                        //                                        if ($userCreatedBy == null) return null;
                        //                                        return $userCreatedBy->fullname;
                        //                                    }
                        //                                ],
                        //                                [
                        //                                    'attribute' => 'updated_by',
                        //                                    'value' => function ($model) {
                        //                                        /*$user = new backend\modules\appmyauris\models\AppMyaurisCustomerLog();*/
                        //                                        $user = new \backend\modules\user\models\User();
                        //                                        $userCreatedBy = $user->getUserCreatedBy($model->updated_by);
                        //                                        if ($userCreatedBy == null) return null;
                        //                                        return $userCreatedBy->fullname;
                        //                                    }
                        //                                ],
                        //
                        //                            ],
                        //                        ]);
                        ?>

                        <div class="don-nhap p-1">

                            <table class="table">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Sản phẩm</th>
                                    <th scope="col">Đơn giá</th>
                                    <th scope="col">Số lượng</th>
                                    <th scope="col">Thành tiền</th>
                                    <th scope="col">Loại</th>
                                    <th scope="col">Chiết khấu</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $data = json_decode($model->don_hang, true);
                                if (is_string($data)) $data = json_decode($data, true);
                                if (is_array($data)):
                                    $stt = 0;
                                    foreach ($data as $item):
                                        $stt++;
                                        ?>
                                        <tr>
                                            <th scope="row"><?= $stt ?></th>
                                            <td><?= $item['name'] ?></td>
                                            <td><?= number_format($item['don_gia'], 0, '', '.') ?></td>
                                            <td><?= $item['so_luong'] ?></td>
                                            <td><?= number_format($item['don_gia'] * $item['so_luong'], 0, '', '.') ?></td>
                                            <td><?php
                                                if ($item['loai'] == 1) {
                                                    echo "VNĐ";
                                                } else {
                                                    echo "Phần trăm";
                                                } ?>
                                            </td>
                                            <td><?= $item['chiet_khau'] != '' ? number_format($item['chiet_khau'], 0, '', '.') : '-' ?></td>

                                        </tr>
                                    <?php
                                    endforeach;
                                endif;
                                ?>

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

