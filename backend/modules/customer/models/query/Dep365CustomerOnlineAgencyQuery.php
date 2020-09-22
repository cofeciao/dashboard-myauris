<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/4/14
 * Time: 2:31 PM
 */

namespace backend\modules\customer\models\query;

use backend\modules\customer\models\Dep365Agency;
use yii\db\ActiveQuery;

class Dep365CustomerOnlineAgencyQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere(['dep365_agency.status' => Dep365Agency::STATUS_PUBLISHED]);
        $this->orderBy(['dep365_agency.id' => SORT_DESC]);
        return $this;
    }
}
