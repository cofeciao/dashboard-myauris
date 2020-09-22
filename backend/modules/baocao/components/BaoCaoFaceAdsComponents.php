<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 04-Apr-19
 * Time: 3:03 PM
 */

namespace backend\modules\baocao\components;

use backend\models\CustomerModel;
use backend\modules\customer\components\CustomerComponents;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineTree;

class BaoCaoFaceAdsComponents extends \yii\base\Component
{
    /*
     * Lấy ra tổng số điện thoại theo ngày, khu vực
     */
    public static function getPhoneCustomerInDay($day, $list_province, $page_chay, $listUser, $sanPham)
    {
        $from = $to = strtotime($day);
        $result = CustomerComponents::getPhoneCustomerWithDay($from, $to, 0, $list_province, $page_chay, null, $listUser, $sanPham);
        return $result;
    }

    /*
     * Lấy ra tổng số điện thoại gọi được theo ngày, khu vực
     */
    public static function getPhoneCallCustomerWithDay($day, $list_province, $page_chay, $listUser, $sanPham)
    {
        $from = $to = strtotime($day);
        $result = CustomerComponents::getPhoneCallSuccessCustomerWithDay($from, $to, 0, $list_province, $page_chay, null, $listUser, $sanPham);
        return $result;
    }

    /*
     * Lấy ra tổng lịch mới theo ngày, theo khu vực
     */
    public static function getLichMoiCustomerWithDay($day, $list_province, $page_chay, $listUser, $sanPham)
    {
        $from = $to = strtotime($day);
        $result = CustomerComponents::getKhachAllDatHen($from, $to, 5, $list_province, $page_chay, null, $listUser, $sanPham);
        return $result;
    }


    /*
     * Lấy ra khách đến theo ngày, theo khu vực
     */
    public static function getKhachDenCustomerWithDay($day, $list_province, $page_chay, $listUser, $sanPham)
    {
        $from = $to = strtotime($day);
        $result = CustomerComponents::getTotalCustomerGotoAuris($from, $to, 0, $list_province, $page_chay, null, $listUser, $sanPham);
        return $result;
    }
}
