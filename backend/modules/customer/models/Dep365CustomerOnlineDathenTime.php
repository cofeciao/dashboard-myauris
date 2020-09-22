<?php

namespace backend\modules\customer\models;

use backend\modules\user\models\User;
use common\models\UserProfile;

class Dep365CustomerOnlineDathenTime extends \yii\db\ActiveRecord
{
    public $user;
    public $co_so;
    public $team;
    public $count_customer_dat_hen;

    public static function tableName()
    {
        return 'dep365_customer_online_dathen_time';
    }

    public function getCustomerHasOne()
    {
        return $this->hasOne(Dep365CustomerOnline::class, ['id' => 'customer_online_id']);
    }

    public function getUserHasOne()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'user_id']);
    }

    public function getTableUserHasOne()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
