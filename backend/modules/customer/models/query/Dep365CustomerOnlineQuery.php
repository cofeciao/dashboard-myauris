<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/4/14
 * Time: 2:31 PM
 */

namespace backend\modules\customer\models\query;

use backend\modules\customer\models\Dep365CustomerOnline;
use yii\db\ActiveQuery;

class Dep365CustomerOnlineQuery extends ActiveQuery
{
    public function findCustomerOfOnline()
    {
        $this->andWhere(['dep365_customer_online.is_customer_who' => Dep365CustomerOnline::CUSTOMER_WITH_ONLINE]);
        return $this;
    }
}
