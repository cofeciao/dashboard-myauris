<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 19-04-2019
 * Time: 06:14 PM
 */

use yii\grid\GridView;
use yii\widgets\Pjax;

?>
<div class="sub-panel">
    <?php Pjax::begin() ?>
    <?= GridView::widget([
        'dataProvider' => $dataOrderProvider,
        'layout' => '{items}{pager}',
        'columns' => [
            [
                'header' => '<p class="font-weight-bold text-center">Đơn hàng</p>',
                'value' => function ($model) {
                    return $model->phong_kham_don_hang_id;
                }
            ],

            [
                'header' => '<p class="font-weight-bold text-center">Tạm ứng</p>',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->getTamUng();
                }
            ],

            [
                'header' => '<p class="font-weight-bold text-center">Loại thanh toán</p>',
                'format' => 'html',
                'value' => function ($model) {
                    return $model->loai_thanh_toan;
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
</div>
