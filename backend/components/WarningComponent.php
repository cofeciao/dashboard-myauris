<?php

namespace backend\components;

use backend\modules\baocao\models\BaocaoChayAdsFace;
use backend\modules\clinic\models\PhongKhamLichDieuTri;
use backend\modules\customer\components\CustomerComponents;
use backend\modules\customer\models\Dep365CustomerOnlineCome;
use backend\models\CustomerModel;
use backend\models\CanhBao;
use backend\modules\setting\models\Dep365CoSo;
use common\models\User;
use DateInterval;
use DatePeriod;
use DateTime;
use yii\helpers\ArrayHelper;

class WarningComponent extends MyComponent
{
    public static function warningRate($lichdieutri_id = null)
    {
        if (!empty($lichdieutri_id)) {
            $model = PhongKhamLichDieuTri::find()->where(['id' => $lichdieutri_id, 'danh_gia' => 2])->one();
            if (!empty($model)) {
                $warning = new CanhBao();
                $warning->user_id = $model->ekip;
                $warning->parent_id = $model->customer_id;
                $warning->type = CanhBao::KHACH_HANG_DANH_GIA;
                $warning->date = time();
                $warning->save();
            }
        }
    }

    public function getWarning($page = null)
    {
        $limit = 20;
        if (empty($page)) {
            $page = 1;
        }
        $data = CanhBao::find()->where(['not in', 'type', [CanhBao::CHOT_DONE, CanhBao::CHOT_FAIL, CanhBao::LAST_UPDATE]])
            ->orderBy(['date' => SORT_DESC])->limit($limit)->offset(($page - 1) * $limit)->all();
        if (!empty($data)) {
            return $data;
        }
        return null;
    }


    public function warningCreate()
    {
        /*
         * Lấy ra ngày được check gần nhất và check từ ngày đó đến ngày hiện tại
         * Ngày mặt định được lấy là ngày 01 của tháng hiện tại
         */
        // $begin = new DateTime(date('1-m-Y'));

        $lastUpdate = CanhBao::lastUpdate();
        // var_dump($lastUpdate);die;

        $begin = new DateTime(date('d-m-Y', $lastUpdate));

        $end = new DateTime(date('d-m-Y', time()));

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);

        $nv = $this->getNhanVienOnline();
        $cosoData = $this->getCoSo();
        $listAccept = ArrayHelper::map(Dep365CustomerOnlineCome::find()->where(['accept' => Dep365CustomerOnlineCome::STATUS_ACCEPT])->all(), 'id', 'id');

        try {
            foreach ($period as $dt) {
                $dt = strtotime($dt->format('d-m-Y'));

                /*
                 * Giá tương tác chạy Ads
                 * Giá TB 1 tương tác trên 200k và dưới 120k
                 */
                $this->tbTuongtac($dt);

                /*
                 * Hiệu suất đội Online tỷ lệ đến/đặt hẹn
                 * Dưới 50% và trên 70%
                 */
                $this->hieuSuatDoiOnLine($dt, $nv);

                /*
                 * Tỷ lệ lịch/tương tác của Online X
                 * Dưới 10% và trên 12%
                 */
                $this->onlineTuongTac($dt, $nv);

                /*
                * Tỷ lệ lịch/tương tác của Đội Online
                * Dưới 8% và trên 11%
                */
                $this->doiOnlineTuongTac($dt, $nv);

                /*
                * Lưu lại cảnh báo tỉ lệ chốt nếu thấp hơn 48% hoặc cao hơn 65% cho đội Direcsale
                */
                $this->saveCustomerComeToAurisPer($dt, $cosoData);
            }

            /*
            * Cảnh báo Direcsale khi liên tục fail > 2 và done > 3
            */
            $direcsale = [];

            $failOrdone = $this->saveDoneOrFail($lastUpdate, $listAccept);

            if (!empty($failOrdone)) {
                foreach ($failOrdone as $key => $value) {
                    if (isset($value['done'])) {
                        $direcsale[$key]['fail'] = 0;
                        $done = self::getFailorDoneNotFinish(CanhBao::CHOT_DONE, $key);
                        if (!isset($direcsale[$key]['done'])) {
                            $direcsale[$key]['done'] = $done;
                            self::resetFailorDoneNotFinish(CanhBao::CHOT_DONE, $key);
                        }
                        $direcsale[$key]['done'] = $direcsale[$key]['done'] + $value['done'];
                        if ($direcsale[$key]['done'] >= 3) {
                            self::saveWarning(CanhBao::DIRECT_SALE_CHOT_THANH_CONG, $key, null, $value['time']);
                            $direcsale[$key]['done'] = 0;
                        }
                    }
                    if (isset($value['fail'])) {
                        $direcsale[$key]['done'] = 0;
                        $fail = self::getFailorDoneNotFinish(CanhBao::CHOT_FAIL, $key);
                        if (!isset($direcsale[$key]['fail'])) {
                            $direcsale[$key]['fail'] = $fail;
                            self::resetFailorDoneNotFinish(CanhBao::CHOT_FAIL, $key);
                        }
                        $direcsale[$key]['fail'] = $direcsale[$key]['fail'] + $value['fail'];
                        if ($direcsale[$key]['fail'] >= 2) {
                            self::saveWarning(CanhBao::DIRECT_SALE_CHOT_FAIL, $key, null, $value['time']);
                            $direcsale[$key]['fail'] = 0;
                        }
                    }
                }
            }
            /*
             * Lưu lại lượt chốt khách không thành công chưa < 2 hoặc thành công chưa > 3
             */
            if (!empty($direcsale)) {
                foreach ($direcsale as $key => $value) {
                    if (isset($value['done']) && $value['done'] > 0 && $value['done'] < 3) {
                        self::saveWarning(CanhBao::CHOT_DONE, $key, $value['done'], $dt);
                    }
                    if (isset($value['fail']) && $value['fail'] == 1) {
                        self::saveWarning(CanhBao::CHOT_FAIL, $key, $value['fail'], $dt);
                    }
                }
            }
        } catch (\Exception $exception) {
            echo $exception->getMessage();
            die;
        }
        CanhBao::lastUpdate(true);
    }

    protected function saveDoneOrFail($lastUpdate, $listAccept)
    {
        $data = [];

        $customerGotoAuris = CustomerModel::find()->select(['id', 'customer_come_time_to', 'directsale', 'customer_come'])
            ->where(['is not', 'customer_come', null])
            ->andWhere(['between', 'customer_come', $lastUpdate, time()])
            ->andWhere(['is not', 'directsale', null])
            ->andWhere(['is not', 'customer_come_time_to', null])
            ->groupBy(['id', 'customer_come_time_to', 'directsale', 'customer_come'])
            ->orderBy(['customer_come' => SORT_ASC]);
//        echo $customerGotoAuris->createCommand()->rawSql;die;
        $customerGotoAuris = $customerGotoAuris->all();

        if (!empty($customerGotoAuris)) {
            foreach ($customerGotoAuris as $item) {
                if (in_array($item->customer_come_time_to, $listAccept)) {
                    if (!isset($data[$item->directsale]['done'])) {
                        $data[$item->directsale]['done'] = 0;
                    }
                    $data[$item->directsale]['done']++;
                } else {
                    if (!isset($data[$item->directsale]['fail'])) {
                        $data[$item->directsale]['fail'] = 0;
                    }
                    $data[$item->directsale]['fail']++;
                }
                $data[$item->directsale]['time'] = $item->customer_come;
            }
        }
        return $data;
    }

    protected function saveCustomerComeToAurisPer($date, $cosoData)
    {
        $totalCustomerGotoAuris = CustomerComponents::getTotalCustomerGotoAuris($date, $date, 5, null, null, null, null, null, null);
        $customerDone = CustomerComponents::getCustomerDone($date, $date);

        $dt = date('d-m-Y', $date);
        $total_come = $total_done = 0;

        foreach ($cosoData as $coso) {
            $come = $done = 0;
            if (array_key_exists($coso->id, $totalCustomerGotoAuris)) {
                $come = $totalCustomerGotoAuris[$coso->id]->SDT;
            }
            if (array_key_exists($coso->id, $customerDone)) {
                $done = $customerDone[$coso->id]->SDT;
            }
            $total_come += $come;
            $total_done += $done;
        }

        if ($total_come != 0 && $total_done != 0) {
            $per = @round(($total_done / $total_come) * 100, 2);
            $warning = new CanhBao();
            if ($per < 48) {
                $warning->type = CanhBao::DIREC_SALE_CHOT_TB_DUOI_48;
            } elseif ($per > 65) {
                $warning->type = CanhBao::DIREC_SALE_CHOT_TB_TREM_65;
            }
            $warning->date = strtotime($dt);
            $warning->save();
        }
    }

    public function onlineTuongTac($date, $nv)
    {
        $data = [];

        $lichMoi = CustomerComponents::getKhachAllLichHen($date, $date, 2, null, null, null, array_keys($nv));
        foreach ($lichMoi as $item) {
            $data[$item->user_id] = $item->user;
        }
        $tuongTac = CustomerComponents::getTuongTacKhachHang($date, $date, 3, null, null, array_keys($nv));

        if (isset($tuongTac) && !empty($tuongTac)) {
            foreach ($tuongTac as $item) {
                $lich = !isset($data[$item->user_id]) ? 0 : $data[$item->user_id];
                $avg = @round(($lich / $item->NUM) * 100, 2);
                if (isset($item->NUM)) {
                    $warning = new CanhBao();
                    $warning->description = $lich . '/' . $item->NUM;
                    $warning->user_id = $item->user_id;
                    if ($avg < 10) {
                        $warning->type = CanhBao::TY_LE_LICH_TUONG_TAC_DƯƠI_10;
                    } elseif ($avg > 12) {
                        $warning->type = CanhBao::TY_LE_LICH_TUONG_TAC_TREN_12;
                    }
                    $warning->date = $date;
                    $warning->save();
                }
            }
        }
    }

    public function doiOnlineTuongTac($date, $nv)
    {
        $lichMoi = CustomerComponents::getKhachAllDatHen($date, $date, 1, null, null, null, array_keys($nv));
        $tuongTac = CustomerComponents::getTuongTacKhachHang($date, $date, 1, null, null, array_keys($nv));

        if (!empty($tuongTac)) {
            $per = @round(($lichMoi / $tuongTac) * 100, 2);
            $warning = new CanhBao();
            $warning->description = $lichMoi . '/' . $tuongTac;
            if ($per < 8) {
                $warning->type = CanhBao::DOI_ONLINE_TY_LE_TB_DUOI_8;
            } elseif ($per > 11) {
                $warning->type = CanhBao::DOI_ONLINE_TY_LE_TB_TREN_11;
            }
            $warning->date = $date;
            $warning->save();
        }
    }

    public function hieuSuatDoiOnLine($date, $nv)
    {
        $totalCustomerGotoAuris = CustomerComponents::getTotalCustomerGotoAuris($date, $date, 1, null, null, null, array_keys($nv), null, null);
        $customerBooking = CustomerComponents::getKhachAllLichHen($date, $date, 1, null, null, null, array_keys($nv));

        if ($customerBooking != 0) {
            $per = @round(($totalCustomerGotoAuris / $customerBooking) * 100, 2);
            $warning = new CanhBao();
            $warning->description = $totalCustomerGotoAuris . '/' . $customerBooking;
            if ($per < 50) {
                $warning->type = CanhBao::TY_LE_DEN_LICH_HEN_DUOI_50;
            } elseif ($per > 70) {
                $warning->type = CanhBao::TY_LE_DEN_LICH_HEN_TREN_70;
            }
            $warning->date = $date;
            $warning->save();
        }
    }

    protected function tbTuongtac($date)
    {
        $tongTien = 0;
        $itemTuongTac = 0;

        $querySTC = BaocaoChayAdsFace::find()->select('ngay_chay, sum(so_tien_chay) AS STC')->where(['between', 'ngay_chay', $date, $date]);
        $querySumTuongTac = BaocaoChayAdsFace::find()->select('ngay_chay, sum(tuong_tac) AS TT')->where(['between', 'ngay_chay', $date, $date]);
        $tuongTac = $querySumTuongTac->groupBy('ngay_chay')->all();
        $moneyTong = $querySTC->groupBy('ngay_chay')->all();

        if (isset($tuongTac[0]->TT)) {
            $itemTuongTac = $tuongTac[0]->TT;
        }

        if (isset($moneyTong[0]->STC)) {
            $tongTien = $moneyTong[0]->STC;
        }

        $itemGiaTuongTac = @round(($tongTien / $itemTuongTac), 0);

        $warning = new CanhBao();
        $warning->description = $tongTien . '/' . $itemTuongTac;
        if ($itemGiaTuongTac > 200000) {
            $warning->type = CanhBao::GIA_TB_TUONG_TAC_TREN_200;
        } elseif ($itemGiaTuongTac < 120000) {
            $warning->type = CanhBao::GIA_TB_TUONG_TAC_DUOI_120;
        }
        $warning->date = $date;
        $warning->save();
    }


    /*
     * Lấy lượt chốt khách không thành công chưa < 2 hoặc thành công chưa > 3
     */
    protected static function getFailorDoneNotFinish($type, $user_id)
    {
        $total = CanhBao::find()->select('parent_id')->where(['type' => $type, 'user_id' => $user_id])->one();
        if (!empty($total)) {
            return $total->parent_id;
        }
        return 0;
    }

    protected static function resetFailorDoneNotFinish($type, $user_id)
    {
        $data = CanhBao::findOne(['type' => $type, 'user_id' => $user_id]);
        if (!empty($data)) {
            $data->updateAttributes(['parent_id' => 0]);
        }
    }

    protected static function saveWarning($type = null, $user_id = null, $parent_id = null, $date = null, $description = null)
    {
        $warning = new CanhBao();
        $warning->type = $type;
        $warning->user_id = $user_id;
        $warning->parent_id = $parent_id;
        $warning->description = $description;
        $warning->date = $date;
        try {
            $warning->save();
        } catch (\Exception $e) {
            $e->getMessage();
            die;
        }
    }

    public function getCoSo()
    {
        $cosoData = Dep365CoSo::find()->indexBy('id')->published()->orderBy(['id' => SORT_ASC])->all();
        if (!empty($cosoData)) {
            return $cosoData;
        }
        return null;
    }

    protected function getNhanVienOnline()
    {
        return User::getNhanVienIsActiveArray();
    }
}
