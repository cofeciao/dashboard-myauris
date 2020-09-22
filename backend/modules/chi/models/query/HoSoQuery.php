<?php

namespace backend\modules\chi\models\query;

use backend\modules\chi\models\HoSo;

/**
 * This is the ActiveQuery class for [[HoSo]].
 *
 * @see HoSo
 */
class HoSoQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([HoSo::tableName() . '.status' => HoSo::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([HoSo::tableName() . '.status' => HoSo::STATUS_DISABLED]);
    }

    /**
     * {@inheritdoc}
     * @return HoSo[]|array
     */
    /*public function all($db = null)
    {
        return parent::all($db);
    }*/

    /**
     * {@inheritdoc}
     * @return HoSo|array|null
     */
    /*public function one($db = null)
    {
        return parent::one($db);
    }*/
}
