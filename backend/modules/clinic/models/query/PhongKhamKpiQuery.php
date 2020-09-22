<?php

namespace backend\modules\clinic\models\query;

use backend\modules\clinic\models\PhongKhamKpi;

/**
 * This is the ActiveQuery class for [[PhongKhamKpi]].
 *
 * @see PhongKhamKpi
 */
class PhongKhamKpiQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([PhongKhamKpi::tableName() . '.status' => PhongKhamKpi::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([PhongKhamKpi::tableName() . '.status' => PhongKhamKpi::STATUS_DISABLED]);
    }

    /**
     * {@inheritdoc}
     * @return PhongKhamKpi[]|array
     */
    /*public function all($db = null)
    {
        return parent::all($db);
    }*/

    /**
     * {@inheritdoc}
     * @return PhongKhamKpi|array|null
     */
    /*public function one($db = null)
    {
        return parent::one($db);
    }*/
}
