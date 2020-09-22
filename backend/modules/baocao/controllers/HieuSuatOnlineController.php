<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 24-Apr-19
 * Time: 10:52 PM
 */

namespace backend\modules\baocao\controllers;

use backend\components\MyController;
use backend\modules\baocao\components\BaoCaoOnline;
use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineFanpage;
use common\models\User;
use Yii;
use yii\web\Response;

class HieuSuatOnlineController extends MyController
{
    public function actionIndex()
    {
        $page = Dep365CustomerOnlineFanpage::getListFanpageArray();
        $loc = BaocaoLocation::getBaocaoLocationArray();
        return $this->render('index', [
            'page' => $page,
            'loc' => $loc,
        ]);
    }

    /*
     * Lấy ra data của hiệu suất Online
     */
    public function actionGetData()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $startDateReport = \Yii::$app->request->post('startDateReport');
            $endDateReport = \Yii::$app->request->post('endDateReport');
            $pageonline = \Yii::$app->request->post('pageonline');
            $loc = \Yii::$app->request->post('loc');

            $nv = self::getNhanVienOnline();
            ksort($nv);
            list($data) = $this->getData($startDateReport, $endDateReport, $nv, $pageonline, $loc);
            return ['data' => $data, 'nv' => array_values($nv)];
        }
    }

    protected function getData($from, $to, $nv, $pageonline, $loc)
    {
        $data = BaoCaoOnline::getHieuSuatOnline($from, $to, $nv, $pageonline, $loc);
        return [$data];
    }

    protected static function getNhanVienOnline()
    {
        return User::getNhanVienIsActiveArray();
    }
}
