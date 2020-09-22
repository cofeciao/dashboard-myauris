<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 23-05-2019
 * Time: 11:15 AM
 */

namespace backend\modules\customer\models\query;

use yii\db\ActiveQuery;
use backend\modules\customer\models\Dep365CustomerOnlineFailDathen;

class Dep365CustomerOnlineFailDathenQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere([Dep365CustomerOnlineFailDathen::tableName().'.status' => Dep365CustomerOnlineFailDathen::STATUS_PUBLISHED]);
        return $this;
    }
}
