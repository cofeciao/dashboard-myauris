<?php
namespace backend\modules\report\controllers;

use backend\components\MyController;
use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\customer\models\Dep365CustomerOnlineFanpage;
use backend\modules\report\components\LichMoi;
use backend\modules\setting\models\Dep365CoSo;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class LichMoiController extends MyController
{
    public function actionIndex()
    {
        $listPage = Dep365CustomerOnlineFanpage::getListFanpageArray();
        $listOnline = self::getNhanVienOnline();
        $listLocation = BaocaoLocation::getBaocaoLocationArray();
        $listCoSo = ArrayHelper::map(Dep365CoSo::getCoSo(), 'id', 'name');

        return $this->render('index', [
            'listPage' => $listPage,
            'listOnline' => $listOnline,
            'listLocation' => $listLocation,
            'listCoSo' => $listCoSo
        ]);
    }

    public function actionGetData()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $startDateReport = \Yii::$app->request->post('startDateReport');
            $endDateReport = \Yii::$app->request->post('endDateReport');
            $id_online = \Yii::$app->request->post('id_online');
            $id_location = \Yii::$app->request->post('id_location');
            $id_page = \Yii::$app->request->post('id_page');
            $id_coso = \Yii::$app->request->post('id_coso');
            $data = $this->getData($startDateReport, $endDateReport, $id_online, $id_location, $id_page, $id_coso);
            return [ 'data' => $data];
        }
    }

    protected function getData($from, $to, $id_online, $id_location, $id_page, $id_coso)
    {
        $data = LichMoi::getDataOnline($from, $to, $id_online, $id_location, $id_page, $id_coso);
        return $data;
    }
    protected static function getNhanVienOnline()
    {
        return User::getNhanVienIsActiveArray();
    }
}
