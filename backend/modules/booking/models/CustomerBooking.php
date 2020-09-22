<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 26-04-2019
 * Time: 05:22 PM
 */

namespace backend\modules\booking\models;

use backend\models\CustomerModel;

class CustomerBooking extends CustomerModel
{
    public function rules()
    {
        return [
            [['co_so', 'time_lichhen'], 'required'],
            [['co_so', 'time_lichhen'], 'integer'],
        ];
    }
}
