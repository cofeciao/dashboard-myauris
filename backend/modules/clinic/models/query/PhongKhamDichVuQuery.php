<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/4/14
 * Time: 2:31 PM
 */

namespace backend\modules\clinic\models\query;

use backend\modules\clinic\models\PhongKhamDichVu;
use yii\db\ActiveQuery;

class PhongKhamDichVuQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere(['phong_kham_dich_vu.status' => PhongKhamDichVu::STATUS_PUBLISHED]);
        $this->orderBy(['phong_kham_dich_vu.id' => SORT_DESC]);
        return $this;
    }
}
