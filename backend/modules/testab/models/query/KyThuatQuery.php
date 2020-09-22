<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/4/14
 * Time: 2:31 PM
 */

namespace backend\modules\testab\models\query;

use backend\modules\testab\models\AbAddKythuat;
use yii\db\ActiveQuery;

class KyThuatQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere(['ab_add_kythuat.status' => AbAddKythuat::STATUS_PUBLISHED]);
        $this->orderBy(['ab_add_kythuat.id' => SORT_DESC]);
        return $this;
    }
}
