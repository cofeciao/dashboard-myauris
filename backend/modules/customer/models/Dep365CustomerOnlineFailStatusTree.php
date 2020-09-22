<?php

namespace backend\modules\customer\models;

use Yii;
use yii\db\ActiveRecord;

class Dep365CustomerOnlineFailStatusTree extends ActiveRecord
{
    public static function tableName()
    {
        return 'dep365_customer_online_status_fail_tree';
    }

    public function getCustomerOnlineHasOne()
    {
        return $this->hasOne(Dep365CustomerOnline::class, ['id' => 'customer_online_id']);
    }
}
