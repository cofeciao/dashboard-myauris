<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 7/4/14
 * Time: 2:31 PM
 */

namespace backend\modules\clinic\models\query;

use backend\modules\clinic\models\PhongKhamSanPham;
use yii\db\ActiveQuery;

class PhongKhamSanPhamQuery extends ActiveQuery
{
    public function published()
    {
        $this->andWhere(['phong_kham_san_pham.status' => PhongKhamSanPham::STATUS_PUBLISHED]);
        $this->orderBy(['phong_kham_san_pham.id' => SORT_DESC]);
        return $this;
    }
}
