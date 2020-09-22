<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23-Apr-19
 * Time: 4:43 PM
 */

namespace backend\modules\baocao\controllers;

use backend\components\MyController;
use backend\models\CustomerModel;
use backend\modules\baocao\components\BaoCaoOnline;
use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineFanpage;
use common\models\User;
use Yii;
use yii\web\Response;

class BaoCaoOnlineController extends MyController
{
    public function actionIndex()
    {
        $page = Dep365CustomerOnlineFanpage::getListFanpageArray();
        $loc = BaocaoLocation::getBaocaoLocationArray();
        $online = self::getNhanVienOnline();

        return $this->render('index', [
            'page' => $page,
            'loc' => $loc,
            'online' => $online
        ]);
    }

    /*
     * Lấy ra data của phòng Online
     */
    public function actionGetData()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $startDateReport = \Yii::$app->request->post('startDateReport');
            $endDateReport = \Yii::$app->request->post('endDateReport');
            $pageonline = \Yii::$app->request->post('pageonline');
            $loc = \Yii::$app->request->post('loc');
            $nv = \Yii::$app->request->post('nv');
            $listNv = self::getNhanVienOnline();
            ksort($listNv);
            list($data, $tongTuongTac, $sdtTong, $sdtTongCall, $datlichMoi, $lichHen, $khachDen) = $this->getData($startDateReport, $endDateReport, $pageonline, $loc, $nv, $listNv);
            return ['data' => $data, 'tongTuongTac' => $tongTuongTac, 'sdtTong' => $sdtTong, 'sdtTongCall' => $sdtTongCall, 'datlichMoi' => $datlichMoi, 'lichHen'=>$lichHen, 'khachDen' => $khachDen];
        }
    }

    protected function getData($from, $to, $pageonline, $loc, $nv, $listNv)
    {
        $data = BaoCaoOnline::getDataOnline($from, $to, $pageonline, $loc, $nv, $listNv);
        $tongTuongTac = BaoCaoOnline::getTongTuongTac($from, $to, 1, $pageonline, $nv, array_keys($listNv));
        $sdtTong = BaoCaoOnline::getTongSDT($from, $to, 1, $pageonline, $loc, $nv, array_keys($listNv));
        $sdtTongCall = BaoCaoOnline::getTongSDTCall($from, $to, 1, $pageonline, $loc, $nv, array_keys($listNv));
        $datlichMoi = BaoCaoOnline::getDatlichMoi($from, $to, 1, $pageonline, $loc, $nv, array_keys($listNv));
        $khachDen = BaoCaoOnline::getKhachDen($from, $to, 1, $pageonline, $loc, $nv, array_keys($listNv));
        $lichHen = BaoCaoOnline::getLichHen($from, $to, 1, $pageonline, $loc, $nv, array_keys($listNv));
        return [$data, $tongTuongTac, $sdtTong, $sdtTongCall, $datlichMoi, $lichHen, $khachDen];
    }

    protected static function getNhanVienOnline()
    {
        return User::getNhanVienIsActiveArray();
    }
}
