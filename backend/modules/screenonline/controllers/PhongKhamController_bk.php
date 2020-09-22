<?php


namespace backend\modules\screenonline\controllers;

use backend\components\MyController;
use backend\modules\customer\components\CustomerComponents;
use backend\modules\customer\models\Dep365CustomerOnlineDichVu;
use backend\modules\setting\models\Dep365CoSo;
use common\models\User;
use yii\web\Response;

class PhongKhamController extends MyController
{
    public function actionIndex()
    {
        list($cot1benphai,
            $lichhenTotalNgay,
            $khachdenTotalNgay,
            $khachChotTotalNgay,
            $doanhThuTotalNgay,
            $doanhThuTheoThang,
            $doanhThuTotalThangTotal,
            $doanhThuTheoDichVu,
            $dataCot1Trai,
            $lichHenTotalThang,
            $dataCot1TraiDong2,
            $lichhenConLaiTrongThang,
            $lichHenDutruTrongThang,
            $khachdenTheoThang,
            $khachDenTotal,
            $phanTramKhachDenSoLichHen,
            $duTruKhachDenTrongThang,
            $duTruKhachDenTrongThangPhanTram,
            $khacVangLaiResult,
            $khacVangLaiResultTotal,
            $duTruKhachVangLai,
            $khachChotTrongThangResult,
            $khachChotTrongThangResultTotal,
            $duTruKhachChotTrongThang,
            $duTruKhachChotTrongThangPhanTram,
            $khachTuVanDirectSaleNgayResultFinal,
            $khachTuVanDirectSaleThangResultFinal) = $this->getData();

        return $this->render('index', [
            'cot1benphai' => $cot1benphai,
            'lichhenTotalNgay' => $lichhenTotalNgay,
            'khachdenTotalNgay' => $khachdenTotalNgay,
            'khachChotTotalNgay' => $khachChotTotalNgay,
            'doanhThuTotalNgay' => $doanhThuTotalNgay,
            'doanhThuTheoThang' => $doanhThuTheoThang,
            'doanhThuTotalThangTotal' => $doanhThuTotalThangTotal,
            'doanhThuTheoDichVu' => $doanhThuTheoDichVu,
            'dataCot1Trai' => $dataCot1Trai,
            'lichHenTotalThang' => $lichHenTotalThang,
            'dataCot1TraiDong2' => $dataCot1TraiDong2,
            'lichhenConLaiTrongThang' => $lichhenConLaiTrongThang,
            'lichHenDutruTrongThang' => $lichHenDutruTrongThang,
            'khachdenTheoThang' => $khachdenTheoThang,
            'khachDenTotal' => $khachDenTotal,
            'phanTramKhachDenSoLichHen' => $phanTramKhachDenSoLichHen,
            'duTruKhachDenTrongThang' => $duTruKhachDenTrongThang,
            'duTruKhachDenTrongThangPhanTram' => $duTruKhachDenTrongThangPhanTram,
            'khacVangLaiResult' => $khacVangLaiResult,
            'khacVangLaiResultTotal' => $khacVangLaiResultTotal,
            'duTruKhachVangLai' => $duTruKhachVangLai,
            'khachChotTrongThangResult' => $khachChotTrongThangResult,
            'khachChotTrongThangResultTotal' => $khachChotTrongThangResultTotal,
            'duTruKhachChotTrongThang' => $duTruKhachChotTrongThang,
            'duTruKhachChotTrongThangPhanTram' => $duTruKhachChotTrongThangPhanTram,
            'khachTuVanDirectSaleNgayResultFinal' => $khachTuVanDirectSaleNgayResultFinal,
            'khachTuVanDirectSaleThangResultFinal' => $khachTuVanDirectSaleThangResultFinal
        ]);
    }

    protected function getData()
    {
        //Ngày hiện tại
        $day = strtotime(date('d-m-Y'));
        $from = strtotime(date('d-m-Y') . '+1 day');
        $startMonth = strtotime(date('01-m-Y'));
        //Ngày cuối tháng
        $callDayinMonth = date('t', strtotime(date('d-m-Y')));//cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
        $lastTo = strtotime(date($callDayinMonth . '-m-Y'));

        //Lấy ra lịch hẹn hôm nay của 3 cơ sở
        $lichHen = CustomerComponents::getKhachAllLichHen($day, $day, 5, null, null, null, null, null, true);

        //Lấy ra khách đến My Auris thực tế
        $customerGotoAuris = CustomerComponents::getTotalCustomerGotoAuris($day, $day, 5, null, null, null, null, null, true);
        $customerGotoAurisTheoThang = CustomerComponents::getTotalCustomerGotoAuris($startMonth, $day, 5, null, null, null, null, null, 1);

        //Lấy ra khách có làm dịch vụ bên My Auris trong ngày
        $customerDone = CustomerComponents::getCustomerDone($day, $day);

        //Doanh thu khách hàng theo thời gian
        $incomeCustomer = CustomerComponents::getInCome($day, $day);

        //Tính doanh thu tháng hiện tại theo cơ sở
        $doanhThuTheoThangData = CustomerComponents::getInCome($startMonth, $day);

        //Tính doanh thu tháng hiện tại theo dịch vụ
        $doanhThuTheoDichVuData = CustomerComponents::getInComeTheoDichVu($startMonth, $day);

        //Tính lich hẹn theo tháng hiện tại
        $lichHenTheoThang = CustomerComponents::getTotalLichHenWithDay($startMonth, $lastTo, 2);

        //Tinh lich hen tu ngay mai toi cuoi thang theo cơ sở
        $totalCustomerTodayToLastMonthWithCoSo = CustomerComponents::getTotalLichHenWithDay($from, $lastTo, 2);

        //Tinh lich hen tu dau thang tới ngày hiện tại
        $totalCustomerToDayInMonth = CustomerComponents::getTotalLichHenWithDay($startMonth, $day, 1);

        //Tinh lich hen tren 1 ngay tu dau thang toi thoi diem hien tai
        $today = date('d');
        $tyleLichHenTrongThangToiHienTai = $totalCustomerToDayInMonth / $today;

        //Tinh lich hen tu ngay mai toi cuoi thang
        $totalCustomerTodayToLastMonth = CustomerComponents::getTotalLichHenWithDay($from, $lastTo, 1);

        //Uoc tinh lich hen con lai
        $dayConLai = $callDayinMonth - $today;
        $lichHenDutruTrongThang = (int)($tyleLichHenTrongThangToiHienTai * $dayConLai + $totalCustomerTodayToLastMonth);

        //Tính khách vãng lai theo tháng
        $khachVangLai = CustomerComponents::getTotalCustomerGotoAuris($startMonth, $day, 5, null, null, null, null, null, 2);

        //Lấy ra khách có làm dịch vụ bên My Auris trong thang
        $customerDoneTrongThang = CustomerComponents::getCustomerDone($startMonth, $day, $type = 1);

        $cosoData = Dep365CoSo::find()->indexBy('id')->published()->all();
        $dichVuData = Dep365CustomerOnlineDichVu::find()->indexBy('id')->published()->all();
        ksort($cosoData);
        ksort($dichVuData);
        $result = $dataLichHenWithCoSoToLastMonth = $doanhThuTheoThang = [];
        $dataCot1Trai = [];
        $lichhenTotalNgay = $lichHenTotalThang = $doanhThuTotalThangTotal = $doanhThuTotalNgay = $khachChotTotalNgay = $khachdenTotalNgay = $khachDenTotal = $khacVangLaiResultTotal = $khachChotTrongThangResultTotal = $lichhenConLaiTrongThang = 0;
        foreach ($cosoData as $key => $value) {
            $doanhthu = $khachchot = $khachden = $doanh3T = $temp = $khachdenTheoThangTemp = $khachVangLaiTemp = $khachChotTrongThangTemp = $khachTuVanDirectSaleThangTemp = 0;
            if (!array_key_exists($key, $lichHen)) {
                $result[$key]['lichhen'] = 0;
            } else {
                $result[$key]['lichhen'] = $lichHen[$key]->user;
                $lichhenTotalNgay = $lichhenTotalNgay + $lichHen[$key]->user;
            }

            if (!array_key_exists($key, $customerGotoAuris)) {
                $result[$key]['khachden'] = 0;
            } else {
                $khachden = (int)$customerGotoAuris[$key]->SDT;
                $result[$key]['khachden'] = $khachden;
            }
            $khachdenTotalNgay = $khachdenTotalNgay + $khachden;

            if (!array_key_exists($key, $customerDone)) {
                $result[$key]['khachchot'] = 0;
            } else {
                $khachchot = (int)$customerDone[$key]->SDT;
                $result[$key]['khachchot'] = $khachchot;
            }
            $khachChotTotalNgay = $khachChotTotalNgay + $khachchot;

            if (!array_key_exists($key, $incomeCustomer)) {
                $result[$key]['doanhthu'] = 0;
            } else {
                $doanhthu = (int)$incomeCustomer[$key]->tien;
                $result[$key]['doanhthu'] = $doanhthu;
            }
            $doanhThuTotalNgay = $doanhThuTotalNgay + $doanhthu;

            if (!array_key_exists($key, $doanhThuTheoThangData)) {
                $doanhThuTheoThang[$key]['doanhthutheothang'] = 0;
            } else {
                $doanh3T = (int)$doanhThuTheoThangData[$key]->tien;
                $doanhThuTheoThang[$key]['doanhthutheothang'] = $doanh3T;
            }
            //Tính doanh thu tổng theo tháng hiện tại
            $doanhThuTotalThangTotal = $doanhThuTotalThangTotal + $doanh3T;

            //Tinh lịch hẹn theo tháng
            if (!array_key_exists($key, $lichHenTheoThang)) {
                $dataCot1Trai[$key]['name'] = $value->name;
                $dataCot1Trai[$key]['lichHenTheoThang'] = 0;
            } else {
                $temp = (int)$lichHenTheoThang[$key]->SDT;
                $dataCot1Trai[$key]['name'] = $value->name;
                $dataCot1Trai[$key]['lichHenTheoThang'] = $temp;
            }
            //Tính lịch hẹn tổng tháng hiện tại
            $lichHenTotalThang = $lichHenTotalThang + $temp;

            //Chia lich hen con lai theo cơ sở kể cả cơ sở = null
            if (!array_key_exists($key, $totalCustomerTodayToLastMonthWithCoSo)) {
                $dataCot1TraiDong2[$key]['lichhenConLaiTrongThang'] = 0;
                $khachConLaiTemp = 0;
            } else {
                $khachConLaiTemp = $totalCustomerTodayToLastMonthWithCoSo[$key]->SDT;
                $dataCot1TraiDong2[$key]['lichhenConLaiTrongThang'] = $khachConLaiTemp;
            }
            $lichhenConLaiTrongThang = $lichhenConLaiTrongThang + $khachConLaiTemp;

            //Tinh khach den myauris theo tháng
            if (!array_key_exists($key, $customerGotoAurisTheoThang)) {
                $khachdenTheoThang[$key]['khachdenTheoThang'] = 0;
                $khachdenTheoThang[$key]['phantram'] = 0;
            } else {
                $khachdenTheoThangTemp = (int)$customerGotoAurisTheoThang[$key]->SDT;
                $khachdenTheoThang[$key]['khachdenTheoThang'] = $khachdenTheoThangTemp;

                if (isset($dataCot1Trai[$key]['lichHenTheoThang']) && $dataCot1Trai[$key]['lichHenTheoThang'] != 0) {
                    $khachdenTheoThang[$key]['phantram'] = round(($khachdenTheoThangTemp / $dataCot1Trai[$key]['lichHenTheoThang']) * 100, 2);
                } else {
                    $khachdenTheoThang[$key]['phantram'] = 0;
                }
                //Tổng khách đến
                $khachDenTotal = $khachDenTotal + $khachdenTheoThangTemp;
            }

            //Tinh khách vãng lai từng cơ sở
            if (!array_key_exists($key, $khachVangLai)) {
                $khacVangLaiResult[$key]['khachVangLaiTheoCoSo'] = 0;
            } else {
                $khachVangLaiTemp = (int)$khachVangLai[$key]->SDT;
                $khacVangLaiResult[$key]['khachVangLaiTheoCoSo'] = $khachVangLaiTemp;
                //Tổng khách vãng lai
                $khacVangLaiResultTotal = $khacVangLaiResultTotal + $khachVangLaiTemp;
            }

            //Tính khách chốt trong tháng
            if (!array_key_exists($key, $customerDoneTrongThang)) {
                $khachChotTrongThangResult[$key]['khachChotTrongThang'] = 0;
                $khachChotTrongThangResult[$key]['phantram'] = 0;
            } else {
                $khachChotTrongThangTemp = (int)$customerDoneTrongThang[$key]->SDT;
                $khachChotTrongThangResult[$key]['khachChotTrongThang'] = $khachChotTrongThangTemp;

                if (isset($khachdenTheoThang[$key]['khachdenTheoThang']) && $khachdenTheoThang[$key]['khachdenTheoThang'] != 0) {
                    $khachChotTrongThangResult[$key]['phantram'] = round(($khachChotTrongThangTemp / ($khachdenTheoThang[$key]['khachdenTheoThang'] + $khacVangLaiResult[$key]['khachVangLaiTheoCoSo'])) * 100, 2);
                } else {
                    $khachChotTrongThangResult[$key]['phantram'] = 0;
                }
                //Tổng khách chot
                $khachChotTrongThangResultTotal = $khachChotTrongThangResultTotal + $khachChotTrongThangTemp;
            }
        }

        //Tính phần trăm tổng của khách đến so với lịch hẹn
        if ($lichHenTotalThang != 0) {
            $phanTramKhachDenSoLichHen = $lichHenTotalThang == 0 ? 0 : round(($khachDenTotal / $lichHenTotalThang) * 100, 2);
        } else {
            $phanTramKhachDenSoLichHen = 0;
        }

        //Tính dự trù khách đến trong tháng
        $trungBinhKhachDenTrongNgay = round($khachDenTotal / $today, 2);
        $duTruKhachDenTrongThang = (int)($khachDenTotal + $trungBinhKhachDenTrongNgay * $dayConLai);
        //Tính phần trăm
        $duTruKhachDenTrongThangPhanTram = ($lichHenDutruTrongThang + $lichHenTotalThang) == 0 ? 0 : round(($duTruKhachDenTrongThang / ($lichHenDutruTrongThang + $lichHenTotalThang)) * 100, 2);

        //Tinh dự trù khách vãng lai trong tháng
        $trungBinhKhachVangLaiTrongNgay = round($khacVangLaiResultTotal / $today, 2);
        $duTruKhachVangLai = (int)($khacVangLaiResultTotal + $trungBinhKhachVangLaiTrongNgay * $dayConLai);

        //Tinh dự trù tổng khách chốt trong tháng
        $tyLeKhachChotTrongNgay = round($khachChotTrongThangResultTotal / $today, 2);
        $duTruKhachChotTrongThang = (int)($khachChotTrongThangResultTotal + $tyLeKhachChotTrongNgay * $dayConLai);

        //Tính phần trăm dự trù khách chốt theo tháng
        $tongKhachDenDuTruVangLaiVaOnline = $duTruKhachDenTrongThang + $duTruKhachVangLai;
        $duTruKhachChotTrongThangPhanTram = $tongKhachDenDuTruVangLaiVaOnline == 0 ? 0 : round(($duTruKhachChotTrongThang / $tongKhachDenDuTruVangLaiVaOnline) * 100, 2);

        foreach ($dichVuData as $key => $value) {
            if ($key == 7) {
                continue;
            }
            $doanhThuTheoDichVu[$key]['name'] = $value->name;
            if (!array_key_exists($key, $doanhThuTheoDichVuData)) {
                $doanhThuTheoDichVu[$key]['doanhthutheodichvu'] = 0;
            } else {
                $doanhThuTheoDichVuTemp = (int)$doanhThuTheoDichVuData[$key]->tien;
                $doanhThuTheoDichVu[$key]['doanhthutheodichvu'] = $doanhThuTheoDichVuTemp;
            }
        }

        //Tinh khach tu van va khach chot cho direct sale theo ngay
        $listDirectSale = User::getNhanVienTuDirectSaleIsActiveArray();
        ksort($listDirectSale);

        $khachTuVanOfDirectSaleNgay = CustomerComponents::getKhachTuVanTheoNhanVien($day, $day, 2, array_keys($listDirectSale));
        $khachChotOfDirectSaleNgay = CustomerComponents::getKhachChotTheoNhanVien($day, $day, 2, array_keys($listDirectSale));
        $n = 0;
        $khachTuVanDirectSaleNgayResultFinal = [];
        foreach ($listDirectSale as $key => $value) {
            if (!array_key_exists($key, $khachTuVanOfDirectSaleNgay)) {
                $khachTuVanDirectSaleNgayResult[$n]['khachDirectSakeTuVan'] = 0;
            } else {
                $khachTuVanDirectSaleNgayTemp = (int)$khachTuVanOfDirectSaleNgay[$key]->SDT;
                $khachTuVanDirectSaleNgayResult[$n]['khachDirectSakeTuVan'] = $khachTuVanDirectSaleNgayTemp;
            }

            if (!array_key_exists($key, $khachChotOfDirectSaleNgay)) {
                $khachChotDirectSaleNgayResult[$n]['khachDirectSakeChot'] = 0;
            } else {
                $khachChotDirectSaleNgayTemp = (int)$khachChotOfDirectSaleNgay[$key]->SDT;
                $khachChotDirectSaleNgayResult[$n]['khachDirectSakeChot'] = $khachChotDirectSaleNgayTemp;
            }
            if ($khachTuVanDirectSaleNgayResult[$n]['khachDirectSakeTuVan'] == 0) {
                $phanTramChotVoiTuVanNgay[$n]['phantram'] = 0;
            } else {
                $phanTramChotVoiTuVanNgay[$n]['phantram'] = round(($khachChotDirectSaleNgayResult[$n]['khachDirectSakeChot'] / $khachTuVanDirectSaleNgayResult[$n]['khachDirectSakeTuVan']) * 100, 2);
            }

            $khachTuVanDirectSaleNgayResultFinal[$n]['name'] = $value;
            $khachTuVanDirectSaleNgayResultFinal[$n]['khachDirectSakeChot'] = $khachChotDirectSaleNgayResult[$n]['khachDirectSakeChot'];
            $khachTuVanDirectSaleNgayResultFinal[$n]['khachDirectSakeTuVan'] = $khachTuVanDirectSaleNgayResult[$n]['khachDirectSakeTuVan'];
            $khachTuVanDirectSaleNgayResultFinal[$n]['phantram'] = $phanTramChotVoiTuVanNgay[$n]['phantram'];
            $n++;
        }

        //Tinh khach tu van va khach chot cho direct sale theo thang
        $khachTuVanOfDirectSaleThang = CustomerComponents::getKhachTuVanTheoNhanVien($startMonth, $day, 2, array_keys($listDirectSale));
        $khachChotOfDirectSaleThang = CustomerComponents::getKhachChotTheoNhanVien($startMonth, $day, 2, array_keys($listDirectSale));
        $i = 0;
        $khachTuVanDirectSaleThangResultFinal = [];
        foreach ($listDirectSale as $key => $value) {
            if (!array_key_exists($key, $khachTuVanOfDirectSaleThang)) {
                $khachTuVanDirectSaleThangResult[$i]['khachDirectSakeTuVan'] = 0;
            } else {
                $khachTuVanDirectSaleThangTemp = (int)$khachTuVanOfDirectSaleThang[$key]->SDT;
                $khachTuVanDirectSaleThangResult[$i]['khachDirectSakeTuVan'] = $khachTuVanDirectSaleThangTemp;
            }

            if (!array_key_exists($key, $khachChotOfDirectSaleThang)) {
                $khachChotDirectSaleThangResult[$i]['khachDirectSakeChot'] = 0;
            } else {
                $khachChotDirectSaleThangTemp = (int)$khachChotOfDirectSaleThang[$key]->SDT;
                $khachChotDirectSaleThangResult[$i]['khachDirectSakeChot'] = $khachChotDirectSaleThangTemp;
            }
            if ($khachTuVanDirectSaleThangResult[$i]['khachDirectSakeTuVan'] == 0) {
                $phanTramChotVoiTuVan[$i]['phantram'] = 0;
            } else {
                $phanTramChotVoiTuVan[$i]['phantram'] = round(($khachChotDirectSaleThangResult[$i]['khachDirectSakeChot'] / $khachTuVanDirectSaleThangResult[$i]['khachDirectSakeTuVan']) * 100, 2);
            }
            $khachTuVanDirectSaleThangResultFinal[$i]['name'] = $value;
            $khachTuVanDirectSaleThangResultFinal[$i]['khachDirectSakeChot'] = $khachChotDirectSaleThangResult[$i]['khachDirectSakeChot'];
            $khachTuVanDirectSaleThangResultFinal[$i]['khachDirectSakeTuVan'] = $khachTuVanDirectSaleThangResult[$i]['khachDirectSakeTuVan'];
            $khachTuVanDirectSaleThangResultFinal[$i]['phantram'] = $phanTramChotVoiTuVan[$i]['phantram'];
            $i++;
        }

        $count = count($khachTuVanDirectSaleThangResultFinal);
        for ($i = 0; $i < $count - 1; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $temp = $khachTuVanDirectSaleThangResultFinal[$i];
                if ($khachTuVanDirectSaleThangResultFinal[$i]['phantram'] < $khachTuVanDirectSaleThangResultFinal[$j]['phantram']) {
                    $khachTuVanDirectSaleThangResultFinal[$i] = $khachTuVanDirectSaleThangResultFinal[$j];
                    $khachTuVanDirectSaleThangResultFinal[$j] = $temp;
                }
            }
        }

        $countN = count($khachTuVanDirectSaleNgayResultFinal);
        for ($i = 0; $i < $countN - 1; $i++) {
            for ($j = $i + 1; $j < $countN; $j++) {
                $tempN = $khachTuVanDirectSaleNgayResultFinal[$i];
                if ($khachTuVanDirectSaleNgayResultFinal[$i]['phantram'] < $khachTuVanDirectSaleNgayResultFinal[$j]['phantram']) {
                    $khachTuVanDirectSaleNgayResultFinal[$i] = $khachTuVanDirectSaleNgayResultFinal[$j];
                    $khachTuVanDirectSaleNgayResultFinal[$j] = $tempN;
                }
            }
        }

//        var_dump($khachTuVanDirectSaleThangResultFinal);
//        die;
        return [
            $result,
            $lichhenTotalNgay,
            $khachdenTotalNgay,
            $khachChotTotalNgay,
            $doanhThuTotalNgay,
            $doanhThuTheoThang,
            $doanhThuTotalThangTotal,
            $doanhThuTheoDichVu,
            $dataCot1Trai,
            $lichHenTotalThang,
            $dataCot1TraiDong2,
            $lichhenConLaiTrongThang,
            $lichHenDutruTrongThang,
            $khachdenTheoThang,
            $khachDenTotal,
            $phanTramKhachDenSoLichHen,
            $duTruKhachDenTrongThang,
            $duTruKhachDenTrongThangPhanTram,
            $khacVangLaiResult,
            $khacVangLaiResultTotal,
            $duTruKhachVangLai,
            $khachChotTrongThangResult,
            $khachChotTrongThangResultTotal,
            $duTruKhachChotTrongThang,
            $duTruKhachChotTrongThangPhanTram,
            $khachTuVanDirectSaleNgayResultFinal,
            $khachTuVanDirectSaleThangResultFinal,
        ];
    }
}
