<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 18-04-2019
 * Time: 10:15 AM
 */
namespace backend\models\quanly;

use backend\models\CustomerModel;

class CustomerInfo extends CustomerModel
{
    public function rules()
    {
        return [
            [['full_name', 'forename', 'birthday', 'sex', 'time_lichhen', 'dat_hen', 'phone', 'address'], 'required'],
            [['full_name', 'forename','birthday', 'customer_mongmuon', 'note_direct', 'phone', 'address'], 'string'],
            ['phone', 'telnumvn', 'exceptTelco' => ['landLine']],
            [['sex', 'dat_hen'], 'integer'],
            [['time_lichhen'], 'safe'],
        ];
    }
}
