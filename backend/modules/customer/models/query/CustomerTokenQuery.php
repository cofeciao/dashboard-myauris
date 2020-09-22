<?php

namespace backend\modules\customer\models\query;

use backend\modules\customer\models\CustomerToken;

/**
 * This is the ActiveQuery class for [[CustomerToken]].
 *
 * @see CustomerToken
 */
class CustomerTokenQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([CustomerToken::tableName() . '.status' => CustomerToken::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([CustomerToken::tableName() . '.status' => CustomerToken::STATUS_DISABLED]);
    }

    /**
     * {@inheritdoc}
     * @return CustomerToken[]|array
     */
    /*public function all($db = null)
    {
        return parent::all($db);
    }*/

    /**
     * {@inheritdoc}
     * @return CustomerToken|array|null
     */
    /*public function one($db = null)
    {
        return parent::one($db);
    }*/
}
