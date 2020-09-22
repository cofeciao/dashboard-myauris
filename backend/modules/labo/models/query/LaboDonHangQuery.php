<?php

namespace backend\modules\labo\models\query;

use backend\modules\labo\models\LaboDonHang;

/**
 * This is the ActiveQuery class for [[LaboDonHang]].
 *
 * @see LaboDonHang
 */
class LaboDonHangQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([LaboDonHang::tableName() . '.status' => LaboDonHang::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([LaboDonHang::tableName() . '.status' => LaboDonHang::STATUS_DISABLED]);
    }

    /**
     * {@inheritdoc}
     * @return LaboDonHang[]|array
     */
    /*public function all($db = null)
    {
        return parent::all($db);
    }*/

    /**
     * {@inheritdoc}
     * @return LaboDonHang|array|null
     */
    /*public function one($db = null)
    {
        return parent::one($db);
    }*/
}
