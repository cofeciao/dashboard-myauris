<?php


namespace backend\modules\support\models\query;

use backend\modules\support\models\Support;
use yii\db\ActiveQuery;

class SupportQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere(['status' => Support::STATUS_PUBLISHED]);
        $this->orderBy(['id' => SORT_DESC]);
        return $this;
    }
}
