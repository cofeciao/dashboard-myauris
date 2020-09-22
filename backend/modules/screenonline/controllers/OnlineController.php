<?php
/**
 * Created by PhpStorm.
 * User: luken
 * Date: 6/6/2020
 * Time: 09:35
 */

namespace backend\modules\screenonline\controllers;

use backend\components\MyController;
use backend\modules\customer\components\CustomerComponents;
use backend\modules\customer\models\Dep365CustomerOnlineDichVu;
use backend\modules\setting\models\Dep365CoSo;
use backend\modules\user\models\UserTimelineModel;
use common\models\User;
use yii\web\Response;

/**
 * Default controller for the `screenonline` module
 */
class OnlineController extends MyController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = '@backend/views/layouts/public';
        $online = User::getNhanVienIsActiveArray();
        $day = strtotime(date('d-m-Y'));
        $startMonth = strtotime(date('1-m-Y'));
        $from = strtotime(date('d-m-Y') . '+1 day');
        $callDayinMonth = date('t', strtotime(date('d-m-Y')));//cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
        $lastTo = strtotime(date($callDayinMonth . '-m-Y'));
        $to = strtotime(date('d-m-Y'));

        //Start redis lich moi
        $totalLichMoi = CustomerComponents::getKhachAllDatHen($day, $day, 4, null, null, null, array_keys($online));

        //Tinh lich moi cho cac ban nhan vien
        $data = CustomerComponents::getKhachAllDatHen($day, $day, 3, null, null, null, array_keys($online));
        $res = [];
        foreach ($data as $key => $item) {
            $res[$online[$item->user_id]] = $item->user;
        }

        //Tinh lich moi theo Team
        $totalLichMoiTeam = CustomerComponents::getKhachAllDatHen($day, $day, 6, null, null, null, array_keys($online));
        $team = [];
        $teamArr = \Yii::$app->controller->module->params['team'];
        $demTeam = count($teamArr);
        for ($i = 1; $i <= $demTeam; $i++) {
            if (!array_key_exists($i, $totalLichMoiTeam)) {
                $team[$i]['team'] = \Yii::$app->controller->module->params['team'][$i];
                $team[$i]['khachmoi'] = 0;
                $team[$i]['tyle'] = 0;
            } else {
                $team[$i]['team'] = \Yii::$app->controller->module->params['team'][$i];
                $khach = (int)$totalLichMoiTeam[$i]->user;
                $team[$i]['khachmoi'] = $khach;
                if ($totalLichMoi != 0) {
                    $team[$i]['tyle'] = round(($khach / $totalLichMoi) * 100, 2);
                } else {
                    $team[$i]['tyle'] = 0;
                }
            }
        }
//        End redis cache lich moi

        //Tinh lich hen
        $lichHenTotal = CustomerComponents::getKhachAllLichHen($day, $day, 1, null, null, null, array_keys($online));
        $lichHen = CustomerComponents::getKhachAllLichHen($day, $day, 5, null, null, null, array_keys($online));

        //Tinh khach den trong ngày hôm nay
        $customerGotoAurisTotal = CustomerComponents::getTotalCustomerGotoAuris($day, $day, 1, null, null, null, array_keys($online));
        $customerGotoAuris = CustomerComponents::getTotalCustomerGotoAuris($day, $day, 5, null, null, null, array_keys($online));

        //Tinh khach den trong tháng hiện tại
        $customerGotoAurisWithMonthWithCoSo = CustomerComponents::getTotalCustomerGotoAuris($startMonth, $lastTo, 5, null, null, null, array_keys($online));
        $customerGotoAurisWithMonthWithCoSoTotal = CustomerComponents::getTotalCustomerGotoAuris($startMonth, $lastTo, 1, null, null, null, array_keys($online));

        //Tinh lich hen trong tháng
        $totalCustomerInMonth = CustomerComponents::getTotalLichHenWithDay($startMonth, $lastTo, 2);

        $cosoData = Dep365CoSo::find()->indexBy('id')->published()->all();
        ksort($cosoData);
        $dataCoso = [];
        foreach ($cosoData as $key => $value) {
            $name = $value->name;
            $dataCoso[$key]['name'] = $name;
            if (!array_key_exists($key, $lichHen)) {
                $dataCoso[$key]['lichhen'] = 0;
            } else {
                $dataCoso[$key]['lichhen'] = $lichHen[$key]->user;
            }

            if (!array_key_exists($key, $customerGotoAuris)) {
                $dataCoso[$key]['khachden'] = 0;
                $dataCoso[$key]['phantram'] = 0;
            } else {
                $khachden = (int)$customerGotoAuris[$key]->SDT;
                $dataCoso[$key]['khachden'] = $khachden;
                if (isset($lichHen[$key]) && $lichHen[$key]->user != 0) {
                    $dataCoso[$key]['phantram'] = round(($khachden / $lichHen[$key]->user) * 100, 2);
                } else {
                    $dataCoso[$key]['phantram'] = 0;
                }
            }
            if (!array_key_exists($key, $customerGotoAurisWithMonthWithCoSo)) {
                $dataCoso[$key]['khachdentheothang'] = 0;
            } else {
                $dataCoso[$key]['khachdentheothang'] = $customerGotoAurisWithMonthWithCoSo[$key]->SDT;
            }
            if (!array_key_exists($key, $totalCustomerInMonth)) {
                $dataCoso[$key]['lichhentheothang'] = 0;
            } else {
                $dataCoso[$key]['lichhentheothang'] = $totalCustomerInMonth[$key]->SDT;
            }
        }

        $dataTimeline = UserTimelineModel::find()->joinWith(['nameCustomerHasOne', 'nameUserHasOne'])
            ->limit('10')
            ->orderBy(['user_timeline.created_at' => SORT_DESC])
            ->all();

        //Tinh lich hen tu ngay mai toi cuoi thang
        $totalCustomerTodayToLastMonth = CustomerComponents::getTotalLichHenWithDay($from, $lastTo, 1);

        //Tinh lich hen tu ngay mai toi cuoi thang theo cơ sở
        $totalCustomerTodayToLastMonthWithCoSo = CustomerComponents::getTotalLichHenWithDay($from, $lastTo, 2);

        //Chia lich hen theo cơ sở kể cả cơ sở = null
        $dataLichHenWithCoSoToLastMonth = [];
        foreach ($cosoData as $key => $value) {
            if (!array_key_exists($key, $totalCustomerTodayToLastMonthWithCoSo)) {
                $dataLichHenWithCoSoToLastMonth[$key]['lichhen'] = 0;
            } else {
                $dataLichHenWithCoSoToLastMonth[$key]['lichhen'] = $totalCustomerTodayToLastMonthWithCoSo[$key]->SDT;
            }
        }

        //Tinh lich hen tu dau thang tới ngày hiện tại
        $totalCustomerToDayInMonth = CustomerComponents::getTotalLichHenWithDay($startMonth, $to, 1);

        //Tinh lich hen tren 1 ngay tu dau thang toi thoi diem hien tai
        $today = date('d');
        $tyleLichHenTrongThangToiHienTai = (int)($totalCustomerToDayInMonth / $today);

        //Uoc tinh lich hen con lai
        $dayConLai = $callDayinMonth - $today;
        $lichHenDutru = $tyleLichHenTrongThangToiHienTai * $dayConLai + $totalCustomerTodayToLastMonth;

        //Tinh khach da den toi thoi diem hien tai
        $khachDenToiHienTai = CustomerComponents::getTotalCustomerGotoAuris($startMonth, $to, 1, null, null, null, null);

        //Tinh ty le khach da den so voi lich hen
        if ($totalCustomerToDayInMonth == 0) {
            $tyleKhachDenVoiLichHen = 0;
        } else {
            $tyleKhachDenVoiLichHen = round($khachDenToiHienTai / $totalCustomerToDayInMonth, 2) * 100;
        } //return 61%;

        //Uoc tinh khach den toi cuoi thang
        $uoctinhKhachDen = (int)(($tyleKhachDenVoiLichHen * $lichHenDutru) / 100);

        //Tinh tong lich moi tu dau thang toi hien tai
        $totalLichMoiToiHienTai = CustomerComponents::getCalendarNewOfCustomer($startMonth, $to, 1);

        //Tinh tong lich moi tu dau thang toi hien tai theo co so
        $lichMoiTheoCoSo = CustomerComponents::getCalendarNewOfCustomer($startMonth, $to, 2);

        //Chia lich moi theo cơ sở kể cả cơ sở = null
        $dataLichMoiTheoCoSo = [];
        foreach ($cosoData as $key => $value) {
            if (!array_key_exists($key, $lichMoiTheoCoSo)) {
                $dataLichMoiTheoCoSo[$key]['lichmoi'] = 0;
            } else {
                $dataLichMoiTheoCoSo[$key]['lichmoi'] = $lichMoiTheoCoSo[$key]->SDT;
            }
        }

        return $this->render('index1', [
            'totalCustomerTodayToLastMonth' => $totalCustomerTodayToLastMonth,
            'dataLichHenWithCoSoToLastMonth' => $dataLichHenWithCoSoToLastMonth,
            'lichHenDutru' => $lichHenDutru,
            'uoctinhKhachDen' => $uoctinhKhachDen,
            'totalLichMoiToiHienTai' => $totalLichMoiToiHienTai,
            'dataLichMoiTheoCoSo' => $dataLichMoiTheoCoSo,
            'res' => $res,
            'totalLichMoi' => $totalLichMoi,
            'team' => $team,
            'coSo' => $dataCoso,
            'lichHenTotal' => $lichHenTotal,
            'customerGotoAurisTotal' => $customerGotoAurisTotal,
            'customerGotoAurisWithMonthWithCoSoTotal' => $customerGotoAurisWithMonthWithCoSoTotal,
            'dataTimeline' => $dataTimeline,
        ]);
    }

    public function actionGetDataProductToday()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            $startMonth = strtotime(\Yii::$app->request->post('startDateReport'));
            $day = strtotime(\Yii::$app->request->post('endDateReport'));
            $user = new \backend\modules\user\models\User();
            $roleName = $user->getRoleName(\Yii::$app->user->id);
            $co_so = \Yii::$app->user->identity->permission_coso;

            //Lấy danh sách dịch vụ
            $listDichVu = Dep365CustomerOnlineDichVu::getSanPhamDichVuArray();
            ksort($listDichVu);

            //Lấy danh sách cơ sở
            $listCoSo = Dep365CoSo::getCoSoArray();

            $outputTemp = $ouput = [];
            if ($roleName == User::USER_QUANLY_PHONGKHAM) {
                $totalKhachLamTungDichVu = CustomerComponents::getTotalKhachLamTungDichVu($startMonth, $day, 2);

                /*foreach ($totalKhachLamTungDichVu as $key => $value) {
                    if ($value['id'] != null && array_key_exists($value['id'], $listDichVu)) {
                        if ($key != $co_so) continue;
                        $sP[$value['id']] = [
                            'name' => $listDichVu[$value['id']],
                            'sl' => $value['total_khachLam']
                        ];
                    }
                }*/
                /*foreach ($listDichVu as $key => $value) {
                    if (!array_key_exists($key, $sP)) {
                        $sP[$key]['name'] = $value;
                        $sP[$key]['sl'] = 0;
                    }
                }
                ksort($sP);*/
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

            } else {
                //Tính khách hàng theo lịch
                $sanPham = CustomerComponents::getKhachHangTheoSanPham($startMonth, $day, $co_so);

                foreach ($listDichVu as $key => $value) {
                    if (!array_key_exists($key, $sanPham)) {
                        $output[$key]['name'] = $value;
                        $output[$key]['sl'] = 0;
                    } else {
                        $output[$key]['name'] = $value;
                        $output[$key]['sl'] = $sanPham[$key]->SDT;
                    }
                }
                ksort($output);
            }

            return $this->renderPartial('_wg-product-today', [
                'products' => $output
            ]);
        }
    }

    public function actionGetDataCardGroupApmt()
    {
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = Response::FORMAT_JSON;

            //Ngày
            $startMonth = strtotime(\Yii::$app->request->post('startDateReport'));
            $day = strtotime(\Yii::$app->request->post('endDateReport'));
            $lastTo = strtotime(\Yii::$app->request->post('lastDateReport'));

            $online = User::getNhanVienIsActiveArray();
            $user = new \backend\modules\user\models\User();
            $roleName = $user->getRoleName(\Yii::$app->user->id);
            $coso = \Yii::$app->user->identity->permission_coso;
            $listUser = $roleName == User::USER_QUANLY_PHONGKHAM ? null : array_keys($online);

            //Lịch hẹn hôm nay
//            $lichHenHomNay = CustomerComponents::getKhachAllLichHen($day, $day, 5, null, null, null, array_keys($online));
            $lichHenHomNay = CustomerComponents::getArrayAppointmentByTime($day, $day + 86399);
            //Lịch hẹn trong tháng
            $tongLichHenThang = CustomerComponents::getArrayAppointmentByTime($startMonth, $lastTo + 86399);
            //Khách đến hôm nay
            $khachDenHomNay = CustomerComponents::getTotalCustomerGotoAuris($day, $day, 5, null, null, null, $listUser, null, null);
            //Khách đến trong tháng
            $khachDenTrongThangTungCoSo = CustomerComponents::getTotalCustomerGotoAuris($startMonth, $lastTo, 5, null, null, null, $listUser, null, null);

            //Khách làm hôm nay
            $khachLamHomNay = CustomerComponents::getCustomerDone($day, $day, $type = 1);
            //Khách làm trong thang
            $tongKhachLamTrongThang = CustomerComponents::getCustomerDone($startMonth, $lastTo, $type = 1);

            $cosoData = Dep365CoSo::find()->indexBy('id')->published()->all();
            ksort($cosoData);

            $dataCoso = [];
            $dataTotal = [
                'tong_lichhen_ngay' => 0,
                'tong_lichhen_thang' => 0,
                'tong_khachden_ngay' => 0,
                'tong_khachden_thang' => 0,
                'tong_khachlam_ngay' => 0,
                'tong_khachlam_thang' => 0,
            ];

            foreach ($cosoData as $key => $value) {
                //lich hen co so
                if (!array_key_exists($key, $lichHenHomNay)) {
                    $dataCoso[$key]['lich_hen'] = 0;
                } else {
                    $dataCoso[$key]['lich_hen'] = $lichHenHomNay[$key]->user;

                    //tong lich hen ngay tat ca co so
                    $dataTotal['tong_lichhen_ngay'] += $lichHenHomNay[$key]->user;
                }

                //tong lich hen thang theo co so
                if (!array_key_exists($key, $tongLichHenThang)) {
                    $dataCoso[$key]['tong_lichhen_thang'] = 0;
                } else {
                    $dataCoso[$key]['tong_lichhen_thang'] = $tongLichHenThang[$key]->user;

                    //tong lich hen thang tat ca co so
                    $dataTotal['tong_lichhen_thang'] += $tongLichHenThang[$key]->user;
                }

                //Khach den
                if (!array_key_exists($key, $khachDenHomNay)) {
                    $dataCoso[$key]['khach_den'] = 0;
                    $dataCoso[$key]['phantram_khachden'] = 0;
                } else {
                    $khachden = (int)$khachDenHomNay[$key]->SDT;
                    $dataCoso[$key]['khach_den'] = $khachden;

                    if (isset($lichHenHomNay[$key]) && $lichHenHomNay[$key]->user != 0) {
                        $dataCoso[$key]['phantram_khachden'] = round(($khachden / $lichHenHomNay[$key]->user) * 100, 2);
                    } else {
                        $dataCoso[$key]['phantram_khachden'] = 0;
                    }

                    //tong khach den trong ngay tat ca co so
                    $dataTotal['tong_khachden_ngay'] += (int)$khachDenHomNay[$key]->SDT;
                }

                //tong khach den trong thang theo tung co so
                if (!array_key_exists($key, $khachDenTrongThangTungCoSo)) {
                    $dataCoso[$key]['tong_khachden_thang'] = 0;
                } else {
                    $dataCoso[$key]['tong_khachden_thang'] = $khachDenTrongThangTungCoSo[$key]->SDT;

                    //tong khach den trong thang tat ca co so
                    $dataTotal['tong_khachden_thang'] += $khachDenTrongThangTungCoSo[$key]->SDT;
                }

                //khach lam
                if (!array_key_exists($key, $khachLamHomNay)) {
                    $dataCoso[$key]['khach_lam'] = 0;
                    $dataCoso[$key]['phantram_khachlam'] = 0;
                } else {
                    $khachlam = (int)$khachLamHomNay[$key]->SDT;
                    $dataCoso[$key]['khach_lam'] = $khachlam;

                    if (isset($khachDenHomNay[$key]) && $khachDenHomNay[$key]->SDT != 0) {
                        $dataCoso[$key]['phantram_khachlam'] = round(($khachlam / $khachDenHomNay[$key]->SDT) * 100, 2);
                    } else {
                        $dataCoso[$key]['phantram_khachlam'] = 0;
                    }

                    //tong khach lam trong ngay tat ca co so
                    $dataTotal['tong_khachlam_ngay'] += $khachLamHomNay[$key]->SDT;
                }

                if (!array_key_exists($key, $tongKhachLamTrongThang)) {
                    $dataCoso[$key]['tong_khachlam_thang'] = 0;
                } else {
                    $dataCoso[$key]['tong_khachlam_thang'] = $tongKhachLamTrongThang[$key]->SDT;

                    //tong khach den trong thang tat ca co so
                    $dataTotal['tong_khachlam_thang'] += $tongKhachLamTrongThang[$key]->SDT;
                }
            }

            /*if (!in_array($roleName, [User::USER_DEVELOP, User::USER_ADMINISTRATOR])) {
                if ($coso != null) {
                    $dataCoso = [
                        $coso => $dataCoso[$coso]
                    ];
                } else {
                    $dataCoso = [];
                }
            }*/

            if ($coso != null) {
                $dataCoso = [
                    $coso => $dataCoso[$coso]
                ];
            }

            return $this->renderPartial('_wg-group-card', [
                'dataCoso' => $dataCoso,
                'dataTotal' => $dataTotal,
            ]);
        }
    }

    public function actionIndex2()
    {
        return $this->render('index2', []);
    }

    public function actionIndex3()
    {
        return $this->render('index3', []);
    }


}