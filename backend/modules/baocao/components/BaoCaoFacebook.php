<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 22-Apr-19
 * Time: 4:59 PM
 */

namespace backend\modules\baocao\components;

use yii\base\Component;
use backend\modules\baocao\models\BaocaoChayAdsFace;

class BaoCaoFacebook extends Component
{
    public static function tongTien($from, $to, $ads = null, $loc = null, $sanPham = null)
    {
        $from = strtotime($from);
        $to = strtotime($to);
        $query = BaocaoChayAdsFace::find()->where(['between', 'ngay_chay', $from, $to]);
        if ($ads != null) {
            $query->andWhere(['created_by' => $ads]);
        }
        if ($loc != null) {
            $query->andWhere(['location_id' => $loc]);
        }
        if ($sanPham != null) {
            $query->andWhere(['san_pham' => $sanPham]);
        }

        return $query->sum('so_tien_chay');
    }

    public static function getTuongTacTong($from, $to, $ads = null, $loc = null, $sanPham = null)
    {
        $from = strtotime($from);
        $to = strtotime($to);
        $query = BaocaoChayAdsFace::find()->where(['between', 'ngay_chay', $from, $to]);
        if ($ads != null) {
            $query->andWhere(['created_by' => $ads]);
        }
        if ($loc != null) {
            $query->andWhere(['location_id' => $loc]);
        }

        if ($sanPham != null) {
            $query->andWhere(['san_pham' => $sanPham]);
        }

        return $query->sum('tuong_tac');
    }

    public static function getSdtTong($from, $to, $ads = null, $loc = null, $sanPham = null)
    {
        $from = strtotime($from);
        $to = strtotime($to);
        $query = BaocaoChayAdsFace::find()->where(['between', 'ngay_chay', $from, $to]);
        if ($ads != null) {
            $query->andWhere(['created_by' => $ads]);
        }
        if ($loc != null) {
            $query->andWhere(['location_id' => $loc]);
        }
        if ($sanPham != null) {
            $query->andWhere(['san_pham' => $sanPham]);
        }
        return $query->sum('so_dien_thoai');
    }

    public static function getCalendarTotal($from, $to, $ads = null, $loc = null, $sanPham = null)
    {
        $from = strtotime($from);
        $to = strtotime($to);
        $query = BaocaoChayAdsFace::find()->where(['between', 'ngay_chay', $from, $to]);
        if ($ads != null) {
            $query->andWhere(['created_by' => $ads]);
        }
        if ($loc != null) {
            $query->andWhere(['location_id' => $loc]);
        }
        if ($sanPham != null) {
            $query->andWhere(['san_pham' => $sanPham]);
        }
        return $query->sum('lich_hen');
    }

    public static function getDataFacebook($from, $to, $ads = null, $loc = null, $sanPham = null)
    {
        $from = strtotime($from);
        $to = strtotime($to);
        $result = [];
        $itemGiaSdt = [];
        $itemSdt = [];
        $itemNgay = [];
        $tongTien = [];
        $itemTuongTac = [];
        $itemGiaTuongTac = [];
        $itemLich = [];
        $itemGiaLich = [];

        $sdtVStuongtac = [];
        $lichVSsdt = [];
        $lichVStuongtac = [];

        $querySTC = BaocaoChayAdsFace::find()->select('ngay_chay, sum(so_tien_chay) AS STC')->where(['between', 'ngay_chay', $from, $to]);
        $queryNC = BaocaoChayAdsFace::find()->select('ngay_chay')->where(['between', 'ngay_chay', $from, $to]);

        $querySumSdt = BaocaoChayAdsFace::find()->select('ngay_chay, sum(so_dien_thoai) AS SDT')->where(['between', 'ngay_chay', $from, $to]);
        $querySumTuongTac = BaocaoChayAdsFace::find()->select('ngay_chay, sum(tuong_tac) AS TT')->where(['between', 'ngay_chay', $from, $to]);
        $querySumLich = BaocaoChayAdsFace::find()->select('ngay_chay, sum(lich_hen) AS LL')->where(['between', 'ngay_chay', $from, $to]);

        if ($ads != null) {
            $querySTC->andWhere(['created_by' => $ads]);
            $queryNC->andWhere(['created_by' => $ads]);

            $querySumSdt->andWhere(['created_by' => $ads]);
            $querySumTuongTac->andWhere(['created_by' => $ads]);
            $querySumLich->andWhere(['created_by' => $ads]);
        }

        if ($loc != null) {
            $querySTC->andWhere(['location_id' => $loc]);
            $queryNC->andWhere(['location_id' => $loc]);

            $querySumSdt->andWhere(['location_id' => $loc]);
            $querySumTuongTac->andWhere(['location_id' => $loc]);
            $querySumLich->andWhere(['location_id' => $loc]);
        }

        if ($sanPham != null) {
            $querySTC->andWhere(['san_pham' => $sanPham]);
            $queryNC->andWhere(['san_pham' => $sanPham]);

            $querySumSdt->andWhere(['san_pham' => $sanPham]);
            $querySumTuongTac->andWhere(['san_pham' => $sanPham]);
            $querySumLich->andWhere(['san_pham' => $sanPham]);
        }

        $lich = $querySumLich->groupBy('ngay_chay')->all();
        foreach ($lich as $item) {
            $itemLich[] = $item->LL;
        }


        $Sdt = $querySumSdt->groupBy('ngay_chay')->all();
        foreach ($Sdt as $item) {
            $itemSdt[] = $item->SDT;
        }

        $tuongTac = $querySumTuongTac->groupBy('ngay_chay')->all();
        foreach ($tuongTac as $item) {
            $itemTuongTac[] = $item->TT;
        }

        $moneyTong = $querySTC->groupBy('ngay_chay')->all();
        foreach ($moneyTong as $item) {
            $tongTien[] = $item->STC;
        }

        $dem = count($tongTien);
        for ($i = 0; $i < $dem; $i++) {
            /*
             * Tính giá 1 số điện thoại
             */
            if ($itemSdt[$i] != 0) {
                $itemGiaSdt[] = round(($tongTien[$i] / $itemSdt[$i]), 0);
            } else {
                $itemGiaSdt[] = 0;
            }

            /*
             * Tính giá tương tác
             */
            if ($itemTuongTac[$i] != 0) {
                $itemGiaTuongTac[] = round(($tongTien[$i] / $itemTuongTac[$i]), 0);
            } else {
                $itemGiaTuongTac[] = 0;
            }

            /*
             * Tính giá 1 lịch
             */
            if ($itemLich[$i] != 0) {
                $itemGiaLich[] = round(($tongTien[$i] / $itemLich[$i]), 0);
            } else {
                $itemGiaLich[] = 0;
            }

            /*
             * Tính phần trăm sdt với tương tác
             */
            if ($itemTuongTac[$i] != 0) {
                $sdtVStuongtac[] = round(($itemSdt[$i] / $itemTuongTac[$i]) * 100, 2);
            } else {
                $sdtVStuongtac[] = 0;
            }
            /*
             * Tính phần trăm lịch với sdt
             */
            if ($itemSdt[$i] != 0) {
                $lichVSsdt[] = round(($itemLich[$i] / $itemSdt[$i]) * 100, 2);
            } else {
                $lichVSsdt[] = 0;
            }

            /*
             * Tính phần trăm lịch với tương tác
             */
            if ($itemTuongTac[$i] != 0) {
                $lichVStuongtac[] = round(($itemLich[$i] / $itemTuongTac[$i]) * 100, 2);
            } else {
                $lichVStuongtac[] = 0;
            }
        }

        $ngayChay = $queryNC->groupBy('ngay_chay')->all();
        foreach ($ngayChay as $item) {
            $itemNgay[] = date('d-m-Y', $item->ngay_chay);
        }

        $result['money_sodienthoai'] = $itemGiaSdt;
        $result['so_dien_thoai'] = $itemSdt;
        $result['ngay_chay'] = $itemNgay;
        $result['tuong_tac'] = $itemTuongTac;
        $result['money_tuongtac'] = $itemGiaTuongTac;
        $result['lich_hen'] = $itemLich;
        $result['money_lichhen'] = $itemGiaLich;
        $result['sdt_tuongtac'] = $sdtVStuongtac;
        $result['lich_sdt'] = $lichVSsdt;
        $result['lich_tuongtac'] = $lichVStuongtac;

        return $result;
    }
}
