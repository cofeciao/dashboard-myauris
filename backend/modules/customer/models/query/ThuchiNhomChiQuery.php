<?php

namespace backend\modules\customer\models\query;

use backend\modules\customer\models\ThuchiNhomChi;

/**
 * This is the ActiveQuery class for [[ThuchiNhomChi]].
 *
 * @see ThuchiNhomChi
 */
class ThuchiNhomChiQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([ThuchiNhomChi::tableName() . '.status' => ThuchiNhomChi::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([ThuchiNhomChi::tableName() . '.status' => ThuchiNhomChi::STATUS_DISABLED]);
    }

    /**
     * {@inheritdoc}
     * @return ThuchiNhomChi[]|array
     */
    /*public function all($db = null)
    {
        return parent::all($db);
    }*/

    /**
     * {@inheritdoc}
     * @return ThuchiNhomChi|array|null
     */
    /*public function one($db = null)
    {
        return parent::one($db);
    }*/
}
