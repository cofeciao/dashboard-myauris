<?php

namespace backend\modules\customer\models;

use Yii;
use yii\db\ActiveRecord;

class Dep365CustomerOnlineTree extends ActiveRecord
{
    public static function tableName()
    {
        return 'dep365_customer_online_tree';
    }

    public function getCustomerOnlineHasOne()
    {
        return $this->hasOne(Dep365CustomerOnline::class, ['id' => 'customer_online_id']);
    }

    public function getFailStatusTreeHasOne()
    {
        return $this->hasOne(Dep365CustomerOnlineFailStatusTree::class, ['customer_online_id' => 'customer_online_id']);
    }
}
