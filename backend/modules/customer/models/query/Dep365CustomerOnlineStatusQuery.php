<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/4/14
 * Time: 2:31 PM
 */

namespace backend\modules\customer\models\query;

use backend\modules\customer\models\Dep365CustomerOnlineStatus;
use yii\db\ActiveQuery;

class Dep365CustomerOnlineStatusQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere(['status' => Dep365CustomerOnlineStatus::STATUS_PUBLISHED]);
        $this->orderBy(['possition' => SORT_DESC]);
        return $this;
    }
}
