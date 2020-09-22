<?php

namespace backend\modules\helper\controllers;

use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\helper\models\HelperDonHang;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class DonHangController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionRun()
    {
        if (Yii::$app->request->isAjax) {
            $id_don_hang = Yii::$app->request->post('id_don_hang');
            $tien_no = HelperDonHang::updatePhongKhamHoaDonHoanThanh($id_don_hang);
            return json_encode([
                'msg' => $tien_no,
            ]);
        }
    }

    public function actionUpdate()
    {
        $listModel = PhongKhamDonHang::find()->select('id')
            ->where(['trang_thai_hoan_thanh' => null])
            ->orderBy('id DESC')
            ->limit(1000)
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
