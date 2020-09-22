<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 22-Apr-19
 * Time: 3:30 PM
 */

namespace backend\modules\baocao\controllers;

use backend\components\MyController;
use backend\modules\baocao\components\BaoCaoFacebook;
use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\customer\models\Dep365CustomerOnlineDichVu;
use common\models\User;
use yii\web\Response;

class BaoCaoFacebookController extends MyController
{
    public function actionIndex()
    {
        $khuVuc = BaocaoLocation::getBaocaoLocationArray();
        $chayAds = User::getNhanVienChayAdvertisingManager();
        $sanPham = Dep365CustomerOnlineDichVu::getSanPhamDichVuArray();
        $ads = [];
        foreach ($chayAds as $item) {
            $ads[$item->id] = $item->fullname;
        }
        return $this->render('index', [
            'khuVuc' => $khuVuc,
            'ads' => $ads,
            'sanPham' => $sanPham,
        ]);
    }

    /*
     * Lấy ra data chạy facebook ads
     */
    public function actionGetData()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            $startDateReport = \Yii::$app->request->post('startDateReport');
            $endDateReport = \Yii::$app->request->post('endDateReport');
            $ads = (int)\Yii::$app->request->post('ads');
            $loc = (int)\Yii::$app->request->post('loc');
            $sanPham = (int)\Yii::$app->request->post('sanpham');
            list($tongTien, $tuongTacTong, $sdtTong, $calendarTotal, $data) = $this->getData($startDateReport, $endDateReport, $ads, $loc, $sanPham);
            return ['tongMeny' => $tongTien, 'tuongTacTong' => $tuongTacTong, 'sdtTong' => $sdtTong, 'calendarTotal' => $calendarTotal, 'data' => $data];
        }
    }

    protected function getData($from, $to, $ads = null, $loc = null, $sanPham = null)
    {
        $tongTien = BaoCaoFacebook::tongTien($from, $to, $ads, $loc, $sanPham);
        $tuongTacTong = BaoCaoFacebook::getTuongTacTong($from, $to, $ads, $loc, $sanPham);
        $sdtTong = BaoCaoFacebook::getSdtTong($from, $to, $ads, $loc, $sanPham);
        $calendarTotal = BaoCaoFacebook::getCalendarTotal($from, $to, $ads, $loc, $sanPham);
        $data = BaoCaoFacebook::getDataFacebook($from, $to, $ads, $loc, $sanPham);
        return [$tongTien, $tuongTacTong, $sdtTong, $calendarTotal, $data];
    }
}
