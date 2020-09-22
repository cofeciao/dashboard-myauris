<?php

namespace backend\modules\clinic\models\search;

use backend\models\CustomerModel;
use backend\models\doanhthu\ThanhToanModel;
use backend\modules\clinic\models\Customer;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\PhongKhamDonHangWThanhToan;
use backend\modules\clinic\models\PhongKhamLichDieuTri;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class CustomerDoanhThuSearch extends Customer
{
    const DATE_HOA_DON = 1;
    const DATE_THANH_TOAN = 2;
    const DATE_LICH_DIEU_TRI = 3;
    const DATE_THANH_TOAN_FIX = 4;

    public $button = '';
    public $from;
    public $to;
    public $type_search_date = 'date';
    public $type_search_code = 'full_name';
    public $type_search_create = 'date';
    public $type_search_appointment = 'date';
    public $type_search_payment = 'date';
    public $keyword;
    public $creation_time_from;
    public $creation_time_to;
    public $loai_thanh_toan;
    public $tam_ung;
    public $trang_thai;
    public $trang_thai_don_thanh_toan;

    public $chon_ngay_hoa_don_or_thanh_toan = self::DATE_HOA_DON;


    public static function arrayLoaiSearchNgay()
    {
        return [
            self::DATE_HOA_DON => 'Hóa đơn',
            self::DATE_THANH_TOAN => 'Thanh toán',
            self::DATE_THANH_TOAN_FIX => 'Thanh toán Lịch điều trị Fix', //  chỉ trả về nhưng đơn có tồn lại thời gian thanh toán
            // self::DATE_LICH_DIEU_TRI => 'Lịch điều trị',
        ];
    }

    public static function getlistField()
    {
        return [
            'dt_customer' => 'Khách hàng',
            'dt_order_code' => 'Mã hóa đơn',
            'dt_trang_thai' => 'Trạng thái DV',
            'dt_trang_thai_hoan_thanh' => 'Thanh toán',
            'dt_dich_vu' => 'Gói DV SP SL',
            'dt_tong_tien_truoc_chiet_khau' => 'TỔNG TIỀN TRƯỚC CK',
            'dt_tong_tien_chiet_khau' => 'TIỀN CK',
            'dt_chi_tiet_chiet_khau' => 'CHI TIẾT CK',
            'dt_tong_tien_hd' => 'TỔNG TIỀN HĐ',
            'dt_khach_hang_no' => 'KHÁCH HÀNG NỢ',
            'dt_tong_tien_thuc_thu' => 'TỔNG THỰC THU (1)',
            'dt_thuc_thu_the' => 'THỰC THU THẺ (1)',
            'dt_thuc_thu_tien_mat' => 'THỰC THU TM (1)',
            'dt_tong_thanh_toan' => 'TỔNG THANH TOÁN (2)',
            'dt_thanh_toan_the' => 'THANH TOÁN THẺ (2)',
            'dt_thanh_toan_tien_mat' => 'THANH TOÁN TM (2)',
            'dt_tong_dat_coc' => 'TỔNG ĐẶT CỌC (3)',
            'dt_dat_coc_the' => 'ĐẶT CỌC THẺ (3)',
            'dt_dat_coc_tien_mat' => 'ĐẶT CỌC TM(3)',
            'dt_tra_gop' => 'TRẢ GÓP',
            'dt_hoan_coc' => 'HOÀN CỌC (4)',
            'dt_hoan_coc_the' => 'HOÀN CỌC THẺ (4)',
            'dt_hoan_coc_tien_mat' => 'HOÀN CỌC TIỀN MẶT (4)',
            'dt_huy_dich_vu' => 'HỦY DỊCH VỤ',
            'dt_chi_tiet_giao_dich' => 'CHI TIẾT GIAO DỊCH',
            'dt_co_so' => 'CƠ SỞ',
            'dt_sale_pk' => 'Sale PK',
//            'dt_bac_si' => 'BÁC SĨ',
            'dt_bac_si_mai' => 'BS mài',
            'dt_bac_si_lap' => 'BS lắp',
            'dt_bac_si_loi' => 'BS lợi',
            'dt_bac_si_khac' => 'BS thao tác khác',
//            'dt_tro_thu' => 'Trợ thủ',
            'dt_tro_thu_mai' => 'TT mài',
            'dt_tro_thu_lap' => 'TT lắp',
            'dt_tro_thu_loi' => 'TT lợi',
            'dt_tro_thu_khac' => 'TT thao tác khác',

            'dt_created_at' => 'Ngày tạo đơn',
            'dt_created_by' => 'Người tạo',
        ];
    }

    public function rules()
    {
        return [
            [['co_so', 'permission_user', 'directsale', 'province', 'dat_hen'], 'integer'],
            [['full_name', 'phone', 'customer_code', 'sex', 'note', 'note_direct',], 'safe'],
            [['button', 'from', 'to', 'type_search_date', 'type_search_code'], 'string'],
            [['button', 'keyword', 'appointment_time_from', 'appointment_time_to', 'type_search_payment', 'creation_time_from', 'creation_time_to', 'type_search_create', 'type_search_appointment', 'customer_come_time_to', 'export_excel', 'tam_ung', 'trang_thai', 'loai_thanh_toan', 'chon_ngay_hoa_don_or_thanh_toan', 'trang_thai_don_thanh_toan'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params, &$sum_don_hang)
    {
        $query = PhongKhamDonHang::find();
        $query->joinWith(['customerOnlineHasOne']);
//        $query->joinWith(['customerOnlineHasOne','phongKhamDonHangWThanhToanHasMany']);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        $this->bodySearch($query);

        $result = $query->all();
        $data = ArrayHelper::toArray($result);
        $data = array_reverse($data); // dao chieu mang theo id
        Yii::$app->session->set("exportDoanhThu", $data);


//        if (!$this->validate()) {
//            return $dataProvider;
//        }

        $sum_don_hang = $query->sum(PhongKhamDonHang::tableName() . '.thanh_tien') - $query->sum(PhongKhamDonHang::tableName() . '.chiet_khau');

        return $dataProvider;
    }

    protected function bodySearch(&$query)
    {
        if ($this->button == '') {
//            $this->creation_time_from = date('01-11-2019');
//            $this->creation_time_to = date('08-11-2019');

            $this->creation_time_from = date('01-m-Y');
            $this->creation_time_to = date('d-m-Y');
            $this->type_search_create = 'range';
        }

        if ($this->creation_time_from != null) {
            $from = strtotime($this->creation_time_from);
            if ($this->creation_time_to == null || $this->type_search_create == 'date') {
                $this->creation_time_to = $this->creation_time_from;
            }
            $to = strtotime($this->creation_time_to) + 86399;


            // theo ngay thanh toan cuoi
            if ($this->chon_ngay_hoa_don_or_thanh_toan == self::DATE_HOA_DON) {
                $query->andFilterWhere(['BETWEEN', PhongKhamDonHang::tableName() . '.created_at', $from, $to]);
            } elseif ($this->chon_ngay_hoa_don_or_thanh_toan == self::DATE_THANH_TOAN) {
                $wThanhToan = ThanhToanModel::find();
                $wThanhToan->select('DISTINCT(' . ThanhToanModel::tableName() . '.phong_kham_don_hang_id)');
                $wThanhToan->andFilterWhere(['BETWEEN', ThanhToanModel::tableName() . '.ngay_tao', $from, $to]);
                $listDonHang = $wThanhToan->all();
                $ArrayDonHangNgayThanhToanID = [];
                foreach ($listDonHang as $item) {
                    $ArrayDonHangNgayThanhToanID[] = $item->phong_kham_don_hang_id;
                }

                $query->andFilterWhere(['in', PhongKhamDonHang::tableName() . '.id', $ArrayDonHangNgayThanhToanID]);
            } elseif ($this->chon_ngay_hoa_don_or_thanh_toan == self::DATE_LICH_DIEU_TRI) { // self::DATE_LICH_DIEU_TRI
                $mLichDieuTri = PhongKhamLichDieuTri::find();
                $mLichDieuTri->select('DISTINCT(' . PhongKhamLichDieuTri::tableName() . '.order_code)');
                $mLichDieuTri->andFilterWhere(['BETWEEN', PhongKhamLichDieuTri::tableName() . '.created_at', $from, $to]);
                $listModel = $mLichDieuTri->all();
                $aOrderCode = [];
                foreach ($listModel as $item) {
                    $aOrderCode[] = $item->order_code;
                }
                $query->andFilterWhere(['in', PhongKhamDonHang::tableName() . '.order_code', $aOrderCode]);
            } else { 
                // DATE_THANH_TOAN_FIX
                // 1. Danh sach danh sach ArrayDonHangNgayThanhToanID
                // 2. Tim don co ngay bị lố
                // 3. Kêt quả danh sach don ko có ngày bị lố
                $wThanhToan = ThanhToanModel::find();
                $wThanhToan->select('DISTINCT(' . ThanhToanModel::tableName() . '.phong_kham_don_hang_id)');
                $wThanhToan->andFilterWhere(['BETWEEN', ThanhToanModel::tableName() . '.ngay_tao', $from, $to]);
                $listDonHang = $wThanhToan->all();
                $ArrayDonHangNgayThanhToanID = [];
                foreach ($listDonHang as $item) {
                    $ArrayDonHangNgayThanhToanID[] = $item->phong_kham_don_hang_id;
                }
                // lay $ArrayThanhToanNgoai ngay tao lon hon thang chon
                $wThanhToan = ThanhToanModel::find();
                $wThanhToan->select('DISTINCT(' . ThanhToanModel::tableName() . '.phong_kham_don_hang_id)');
                $wThanhToan->andFilterWhere(['>', ThanhToanModel::tableName() . '.ngay_tao', $to]);
                $wThanhToan->andFilterWhere(['in', ThanhToanModel::tableName() . '.phong_kham_don_hang_id',$ArrayDonHangNgayThanhToanID ]);
                $listDonHang = $wThanhToan->all();
                $ArrayThanhToanNgoai = $ArrayResultNgayThanhToan = [];
                foreach ($listDonHang as $item) {
                    $ArrayThanhToanNgoai[] = $item->phong_kham_don_hang_id;
                }
                foreach ($ArrayDonHangNgayThanhToanID as $value){
                    if(!in_array($value,$ArrayThanhToanNgoai)){
                       $ArrayResultNgayThanhToan[$value] = $value;
                    }
                }

                // Lich dieu tri Fix 
                // 1. lay danh sach order_code tu ArrayResultNgayThanhToan
                // 2. Tim don có lich dieu trị hoàn thanh trễ, bị lố
                // 3. danh sach Những đơn hàng ko có lich dieu trị bị lố



                $listOrderCodeDonHang = PhongKhamDonHang::find()
                                        ->select('DISTINCT('.PhongKhamDonHang::tableName().'.order_code)')
                                        ->andFilterWhere(['in','id',$ArrayResultNgayThanhToan])
                                        ->all();
                $arrayOrderCodeDonHang = ArrayHelper::map($listOrderCodeDonHang,'order_code','order_code'); // 1


                
                $lichDieuTriBiLo = PhongKhamLichDieuTri::find()
                                    ->select('DISTINCT('.PhongKhamLichDieuTri::tableName().'.order_code)')
                                    ->andFilterWhere(['>', PhongKhamLichDieuTri::tableName() . '.time_dieu_tri', $to])
                                    ->andFilterWhere(['in', PhongKhamLichDieuTri::tableName() . '.order_code', $arrayOrderCodeDonHang])->all();

                $OrderCoderLichDieuTriBiLo = ArrayHelper::map($lichDieuTriBiLo,'order_code','order_code');

                if(count($OrderCoderLichDieuTriBiLo) > 0){ //3
                    $DonHangByLichDieuTriBiLo = PhongKhamDonHang::find()
                        ->select('DISTINCT('.PhongKhamDonHang::tableName().'.id)')
                        ->andFilterWhere(['in',PhongKhamDonHang::tableName().'.order_code',$OrderCoderLichDieuTriBiLo])->all();
                    $ArrayIdDonHangLichDieuTriBiLo = ArrayHelper::map($DonHangByLichDieuTriBiLo,'id','id');

                    if(count($ArrayIdDonHangLichDieuTriBiLo) > 0){
                        foreach($ArrayIdDonHangLichDieuTriBiLo as $Id){
                            unset($ArrayResultNgayThanhToan[$Id]);
                        }
                    }
                }
                
                $query->andFilterWhere(['in', PhongKhamDonHang::tableName() . '.id', $ArrayResultNgayThanhToan]);
            }
        }

        Yii::$app->session->set("exportDoanhThuThoiGian", [
            'from' => $this->creation_time_from,
            'to' => $this->creation_time_to,
            'type' => $this->type_search_create,
        ]);

        if ($this->co_so != null) {
//            $query->andFilterWhere([CustomerModel::tableName() . '.co_so' => $this->co_so]);
            $query->andFilterWhere([PhongKhamDonHang::tableName() . '.co_so' => $this->co_so]);
        }

        // hinh thuc thanh toan
        if ($this->tam_ung != null) {
            $wThanhToan = ThanhToanModel::find();
            $wThanhToan->select('DISTINCT(' . ThanhToanModel::tableName() . '.phong_kham_don_hang_id)');
            $wThanhToan->andFilterWhere([ThanhToanModel::tableName() . '.tam_ung' => $this->tam_ung]);
            $list = $wThanhToan->all();
            $ArrayDonHangID = [];
            foreach ($list as $item) {
                $ArrayDonHangID[] = $item->phong_kham_don_hang_id;
            }
            $query->andFilterWhere(['in', PhongKhamDonHang::tableName() . '.id', $ArrayDonHangID]);
        }


        // NGHIA NOT good performance // trang thai hoan thanh dich vu
        if ($this->trang_thai != null) {
            $ArrayDonHangLichDieuTriID = [];
            $mLichDieuTri = PhongKhamLichDieuTri::find();
            $mLichDieuTri->select('DISTINCT(' . PhongKhamLichDieuTri::tableName() . '.order_code)');
            $mLichDieuTri->andFilterWhere([PhongKhamLichDieuTri::tableName() . '.last_dieu_tri' => PhongKhamDonHang::HOAN_THANH_KHAM]);
            if ($this->trang_thai == PhongKhamDonHang::HOAN_THANH_KHAM) {
                $list = $mLichDieuTri->all();
                foreach ($list as $item) {
                    $ArrayDonHangLichDieuTriID[] = $item->order_code;
                }
                $query->andFilterWhere(['in', PhongKhamDonHang::tableName() . '.order_code', $ArrayDonHangLichDieuTriID]);
            } else {
                // CHUA_HOAN_THANH_KHAM = [0,null]
                $list = $mLichDieuTri->all();
                foreach ($list as $item) {
                    $ArrayDonHangLichDieuTriID[] = $item->order_code;
                }
                $query->andFilterWhere(['not in', PhongKhamDonHang::tableName() . '.order_code', $ArrayDonHangLichDieuTriID]);
            }
        } //

        // Search loai thanh toan theo don hang
        if ($this->loai_thanh_toan != null) {

            $ArrayDonHangLoaiThanhToanID = [];
            $wThanhToan = ThanhToanModel::find();
            $wThanhToan->select('DISTINCT(' . ThanhToanModel::tableName() . '.phong_kham_don_hang_id)');
            $wThanhToan->andFilterWhere([ThanhToanModel::tableName() . '.loai_thanh_toan' => $this->loai_thanh_toan]);
            $list = $wThanhToan->all();
            foreach ($list as $item) {
                $ArrayDonHangLoaiThanhToanID[] = $item->phong_kham_don_hang_id;
            }
            $query->andFilterWhere(['in', PhongKhamDonHang::tableName() . '.id', $ArrayDonHangLoaiThanhToanID]);
        }

        // grid filtering conditions

        if ($this->keyword != null) {
            $query->andFilterWhere(['OR',
                ['like', CustomerModel::tableName() . '.full_name', $this->keyword],
                ['like', CustomerModel::tableName() . '.forename', $this->keyword],
                ['like', CustomerModel::tableName() . '.name', $this->keyword],
                ['like', CustomerModel::tableName() . '.customer_code', $this->keyword],
                ['like', PhongKhamDonHang::tableName() . '.order_code', $this->keyword],
            ]);
        }

        if ($this->trang_thai_don_thanh_toan != null) {
            if ($this->trang_thai_don_thanh_toan == PhongKhamDonHang::HOAN_THANH_THANH_TOAN) {
                $query->andFilterWhere([PhongKhamDonHang::tableName() . '.trang_thai_hoan_thanh' => PhongKhamDonHang::HOAN_THANH_THANH_TOAN]);
            } else {
                $query->andFilterWhere([PhongKhamDonHang::tableName() . '.trang_thai_hoan_thanh' => [null, PhongKhamDonHang::CHUA_HOAN_THANH_THANH_TOAN]]);
            }
        }
    }

    // CK + tiền mặt thành tiền mặt
    // 1 la tien mac , 4 la chuyen khoan $tien_mat = false , true la the

    public function handleSumThanhToan($params, $hoan_coc)
    {
        $query = PhongKhamDonHang::find();
        $query->joinWith(['customerOnlineHasOne', 'phongKhamDonHangWThanhToanHasMany']);

        $this->load($params);

        $this->bodySearch($query);

        if ($hoan_coc == true) {
            $query->andWhere([PhongKhamDonHangWThanhToan::tableName() . '.tam_ung' => ThanhToanModel::HOAN_COC]);
        }
        return $query->sum(PhongKhamDonHangWThanhToan::tableName() . '.tien_thanh_toan');
    }

    // CK + tiền mặt thành tiền mặt
    // 1 la tien mac , 4 la chuyen khoan $tien_mat = false , true la the

    public function handleSumThucThuChiTiet($params, $tien_mat = false)
    {
        $query = PhongKhamDonHang::find();
        $query->joinWith(['customerOnlineHasOne', 'phongKhamDonHangWThanhToanHasMany']);

        $this->load($params);

        $this->bodySearch($query);

        if ($tien_mat) {
            $query->andWhere([PhongKhamDonHangWThanhToan::tableName() . '.tam_ung' => [ThanhToanModel::THANH_TOAN, ThanhToanModel::DAT_COC], PhongKhamDonHangWThanhToan::tableName() . '.loai_thanh_toan' => [1, 4]]);
        } else {
            $query->andWhere([PhongKhamDonHangWThanhToan::tableName() . '.tam_ung' => [ThanhToanModel::THANH_TOAN, ThanhToanModel::DAT_COC], PhongKhamDonHangWThanhToan::tableName() . '.loai_thanh_toan' => [2]]);
        }
        return $query->sum(PhongKhamDonHangWThanhToan::tableName() . '.tien_thanh_toan');
    }

    // CK + tiền mặt thành tiền mặt
    // 1 la tien mac , 4 la chuyen khoan $tien_mat = false , true la the

    public function handleSumThanhToanChiTiet($params, $tien_mat = false)
    {
        $query = PhongKhamDonHang::find();
        $query->joinWith(['customerOnlineHasOne', 'phongKhamDonHangWThanhToanHasMany']);

        $this->load($params);

        $this->bodySearch($query);

        if ($tien_mat) {
            $query->andWhere([PhongKhamDonHangWThanhToan::tableName() . '.tam_ung' => ThanhToanModel::THANH_TOAN, PhongKhamDonHangWThanhToan::tableName() . '.loai_thanh_toan' => [1, 4]]);
        } else {
            $query->andWhere([PhongKhamDonHangWThanhToan::tableName() . '.tam_ung' => ThanhToanModel::THANH_TOAN, PhongKhamDonHangWThanhToan::tableName() . '.loai_thanh_toan' => [2]]);
        }
        return $query->sum(PhongKhamDonHangWThanhToan::tableName() . '.tien_thanh_toan');
    }

    public function handleSumDatCocChiTiet($params, $tien_mat = false)
    {
        $query = PhongKhamDonHang::find();
        $query->joinWith(['customerOnlineHasOne', 'phongKhamDonHangWThanhToanHasMany']);

        $this->load($params);

        $this->bodySearch($query);

        if ($tien_mat) {
            $query->andWhere([PhongKhamDonHangWThanhToan::tableName() . '.tam_ung' => ThanhToanModel::THANH_TOAN, PhongKhamDonHangWThanhToan::tableName() . '.loai_thanh_toan' => [1, 4]]);
        } else {
            $query->andWhere([PhongKhamDonHangWThanhToan::tableName() . '.tam_ung' => ThanhToanModel::THANH_TOAN, PhongKhamDonHangWThanhToan::tableName() . '.loai_thanh_toan' => [2]]);
        }
        return $query->sum(PhongKhamDonHangWThanhToan::tableName() . '.tien_thanh_toan');
    }

    public function handleSumHoanCocChiTiet($params, $tien_mat = false)
    {
        $query = PhongKhamDonHang::find();
        $query->joinWith(['customerOnlineHasOne', 'phongKhamDonHangWThanhToanHasMany']);

        $this->load($params);

        $this->bodySearch($query);

        if ($tien_mat) {
            $query->andWhere([PhongKhamDonHangWThanhToan::tableName() . '.tam_ung' => ThanhToanModel::HOAN_COC, PhongKhamDonHangWThanhToan::tableName() . '.loai_thanh_toan' => [1, 4]]);
        } else {
            $query->andWhere([PhongKhamDonHangWThanhToan::tableName() . '.tam_ung' => ThanhToanModel::HOAN_COC, PhongKhamDonHangWThanhToan::tableName() . '.loai_thanh_toan' => [2]]);
        }
        return $query->sum(PhongKhamDonHangWThanhToan::tableName() . '.tien_thanh_toan');
    }

    public function handleSumHoanCoc($params)
    {
        $query = PhongKhamDonHang::find();
        $query->joinWith(['customerOnlineHasOne', 'phongKhamDonHangWThanhToanHasMany']);

        $this->load($params);

        $this->bodySearch($query);

        $query->andWhere([PhongKhamDonHangWThanhToan::tableName() . '.tam_ung' => ThanhToanModel::HOAN_COC]);

        return $query->sum(PhongKhamDonHangWThanhToan::tableName() . '.tien_thanh_toan');
    }

    public function handleSumChietKhau($params)
    {
        $query = PhongKhamDonHang::find();
        $query->joinWith(['customerOnlineHasOne']);

        $this->load($params);

        $this->bodySearch($query);

        return $query->sum(PhongKhamDonHang::tableName() . '.chiet_khau');
    }

    // tra gop

    public function handleSumThanhTienTruocChietKhau($params)
    {
        $query = PhongKhamDonHang::find();
        $query->joinWith(['customerOnlineHasOne']);

        $this->load($params);

        $this->bodySearch($query);

        return $query->sum(PhongKhamDonHang::tableName() . '.thanh_tien');
    }

    public function handleSumTraGop($params)
    {
        $query = PhongKhamDonHang::find();
        $query->joinWith(['customerOnlineHasOne', 'phongKhamDonHangWThanhToanHasMany']);

        $this->load($params);

        $this->bodySearch($query);

        $query->andWhere([PhongKhamDonHangWThanhToan::tableName() . '.loai_thanh_toan' => [3]]);

        return $query->sum(PhongKhamDonHangWThanhToan::tableName() . '.tien_thanh_toan');
    }
}
