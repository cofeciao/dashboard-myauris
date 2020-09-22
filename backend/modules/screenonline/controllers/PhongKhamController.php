<?php


namespace backend\modules\screenonline\controllers;

use backend\components\MyController;
use backend\models\doanhthu\DonHangModel;
use backend\modules\clinic\models\PhongKhamDonHangWOrder;
use backend\modules\clinic\models\PhongKhamKpi;
use backend\modules\clinic\models\PhongKhamSanPham;
use backend\modules\customer\components\CustomerComponents;
use backend\modules\customer\models\Dep365CustomerOnlineDichVu;
use backend\modules\customer\models\Dep365CustomerOnlineFanpage;
use backend\modules\setting\models\Dep365CoSo;
use common\models\User;
use yii\web\Response;

class PhongKhamController extends MyController
{
    public function actionIndex()
    {
        list($dataCot1Trai,
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
            $duTruKhachChotTrongThangPhanTram) = $this->getData();

        return $this->render('index2', [
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

        //Lấy ra khách đến My Auris thực tế
        $customerGotoAurisTheoThang = CustomerComponents::getTotalCustomerGotoAuris($startMonth, $day, 5, null, null, null, null, null, 1);

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
        ksort($cosoData);
        $dataCot1Trai = [];
        $lichHenTotalThang = $khachDenTotal = $khacVangLaiResultTotal = $khachChotTrongThangResultTotal = $lichhenConLaiTrongThang = 0;
        foreach ($cosoData as $key => $value) {
            $temp = $khachdenTheoThangTemp = $khachVangLaiTemp = $khachChotTrongThangTemp = $khachTuVanDirectSaleThangTemp = 0;

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

//        var_dump($khachTuVanDirectSaleThangResultFinal);
//        die;
        return [
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
        ];
    }

    public function actionGetDataApmtChart()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            //Ngày hiện tại
            $startDateReport = strtotime(date(\Yii::$app->request->post('startDateReport')));
            $endDateReport = strtotime(date(\Yii::$app->request->post('endDateReport')));

            $online = User::getNhanVienIsActiveArray();

            //Lấy ra lịch hẹn hôm nay của 3 cơ sở
            $lichHen = CustomerComponents::getKhachAllLichHen($startDateReport, $endDateReport, 5, null, null, null, array_keys($online), null, true);

            //Lấy ra khách đến My Auris thực tế
            $customerGotoAuris = CustomerComponents::getTotalCustomerGotoAuris($startDateReport, $endDateReport, 5, null, null, null, null, null, null);

            //Lấy ra khách có làm dịch vụ bên My Auris trong ngày
            $customerDone = CustomerComponents::getCustomerDone($startDateReport, $endDateReport, 1);

            //Doanh thu khách hàng theo thời gian
            $incomeCustomer = CustomerComponents::getInCome($startDateReport, $endDateReport);

            $cosoData = Dep365CoSo::find()->indexBy('id')->published()->all();
            ksort($cosoData);
            $result = $lichHenHomNay = $khachDenHomNay = $khachChotHomNay = $doanhthuHomNay = [];
            $lichhenTotalNgay = $doanhThuTotalNgay = $khachChotTotalNgay = $khachdenTotalNgay = $khachVangLaiTemp = $khacVangLaiResultTotal = 0;

            $listLabel[] = 'Tổng';

            foreach ($cosoData as $key => $value) {
                $doanhthu = $khachchot = $khachden = 0;

                $listLabel[] = 'Cơ sở ' . $key;

                if (!array_key_exists($key, $lichHen)) {
                    $result[$key]['lichhen'] = 0;
                } else {
                    $result[$key]['lichhen'] = $lichHen[$key]->user;
                    $lichhenTotalNgay = $lichhenTotalNgay + $lichHen[$key]->user;
                }
                $lichHenHomNay[] = (int)$result[$key]['lichhen'];

                if (!array_key_exists($key, $customerGotoAuris)) {
                    $result[$key]['khachden'] = 0;
                } else {
                    $khachden = (int)$customerGotoAuris[$key]->SDT;
                    $result[$key]['khachden'] = $khachden;
                }
                $khachdenTotalNgay = $khachdenTotalNgay + $khachden;
                $khachDenHomNay[] = (int)$result[$key]['khachden'];

                if (!array_key_exists($key, $customerDone)) {
                    $result[$key]['khachchot'] = 0;
                } else {
                    $khachchot = (int)$customerDone[$key]->SDT;
                    $result[$key]['khachchot'] = $khachchot;
                }
                $khachChotTotalNgay = $khachChotTotalNgay + $khachchot;
                $khachChotHomNay[] = (int)$result[$key]['khachchot'];

//                if (!array_key_exists($key, $incomeCustomer)) {
//                    $result[$key]['doanhthu'] = 0;
//                } else {
//                    $doanhthu = (int)$incomeCustomer[$key]->tien;
//                    $result[$key]['doanhthu'] = $doanhthu;
//                }
//                $doanhThuTotalNgay += $doanhthu;
//                $doanhthuHomNay[] = (int)$result[$key]['doanhthu'];
            }

            $lichHenHomNay = array_merge([$lichhenTotalNgay], $lichHenHomNay);
            $khachDenHomNay = array_merge([$khachdenTotalNgay], $khachDenHomNay);
            $khachChotHomNay = array_merge([$khachChotTotalNgay], $khachChotHomNay);
//            $doanhthuHomNay = array_merge([$doanhThuTotalNgay], $doanhthuHomNay);

            $dataReturn = [
                'lichHenHomNay' => $lichHenHomNay,
                'khachDenHomNay' => $khachDenHomNay,
                'khachChotHomNay' => $khachChotHomNay,
//                'doanhThuHomNay' => $doanhthuHomNay,
            ];

            return [
                'data' => $dataReturn,
                'listLabel' => $listLabel,
            ];
        }
    }

    public function actionGetDataServiceRevenue()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            //Ngày hiện tại
            $startDateReport = strtotime(\Yii::$app->request->post('startDateReport'));
            $endDateReport = strtotime(\Yii::$app->request->post('endDateReport'));

            $dayStart = date('d', $startDateReport);
            $monthStart = date('m', $startDateReport);

            $dayEnd = date('d', $endDateReport);
            $monthEnd = date('m', $endDateReport);

            $title = '';
            if ($monthStart == $monthEnd) {
                if ($dayStart == $dayEnd ||
                    $dayEnd - $dayStart == 6 ||
                    $dayEnd - $dayStart >= 7)
                {
                    $title = 'Doanh thu theo dịch vụ tháng ' . date('m-Y', $startDateReport);
                    $startDateReport = strtotime(date('01-m-Y', $startDateReport));
                }
            } else {
                $title = 'Doanh thu theo dịch vụ từ ' . date('m-Y', $startDateReport) . ' ~ ' . date('m-Y', $endDateReport);
                $startDateReport = strtotime(date('01-m-Y', $startDateReport));
                $endDateReport = strtotime(date('t-m-Y', $endDateReport));
            }

            //Tính doanh thu tháng hiện tại theo dịch vụ
            $revenueByServiceData = CustomerComponents::getServiceRevenue($startDateReport, $endDateReport);
            $serviceData = Dep365CustomerOnlineDichVu::find()->indexBy('id')->published()->all();
            ksort($serviceData);

            $revenueByService = [];
            foreach ($serviceData as $key => $value) {
                if ($key == 7) continue;

                $listLabel[] = $value->name;
                if (!array_key_exists($key, $revenueByServiceData)) {
                    $revenue = 0;
                } else {
                    $revenue = (int)$revenueByServiceData[$key]->tien;
                }
                $revenueByService[] = [
                    'name' => $value->name,
                    'value' => $revenue
                ];
            }

            return [
                'listLabel' => $listLabel,
                'revenueByService' => $revenueByService,
                'title' => $title
            ];
        }
    }

    public function actionGetDataRevenueDay()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $startDateReport = strtotime(\Yii::$app->request->post('startDateReport'));
            $endDateReport = strtotime(\Yii::$app->request->post('endDateReport'));
            $coso = \Yii::$app->user->identity->permission_coso;

            //Doanh thu khách hàng theo thời gian
            $incomeCustomer = CustomerComponents::getInCome($startDateReport, $endDateReport);

            $cosoData = Dep365CoSo::find()->indexBy('id')->published()->all();
            ksort($cosoData);

            $doanhThuNgayTheoCoSo = [];
            $totalDoanhThuNgay = 0;

            foreach ($cosoData as $key => $value) {
                $doanhthu = 0;

                if (!array_key_exists($key, $incomeCustomer)) {
                    $doanhThuNgayTheoCoSo[$key]['doanhthu'] = 0;
                } else {
                    $doanhthu = (int)$incomeCustomer[$key]->tien;
                    $doanhThuNgayTheoCoSo[$key]['doanhthu'] = $doanhthu;
                }
                $totalDoanhThuNgay += $doanhthu;
            }

            if ($coso != null) {
                $doanhThuNgayTheoCoSo = [
                    $coso => $doanhThuNgayTheoCoSo[$coso]
                ];
            }

            return $this->renderPartial('_wg-revenue-day', [
                'doanhThuNgayTheoCoSo' => $doanhThuNgayTheoCoSo,
                'totalDoanhThuNgay' => $totalDoanhThuNgay,
            ]);
        }
    }

    public function actionGetDataRevenueWeek()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $startMonth = strtotime(\Yii::$app->request->post('startDateReport'));
            $endDateReport = strtotime(\Yii::$app->request->post('endDateReport'));

//            $user = new \backend\modules\user\models\User();
//            $roleName = $user->getRoleName(\Yii::$app->user->id);
            $coso = \Yii::$app->user->identity->permission_coso;

            //Tính doanh thu theo cơ sở
            $doanhThuTheoTuanData = CustomerComponents::getRevenue($startMonth, $endDateReport);

            $cosoData = Dep365CoSo::find()->indexBy('id')->published()->all();
            ksort($cosoData);

            $doanhThuTuanTheoCoSo = [];
            $totalDoanhThuTuan = 0;

            foreach ($cosoData as $key => $value) {
                $doanh_thu_temp = 0;

                if (!array_key_exists($key, $doanhThuTheoTuanData)) {
                    $doanhThuTuanTheoCoSo[$key]['doanhthutheotuan'] = 0;
                } else {
                    $doanh_thu_temp = (int)$doanhThuTheoTuanData[$key]->tien;
                    $doanhThuTuanTheoCoSo[$key]['doanhthutheotuan'] = $doanh_thu_temp;
                }
                //Tính doanh thu tổng theo tháng hiện tại
                $totalDoanhThuTuan += $doanh_thu_temp;
            }

            /*if (!in_array($roleName, [User::USER_DEVELOP, User::USER_ADMINISTRATOR])) {
                if ($coso != null) {
                    $doanhThuTuanTheoCoSo = [
                        $coso => $doanhThuTuanTheoCoSo[$coso]
                    ];
                } else {
                    $doanhThuTuanTheoCoSo = [];
                }
            }*/

            if ($coso != null) {
                $doanhThuTuanTheoCoSo = [
                    $coso => $doanhThuTuanTheoCoSo[$coso]
                ];
            }

            return $this->renderPartial('_wg-revenue-week', [
                'doanhThuTuanTheoCoSo' => $doanhThuTuanTheoCoSo,
                'totalDoanhThuTuan' => $totalDoanhThuTuan,
            ]);
        }
    }

    public function actionGetDataRevenueMonth()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $startDateReport = strtotime(\Yii::$app->request->post('startDateReport'));
            $endDateReport = strtotime(\Yii::$app->request->post('endDateReport'));

            $dayStart = date('d', $startDateReport);
            $monthStart = date('m', $startDateReport);

            $dayEnd = date('d', $endDateReport);
            $monthEnd = date('m', $endDateReport);

            $title = '';
            if ($monthStart == $monthEnd) {
                if ($dayStart == $dayEnd ||
                    $dayEnd - $dayStart == 6 ||
                    $dayEnd - $dayStart >= 7)
                {
                    $title = 'Doanh thu tháng ' . date('m-Y', $startDateReport);
                    $startDateReport = strtotime(date('01-m-Y', $startDateReport));
                }
            } else {
                $title = 'Doanh thu từ ' . date('m-Y', $startDateReport) . ' ~ ' . date('m-Y', $endDateReport);
                $startDateReport = strtotime(date('01-m-Y', $startDateReport));
                $endDateReport = strtotime(date('t-m-Y', $endDateReport));
            }

//            $user = new \backend\modules\user\models\User();
//            $roleName = $user->getRoleName(\Yii::$app->user->id);
            $coso = \Yii::$app->user->identity->permission_coso;

            //Tính doanh thu tháng hiện tại theo cơ sở
            $doanhThuTheoThangData = CustomerComponents::getRevenue($startDateReport, $endDateReport);

            $cosoData = Dep365CoSo::find()->indexBy('id')->published()->all();
            ksort($cosoData);

            $doanhThuThangTheoCoSo = [];
            $totalDoanhThuThang = 0;

            foreach ($cosoData as $key => $value) {
                $doanh3T = 0;

                if (!array_key_exists($key, $doanhThuTheoThangData)) {
                    $doanhThuThangTheoCoSo[$key]['doanhthutheothang'] = 0;
                } else {
                    $doanh3T = (int)$doanhThuTheoThangData[$key]->tien;
                    $doanhThuThangTheoCoSo[$key]['doanhthutheothang'] = $doanh3T;
                }
                //Tính doanh thu tổng theo tháng hiện tại
                $totalDoanhThuThang += $doanh3T;
            }

            /*if (!in_array($roleName, [User::USER_DEVELOP, User::USER_ADMINISTRATOR])) {
                if ($coso != null) {
                    $doanhThuThangTheoCoSo = [
                        $coso => $doanhThuThangTheoCoSo[$coso]
                    ];
                } else {
                    $doanhThuThangTheoCoSo = [];
                }
            }*/

            if ($coso != null) {
                $doanhThuThangTheoCoSo = [
                    $coso => $doanhThuThangTheoCoSo[$coso]
                ];
            }

            return $this->renderPartial('_wg-revenue-month', [
                'doanhThuThangTheoCoSo' => $doanhThuThangTheoCoSo,
                'totalDoanhThuThang' => $totalDoanhThuThang,
                'title' => $title,
            ]);
        }
    }

    public function actionGetDataProductByApmt()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            //Ngày hiện tại
            $startDateReport = strtotime(\Yii::$app->request->post('startDateReport'));
            $endDateReport = strtotime(\Yii::$app->request->post('endDateReport'));

            $dayStart = date('d', $startDateReport);
            $monthStart = date('m', $startDateReport);

            $dayEnd = date('d', $endDateReport);
            $monthEnd = date('m', $endDateReport);

            $title = '';
            if ($monthStart == $monthEnd) {
                if ($dayStart == $dayEnd ||
                    $dayEnd - $dayStart == 6 ||
                    $dayEnd - $dayStart >= 7)
                {
                    $title = 'Sản phẩm sử dụng tháng ' . date('m-Y', $startDateReport);
                    $startDateReport = strtotime(date('01-m-Y', $startDateReport));
                }
            } else {
                $title = 'Sản phẩm sử dụng từ ' . date('m-Y', $startDateReport) . ' ~ ' . date('m-Y', $endDateReport);
                $startDateReport = strtotime(date('01-m-Y', $startDateReport));
                $endDateReport = strtotime(date('t-m-Y', $endDateReport));
            }

            $co_so = \Yii::$app->request->post('co_so');

            $query = PhongKhamDonHangWOrder::find()->select('SUM(so_luong) as total_qty, san_pham')
                ->where(['BETWEEN', PhongKhamDonHangWOrder::tableName() . '.ngay_tao', $startDateReport, $endDateReport]);

            if ($co_so != null) {
                $query->joinWith(['donHangModelHasOne']);
                $query->andWhere(['=', DonHangModel::tableName() . '.co_so', $co_so]);
            }

            $totalQtyEachProduct = $query->groupBy('san_pham')->indexBy('san_pham')->all();

            $listProduct = PhongKhamSanPham::getSanPhamArray();

            $listLabelProduct = $listDataProduct = [];

            foreach ($listProduct as $key => $value) {
                $listLabelProduct[$key] = $value;

                if (array_key_exists($key, $totalQtyEachProduct)) {
                    $listDataProduct[$key] = [
                        'name' => $value,
                        'qty' => (int)$totalQtyEachProduct[$key]->total_qty
                    ];
                }
            }

            usort($listDataProduct, function ($a, $b) {
                if ($a['qty'] == $b['qty']) return 0;
                return $a['qty'] < $b['qty'] ? 1 : -1;
            });

            $total = 0;
            $listDataProductFn = $listLabelProductFn = [];
            if (count($listDataProduct) > 6) {
                for ($i = 0; $i < count($listDataProduct); $i++) {
                    if ($i <= 5) {
                        $listLabelProductFn[] = $listDataProduct[$i]['name'];

                        $listDataProductFn[] = [
                            'name' => $listDataProduct[$i]['name'],
                            'value' => $listDataProduct[$i]['qty']
                        ];
                    } else {
                        $total += $listDataProduct[$i]['qty'];
                    }
                }
                $listLabelProductFn[] = 'Khác';
                $listDataProductFn[] = [
                    'name' => 'Khác',
                    'value' => $total
                ];
            }

            return [
                'listDataProduct' => $listDataProductFn,
                'listLabelProduct' => $listLabelProductFn,
                'title' => $title,
                'dayStart' => $dayStart,
                'dayEnd' => $dayEnd
            ];
        }
    }

    public function actionGetDataApmtByFacebookPage()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $startMonth = strtotime(\Yii::$app->request->post('startDateReport'));
            $day = strtotime(\Yii::$app->request->post('endDateReport'));

            $totalLichHenByFanpage = CustomerComponents::getTotalLichHenFanpage($startMonth, $day);
            $listFanpage = Dep365CustomerOnlineFanpage::getListFanpageArray();

            $lichHenFanpage = [];
            foreach ($listFanpage as $key => $value) {
                if (array_key_exists($key, $totalLichHenByFanpage)) {
                    $lichHenFanpage[] = [
                        'name' => $value,
                        'value' => (int)$totalLichHenByFanpage[$key]->total
                    ];
                }
            }

            usort($lichHenFanpage, function ($a, $b) {
                if ($a['value'] == $b['value']) return 0;
                return $a['value'] < $b['value'] ? 1 : -1;
            });

            $tongLichHenFanpage = 0;
            foreach ($lichHenFanpage as $key => $value) {
                $tongLichHenFanpage += $value['value'];
            }

            return $this->renderPartial('_wg-apmt-by-facebook-page', [
                'lichHenFanpage' => $lichHenFanpage,
                'tongLichHenFanpage' => $tongLichHenFanpage,
            ]);
        }
    }

    public function actionGetDataKpiClinic()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $startMonth = strtotime(\Yii::$app->request->post('startDateReport'));
            $endDateReport = strtotime(\Yii::$app->request->post('endDateReport'));

            //Tính tổng tương tác từng dịch vụ
            $totalTuongTacTungDichVu = CustomerComponents::getTuongTacTungDichVu($startMonth, $endDateReport);

            //Tính tổng lịch mới từng dịch vụ
            $totalLichMoiTungDichVu = CustomerComponents::getLichMoiTungDichVu($startMonth, $endDateReport);

            //Tính tổng lịch hẹn từng dịch vụ
            $totalLichHenTungDichVu = CustomerComponents::getLichHenTungDichVu($startMonth, $endDateReport);

            //Tính tổng khách đến từng dịch vụ
            $totalKhachDenTungDichVu = CustomerComponents::getTotalKhachDenTungDichVu($startMonth, $endDateReport);

            //Tính tổng khách làm từng dịch vụ
            $totalKhachLamTungDichVu = CustomerComponents::getTotalKhachLamTungDichVu($startMonth, $endDateReport, 1);

            //Lấy KPI
            $clinicKpi = PhongKhamKpi::find()
                ->select('id_dich_vu, kpi_tuong_tac, kpi_lich_moi, kpi_lich_hen, kpi_khach_den, kpi_khach_lam')
                ->where(['BETWEEN', 'kpi_time', $startMonth, $endDateReport])
                ->groupBy('id_dich_vu, kpi_tuong_tac, kpi_lich_moi, kpi_lich_hen, kpi_khach_den, kpi_khach_lam')->indexBy('id_dich_vu')
                ->all();

            //Lấy danh sách dịch vụ
            $listDichVu = Dep365CustomerOnlineDichVu::getSanPhamDichVuArray();
            ksort($listDichVu);

            $kpiData = [];
            $day_num = date('d');

            foreach ($clinicKpi as $key => $value) {
                if (array_key_exists($key, $listDichVu)) {
                    $kpiData[$key]['name'] = $listDichVu[$key];
                    $kpiData[$key]['tuong_tac']['kpi'] = $day_num * $value['kpi_tuong_tac'];
                    $kpiData[$key]['lich_moi']['kpi'] = $day_num * $value['kpi_lich_moi'];
                    $kpiData[$key]['lich_hen']['kpi'] = $day_num * $value['kpi_lich_hen'];
                    $kpiData[$key]['khach_den']['kpi'] = $day_num * $value['kpi_khach_den'];
                    $kpiData[$key]['khach_lam']['kpi'] = $day_num * $value['kpi_khach_lam'];

                    if (!array_key_exists($key, $totalTuongTacTungDichVu)) {
                        $kpiData[$key]['tuong_tac']['thuc_te'] = 0;
                        $kpiData[$key]['tuong_tac']['phan_tram'] = 0;
                    } else {
                        $kpiData[$key]['tuong_tac']['thuc_te'] = $totalTuongTacTungDichVu[$key]->total_tuongTac == null ? 0 : $totalTuongTacTungDichVu[$key]->total_tuongTac;
                        $kpiData[$key]['tuong_tac']['phan_tram'] = round($kpiData[$key]['tuong_tac']['thuc_te'] / $kpiData[$key]['tuong_tac']['kpi'] * 100, 2);
                    }

                    if (!array_key_exists($key, $totalLichMoiTungDichVu)) {
                        $kpiData[$key]['lich_moi']['thuc_te'] = 0;
                        $kpiData[$key]['lich_moi']['phan_tram'] = 0;
                    } else {
                        $kpiData[$key]['lich_moi']['thuc_te'] = $totalLichMoiTungDichVu[$key]->total_lichMoi == null ? 0 : $totalLichMoiTungDichVu[$key]->total_lichMoi;
                        $kpiData[$key]['lich_moi']['phan_tram'] = round($kpiData[$key]['lich_moi']['thuc_te'] / $kpiData[$key]['lich_moi']['kpi'] * 100, 2);
                    }

                    if (!array_key_exists($key, $totalLichHenTungDichVu)) {
                        $kpiData[$key]['lich_hen']['thuc_te'] = 0;
                        $kpiData[$key]['lich_hen']['phan_tram'] = 0;
                    } else {
                        $kpiData[$key]['lich_hen']['thuc_te'] = $totalLichHenTungDichVu[$key]->total_lichHen == null ? 0 : $totalLichHenTungDichVu[$key]->total_lichHen;
                        $kpiData[$key]['lich_hen']['phan_tram'] = round($kpiData[$key]['lich_hen']['thuc_te'] / $kpiData[$key]['lich_hen']['kpi'] * 100, 2);
                    }

                    if (!array_key_exists($key, $totalKhachDenTungDichVu)) {
                        $kpiData[$key]['khach_den']['thuc_te'] = 0;
                        $kpiData[$key]['khach_den']['phan_tram'] = 0;
                    } else {
                        $kpiData[$key]['khach_den']['thuc_te'] = $totalKhachDenTungDichVu[$key]->total_khachDen == null ? 0 : $totalKhachDenTungDichVu[$key]->total_khachDen;
                        $kpiData[$key]['khach_den']['phan_tram'] = round($kpiData[$key]['khach_den']['thuc_te'] / $kpiData[$key]['khach_den']['kpi'] * 100, 2);
                    }

                    if (!array_key_exists($key, $totalKhachLamTungDichVu)) {
                        $kpiData[$key]['khach_lam']['thuc_te'] = 0;
                        $kpiData[$key]['khach_lam']['phan_tram'] = 0;
                    } else {
                        $kpiData[$key]['khach_lam']['thuc_te'] = $totalKhachLamTungDichVu[$key]->total_khachLam == null ? 0 : $totalKhachLamTungDichVu[$key]->total_khachLam;
                        $kpiData[$key]['khach_lam']['phan_tram'] = round($kpiData[$key]['khach_lam']['thuc_te'] / $kpiData[$key]['khach_lam']['kpi'] * 100, 2);
                    }
                }
            }

            return $this->renderPartial('_wg-kpi', [
                'kpiData' => $kpiData
            ]);
        }
    }

    public function actionGetDataCustomerDoneByDirectSale()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $startDateReport = strtotime(\Yii::$app->request->post('startDateReport'));
            $endDateReport = strtotime(\Yii::$app->request->post('endDateReport'));

            $dayStart = date('d', $startDateReport);
            $monthStart = date('m', $startDateReport);

            $dayEnd = date('d', $endDateReport);
            $monthEnd = date('m', $endDateReport);

            $title = '';
            if ($monthStart == $monthEnd) {
                if ($dayStart == $dayEnd)
                {
                    $title = 'Tỉ lệ khách chốt / khách tư vấn ngày ' . date('d-m-Y', $startDateReport);
                    $startDateReport = strtotime(date('d-m-Y', $startDateReport));
                } else {
                    $title = 'Tỉ lệ khách chốt / khách tư vấn từ ' . date('d-m-Y', $startDateReport) . ' ~ ' . date('d-m-Y', $endDateReport);
                    $startDateReport = strtotime(date('d-m-Y', $startDateReport));
                    $endDateReport = strtotime(date('d-m-Y', $endDateReport));
                }
            } else {
                $title = 'Tỉ lệ khách chốt / khách tư vấn từ ' . date('d-m-Y', $startDateReport) . ' ~ ' . date('d-m-Y', $endDateReport);
                $startDateReport = strtotime(date('d-m-Y', $startDateReport));
            }

                //Tinh khach tu van va khach chot cho direct sale theo ngay
            $listDirectSale = User::getNhanVienTuDirectSaleIsActiveArray();
            ksort($listDirectSale);

            $khachTuVanOfDirectSaleNgay = CustomerComponents::getKhachTuVanTheoNhanVien($startDateReport, $endDateReport, 2, array_keys($listDirectSale));
            $khachChotOfDirectSaleNgay = CustomerComponents::getKhachChotTheoNhanVien($startDateReport, $endDateReport, 2, array_keys($listDirectSale));

            $n = 0;
            $khachTuVanDirectSaleNgayResultFinal = [];

            foreach ($listDirectSale as $key => $value) {
                if (!array_key_exists($key, $khachTuVanOfDirectSaleNgay)) {
                    $khachTuVanDirectSaleNgayResult[$n]['khachDirectSaleTuVan'] = 0;
                } else {
                    $khachTuVanDirectSaleNgayTemp = (int)$khachTuVanOfDirectSaleNgay[$key]->SDT;
                    $khachTuVanDirectSaleNgayResult[$n]['khachDirectSaleTuVan'] = $khachTuVanDirectSaleNgayTemp;
                }

                if (!array_key_exists($key, $khachChotOfDirectSaleNgay)) {
                    $khachChotDirectSaleNgayResult[$n]['khachDirectSaleChot'] = 0;
                } else {
                    $khachChotDirectSaleNgayTemp = (int)$khachChotOfDirectSaleNgay[$key]->SDT;
                    $khachChotDirectSaleNgayResult[$n]['khachDirectSaleChot'] = $khachChotDirectSaleNgayTemp;
                }
                if ($khachTuVanDirectSaleNgayResult[$n]['khachDirectSaleTuVan'] == 0) {
                    $phanTramChotVoiTuVanNgay[$n]['phantram'] = 0;
                } else {
                    $phanTramChotVoiTuVanNgay[$n]['phantram'] = round(($khachChotDirectSaleNgayResult[$n]['khachDirectSaleChot'] / $khachTuVanDirectSaleNgayResult[$n]['khachDirectSaleTuVan']) * 100, 2);
                }

                $khachTuVanDirectSaleNgayResultFinal[$n]['name'] = $value;
                $khachTuVanDirectSaleNgayResultFinal[$n]['khachDirectSaleChot'] = $khachChotDirectSaleNgayResult[$n]['khachDirectSaleChot'];
                $khachTuVanDirectSaleNgayResultFinal[$n]['khachDirectSaleTuVan'] = $khachTuVanDirectSaleNgayResult[$n]['khachDirectSaleTuVan'];
                $khachTuVanDirectSaleNgayResultFinal[$n]['phantram'] = $phanTramChotVoiTuVanNgay[$n]['phantram'];
                $n++;
            }

            //Tinh khach tu van va khach chot cho direct sale theo thang
            $khachTuVanOfDirectSaleThang = CustomerComponents::getKhachTuVanTheoNhanVien($startDateReport, $endDateReport, 2, array_keys($listDirectSale));
            $khachChotOfDirectSaleThang = CustomerComponents::getKhachChotTheoNhanVien($startDateReport, $endDateReport, 2, array_keys($listDirectSale));
            $i = 0;
            $khachTuVanDirectSaleThangResultFinal = [];
            foreach ($listDirectSale as $key => $value) {
                if (!array_key_exists($key, $khachTuVanOfDirectSaleThang)) {
                    $khachTuVanDirectSaleThangResult[$i]['khachDirectSaleTuVan'] = 0;
                } else {
                    $khachTuVanDirectSaleThangTemp = (int)$khachTuVanOfDirectSaleThang[$key]->SDT;
                    $khachTuVanDirectSaleThangResult[$i]['khachDirectSaleTuVan'] = $khachTuVanDirectSaleThangTemp;
                }

                if (!array_key_exists($key, $khachChotOfDirectSaleThang)) {
                    $khachChotDirectSaleThangResult[$i]['khachDirectSaleChot'] = 0;
                } else {
                    $khachChotDirectSaleThangTemp = (int)$khachChotOfDirectSaleThang[$key]->SDT;
                    $khachChotDirectSaleThangResult[$i]['khachDirectSaleChot'] = $khachChotDirectSaleThangTemp;
                }
                if ($khachTuVanDirectSaleThangResult[$i]['khachDirectSaleTuVan'] == 0) {
                    $phanTramChotVoiTuVan[$i]['phantram'] = 0;
                } else {
                    $phanTramChotVoiTuVan[$i]['phantram'] = round(($khachChotDirectSaleThangResult[$i]['khachDirectSaleChot'] / $khachTuVanDirectSaleThangResult[$i]['khachDirectSaleTuVan']) * 100, 2);
                }
                $khachTuVanDirectSaleThangResultFinal[$i]['name'] = $value;
                $khachTuVanDirectSaleThangResultFinal[$i]['khachDirectSaleChot'] = $khachChotDirectSaleThangResult[$i]['khachDirectSaleChot'];
                $khachTuVanDirectSaleThangResultFinal[$i]['khachDirectSaleTuVan'] = $khachTuVanDirectSaleThangResult[$i]['khachDirectSaleTuVan'];
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

            return $this->renderPartial('_wg-customer-done-by-ds', [
                'khachTuVanDirectSaleNgayResultFinal' => $khachTuVanDirectSaleNgayResultFinal,
                'khachTuVanDirectSaleThangResultFinal' => $khachTuVanDirectSaleThangResultFinal,
                'title' => $title
            ]);
        }
    }

    public function actionGetDataServiceToday()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $startDateReport = strtotime(\Yii::$app->request->post('startDateReport'));
            $endDateReport = strtotime(\Yii::$app->request->post('endDateReport'));

            $dayStart = date('d', $startDateReport);
            $monthStart = date('m', $startDateReport);

            $dayEnd = date('d', $endDateReport);
            $monthEnd = date('m', $endDateReport);

            $title = '';
            if ($monthStart == $monthEnd) {
                if ($dayStart == $dayEnd)
                {
                    $title = 'Dịch vụ khách làm ngày ' . date('d-m-Y', $startDateReport);
                    $startDateReport = strtotime(date('d-m-Y', $startDateReport));
                } else {
                    $title = 'Dịch vụ khách làm từ ' . date('d-m-Y', $startDateReport) . ' ~ ' . date('d-m-Y', $endDateReport);
                    $startDateReport = strtotime(date('d-m-Y', $startDateReport));
                    $endDateReport = strtotime(date('d-m-Y', $endDateReport));
                }
            } else {
                $title = 'Dịch vụ khách làm từ ' . date('d-m-Y', $startDateReport) . ' ~ ' . date('d-m-Y', $endDateReport);
                $startDateReport = strtotime(date('d-m-Y', $startDateReport));
                $endDateReport = strtotime(date('d-m-Y', $endDateReport));
            }

            //Tính tổng khách làm từng dịch vụ
            $totalKhachLamTungDichVu = CustomerComponents::getTotalKhachLamTungDichVu($startDateReport, $endDateReport, 2);

            //Lấy danh sách dịch vụ
            $listDichVu = Dep365CustomerOnlineDichVu::getSanPhamDichVuArray();
            ksort($listDichVu);

            //Lấy danh sách cơ sở
            $listCoSo = Dep365CoSo::getCoSoArray();

            $outputTemp = $output = [];

            foreach ($totalKhachLamTungDichVu as $key => $value) {
                if ($value['id'] != null && array_key_exists($value['id'], $listDichVu)) {
                    $outputTemp[$value['id']] = [
                        'name' => $listDichVu[$value['id']],
                        'num' => $value['total_khachLam'],
                        'co_so' => $value->co_so
                    ];
                }
            }

            foreach ($outputTemp as $key => $value) {
                if (array_key_exists($value['co_so'], $listCoSo)) {
                    $output[$value['co_so']][$key] = [
                        'name' => $listDichVu[$key],
                        'num' => $value['num']
                    ];
                }
            }

            ksort($output);

            return $this->renderPartial('_wg-service-today', [
                'data' => $output,
                'title' => $title,
            ]);
        }
    }
}
