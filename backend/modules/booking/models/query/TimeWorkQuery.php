<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 24-04-2019
 * Time: 09:18 AM
 */

namespace backend\modules\booking\models\query;

use backend\modules\booking\models\TimeWork;

class TimeWorkQuery extends \yii\db\ActiveQuery
{
    public function published()
    {
        $this->andWhere([TimeWork::tableName() . '.status' => TimeWork::STATUS_PUBLISHED]);
        return $this;
    }
}
