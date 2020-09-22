<?php

namespace backend\modules\api\modules\v1\models;

use backend\models\doanhthu\ThanhToanModel;
use backend\modules\customer\models\Dep365CustomerOnline;

class ThanhToan extends ThanhToanModel
{
    public $tong_tien;

    public static function getDoanhThu($customer = null, $from = null, $to = null)
    {
        if ($customer == null) {
            return null;
        }
        $query = self::find()
            ->joinWith(['customerHasOne'])
            ->select(self::tableName() . '.ngay_tao, SUM(' . self::tableName() . '.tien_thanh_toan) AS tong_tien')
            ->where([
                Dep365CustomerOnline::tableName() . '.nguoi_gioi_thieu' => $customer
            ])
            ->groupBy([
                self::tableName() . '.ngay_tao'
            ])
            ->orderBy([
                self::tableName() . '.ngay_tao' => SORT_DESC
            ])
            ->indexBy('ngay_tao');
        if ($from != null && $to != null) {
            $query->andWhere([
            'BETWEEN', self::tableName() . '.ngay_tao', $from, $to
        ]);
        }
        return $query->all();
    }
}
