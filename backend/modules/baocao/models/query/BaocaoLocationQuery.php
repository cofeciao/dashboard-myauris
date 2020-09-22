<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tran
 * Date: 08-04-2019
 * Time: 03:54 PM
 */

namespace backend\modules\baocao\models\query;

use yii\db\ActiveQuery;
use backend\modules\baocao\models\BaocaoLocation;

class BaocaoLocationQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere(['baocao_location.status' => BaocaoLocation::STATUS_PUBLISHED]);
        return $this;
    }
}
