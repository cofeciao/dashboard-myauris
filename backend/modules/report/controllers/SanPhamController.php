<?php


namespace backend\modules\report\controllers;

use backend\components\MyController;
use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\customer\models\Dep365CustomerOnlineDichVu;
use backend\modules\customer\models\Dep365CustomerOnlineFanpage;
use backend\modules\report\components\SanPham;
use common\models\User;
use yii\web\Response;

class SanPhamController extends MyController
{
    public function actionIndex()
    {
        $listPage = Dep365CustomerOnlineFanpage::getListFanpageArray();
        $listDirectSale = User::getNhanVienTuDirectSaleIsActiveArray();
        $listLocation = BaocaoLocation::getBaocaoLocationArray();
        $listDichVu = Dep365CustomerOnlineDichVu::getSanPhamDichVuArray();
        return $this->render('index', [
            'listDirectSale' => $listDirectSale,
            'listLocation' => $listLocation,
            'listDichVu' => $listDichVu,
            'listPage' => $listPage
        ]);
    }

    public function actionGetData()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $startDateReport = \Yii::$app->request->post('startDateReport');
            $endDateReport = \Yii::$app->request->post('endDateReport');
            $direct_sale = \Yii::$app->request->post('direct_sale');
            $id_location = \Yii::$app->request->post('id_location');
            $id_page = \Yii::$app->request->post('id_page');
            $id_dichvu = \Yii::$app->request->post('id_dichvu');

            $data = $this->getData($startDateReport, $endDateReport, $direct_sale, $id_location, $id_page, $id_dichvu);
            return [ 'data' => $data];
        }
    }

    protected function getData($from, $to, $direct_sale, $id_location, $id_page, $id_dichvu)
    {
        $data = SanPham::getDataOnline($from, $to, $direct_sale, $id_location, $id_page, $id_dichvu);
        return $data;
    }
}
