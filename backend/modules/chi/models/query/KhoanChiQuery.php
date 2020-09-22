<?php

namespace backend\modules\chi\models\query;

use backend\modules\chi\models\KhoanChi;

/**
 * This is the ActiveQuery class for [[KhoanChi]].
 *
 * @see KhoanChi
 */
class KhoanChiQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([KhoanChi::tableName() . '.status' => KhoanChi::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([KhoanChi::tableName() . '.status' => KhoanChi::STATUS_DISABLED]);
    }

    /**
     * {@inheritdoc}
     * @return KhoanChi[]|array
     */
    /*public function all($db = null)
    {
        return parent::all($db);
    }*/

    /**
     * {@inheritdoc}
     * @return KhoanChi|array|null
     */
    /*public function one($db = null)
    {
        return parent::one($db);
    }*/
}
