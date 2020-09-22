<?php

namespace backend\modules\clinic\models;

class PhongKhamLichDieuTriTree extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'phong_kham_lich_dieu_tri_tree';
    }

    public function getOrderHasOne()
    {
        return $this->hasOne(PhongKhamDonHang::class, ['order_code' => 'order_code']);
    }
}
