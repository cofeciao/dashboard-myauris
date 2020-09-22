<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/4/14
 * Time: 2:31 PM
 */

namespace backend\modules\customer\models\query;

use backend\modules\customer\models\Dep365CustomerOnlineCome;
use yii\db\ActiveQuery;

class Dep365CustomerOnlineComeQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere(['dep365_customer_online_come.status' => Dep365CustomerOnlineCome::STATUS_PUBLISHED]);
        $this->orderBy(['dep365_customer_online_come.id' => SORT_DESC]);
        return $this;
    }
}
