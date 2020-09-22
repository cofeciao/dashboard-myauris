<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 18-Feb-19
 * Time: 6:00 PM
 */

namespace backend\models;

use backend\components\MyModel;
use yii\db\ActiveRecord;

class Customer extends MyModel
{
    public static function tableName()
    {
        return 'dep365_customer_online';
    }
}
