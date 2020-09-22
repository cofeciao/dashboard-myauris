<?php

/**
 * Created by PhpStorm.
 * User: USER
 * Date: 27-Apr-19
 * Time: 9:38 AM
 */

namespace backend\modules\helper\models;

use backend\models\doanhthu\ThanhToanModel;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\PhongKhamDonHangTree;
use backend\modules\clinic\models\PhongKhamDonHangWOrder;
use backend\modules\clinic\models\PhongKhamDonHangWThanhToan;
use GuzzleHttp\Exception\ClientException;
use yii\base\Model;

class HelperDonHang extends Model
{
    public static function updatePhongKhamHoaDonHoanThanh($id_don_hang)
    {

        try {
            $DonHang = PhongKhamDonHang::findOne($id_don_hang);
            $DonHangTree = PhongKhamDonHangTree::findOne($id_don_hang);
            $ThanhToan = PhongKhamDonHangWThanhToan::find()
                ->where(['phong_kham_don_hang_id' => $id_don_hang])
                ->andWhere(['tam_ung' => [ThanhToanModel::THANH_TOAN, ThanhToanModel::DAT_COC]])
                ->sum('tien_thanh_toan');
            $wOrder = PhongKhamDonHangWOrder::find()
                ->where(['phong_kham_don_hang_id' => $id_don_hang])->all();

            $tien_no = $DonHang->thanh_tien - $DonHang->chiet_khau - $ThanhToan;

            foreach ( $wOrder as $mOrder) {
                if ($mOrder->chiet_khau_theo_order == 1) {
                    $tien_no = $tien_no - $mOrder->chiet_khau_order;
                } else {
                    $tien_no = $tien_no - $mOrder->chiet_khau_order*$mOrder->thanh_tien/100;
                }
            }

            if ($tien_no <= 0) {
                $DonHang->trang_thai_hoan_thanh = 1;
                $DonHang->update();
                if ($DonHangTree) {
                    $DonHangTree->trang_thai_hoan_thanh = 1;
                    $DonHangTree->update();
                }
            } else {
                $DonHang->trang_thai_hoan_thanh = 0;
                $DonHang->update();
                if ($DonHangTree) {
                    $DonHangTree->trang_thai_hoan_thanh = 0;
                    $DonHangTree->update();
                }
            }
            return true;
        } catch (ClientException $e) {
            return false;
        }
    }
}
