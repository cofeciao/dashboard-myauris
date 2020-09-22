<?php

namespace backend\modules\chi\models\query;

use backend\modules\chi\models\DanhMucChi;

/**
 * This is the ActiveQuery class for [[DanhMucChi]].
 *
 * @see DanhMucChi
 */
class DanhMucChiQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([DanhMucChi::tableName() . '.status' => DanhMucChi::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([DanhMucChi::tableName() . '.status' => DanhMucChi::STATUS_DISABLED]);
    }

    /**
     * {@inheritdoc}
     * @return DanhMucChi[]|array
     */
    /*public function all($db = null)
    {
        return parent::all($db);
    }*/

    /**
     * {@inheritdoc}
     * @return DanhMucChi|array|null
     */
    /*public function one($db = null)
    {
        return parent::one($db);
    }*/
}
