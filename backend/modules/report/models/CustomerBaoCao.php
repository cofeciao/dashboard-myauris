<?php
namespace backend\modules\report\models;

use backend\models\CustomerModel;
use backend\models\doanhthu\ThanhToanModel;
use backend\models\doanhthu\DonHangModel;

class CustomerBaoCao extends CustomerModel
{
    public $count;
    public function getThanhToan()
    {
        return $this->hasMany(ThanhToanModel::class, ['customer_id' => 'id']);
    }

    public function getPhongKhamDonHang()
    {
        return $this->hasMany(DonHangModel::class, ['customer_id' => 'id']);
    }
}
