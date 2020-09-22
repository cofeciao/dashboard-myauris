<?php
namespace backend\models;

class CustomerElastic extends \yii\elasticsearch\ActiveRecord
{
    /**
     * @return array the list of attributes for this record
     */
    const NAME_INDEX = "customer-elastics";
    const NAME_TYPE = "customer-elastic";

    public function attributes()
    {
        $customer = new CustomerModel();
        return $customer->attributes();
    }
}
