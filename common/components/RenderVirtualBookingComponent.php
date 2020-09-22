<?php

namespace common\components;

use backend\modules\api\modules\v1\controllers\BookingController;
use backend\modules\booking\models\CustomerOnlineBooking;
use backend\modules\customer\models\Dep365CustomerOnline;
use backend\modules\setting\models\Dep365CoSo;
use Yii;

class RenderVirtualBookingComponent
{
    public function renderVirtualBooking($renderNew = false)
    {
        if (!in_array($renderNew, [true, false])) {
            $renderNew = false;
        }
        $today = date('Y-m-d');
        $dateNow = strtotime($today);
        $return = [
            'success' => [],
            'fail' => []
        ];
        $code = 200;
        $msg = '';
        $listCoSo = Dep365CoSo::find()->where(['render_virtual_booking' => Dep365CoSo::STATUS_PUBLISHED])->published()->all();
        $allCustomer = Dep365CustomerOnline::find()->select(['forename'])->where('forename IS NOT null AND status=2')->all();
        if ($listCoSo != null) {
            foreach ($listCoSo as $coso) {
                $coso_id = $coso->getPrimaryKey();
                $checkBookingIsInit = CustomerOnlineBooking::find()->where(['customer_type' => CustomerOnlineBooking::CUSTOMER_VITUAL, 'coso_id' => $coso_id])->andWhere('booking_date > ' . $dateNow)->count();
                if ($checkBookingIsInit <= 0 || $renderNew === true) {
                    /* CHƯA RENDER LỊCH ẢO HOẶC RENDER MỚI CHO NHỮNG NGÀY ĐÃ CÓ LỊCH ẢO => RENDER LỊCH ẢO CHO 60 NGÀY TIẾP THEO */
                    for ($i = 1; $i <= 61; $i++) {
                        $dateCreate = date('Y-m-d', strtotime($today . ' +' . $i . 'days'));
                        $dateType = date('N', strtotime($dateCreate)); /* LẤY N THEO NGÀY ĐỂ KIỂM TRA T7 CN */
                        $key = 1;
                        if ($i == 1) {
                            $key = 1;
                        } /* NGÀY MAI */
                        elseif (($dateType == '6' || $dateType == '7') && $i < 21) {
                            $key = 3;
                        } /* T7 - CN (3 TUẦN KẾ TIẾP) */
                        elseif ($i <= 7) {
                            $key = 4;
                        } /* TRONG 1 TUẦN KẾ TIẾP */
                        elseif ($i <= 28) {
                            $key = 5;
                        } /* TUẦN T2 ĐẾN TUẦN T4 */
                        elseif ($i == 2) {
                            $key = 2;
                        } /* NGÀY MỐT */
                        else {
                            $key = 6;
                        } /* THÁNG T2 */

                        /* RANDOM TỔNG SỐ LỊCH BOOK TRONG NGÀY */
                        $rBookingByDay = rand(CustomerOnlineBooking::LIST_RANDOM_BY_DAY[$key][0], CustomerOnlineBooking::LIST_RANDOM_BY_DAY[$key][1]);
                        /* TỔNG SỐ LỊCH ĐÃ BOOK TRONG NGÀY */
                        $totalBookingInDay = CustomerOnlineBooking::find()->where(['booking_date' => strtotime($dateCreate), 'coso_id' => $coso_id])->count();
                        /* RANDOM TỔNG SỐ LỊCH BOOK TRONG NGÀY = RANDOM - TỔNG SỐ LỊCH ĐÃ BOOK */
                        $rBookingByDay -= $totalBookingInDay;
                        $listTimeNotFull = Yii::$app->db->createCommand('SELECT tbl.* FROM (SELECT t.*, (SELECT COUNT(*) FROM dep365_customer_online_booking b WHERE b.time_id=t.id AND b.coso_id=' . $coso_id . ' AND b.booking_date=' . strtotime($dateCreate) . ') as countb FROM dep365_time_work t) AS tbl WHERE tbl.countb<' . CustomerOnlineBooking::MAX_BOOKING_IN_TIME)->queryAll();

                        /* TRONG KHI TỔNG SỐ LỊCH ẢO TRONG NGÀY CÒN LỚN HƠN TỔNG MÚI GIỜ TRONG NGÀY => PHÂN CHIA MỖI MÚI GIỜ 1 LỊCH ẢO VÀ TÍNH LẠI TỔNG LỊCH ẢO CÒN DƯ */
                        while ($rBookingByDay > count($listTimeNotFull)) {
                            /* NẾU TỔNG LỊCH ẢO RANDOM TRONG NGÀY > TỔNG MÚI GIỜ LÀM VIỆC TRONG NGÀY => MỖI MÚI GIỜ CÓ ÍT NHẤT 1 LỊCH => LẶP MỖI MÚI GIỜ VÀ INSERT 1 LỊCH */
                            foreach ($listTimeNotFull as $timeInDay) {
                                $rUserRegister = $allCustomer[array_rand($allCustomer)];
                                $data = [
                                    'customer_id' => null,
                                    'customer_name' => $rUserRegister->forename,
                                    'customer_phone' => '*******' . rand(100, 999),
                                    'customer_type' => CustomerOnlineBooking::CUSTOMER_VITUAL,
                                    'ip' => '127.0.0.1',
                                    'booking_date' => strtotime($dateCreate),
                                    'time_id' => $timeInDay['id'],
                                    'coso_id' => $coso_id,
                                    'status' => 1
                                ];
                                $insertBooking = BookingController::actionBookingApi($data, true);
                                if ($insertBooking['code'] != 200) {
                                    $return['fail'][] = [
                                        'data' => $data,
                                        'return' => $insertBooking
                                    ];
                                } else {
                                    $return['success'][] = [
                                        'data' => $data
                                    ];
                                }
                            }
                            /* SAU KHI INSERT => SỐ LỊCH ẢO CÒN DƯ = TỔNG LỊCH ẢO RANDOM TRỪ TỔNG MÚI GIỜ TRONG NGÀY => LẤY SỐ LỊCH ẢO CÒN DƯ PHÂN CHIA TIẾP VÀO CÁC MÚI GIỜ THEO THỨ TỰ RANDOM (Ở BƯỚC DƯỚI) */
                            $rBookingByDay = $rBookingByDay - count($listTimeNotFull);
                            $listTimeNotFull = Yii::$app->db->createCommand('SELECT tbl.* FROM (SELECT t.*, (SELECT COUNT(*) FROM dep365_customer_online_booking b WHERE b.time_id=t.id AND coso_id=' . $coso_id . ' AND b.booking_date=' . strtotime($dateCreate) . ') as countb FROM dep365_time_work t) AS tbl WHERE tbl.countb<' . CustomerOnlineBooking::MAX_BOOKING_IN_TIME)->queryAll();
                        }
                        /* NẾU TỔNG LỊCH ẢO RANDOM TRONG NGÀY < TỔNG MÚI GIỜ LÀM VIỆC TRONG NGÀY => PHÂN CHIA SỐ LỊCH ẢO VÀO CÁC MÚI GIỜ THEO THỨ TỰ RANDOM */
                        if ($rBookingByDay > 0) {
                            /* CHỌN RANDOM VỊ TRÍ MÚI GIỜ ĐỂ PHÂN CHIA LỊCH ẢO CÒN DƯ */
                            $rGetTime = array_rand($listTimeNotFull, $rBookingByDay);
                            if (!is_array($rGetTime)) {
                                $rGetTime = [$rGetTime];
                            }
                            foreach ($rGetTime as $getTime) {
                                $rUserRegister = $allCustomer[array_rand($allCustomer)];
                                $data = [
                                    'customer_id' => null,
                                    'customer_name' => $rUserRegister->forename,
                                    'customer_phone' => '*******' . rand(100, 999),
                                    'customer_type' => CustomerOnlineBooking::CUSTOMER_VITUAL,
                                    'ip' => '127.0.0.1',
                                    'booking_date' => strtotime($dateCreate),
                                    'time_id' => $listTimeNotFull[$getTime]['id'],
                                    'coso_id' => $coso_id,
                                    'status' => 1
                                ];
                                $insertBooking = BookingController::actionBookingApi($data, true);
                                if ($insertBooking['code'] != 200) {
                                    $return['fail'][] = [
                                        'data' => $data,
                                        'return' => $insertBooking
                                    ];
                                } else {
                                    $return['success'][] = [
                                        'data' => $data
                                    ];
                                }
                            }
                        }
                    }
                } else {
                    /* ĐÃ RENDER LỊCH ẢO => RENDER LỊCH ẢO CHO HÔM NAY + NGÀY MAI + NGÀY THỨ 7 (NGÀY ĐẦU TIÊN CỦA TUẦN THỨ 2 SO VỚI NGÀY HÔM QUA) + NGÀY THỨ 28 (NGÀY ĐẦU TIÊN CỦA THÁNG THỨ 2 SO VỚI NGÀY HÔM QUA) + NGÀY THỨ 60 */
                    $listDate = [1, 2, 7, 28, 61];
                    foreach ($listDate as $i) {
                        $dateCreate = date('Y-m-d', strtotime($today . ' +' . $i . 'days'));
                        $dateType = date('N', strtotime($dateCreate));
                        $totalBookingInDay = CustomerOnlineBooking::find()->where(['booking_date' => strtotime($dateCreate), 'coso_id' => $coso_id])->count();
                        $key = 1;
                        if ($i == 1) {
                            $key = 1;
                        } /* NGÀY MAI */
                        elseif (($dateType == '6' || $dateType == '7') && $i < 21) {
                            $key = 3;
                        } /* T7 - CN (3 TUẦN KẾ TIẾP) */
                        elseif ($i <= 7) {
                            $key = 4;
                        } /* TRONG 1 TUẦN KẾ TIẾP */
                        elseif ($i <= 28) {
                            $key = 5;
                        } /* TUẦN T2 ĐẾN TUẦN T4 */
                        elseif ($i == 2) {
                            $key = 2;
                        } /* NGÀY MỐT */
                        else {
                            $key = 6;
                        } /* THÁNG T2 */
                        $maxBookingByDay = max(CustomerOnlineBooking::LIST_RANDOM_BY_DAY[$key]);
                        if ($totalBookingInDay < $maxBookingByDay) {
                            /* NẾU TỔNG LỊCH (ẢO VÀ THẬT) ĐÃ ĐẶT TRONG NGÀY NHỎ HƠN MAX LỊCH ĐƯỢC BOOK TRONG NGÀY => TÍNH SỐ LỊCH CÒN THIẾU VÀ RENDER THÊM */
                            $totalBookingNeedAdd = $maxBookingByDay - $totalBookingInDay;

                            /* LẤY CÁC MÙI GIỜ CHƯA FULL LỊCH (FULL LỊCH SO VỚI CustomerOnlineBooking::MAX_BOOKING_IN_TIME - SỐ LƯỢNG KHÁCH TIẾP TỐI ĐA TRONG 1 MÚI GIỜ) */
                            $listTimeNotFull = Yii::$app->db->createCommand('SELECT tbl.* FROM (SELECT t.*, (SELECT COUNT(*) FROM dep365_customer_online_booking b WHERE b.time_id=t.id AND coso_id=' . $coso_id . ' AND b.booking_date=' . strtotime($dateCreate) . ') as countb FROM dep365_time_work t) AS tbl WHERE tbl.countb<' . CustomerOnlineBooking::MAX_BOOKING_IN_TIME)->queryAll();
                            /*return [
                                'code' => 400,
                                'msg' => 'abc',
                                'total' => $totalBookingNeedAdd,
                                'list' => $listTimeNotFull
                            ];*/
                            if ($totalBookingNeedAdd > count($listTimeNotFull)) {
                                $totalBookingNeedAdd = count($listTimeNotFull);
                            }
                            $rGetTime = array_rand($listTimeNotFull, $totalBookingNeedAdd);
                            if (!is_array($rGetTime)) {
                                $rGetTime = [$rGetTime];
                            }
                            foreach ($rGetTime as $getTime) {
                                $rUserRegister = $allCustomer[array_rand($allCustomer)];
                                $data = [
                                    'customer_id' => null,
                                    'customer_name' => $rUserRegister->forename,
                                    'customer_phone' => '*******' . rand(100, 999),
                                    'customer_type' => CustomerOnlineBooking::CUSTOMER_VITUAL,
                                    'ip' => '127.0.0.1',
                                    'booking_date' => strtotime($dateCreate),
                                    'time_id' => $listTimeNotFull[$getTime]['id'],
                                    'coso_id' => $coso_id,
                                    'status' => 1
                                ];
                                $insertBooking = BookingController::actionBookingApi($data, true);
                                if ($insertBooking['code'] != 200) {
                                    $return['fail'][] = [
                                        'data' => $data,
                                        'return' => $insertBooking
                                    ];
                                    $code = 400;
                                    $msg = 'Lỗi!';
                                } else {
                                    $return['success'][] = [
                                        'data' => $data
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }
        if ($code == 200) {
            $msg = 'Hoàn thành!';
        }
        return [
            'code' => $code,
            'msg' => $msg,
            'return' => $return
        ];
    }
}
