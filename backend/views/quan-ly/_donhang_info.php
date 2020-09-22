<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 19-04-2019
 * Time: 06:14 PM
 */

use backend\modules\clinic\models\PhongKhamDonHangWThanhToan;
use backend\modules\clinic\models\PhongKhamKhuyenMai;
use yii\grid\GridView;
use yii\widgets\Pjax;

?>
<div class="sub-panel">
    <div class="sp-title">
        <h1>Mã HĐ : <span class="font-weight-bold"><?= $order->order_code ?></span>
            <span class="font-italic">
                ( <?= date('d-m-Y', $order->ngay_tao) ?>
                - <?= $order->getCreatedBy() ?> )
            </span>
        </h1>
    </div>
    <?php Pjax::begin() ?>
    <?= GridView::widget([
        'dataProvider' => $dataOrderProviderOrder,
        'layout' => '{items}{pager}',
        'columns' => [
            [
                'header' => '<p class="font-weight-bold text-center">Dịch vụ</p>',
                'value' => function ($model) {
                    if ($model->dichVuHasOne == null) {
                        return null;
                    }
                    return $model->dichVuHasOne->name;
                }
            ],
            [
                'header' => '<p class="font-weight-bold text-center">Sản phẩm</p>',
                'value' => function ($model) {
                    if ($model->sanPhamHasOne == null) {
                        return null;
                    }
                    return $model->sanPhamHasOne->name;
                }
            ],
            [
                'header' => '<p class="font-weight-bold text-center">Số lượng</p>',
                'format' => 'html',
                'value' => function ($model) {
                    return number_format($model->so_luong, 0, '', '.');
                },
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ],

            [
                'header' => '<p class="font-weight-bold text-center">Thành tiền</p>',
                'format' => 'html',
                'value' => function ($model) {
                    return number_format($model->thanh_tien, 0, '', '.');
                },
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ],
            [
                'header' => '<p class="font-weight-bold text-center">Chiết khấu</p>',
                'format' => 'html',
                'value' => function ($model) {
                    $arr = PhongKhamKhuyenMai::TYPE;
                    $khuyenmai = (PhongKhamKhuyenMai::TYPE_PERCENT == $model->chiet_khau_theo_order) ? $arr[$model->chiet_khau_theo_order] : "";
                    return number_format($model->chiet_khau_order, 0, '', '.') . " " . $khuyenmai;
                },
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ]
        ],
    ]) ?>
    <?php Pjax::end() ?>
    <div class="form-group row order-row text-right">
        <div class="col-10">Tổng:</div>
        <div class="col-2">
            <span id="tong"><?= $order->thanh_tien != null ? number_format($order->thanh_tien, 0, '', '.') : 0 ?></span>
        </div>
    </div>

    <?php
    if (isset($listKhuyenMai[$order->khuyen_mai])):
        $arr = PhongKhamKhuyenMai::TYPE;
        $type = $listKhuyenMai[$order->khuyen_mai]['type'];
        $price = $listKhuyenMai[$order->khuyen_mai]['price'];
        $name = $listKhuyenMai[$order->khuyen_mai]['name'];
        $khuyenmai = (PhongKhamKhuyenMai::TYPE_PERCENT == $type) ? " " . $arr[$type] : "";
        ?>
        <div class="form-group row order-row text-right">
            <div class="col-10">Chương trình khuyến mãi : <span class="font-weight-bold"><?= $name ?></span></div>
            <div class="col-2">
                <span id="khuyen-mai">
                    <?= number_format($price, 0, '', '.') . $khuyenmai ?>
                 </span>
            </div>
        </div>
    <?php
    endif;
    ?>

    <div class="form-group row order-row text-right">
        <div class="col-10">Tổng chiết khấu:</div>
        <div class="col-2">
            <span id="chiet-khau"><?= $order->chiet_khau != null ? number_format($order->chiet_khau, 0, '', '.') : 0 ?></span>
        </div>
    </div>

    <div class="form-group row order-row text-right font-weight-bold ">
        <div class="col-10">Thành tiền:</div>
        <div class="col-2">
            <span id="thanh-tien"
                  class="text-primary"><?= number_format($order->thanh_tien - $order->chiet_khau, 0, '', '.') ?></span>
        </div>
    </div>

    <div class="sp-title">
        <h1>Lịch sử thanh toán <span class="font-weight-bold"><?= $order->order_code ?></span></h1>
    </div>


    <?php Pjax::begin() ?>
    <?= GridView::widget([
        'dataProvider' => $dataOrderProviderThanhToan,
        'layout' => '{items}{pager}',
        'columns' => [
            [
                'header' => '<p class="font-weight-bold text-center">Ngày giao dịch</p>',
                'value' => function ($model) {
                    return date('d-m-Y H:i:s', $model->created_at);
                },
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ],

            [
                'header' => '<p class="font-weight-bold text-center">Hình thức</p>',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->getTamUng();
                }
            ],

            [
                'header' => '<p class="font-weight-bold text-center">Loại thanh toán</p>',
                'format' => 'html',
                'value' => function ($model) {
                    if ($model->loaiThanhToanHasOne) {
                        return $model->loaiThanhToanHasOne->name;
                    }
                }
            ],
            [
                'header' => '<p class="font-weight-bold text-center">Tiền thanh toán</p>',
                'value' => function ($model) {
                    return number_format($model->tien_thanh_toan, 0, '', '.');
                },
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ],
        ],
    ]) ?>
    <?php Pjax::end() ?>

    <?php
    $tong_thanh_toan = PhongKhamDonHangWThanhToan::getThanhToanByOrderStatic($order->id);
    $tong_dat_coc = PhongKhamDonHangWThanhToan::getDatCocByOrderStatic($order->id);
    ?>
    <div class="form-group row order-row text-right font-weight-bold">
        <div class="col-10">Tổng đã thanh toán:</div>
        <div class="col-2">
            <span id="tong"><?= number_format($tong_thanh_toan, 0, '', '.') ?></span>
        </div>
    </div>

    <div class="form-group row order-row text-right">
        <div class="col-10">Tổng đã đặt cọc :</div>
        <div class="col-2">
            <span id="tong"><?= number_format($tong_dat_coc, 0, '', '.') ?></span>
        </div>
    </div>

    <div class="form-group row order-row text-right font-weight-bold">
        <div class="col-10">Còn nợ :</div>
        <div class="col-2">
            <span id="tong"
                  class="text-danger"><?= number_format($order->thanh_tien - $order->chiet_khau - $tong_thanh_toan - $tong_dat_coc, 0, '', '.') ?></span>
        </div>
    </div>

</div>
<hr style="background-color: #fff;
    border-top: 2px dashed #8c8b8b;">
