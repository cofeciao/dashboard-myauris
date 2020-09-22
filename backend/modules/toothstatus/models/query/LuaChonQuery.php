<?php

namespace backend\modules\toothstatus\models\query;

use backend\modules\toothstatus\models\LuaChon;
use yii\db\ActiveQuery;

class LuaChonQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere([LuaChon::tableName() . '.status' => LuaChon::STATUS_PUBLISHED]);
        return $this;
    }
}
