<?php

namespace backend\modules\labo\models\query;

use backend\modules\labo\models\LaboFeedback;

/**
 * This is the ActiveQuery class for [[LaboFeedback]].
 *
 * @see LaboFeedback
 */
class LaboFeedbackQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([LaboFeedback::tableName() . '.status' => LaboFeedback::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([LaboFeedback::tableName() . '.status' => LaboFeedback::STATUS_DISABLED]);
    }

    /**
     * {@inheritdoc}
     * @return LaboFeedback[]|array
     */
    /*public function all($db = null)
    {
        return parent::all($db);
    }*/

    /**
     * {@inheritdoc}
     * @return LaboFeedback|array|null
     */
    /*public function one($db = null)
    {
        return parent::one($db);
    }*/
}
