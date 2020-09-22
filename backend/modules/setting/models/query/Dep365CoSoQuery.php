<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/4/14
 * Time: 2:31 PM
 */

namespace backend\modules\setting\models\query;

use backend\modules\setting\models\Dep365CoSo;
use yii\db\ActiveQuery;

class Dep365CoSoQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere(['status' => Dep365CoSo::STATUS_PUBLISHED]);
        $this->orderBy(['id' => SORT_DESC]);
        return $this;
    }
}
