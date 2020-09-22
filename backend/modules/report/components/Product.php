<?php

namespace backend\modules\report\components;

use backend\models\doanhthu\DonHangModel;
use backend\modules\baocao\models\BaocaoLocation;
use backend\modules\clinic\models\PhongKhamDonHangWOrder;
use backend\modules\clinic\models\PhongKhamKhuyenMai;
use backend\modules\clinic\models\PhongKhamLichDieuTri;
use backend\modules\clinic\models\PhongKhamSanPham;
use backend\modules\customer\models\Dep365CustomerOnline;
use yii\base\Component;

class Product extends Component
{
    public static function getDataOnline($from, $to, $direct_sale = null, $id_coso = null, $id_page = null, $id_phongkhamdichvu = null, $id_trangthaidichvu = null)
    {
        //test
//        $from = "1-1-2018";
//        $to   = "30-10-2019";
        $from = strtotime($from);
        $to = strtotime($to);

        $queryNgaytaoCurency = PhongKhamDonHangWOrder::find()
            ->select(['dich_vu', 'san_pham', 'so_luong', 'chiet_khau_order', 'chiet_khau_theo_order', PhongKhamDonHangWOrder::tableName() . '.thanh_tien'])
            ->where(['between', PhongKhamDonHangWOrder::tableName() . '.created_at', $from, $to])
            ->andWhere(["=", "chiet_khau_theo_order", PhongKhamKhuyenMai::TYPE_CURENCY]);

        // Add Search
        $queryNgaytaoCurency->joinWith(['donHangModelHasOne']);
        if ($direct_sale) {
            $queryNgaytaoCurency->andWhere(["=", DonHangModel::tableName() . ".direct_sale_id", $direct_sale]);
        }
        if ($id_coso) {
            $queryNgaytaoCurency->andWhere(["=", DonHangModel::tableName() . ".co_so", $id_coso]);
        }
        if ($id_phongkhamdichvu) {
            $queryNgaytaoCurency->andWhere(["=", PhongKhamDonHangWOrder::tableName() . ".dich_vu", $id_phongkhamdichvu]);
        }
        if ($id_page) {
            $queryNgaytaoCurency->joinWith(['customerOnlineHasOne']);
            $queryNgaytaoCurency->andWhere(["=", Dep365CustomerOnline::tableName() . ".face_fanpage", $id_page]);
        }

        if ($id_trangthaidichvu != null) {
            $queryNgaytaoCurency->joinWith(['phongKhamLichDieuTriHasOne']);
            $queryNgaytaoCurency->andWhere(['=', PhongKhamLichDieuTri::tableName() . '.last_dieu_tri', $id_trangthaidichvu]);
        }

        $dataAll = $queryNgaytaoCurency->all();
        $dataCURENCY = [];
        foreach ($dataAll as $key => $value) {
            if (isset($dataCURENCY[$value->san_pham])) {
                $dataCURENCY[$value->san_pham]['so_luong'] += $value->so_luong;
                $dataCURENCY[$value->san_pham]['tien'] += $value->thanh_tien - $value->chiet_khau_order;
            } else {
                $dataCURENCY[$value->san_pham]['so_luong'] = (integer)$value->so_luong;
                $dataCURENCY[$value->san_pham]['tien'] = $value->thanh_tien - $value->chiet_khau_order;
            }
        }

        //
        $queryNgaytaoPercent = PhongKhamDonHangWOrder::find()
            ->select(['dich_vu', 'san_pham', 'so_luong', 'chiet_khau_order', 'chiet_khau_theo_order', PhongKhamDonHangWOrder::tableName() . '.thanh_tien'])
            ->where(['between', PhongKhamDonHangWOrder::tableName() . '.created_at', $from, $to])
            ->andWhere(["=", "chiet_khau_theo_order", PhongKhamKhuyenMai::TYPE_PERCENT]);

        // Add Search
        $queryNgaytaoPercent->joinWith(['donHangModelHasOne']);
        if ($direct_sale) {
            $queryNgaytaoPercent->andWhere(["=", DonHangModel::tableName() . ".direct_sale_id", $direct_sale]);
        }
        if ($id_coso) {
            $queryNgaytaoPercent->andWhere(["=", DonHangModel::tableName() . ".co_so", $id_coso]);
        }
        if ($id_phongkhamdichvu) {
            $queryNgaytaoPercent->andWhere(["=", PhongKhamDonHangWOrder::tableName() . ".dich_vu", $id_phongkhamdichvu]);
        }
        if ($id_page) {
            $queryNgaytaoPercent->joinWith(['customerOnlineHasOne']);
            $queryNgaytaoPercent->andWhere(["=", Dep365CustomerOnline::tableName() . ".face_fanpage", $id_page]);
        }

        if ($id_trangthaidichvu != null) {
            $queryNgaytaoCurency->joinWith(['phongKhamLichDieuTriHasOne']);
            $queryNgaytaoCurency->andWhere(['=', PhongKhamLichDieuTri::tableName() . '.last_dieu_tri', $id_trangthaidichvu]);
        }

        $dataAll = $queryNgaytaoPercent->all();
        foreach ($dataAll as $key => $value) {
            if (isset($dataCURENCY[$value->san_pham])) {
                $dataCURENCY[$value->san_pham]['so_luong'] += $value->so_luong;
                $dataCURENCY[$value->san_pham]['tien'] += $value->thanh_tien * (100 - $value->chiet_khau_order) / 100;
            } else {
                $dataCURENCY[$value->san_pham]['so_luong'] = (integer)$value->so_luong;
                $dataCURENCY[$value->san_pham]['tien'] = $value->thanh_tien * (100 - $value->chiet_khau_order) / 100;
            }
        }
        $aSanPhamID = [];
        $dataQuery = [];
        foreach ($dataCURENCY as $key => $value) {
            $dataQuery[] = [
                'san_pham' => $key,
                'so_luong' => $value['so_luong'],
                'tien' => $value['tien'],
            ];
            $aSanPhamID[] = $key;
        }
        $listNameSanPham = [];
        $alistSanPham = PhongKhamSanPham::getListSanPhamByListId($aSanPhamID);
        foreach ($alistSanPham as $key => $value) {
            $listNameSanPham[] = [
                'san_pham' => $key,
                'name' => $value
            ];
        }

        return [
            'dataQuery' => $dataQuery,
            'listNameSanPham' => $listNameSanPham
        ];
    }

    /*
     * Lấy ra danh sách các tỉnh thành
     */
    protected static function getLoc($id_location)
    {
        return BaocaoLocation::find()->where(['id' => $id_location])->one();
    }
}
