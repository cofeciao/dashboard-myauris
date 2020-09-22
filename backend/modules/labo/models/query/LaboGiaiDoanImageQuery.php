<?php

namespace backend\modules\labo\models\query;

use backend\modules\labo\models\LaboGiaiDoanImage;

/**
 * This is the ActiveQuery class for [[LaboGiaiDoanImage]].
 *
 * @see LaboGiaiDoanImage
 */
class LaboGiaiDoanImageQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([LaboGiaiDoanImage::tableName() . '.status' => LaboGiaiDoanImage::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([LaboGiaiDoanImage::tableName() . '.status' => LaboGiaiDoanImage::STATUS_DISABLED]);
    }

    /**
     * {@inheritdoc}
     * @return LaboGiaiDoanImage[]|array
     */
    /*public function all($db = null)
    {
        return parent::all($db);
    }*/

    /**
     * {@inheritdoc}
     * @return LaboGiaiDoanImage|array|null
     */
    /*public function one($db = null)
    {
        return parent::one($db);
    }*/
}
