<?php

namespace backend\modules\customer\models\query;

use backend\modules\customer\models\ThuchiKhoanChi;

/**
 * This is the ActiveQuery class for [[ThuchiKhoanChi]].
 *
 * @see ThuchiKhoanChi
 */
class ThuchiKhoanChiQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([ThuchiKhoanChi::tableName() . '.status' => ThuchiKhoanChi::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([ThuchiKhoanChi::tableName() . '.status' => ThuchiKhoanChi::STATUS_DISABLED]);
    }

    /**
     * {@inheritdoc}
     * @return ThuchiKhoanChi[]|array
     */
    /*public function all($db = null)
    {
        return parent::all($db);
    }*/

    /**
     * {@inheritdoc}
     * @return ThuchiKhoanChi|array|null
     */
    /*public function one($db = null)
    {
        return parent::one($db);
    }*/
}
