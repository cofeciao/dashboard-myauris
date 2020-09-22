<?php

namespace backend\modules\screenonline\components;

use backend\modules\customer\components\CustomerComponents;


use yii\base\Component;

class CustomerComponent extends Component
{
    public static function getKhachDenTrongThang($from, $to)
    {
        return CustomerComponents::getTotalCustomerGotoAuris($from, $to, null, null, null, null, null);
    }

    //Lấy ra lich hẹn hôm nay của các cơ sở
}
