<?php

namespace console\controllers;

use yii\console\Controller;
use backend\modules\helper\models\HelperDonHang;
use backend\modules\clinic\models\PhongKhamDonHang;
use yii\helpers\ArrayHelper;
use Yii;

class DonHangController extends Controller
{
    public function actionIndex()
    {
        // Yii::warning("Nghia: Don Hang work");

        $listModel = PhongKhamDonHang::find()->select('id')
            ->where(['trang_thai_hoan_thanh' => null])
            ->orderBy('id DESC')
            ->limit(200)
            ->all();
        $list = ArrayHelper::map($listModel, 'id', 'id');
        if (count($list) > 0) {
            foreach ($list as $key => $value) {
                HelperDonHang::updatePhongKhamHoaDonHoanThanh($value);
            }
        } else {
            echo "done";
        }
    }
}
