<?php

namespace backend\models\query;

use backend\models\Dep365CustomerOnlineRemindCall;
use yii\db\ActiveQuery;

class Dep365CustomerOnlineRemindCallQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere([Dep365CustomerOnlineRemindCall::tableName() . '.remind_call_status' => Dep365CustomerOnlineRemindCall::STATUS_PUBLISHED]);
        return $this;
    }
}
