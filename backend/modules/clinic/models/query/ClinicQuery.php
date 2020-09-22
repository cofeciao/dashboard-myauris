<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/4/14
 * Time: 2:31 PM
 */

namespace backend\modules\clinic\models\query;

use backend\modules\clinic\models\Clinic;
use yii\db\ActiveQuery;

class ClinicQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere(['dep365_customer_online.status' => Clinic::STATUS_DH]);
        $this->orderBy(['dep365_customer_online.id' => SORT_DESC]);
        return $this;
    }
}
