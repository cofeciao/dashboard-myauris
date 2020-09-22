<?php

namespace backend\modules\report\models\query;

use backend\modules\report\models\SmartReportModel;

/**
 * This is the ActiveQuery class for [[SmartReportModel]].
 *
 * @see SmartReportModel
 */
class SmartReportModelQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        return $this->andWhere([SmartReportModel::tableName() . '.status' => SmartReportModel::STATUS_PUBLISHED]);
    }

    public function disabled()
    {
        return $this->andWhere([SmartReportModel::tableName() . '.status' => SmartReportModel::STATUS_DISABLED]);
    }

    /**
     * {@inheritdoc}
     * @return SmartReportModel[]|array
     */
    /*public function all($db = null)
    {
        return parent::all($db);
    }*/

    /**
     * {@inheritdoc}
     * @return SmartReportModel|array|null
     */
    /*public function one($db = null)
    {
        return parent::one($db);
    }*/
}
