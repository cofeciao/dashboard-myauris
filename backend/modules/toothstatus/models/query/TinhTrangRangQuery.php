<?php

namespace backend\modules\toothstatus\models\query;

use backend\modules\toothstatus\models\TinhTrangRang;
use yii\db\ActiveQuery;

class TinhTrangRangQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere([TinhTrangRang::tableName() . '.status' => TinhTrangRang::STATUS_PUBLISHED]);
        return $this;
    }
}
