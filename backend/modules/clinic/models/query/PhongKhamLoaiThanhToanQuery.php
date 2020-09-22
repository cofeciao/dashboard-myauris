<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/4/14
 * Time: 2:31 PM
 */

namespace backend\modules\clinic\models\query;

use backend\modules\clinic\models\PhongKhamLoaiThanhToan;
use yii\db\ActiveQuery;

class PhongKhamLoaiThanhToanQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere(['phong_kham_loai_thanh_toan.status' => PhongKhamLoaiThanhToan::STATUS_PUBLISHED]);
        $this->orderBy(['phong_kham_loai_thanh_toan.id' => SORT_DESC]);
        return $this;
    }
}
