<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/4/14
 * Time: 2:31 PM
 */

namespace backend\modules\toothstatus\models\query;

use backend\modules\toothstatus\models\KyThuatRang;
use yii\db\ActiveQuery;

class KyThuatRangQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere([KyThuatRang::tableName() . '.status' => KyThuatRang::STATUS_ACTIVE]);
        return $this;
    }
}
