<?php

namespace backend\modules\api\modules\v1\models;

use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\modules\customer\models\Dep365CustomerOnlineDathenTime;
use yii\helpers\ArrayHelper;

class Customer extends Dep365CustomerOnline
{
    public static function getCustomerDatHen($customer = null, $from = null, $to = null)
    {
        if ($customer == null) {
            return null;
        }
        $query = Dep365CustomerOnlineDathenTime::find()
            ->select('date_lichhen_new, COUNT(*) AS count_customer_dat_hen')
            ->joinWith(['customerHasOne'])
            ->where([
                self::tableName() . '.nguoi_gioi_thieu' => $customer,
                self::tableName() . '.status' => self::STATUS_DH
            ])
            ->groupBy([
                Dep365CustomerOnlineDathenTime::tableName() . '.date_lichhen_new'
            ])
            ->orderBy([
                Dep365CustomerOnlineDathenTime::tableName() . '.date_lichhen_new' => SORT_ASC
            ])
            ->indexBy('date_lichhen_new');
        if ($from != null && $to != null) {
            $query->andWhere([
            'BETWEEN', Dep365CustomerOnlineDathenTime::tableName() . '.date_lichhen_new', $from, $to
        ]);
        }
        return $query->all();
    }

    public static function getCustomerDatHenKhongDen($customer = null, $from = null, $to = null)
    {
        if ($customer == null) {
            return null;
        }
        $query = Dep365CustomerOnlineDathenTime::find()
            ->select('date_lichhen_new, COUNT(*) AS count_customer_dat_hen')
            ->joinWith(['customerHasOne'])
            ->where([
                self::tableName() . '.nguoi_gioi_thieu' => $customer,
                self::tableName() . '.status' => self::STATUS_DH,
                self::tableName() . '.dat_hen' => self::DAT_HEN_KHONG_DEN
            ])
            ->groupBy([
                Dep365CustomerOnlineDathenTime::tableName() . '.date_lichhen_new'
            ])
            ->orderBy([
                Dep365CustomerOnlineDathenTime::tableName() . '.date_lichhen_new' => SORT_ASC
            ])
            ->indexBy('date_lichhen_new');
        if ($from != null && $to != null) {
            $query->andWhere([
            'BETWEEN', Dep365CustomerOnlineDathenTime::tableName() . '.date_lichhen_new', $from, $to
        ]);
        }
        return $query->all();
    }

    public static function getCustomerDatHenDen($customer = null, $from = null, $to = null)
    {
        if ($customer == null) {
            return null;
        }
        $query = Dep365CustomerOnlineDathenTime::find()
            ->select('date_lichhen_new, COUNT(*) AS count_customer_dat_hen')
            ->joinWith(['customerHasOne'])
            ->where([
                self::tableName() . '.nguoi_gioi_thieu' => $customer,
                self::tableName() . '.status' => self::STATUS_DH,
                self::tableName() . '.dat_hen' => self::DAT_HEN_DEN
            ])
            ->groupBy([
                Dep365CustomerOnlineDathenTime::tableName() . '.date_lichhen_new'
            ])
            ->orderBy([
                Dep365CustomerOnlineDathenTime::tableName() . '.date_lichhen_new' => SORT_ASC
            ])
            ->indexBy('date_lichhen_new');
        if ($from != null && $to != null) {
            $query->andWhere([
            'BETWEEN', Dep365CustomerOnlineDathenTime::tableName() . '.date_lichhen_new', $from, $to
        ]);
        }
        return $query->all();
    }

    public static function getCustomerLam($customer = null, $from = null, $to = null)
    {
        if ($customer == null) {
            return null;
        }
        $listAccept = Dep365CustomerOnlineCome::find()->where(['accept' => Dep365CustomerOnlineCome::STATUS_ACCEPT])->published()->all();
        $query = Dep365CustomerOnlineDathenTime::find()
            ->select('date_lichhen_new, COUNT(*) AS count_customer_dat_hen')
            ->joinWith(['customerHasOne'])
            ->where([
                self::tableName() . '.nguoi_gioi_thieu' => $customer,
                self::tableName() . '.status' => self::STATUS_DH,
                self::tableName() . '.dat_hen' => self::DAT_HEN_DEN
            ])
            ->andWhere([
                'IN', self::tableName().'.customer_come_time_to', ArrayHelper::map($listAccept, 'id', 'id')
            ])
            ->groupBy([
                Dep365CustomerOnlineDathenTime::tableName() . '.date_lichhen_new'
            ])
            ->orderBy([
                Dep365CustomerOnlineDathenTime::tableName() . '.date_lichhen_new' => SORT_ASC
            ])
            ->indexBy('date_lichhen_new');
        if ($from != null && $to != null) {
            $query->andWhere([
            'BETWEEN', Dep365CustomerOnlineDathenTime::tableName() . '.date_lichhen_new', $from, $to
        ]);
        }
        return $query->all();
    }

    public static function getCustomerKhongLam($customer = null, $from = null, $to = null)
    {
        if ($customer == null) {
            return null;
        }
        $listAccept = Dep365CustomerOnlineCome::find()->where(['accept' => Dep365CustomerOnlineCome::STATUS_ACCEPT])->published()->all();
        $query = Dep365CustomerOnlineDathenTime::find()
            ->select('date_lichhen_new, COUNT(*) AS count_customer_dat_hen')
            ->joinWith(['customerHasOne'])
            ->where([
                self::tableName() . '.nguoi_gioi_thieu' => $customer,
                self::tableName() . '.status' => self::STATUS_DH,
                self::tableName() . '.dat_hen' => self::DAT_HEN_DEN
            ])
            ->andWhere([
                'NOT IN', self::tableName().'.customer_come_time_to', ArrayHelper::map($listAccept, 'id', 'id')
            ])
            ->groupBy([
                Dep365CustomerOnlineDathenTime::tableName() . '.date_lichhen_new'
            ])
            ->orderBy([
                Dep365CustomerOnlineDathenTime::tableName() . '.date_lichhen_new' => SORT_ASC
            ])
            ->indexBy('date_lichhen_new');
        if ($from != null && $to != null) {
            $query->andWhere([
            'BETWEEN', Dep365CustomerOnlineDathenTime::tableName() . '.date_lichhen_new', $from, $to
        ]);
        }
        return $query->all();
    }
}
