<?php


namespace backend\modules\report\controllers;

use backend\components\MyController;
use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\modules\customer\models\Dep365CustomerOnlineFanpage;
use backend\modules\report\components\KhachChot;
use backend\modules\setting\models\Dep365CoSo;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class KhachChotController extends MyController
{
    public function actionIndex()
    {
        $listPage = Dep365CustomerOnlineFanpage::getListFanpageArray();
        $listDirectSale = User::getNhanVienTuDirectSaleIsActiveArray();
        $listLocation = BaocaoLocation::getBaocaoLocationArray();
        $listCustomerCome = Dep365CustomerOnlineCome::getCustomerOnlineComeArrayAccept();
        $listCoSo = ArrayHelper::map(Dep365CoSo::getCoSo(), 'id', 'name');

        return $this->render('index', [
            'listPage' => $listPage,
            'listDirectSale' => $listDirectSale,
            'listLocation' => $listLocation,
            'listCustomerCome' => $listCustomerCome,
            'listCoSo' => $listCoSo,
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
            $cusomer_come = \Yii::$app->request->post('cusomer_come');
            $id_coso = \Yii::$app->request->post('id_coso');

            $data = $this->getData($startDateReport, $endDateReport, $direct_sale, $id_location, $id_page, $cusomer_come, $id_coso);
            return [ 'data' => $data];
        }
    }

    protected function getData($from, $to, $direct_sale, $id_location, $id_page, $cusomer_come, $id_coso)
    {
        $data = KhachChot::getDataOnline($from, $to, $direct_sale, $id_location, $id_page, $cusomer_come, $id_coso);
        return $data;
    }
}
