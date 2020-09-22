<?php

namespace backend\modules\clinic\models\query;

use backend\modules\clinic\models\ListChupHinh;
use yii\db\ActiveQuery;

class ListChupHinhQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere([ListChupHinh::tableName() . '.status' => ListChupHinh::STATUS_PUBLISHED]);
        return $this;
    }
}
