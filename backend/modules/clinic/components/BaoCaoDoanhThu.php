<?php
/**
 * Created by PhpStorm.
 * User: USER
 * Date: 16-May-19
 * Time: 10:27 AM
 */

namespace backend\modules\clinic\components;

use backend\modules\baocao\models\doanhthu\BaoCaoDoanhThuModel;
use yii\base\Component;

class BaoCaoDoanhThu extends Component
{
    /*
     * Tính tổng tiền thanh toán theo ngày, theo 1 thời gian nào đó
     * $from: Thời gian bắt đầu
     * $to: Thời gian kết thúc, nếu null thì tính thời gian trong ngày
     * $category: Loại thanh toán: Tiền mặt, CK ...
     * $tamung: Khách hàng tạm ứng hay là thanh toán
     * $cs: Thuộc cơ sở nào
     * $type: Dạng dữ liệu trả về
     *      1: Trả về tổng số tiền
     */
    public static function TongDoanhThuTheoNgay($type, $from, $to = null, $category = null, $tamung = null, $cs = null)
    {
        $query = BaoCaoDoanhThuModel::find();
        if ($cs != null) {
            $query->joinWith(['donHangHasOne']);
            $query->andWhere(['phong_kham_don_hang.co_so' => $cs]);
        }

        if ($to == null) {
            $to = $from + 86400;
        }
        $query->andWhere(['between', 'phong_kham_don_hang_w_thanh_toan.created_at', $from, $to]);

        if ($category != null) {
            $query->andWhere(['phong_kham_don_hang_w_thanh_toan.loai_thanh_toan' => $category]);
        }

        if ($tamung != null) {
            $query->andWhere(['phong_kham_don_hang_w_thanh_toan.tam_ung' => $tamung]);
        }

        if ($type == 1) {
            return $query->sum('phong_kham_don_hang_w_thanh_toan.tien_thanh_toan');
        }
    }
}
