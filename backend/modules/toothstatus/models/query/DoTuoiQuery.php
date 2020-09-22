<?php

namespace backend\modules\toothstatus\models\query;

use backend\modules\toothstatus\models\DoTuoi;
use yii\db\ActiveQuery;

class DoTuoiQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere([DoTuoi::tableName() . '.status' => DoTuoi::STATUS_PUBLISHED]);
        return $this;
    }
}
