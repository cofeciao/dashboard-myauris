<?php

namespace console\models;

use backend\models\CustomerModel;

class CustomerSms extends CustomerModel
{
    /*
     * $date = 1 - Khách có lịch hẹn là ngày mai
     * $date = 3 - Khách có lịch hẹn sau 3 ngày nữa
     * $date = 7 - khách đặt hẹn sau 7 ngày nữa
     */
    public static function getCustomerSendSms($date)
    {
        $datetime = new \DateTime();
        $from = strtotime($datetime->format('d-m-Y'));
        $to = $from + 86400;
        $datetime->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'));

        $query = self::find()->select('id, forename, name, sex, phone, co_so, time_lichhen, created_by');
        $query->where([self::tableName() . '.status' => self::STATUS_DH]);
        $query->andWhere('province <> 97');

        switch ($date) {
            case 1:
                $query->andWhere(self::tableName() . '.time_lichhen between ' . ($from + 86400) . ' and ' . ($to + 86400));
                return $query->all();
                break;
            case 3:
                $query->andWhere(self::tableName() . '.time_lichhen between ' . ($from + 3 * 86400) . ' and ' . ($to + 3 * 86400));
                return $query->all();
                break;
            case 7:
                $query->andWhere(self::tableName() . '.time_lichhen between ' . ($from + 7 * 86400) . ' and ' . ($to + 7 * 86400));
                return $query->all();
                break;
            default:
                break;
        }
    }
}
