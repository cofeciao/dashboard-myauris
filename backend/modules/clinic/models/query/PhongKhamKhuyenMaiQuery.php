<?php

namespace backend\modules\clinic\models\query;

use backend\modules\clinic\models\PhongKhamKhuyenMai;
use yii\db\ActiveQuery;

class PhongKhamKhuyenMaiQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere([PhongKhamKhuyenMai::tableName() . '.status' => PhongKhamKhuyenMai::STATUS_PUBLISHED]);
        return $this;
    }
}
