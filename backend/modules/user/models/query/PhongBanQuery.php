<?php

namespace backend\modules\user\models\query;

use backend\modules\user\models\PhongBan;

/**
 * This is the ActiveQuery class for [[PhongBan]].
 *
 * @see PhongBan
 */
class PhongBanQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([PhongBan::tableName() . '.status' => PhongBan::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([PhongBan::tableName() . '.status' => PhongBan::STATUS_DISABLED]);
    }

    /**
     * {@inheritdoc}
     * @return PhongBan[]|array
     */
    /*public function all($db = null)
    {
        return parent::all($db);
    }*/

    /**
     * {@inheritdoc}
     * @return PhongBan|array|null
     */
    /*public function one($db = null)
    {
        return parent::one($db);
    }*/
}
