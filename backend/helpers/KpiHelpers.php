<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 02-Mar-19
 * Time: 10:17 AM
 */

namespace backend\helpers;

class KpiHelpers
{
    public static function gia1TuongTac(int $soTienChay, int $tongTuongTac): int
    {
        if ($tongTuongTac == 0) {
            return null;
        }
        return $soTienChay / $tongTuongTac;
    }

    public static function tongTuongTac(int $tinnhan, int $binhluan): int
    {
        return $binhluan + $tinnhan;
    }

    public static function gia1SoDienThoaiMoi(int $sotien, int $sodt): int
    {
        if ($sodt == 0) {
            return 0;
        }
        return round($sotien / $sodt, 2);
    }

    public static function kpiTargetChuanFacebook(int $giaSdtMoi, $kpiGia1Sdt)
    {
        if ($giaSdtMoi == 0) {
            return null;
        }
        $kpitargetChuanFace = ($kpiGia1Sdt / $giaSdtMoi) * 100;
        return round($kpitargetChuanFace, 2);
    }

    public static function kpiChayAdsFacebook(int $giaMotTuongTac, $kpiGia1TuongTac)
    {
        if ($giaMotTuongTac == 0) {
            return null;
        }

        $kpiChayAdsFace = ($kpiGia1TuongTac / $giaMotTuongTac) * 100;
        return round($kpiChayAdsFace, 2);
    }

    public static function maxValueScost($sotienchay, $sotienphaichay)
    {
        $defScost = (int)$sotienphaichay / 1000000; //60

        $defSotienChay = round($sotienchay / 1000000, 2);
        if ($defSotienChay > $defScost) {
            $maxValueScost = (int)($defScost - $defSotienChay);
        } else {
            $maxValueScost = 0;
        }
        return $maxValueScost;
    }

    public static function kpiPhongMarketingFacebook($maxValueScost, $kpiTargetChuanFacebook, $kpiChayAdsFacebook)
    {
        return ($kpiTargetChuanFacebook + $kpiChayAdsFacebook) / 2; // - $maxValueScost;
    }
}
