<?php

namespace backend\modules\customer\models\query;

use backend\modules\customer\models\ThuchiDanhMucChi;

/**
 * This is the ActiveQuery class for [[ThuchiDanhMucChi]].
 *
 * @see ThuchiDanhMucChi
 */
class ThuchiDanhMucChiQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([ThuchiDanhMucChi::tableName() . '.status' => ThuchiDanhMucChi::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([ThuchiDanhMucChi::tableName() . '.status' => ThuchiDanhMucChi::STATUS_DISABLED]);
    }

    /**
     * {@inheritdoc}
     * @return ThuchiDanhMucChi[]|array
     */
    /*public function all($db = null)
    {
        return parent::all($db);
    }*/

    /**
     * {@inheritdoc}
     * @return ThuchiDanhMucChi|array|null
     */
    /*public function one($db = null)
    {
        return parent::one($db);
    }*/
}
