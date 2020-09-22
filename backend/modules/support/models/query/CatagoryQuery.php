<?php

namespace backend\modules\support\models\query;

use backend\modules\support\models\SupportCatagory;
use yii\db\ActiveQuery;

class CatagoryQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere(['status' => SupportCatagory::STATUS_PUBLISHED]);
        $this->orderBy(['id' => SORT_DESC]);
        return $this;
    }
}
