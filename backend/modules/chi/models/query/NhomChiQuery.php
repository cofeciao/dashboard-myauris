<?php

namespace backend\modules\chi\models\query;

use backend\modules\chi\models\NhomChi;

/**
 * This is the ActiveQuery class for [[NhomChi]].
 *
 * @see NhomChi
 */
class NhomChiQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([NhomChi::tableName() . '.status' => NhomChi::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([NhomChi::tableName() . '.status' => NhomChi::STATUS_DISABLED]);
    }

    /**
     * {@inheritdoc}
     * @return NhomChi[]|array
     */
    /*public function all($db = null)
    {
        return parent::all($db);
    }*/

    /**
     * {@inheritdoc}
     * @return NhomChi|array|null
     */
    /*public function one($db = null)
    {
        return parent::one($db);
    }*/
}
