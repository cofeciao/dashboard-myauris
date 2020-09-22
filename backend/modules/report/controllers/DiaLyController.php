<?php
namespace backend\modules\report\controllers;

use backend\components\MyController;
use backend\modules\customer\models\Dep365CustomerOnlineFanpage;
use backend\modules\report\components\DiaLy;
use yii\web\Response;

class DiaLyController extends MyController
{
    public function actionIndex()
    {
        $listPage = Dep365CustomerOnlineFanpage::getListFanpageArray();
        return $this->render('index', [
            'listPage' => $listPage,
        ]);
    }
    public function actionGetData()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $startDateReport = \Yii::$app->request->post('startDateReport');
            $endDateReport = \Yii::$app->request->post('endDateReport');
            $page_online = \Yii::$app->request->post('page_online');
            $province = \Yii::$app->request->post('province', null);
            if(!is_numeric($province)) $province = null;
            $data = $this->getData($startDateReport, $endDateReport, $page_online, $province);
            return [ 'data' => $data];
        }
    }

    protected function getData($from, $to, $page_online, $province = null)
    {
        $data = DiaLy::getDataOnline($from, $to, $page_online, $province);
        return $data;
    }
}
