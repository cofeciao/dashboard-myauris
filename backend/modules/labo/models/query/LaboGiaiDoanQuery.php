<?php

namespace backend\modules\labo\models\query;

use backend\modules\labo\models\LaboGiaiDoan;

/**
 * This is the ActiveQuery class for [[LaboGiaiDoan]].
 *
 * @see LaboGiaiDoan
 */
class LaboGiaiDoanQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([LaboGiaiDoan::tableName() . '.status' => LaboGiaiDoan::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([LaboGiaiDoan::tableName() . '.status' => LaboGiaiDoan::STATUS_DISABLED]);
    }

    /**
     * {@inheritdoc}
     * @return LaboGiaiDoan[]|array
     */
    /*public function all($db = null)
    {
        return parent::all($db);
    }*/

    /**
     * {@inheritdoc}
     * @return LaboGiaiDoan|array|null
     */
    /*public function one($db = null)
    {
        return parent::one($db);
    }*/
}
