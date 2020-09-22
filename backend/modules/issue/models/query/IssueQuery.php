<?php

namespace backend\modules\issue\models\query;

use backend\modules\issue\models\Issue;

/**
 * This is the ActiveQuery class for [[Issue]].
 *
 * @see Issue
 */
class IssueQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([Issue::tableName() . '.status' => Issue::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([Issue::tableName() . '.status' => Issue::STATUS_DISABLED]);
    }

    /**
     * {@inheritdoc}
     * @return Issue[]|array
     */
    /*public function all($db = null)
    {
        return parent::all($db);
    }*/

    /**
     * {@inheritdoc}
     * @return Issue|array|null
     */
    /*public function one($db = null)
    {
        return parent::one($db);
    }*/
}
