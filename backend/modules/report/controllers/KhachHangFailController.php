<?php


namespace backend\modules\report\controllers;

use backend\components\MyController;
use backend\modules\report\components\KhachHangFail;
use backend\modules\customer\models\Dep365CustomerOnlineFanpage;
use backend\modules\customer\models\Dep365CustomerOnlineFailStatus;
use common\models\User;
use yii\web\Response;

class KhachHangFailController extends MyController
{
    public function actionIndex()
    {
        $listPage = Dep365CustomerOnlineFanpage::getListFanpageArray();
        $listReasonFail = Dep365CustomerOnlineFailStatus::getListOnlineStatusFailArray();
        return $this->render('index', [
            'listPage' => $listPage,
            'listReasonFail' => $listReasonFail
        ]);
    }

    public function actionGetData()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $startDateReport = \Yii::$app->request->post('startDateReport');
            $endDateReport = \Yii::$app->request->post('endDateReport');
            $page_online = \Yii::$app->request->post('page_online');
            $reason_fail = \Yii::$app->request->post('reason_fail');
            $data = $this->getData($startDateReport, $endDateReport, $page_online, $reason_fail);
            return [ 'data' => $data];
        }
    }

    protected function getData($from, $to, $page_online, $reason_fail)
    {
        $data = KhachHangFail::getDataOnline($from, $to, $page_online, $reason_fail);
        return $data;
    }
}
