<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 22-04-2019
 * Time: 02:54 PM
 */

namespace backend\modules\api\models;

use backend\modules\booking\models\CustomerOnlineBooking;
use backend\modules\booking\models\TimeWork;
use yii\db\ActiveRecord;

class BookingModel extends CustomerOnlineBooking
{
    public function attributeLabels()
    {
        return [
            'user_register_id' => \Yii::t('backend', 'Khách hàng'),
            'coso_id' => \Yii::t('backend', 'Cơ sở'),
            'booking_date' => \Yii::t('backend', 'Ngày đặt hẹn'),
            'time_id' => \Yii::t('backend', 'Thời gian đặt hẹn'),
        ];
    }
}
