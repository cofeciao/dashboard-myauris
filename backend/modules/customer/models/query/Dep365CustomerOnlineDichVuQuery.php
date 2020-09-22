<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/4/14
 * Time: 2:31 PM
 */

namespace backend\modules\customer\models\query;

use backend\modules\customer\models\Dep365CustomerOnlineDichVu;
use yii\db\ActiveQuery;

class Dep365CustomerOnlineDichVuQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere(['status' => Dep365CustomerOnlineDichVu::STATUS_PUBLISHED]);
        $this->orderBy(['id' => SORT_DESC]);
        return $this;
    }
}
