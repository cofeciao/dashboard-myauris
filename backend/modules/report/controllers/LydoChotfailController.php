<?php
namespace backend\modules\report\controllers;

use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\customer\models\Dep365CustomerOnlineFanpage;
use backend\modules\report\components\LyDoChotFail;
use backend\modules\setting\models\Dep365CoSo;
use Yii;
use backend\components\MyController;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class LydoChotfailController extends MyController
{
    public function actionIndex()
    {

        // ly do khong lam
        // dat hen = 1
        // customer_come_time_to = 2
        // direct sale -  getNhanVienTuDirectSale
        $listReasonCancel = Yii::$app->params["ly-do-khong-lam"];
        $listDirectSale = User::getNhanVienTuDirectSaleIsActiveArray();
        $listLocation = BaocaoLocation::getBaocaoLocationArray();
        $listPage = Dep365CustomerOnlineFanpage::getListFanpageArray();
        $listCoSo = ArrayHelper::map(Dep365CoSo::getCoSo(), 'id', 'name');

        return $this->render('index', [
            'listDirectSale' => $listDirectSale,
            'listReasonCancel' => $listReasonCancel,
            'listLocation' => $listLocation,
            'listPage' => $listPage,
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
            $reason_cancel = \Yii::$app->request->post('reason_cancel');
            $id_page = \Yii::$app->request->post('id_page');
            $id_coso = \Yii::$app->request->post('id_coso');
            $data = $this->getData($startDateReport, $endDateReport, $direct_sale, $id_location, $reason_cancel, $id_page, $id_coso);
            return [ 'data' => $data];
        }
    }

    protected function getData($from, $to, $direct_sale, $id_location, $reason_cancel, $id_page, $id_coso)
    {
        $data = LyDoChotFail::getDataOnline($from, $to, $direct_sale, $id_location, $reason_cancel, $id_page, $id_coso);
        return $data;
    }
}
