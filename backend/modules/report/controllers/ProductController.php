<?php
namespace backend\modules\report\controllers;

use backend\components\MyController;
use backend\modules\clinic\models\PhongKhamDichVu;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\customer\models\Dep365CustomerOnlineFanpage;
use backend\modules\report\components\Product;
use backend\modules\setting\models\Dep365CoSo;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class ProductController extends MyController
{
    public function actionIndex()
    {
        $listPage = Dep365CustomerOnlineFanpage::getListFanpageArray();
        $listDirectSale = User::getNhanVienTuDirectSaleIsActiveArray();
        $listPhongKhamDichVu = ArrayHelper::map(PhongKhamDichVu::getDichVu(), 'id', 'name');
        $listCoSo = ArrayHelper::map(Dep365CoSo::getCoSo(), 'id', 'name');
        $listTrangThaiDV = ArrayHelper::toArray(PhongKhamDonHang::getListTrangThaiDonDichVu());
        return $this->render('index', [
            'listPage' => $listPage,
            'listDirectSale' => $listDirectSale,
            'listPhongKhamDichVu' => $listPhongKhamDichVu,
            'listCoSo' => $listCoSo,
            'listTrangThaiDV' => $listTrangThaiDV
        ]);
    }

    public function actionGetData()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $startDateReport = \Yii::$app->request->post('startDateReport');
            $endDateReport = \Yii::$app->request->post('endDateReport');
            $direct_sale = \Yii::$app->request->post('direct_sale');
            $id_coso = \Yii::$app->request->post('id_coso');
            $id_page = \Yii::$app->request->post('id_page');
            $id_phongkhamdichvu = \Yii::$app->request->post('id_phongkhamdichvu');
            $id_trangthaidichvu = \Yii::$app->request->post('id_trangthaidichvu');

            $data = $this->getData($startDateReport, $endDateReport, $direct_sale, $id_coso, $id_page, $id_phongkhamdichvu, $id_trangthaidichvu);
            return [ 'data' => $data];
        }
    }

    protected function getData($from, $to, $direct_sale, $id_coso, $id_page, $id_phongkhamdichvu, $id_trangthaidichvu)
    {
        $data = Product::getDataOnline($from, $to, $direct_sale, $id_coso, $id_page, $id_phongkhamdichvu, $id_trangthaidichvu);
        return $data;
    }
}
