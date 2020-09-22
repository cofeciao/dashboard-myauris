<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 23-Apr-19
 * Time: 4:49 PM
 */

namespace backend\modules\baocao\components;

use backend\models\CustomerModel;
use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\baocao\models\CustomerBaoCao;
use backend\modules\customer\components\CustomerComponents;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\customer\models\Dep365CustomerOnlineTree;
use backend\modules\customer\models\Pancake;
use yii\base\Component;
use yii\web\Response;

class BaoCaoOnline extends Component
{
    public static function getDataOnline($from, $to, $pageonline = null, $loc = null, $nv = null, $listNv = null)
    {
        $from = strtotime($from);
        $to = strtotime($to);

        $result = $ngay_tao = [];

        $sdt = $ngay_sdt = [];

        $sdtCall = $ngay_sdtCall = [];

        $calendarNew = $ngay_calendarNew = [];

        $calendarAbout = $ngay_calendarAbout = [];

        $auris = $ngay_auris = [];


        $tuongtac = $ngay_tuongtac = [];

        $queryNgaytao = CustomerBaoCao::find()->select('ngay_tao')->where(['between', 'ngay_tao', $from, $to])->groupBy(['ngay_tao']);

        /*
        * Tính ngày
        */
        $dataNgaytao = $queryNgaytao->all();
        foreach ($dataNgaytao as $item) {
            $ngay_tao[] = date('d-m-Y', $item->ngay_tao);
        }


        /*
         * Tính tương tác
         */
        $tuongTac = []; //Dùng để tính hiệu suất
        $dataTuongtac = CustomerComponents::getTuongTacKhachHang($from, $to, 2, $pageonline, $nv, array_keys($listNv));

        $dataSdt = CustomerComponents::getPhoneCustomerWithDay($from, $to, 2, $loc, $pageonline, $nv, array_keys($listNv));
        $dataSdtCall = CustomerComponents::getPhoneCallSuccessCustomerWithDay($from, $to, 2, $loc, $pageonline, $nv, array_keys($listNv));

        $lichMoi = []; //Dùng để tính hiệu suất
        $dataSdtCallNew = self::getDatlichMoi(date('d-m-Y', $from), date('d-m-Y', $to), 2, $pageonline, $loc, $nv, array_keys($listNv));

        $lichHen = [];
        $dataCalendarAbout = CustomerComponents::getKhachAllLichHen($from, $to, 3, $loc, $pageonline, $nv, array_keys($listNv));

        $khachDen = []; //Dùng để tính hiệu quả khách đến
        $dataAurisQuery = CustomerComponents::getTotalCustomerGotoAuris($from, $to, 2, $loc, $pageonline, $nv, array_keys($listNv));

        foreach ($ngay_tao as $key => $value) {
            $k = strtotime($value);

            /*
             * Tính tương tác
             */
            if (!array_key_exists($k, $dataTuongtac)) {
                $tuongtac[] = 0;
                $ngay_tuongtac[] = $value;
            } else {
                $tuongtac[] = $dataTuongtac[$k]->NUM;
                $ngay_tuongtac[] = date('d-m-Y', $dataTuongtac[$k]->date_import);
                $tuongTac[$value] = $dataTuongtac[$k]->NUM;
            }

            /*
             * Tính số điện thoại
             */
            if (!array_key_exists($k, $dataSdt)) {
                $sdt[] = 0;
                $ngay_sdt[] = $value;
            } else {
                $sdt[] = $dataSdt[$k]->SDT;
                $ngay_sdt[] = date('d-m-Y', $dataSdt[$k]->ngay_tao);
            }

            /*
            * Tính số điện thoại gọi được
            */
            if (!array_key_exists($k, $dataSdtCall)) {
                $sdtCall[] = 0;
                $ngay_sdtCall[] = $value;
            } else {
                $sdtCall[] = $dataSdtCall[$k]->SDT;
                $ngay_sdtCall[] = date('d-m-Y', $dataSdtCall[$k]->ngay_tao);
            }

            /*
             * Tính lịch mới
             */
            if (!array_key_exists($k, $dataSdtCallNew)) {
                $calendarNew[] = 0;
                $ngay_calendarNew[] = $value;
            } else {
                $calendarNew[] = $dataSdtCallNew[$k]->user;
                $ngay_calendarNew[] = date('d-m-Y', $dataSdtCallNew[$k]->date_change);
                $lichMoi[$value] = $dataSdtCallNew[$k]->user;
            }

            /*
             * Tính lịch hẹn trong khoảng thời gian
             */
            if (!array_key_exists($k, $dataCalendarAbout)) {
                $calendarAbout[] = 0;
                $ngay_calendarAbout[] = $value;
            } else {
                $calendarAbout[] = $dataCalendarAbout[$k]->user;
                $ngay_calendarAbout[] = date('d-m-Y', $dataCalendarAbout[$k]->date_lichhen_new);
                $lichHen[$value] = $dataCalendarAbout[$k]->user;
            }

            /*
             * Tính số khách đến
             */
            ksort($dataAurisQuery);
//            var_dump($dataAurisQuery);die;
            if (!array_key_exists($k, $dataAurisQuery)) {
                $auris[] = 0;
                $ngay_auris[] = $value;
            } else {
                $auris[] = $dataAurisQuery[$k]->SDT;
                $ngay_auris[] = date('d-m-Y', $dataAurisQuery[$k]->customer_come_date);
                $khachDen[$value] = $dataAurisQuery[$k]->SDT;
            }
        }

        /*
         * Tính hiệu quả khách đến và hiệu suất làm việc
         */
        $hieuQua = [];
        $hieuSuat = [];
        foreach ($ngay_tao as $key => $value) {
            /*
             * Tính hiệu quả
             */

            if (isset($khachDen[$value])) {
                if (isset($lichHen[$value]) && $lichHen[$value] != 0) {
                    $hieuQua[] = round(($khachDen[$value] / $lichHen[$value]) * 100, 2);
                } else {
                    $hieuQua[] = 0;
                }
            } else {
                $hieuQua[] = 0;
            }

            /*
             * Tính hiệu suất
             */
            if (isset($lichMoi[$value])) {
                if (isset($tuongTac[$value]) && $tuongTac[$value] != 0) {
                    $hieuSuat[] = round(($lichMoi[$value] / $tuongTac[$value]) * 100, 2);
                } else {
                    $hieuSuat[] = 0;
                }
            } else {
                $hieuSuat[] = 0;
            }
        }

        $result['tuongtac'] = $tuongtac;
        $result['ngay_tuongtac'] = $ngay_tuongtac;

        $result['sdt'] = $sdt;
        $result['ngay_sdt'] = $ngay_sdt;

        $result['call'] = $sdtCall;
        $result['ngay_sdtCall'] = $ngay_sdtCall;

        $result['calendar_new'] = $calendarNew;
        $result['ngay_calendarNew'] = $ngay_calendarNew;

        $result['calendar_about'] = $calendarAbout;
        $result['ngay_calendarAbout'] = $ngay_calendarAbout;

        $result['auris'] = $auris;
        $result['ngay_auris'] = $ngay_auris;

        $result['ngay'] = $ngay_tao;
        $result['hieu_qua'] = $hieuQua;
        $result['hieu_suat'] = $hieuSuat;

        return $result;
    }

    public static function getHieuSuatOnline($from, $to, $nv, $pageonline = null, $loc = null)
    {
        $from = strtotime($from);
        $to = strtotime($to);

        $result = $sdt = $tuongtac = $sdtVtuongtac = $call = $callVsdt = $calendarnew = $calendarnewVcall = $customer = $lichHen = $customerVcalendarnew = $calendarnewVtuongtac = [];

        /*
         * Tính tương tác
         */
        $dataTuongtac = CustomerComponents::getTuongTacKhachHang($from, $to, 3, $pageonline, null, array_keys($nv));
//        $tuongTacTotal = 0; //Tính tỷ lệ trung bình
        foreach ($dataTuongtac as $item) {
//            $tuongTacTotal += $item->NUM;
            $tuongtac[$item->user_id] = $item->NUM;
        }

        $dataSdt = CustomerComponents::getPhoneCustomerWithDay($from, $to, 3, $loc, $pageonline, null, array_keys($nv));
//        $totalSdt = 0; //Tính tỷ lệ trung bình
        foreach ($dataSdt as $item) {
//            $totalSdt += $item->SDT;
            $sdt[$item->permission_user] = $item->SDT;
        }

        $dataSdtCall = CustomerComponents::getPhoneCallSuccessCustomerWithDay($from, $to, 3, $loc, $pageonline, null, array_keys($nv));
//        $totalSdtCall = 0; //Tính tỷ lệ trung bình
        foreach ($dataSdtCall as $item) {
//            $totalSdtCall += $item->SDT;
            $call[$item->permission_user] = $item->SDT;
        }

//        $dataSdtCallCalendarnew = CustomerComponents::getCalendarNewOfCustomer($from, $to, 3, $loc, $pageonline, null, array_keys($nv));
        $dataLichMoi = CustomerComponents::getKhachAllDatHen($from, $to, 3, $loc, $pageonline, null, array_keys($nv));
//        $totalSdtCallCalendarnew = 0; //Tính tỷ lệ trung bình
        foreach ($dataLichMoi as $item) {
//            $totalSdtCallCalendarnew += $item->SDT;
            $calendarnew[$item->user_id] = $item->user;
        }

        $dataLichHen = CustomerComponents::getKhachAllLichHen($from, $to, 2, $loc, $pageonline, null, array_keys($nv));
//        var_dump($dataLichHen);die;
        foreach ($dataLichHen as $item) {
            $lichHen[$item->user_id] = $item->user;
        }

        $dataSdtCallCustomer = CustomerComponents::getTotalCustomerGotoAuris($from, $to, 3, $loc, $pageonline, null, array_keys($nv));
//        $totalCustomerToAuris = 0; //Tính tỷ lệ trung bình
        foreach ($dataSdtCallCustomer as $item) {
//            $totalCustomerToAuris += $item->SDT;
            $customer[$item->permission_user] = $item->SDT;
        }

        foreach ($nv as $key => $item) {
            /*
             * Tính tỷ lệ số điện thoại với tương tác
             */
            if (isset($sdt[$key])) {
                if (isset($tuongtac[$key]) && $tuongtac[$key] != 0) {
                    $sdtVtuongtac[] = round(($sdt[$key] / $tuongtac[$key]) * 100, 2);
                } else {
                    $sdtVtuongtac[] = 0;
                }
            } else {
                $sdtVtuongtac[] = 0;
            }

            /*
             * Tính tỷ lệ gọi được với số điện thoại
             */
            if (isset($call[$key])) {
                if (isset($sdt[$key]) && $sdt[$key] != 0) {
                    $callVsdt[] = round(($call[$key] / $sdt[$key]) * 100, 2);
                } else {
                    $callVsdt[] = 0;
                }
            } else {
                $callVsdt[] = 0;
            }

            /*
             * Tính tỷ lệ lịch với gọi được
             */
            if (isset($calendarnew[$key])) {
                if (isset($call[$key]) && $call[$key] != 0) {
                    $calendarnewVcall[] = round(($calendarnew[$key] / $call[$key]) * 100, 2);
                } else {
                    $calendarnewVcall[] = 0;
                }
            } else {
                $calendarnewVcall[] = 0;
            }

            /*
             * Tính tỷ lệ khách đến với lịch hẹn
             */

            if (isset($customer[$key])) {
                if (isset($lichHen[$key]) && $lichHen[$key] != 0) {
                    $customerVcalendarnew[] = round(($customer[$key] / $lichHen[$key]) * 100, 2);
                } else {
                    $customerVcalendarnew[] = 0;
                }
            } else {
                $customerVcalendarnew[] = 0;
            }

            /*
             * Tính tỷ đặt hẹn với tương tác
             */
            if (isset($calendarnew[$key])) {
                if (isset($tuongtac[$key]) && $tuongtac[$key] != 0) {
                    $calendarnewVtuongtac[] = round(($calendarnew[$key] / $tuongtac[$key]) * 100, 2);
                } else {
                    $calendarnewVtuongtac[] = 0;
                }
            } else {
                $calendarnewVtuongtac[] = 0;
            }
        }

        $result['sdt_tuongtac'] = $sdtVtuongtac;
        $result['call_sdt'] = $callVsdt;
        $result['calendarnew_call'] = $calendarnewVcall;
        $result['customer_calendarnew'] = $customerVcalendarnew;
        $result['calendarnew_tuongtac'] = $calendarnewVtuongtac;

        //Tính tỷ lệ số điện thoại với tương tác tổng (Tính theo tổng số)
//        if (isset($tuongTacTotal) && $tuongTacTotal != 0) {
//            $trungbinhTuongtacVSdt = round(($totalSdt / $tuongTacTotal) * 100, 2);
//        } else
//            $trungbinhTuongtacVSdt = 0;
//        $result['trungbinhTuongtacVSdt'] = $trungbinhTuongtacVSdt;
        //Tính tỷ lệ số điện thoại với tương tác tổng (Tính theo list nhân viên)
        $totalSdt = self::getTongSDT(date('d-m-Y', $from), date('d-m-Y', $to), 4, $pageonline, $loc, null, array_keys($nv));
        $tuongTacTotal = self::getTongTuongTac(date('d-m-Y', $from), date('d-m-Y', $to), 4, $pageonline, null, array_keys($nv));
        if (isset($tuongTacTotal) && $tuongTacTotal != 0) {
            $trungbinhTuongtacVSdt = round(($totalSdt / $tuongTacTotal) * 100, 2);
        } else {
            $trungbinhTuongtacVSdt = 0;
        }
        $result['trungbinhTuongtacVSdt'] = $trungbinhTuongtacVSdt;

//        //Tính tỷ lệ  gọi được với số điện thoại tổng (Tính theo tổng số)
//        if (isset($totalSdt) && $totalSdt != 0) {
//            $trungbinhTyLeGoiVSdt = round(($totalSdtCall / $totalSdt) * 100, 2);
//        } else
//            $trungbinhTyLeGoiVSdt = 0;
//        $result['trungbinhTyLeGoiVSdt'] = $trungbinhTyLeGoiVSdt;
        $totalSdtCall = self::getTongSDTCall(date('d-m-Y', $from), date('d-m-Y', $to), 4, $pageonline, $loc, null, array_keys($nv));
        if (isset($totalSdt) && $totalSdt != 0) {
            $trungbinhTyLeGoiVSdt = round(($totalSdtCall / $totalSdt) * 100, 2);
        } else {
            $trungbinhTyLeGoiVSdt = 0;
        }
        $result['trungbinhTyLeGoiVSdt'] = $trungbinhTyLeGoiVSdt;

//        //Tính tỷ lệ lịch mới với sdt gọi được (Tính theo tổng số)
//        if (isset($totalSdtCall) && $totalSdtCall != 0) {
//            $trungbinhLichMoiVGoiDuoc = round(($totalSdtCallCalendarnew / $totalSdtCall) * 100, 2);
//        } else
//            $trungbinhLichMoiVGoiDuoc = 0;
//        $result['trungbinhLichMoiVGoiDuoc'] = $trungbinhLichMoiVGoiDuoc;
        $totalSdtCallCalendarnew = self::getDatlichMoi(date('d-m-Y', $from), date('d-m-Y', $to), 4, $pageonline, $loc, null, array_keys($nv));
        if (isset($totalSdtCall) && $totalSdtCall != 0) {
            $trungbinhLichMoiVGoiDuoc = round(($totalSdtCallCalendarnew / $totalSdtCall) * 100, 2);
        } else {
            $trungbinhLichMoiVGoiDuoc = 0;
        }
        $result['trungbinhLichMoiVGoiDuoc'] = $trungbinhLichMoiVGoiDuoc;
//
//        //Tính tỷ lệ khách đến với lịch hẹn (Tính theo tổng số)
//        if (isset($totalSdtCallCalendarnew) && $totalSdtCallCalendarnew != 0) {
//            $trungbinhKhachDenVLichHen = round(($totalCustomerToAuris / $totalSdtCallCalendarnew) * 100, 2);
//        } else
//            $trungbinhKhachDenVLichHen = 0;
//        $result['trungbinhKhachDenVLichHen'] = $trungbinhKhachDenVLichHen;
        $totalCustomerToAuris = self::getKhachDen(date('d-m-Y', $from), date('d-m-Y', $to), 4, $pageonline, $loc, null, array_keys($nv));
        $totalLichHen = self::getLichHen(date('d-m-Y', $from), date('d-m-Y', $to), 4, $pageonline, $loc, null, array_keys($nv));
        if (isset($totalLichHen) && $totalLichHen != 0) {
            $trungbinhKhachDenVLichHen = round(($totalCustomerToAuris / $totalLichHen) * 100, 2);
        } else {
            $trungbinhKhachDenVLichHen = 0;
        }
        $result['trungbinhKhachDenVLichHen'] = $trungbinhKhachDenVLichHen;
//
//        //Tính tỷ lệ đặt hẹn với tương tác (Tính theo tổng số)
//        if (isset($tuongTacTotal) && $tuongTacTotal != 0) {
//            $trungbinhLichMoiVTuongtac = round(($totalSdtCallCalendarnew / $tuongTacTotal) * 100, 2);
//        } else
//            $trungbinhLichMoiVTuongtac = 0;
//        $result['trungbinhLichMoiVTuongtac'] = $trungbinhLichMoiVTuongtac;
        if (isset($tuongTacTotal) && $tuongTacTotal != 0) {
            $trungbinhLichMoiVTuongtac = round(($totalSdtCallCalendarnew / $tuongTacTotal) * 100, 2);
        } else {
            $trungbinhLichMoiVTuongtac = 0;
        }
        $result['trungbinhLichMoiVTuongtac'] = $trungbinhLichMoiVTuongtac;

        return $result;
    }

    /*
     * Tổng tương tác
     */
    public static function getTongTuongTac($from, $to, $typeTuongtac, $pageonline = null, $nv = null, $listUser = null)
    {
        $from = strtotime($from);
        $to = strtotime($to);

        return CustomerComponents::getTuongTacKhachHang($from, $to, $typeTuongtac, $pageonline, $nv, $listUser);
    }

    /*
     * Tổng số điện thoại
     */
    public static function getTongSDT($from, $to, $type, $pageonline = null, $loc = null, $nv = null, $listUser = null)
    {
        $from = strtotime($from);
        $to = strtotime($to);
        return CustomerComponents::getPhoneCustomerWithDay($from, $to, $type, $loc, $pageonline, $nv, $listUser);
    }

    /*
     * Tổng số điện thoại gọi được
     */
    public static function getTongSDTCall($from, $to, $type, $pageonline = null, $loc = null, $nv = null, $listUser = null)
    {
        $from = strtotime($from);
        $to = strtotime($to);

        return CustomerComponents::getPhoneCallSuccessCustomerWithDay($from, $to, $type, $loc, $pageonline, $nv, $listUser);
    }

    /*
     * Tổng lich mới
     */
    public static function getDatlichMoi($from, $to, $type, $pageonline = null, $loc = null, $nv = null, $listUser = null)
    {
        $from = strtotime($from);
        $to = strtotime($to);
        /*
         * Khách hàng đặt hẹn được tính trong tất cả các lần đặt hẹn, dùng thuộc tính khách hàng cũ.
         * $khachmoi = CustomerComponents::getCalendarNewOfCustomer($from, $to, 1, $loc, $pageonline, $nv);
         */
        return CustomerComponents::getKhachAllDatHen($from, $to, $type, $loc, $pageonline, $nv, $listUser);
    }

    /*
     * Tổng lịch hẹn
     */
    public static function getLichHen($from, $to, $typeCustomerAll, $pageonline, $loc, $nv, $listUser = null)
    {
        $from = strtotime($from);
        $to = strtotime($to);
        return CustomerComponents::getKhachAllLichHen($from, $to, $typeCustomerAll, $loc, $pageonline, $nv, $listUser);
    }

    /*
     * Tổng khách đã đến
     */
    public static function getKhachDen($from, $to, $type, $pageonline = null, $loc = null, $nv = null, $listUser = null)
    {
        $from = strtotime($from);
        $to = strtotime($to);

        return CustomerComponents::getTotalCustomerGotoAuris($from, $to, $type, $loc, $pageonline, $nv, $listUser);
    }

    protected static function getLoc($loc)
    {
        return BaocaoLocation::find()->where(['id' => $loc])->one();
    }
}
