<?php

namespace backend\modules\toothstatus\models\query;

use backend\modules\toothstatus\models\DichVu;
use yii\db\ActiveQuery;

class DichVuQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere([DichVu::tableName() . '.status' => DichVu::STATUS_PUBLISHED]);
        return $this;
    }
}
