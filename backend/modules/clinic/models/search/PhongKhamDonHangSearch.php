<?php

namespace backend\modules\clinic\models\search;

use backend\models\CustomerModel;
use backend\modules\clinic\models\Clinic;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\PhongKhamDonHangWOrder;
use backend\modules\clinic\models\PhongKhamLichDieuTri;
use backend\modules\clinic\models\PhongKhamDonHangWThanhToan;
use backend\modules\user\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use Yii;

/**
 * PhongKhamDonHangSearch represents the model behind the search form of `backend\modules\clinic\models\PhongKhamDonHang`.
 */
class PhongKhamDonHangSearch extends PhongKhamDonHang
{
    public $button;
    public $keyword;
    public $from;
    public $to;
    public $type_search_date = 'date';
    public $loc_theo = '';
    public $creation_time_from;
    public $creation_time_to;
    public $payment_time_from;
    public $payment_time_to;
    public $type_search_create = 'date';
    public $type_search_payment = 'date';
    public $trang_thai;
    public $trang_thai_don_thanh_toan;
    public $id_dich_vu;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['direct_sale_id', 'co_so', 'tu_van_vien', 'loc_theo'], 'integer'],
            [['order_code', 'customer_order', 'chiet_khau', 'thanh_toan', 'thanh_tien', 'name', 'created_at', 'updated_at', 'created_by', 'updated_by', 'phone_number', 'clinic_code', 'id', 'customer_id', 'customer_order', 'co_so', 'ngay_tao', 'khuyen_mai', 'trang_thai_hoan_thanh', 'trang_thai', 'trang_thai_don_thanh_toan'], 'safe'],
            [['from', 'to', 'type_search_date'], 'string'],
            [['button', 'keyword', 'payment_time_from', 'payment_time_to', 'creation_time_from', 'creation_time_to', 'type_search_create', 'type_search_payment', 'customer_come_time_to', 'directsale', 'id_dich_vu'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $customer_id = null)
    {
        $user = new User();
        $roleUser = $user->getRoleName(\Yii::$app->user->id);

        $co_so = $user->getCoso(\Yii::$app->user->id);
        $this->co_so = $co_so->permission_coso;

        /*
         * NOTE SQL:
         *
         * SELECT tbl.* FROM (
         *    SELECT
         *        `phong_kham_don_hang`.*,
         *        (SELECT SUM(phong_kham_don_hang_w_order.thanh_tien) FROM phong_kham_don_hang_w_order WHERE phong_kham_don_hang_w_order.phong_kham_don_hang_id=phong_kham_don_hang.id) AS dh_thanh_tien,
         *        (SELECT SUM(phong_kham_don_hang_w_thanh_toan.tien_thanh_toan) FROM phong_kham_don_hang_w_thanh_toan WHERE phong_kham_don_hang_w_thanh_toan.phong_kham_don_hang_id=phong_kham_don_hang.id AND phong_kham_don_hang_w_thanh_toan.tam_ung='1') AS dat_coc,
         *        (SELECT SUM(phong_kham_don_hang_w_thanh_toan.tien_thanh_toan) FROM phong_kham_don_hang_w_thanh_toan WHERE phong_kham_don_hang_w_thanh_toan.phong_kham_don_hang_id=phong_kham_don_hang.id AND phong_kham_don_hang_w_thanh_toan.tam_ung='0') AS thanh_toan,
         *        (
         *            IF((SELECT SUM(phong_kham_don_hang_w_order.thanh_tien) FROM phong_kham_don_hang_w_order WHERE phong_kham_don_hang_w_order.phong_kham_don_hang_id=phong_kham_don_hang.id) IS NULL, 0, (SELECT SUM(phong_kham_don_hang_w_order.thanh_tien) FROM phong_kham_don_hang_w_order WHERE phong_kham_don_hang_w_order.phong_kham_don_hang_id=phong_kham_don_hang.id)) -
         *            (
         *                chiet_khau +
         *                IF((SELECT SUM(phong_kham_don_hang_w_thanh_toan.tien_thanh_toan) FROM phong_kham_don_hang_w_thanh_toan WHERE phong_kham_don_hang_w_thanh_toan.phong_kham_don_hang_id=phong_kham_don_hang.id AND phong_kham_don_hang_w_thanh_toan.tam_ung='1') IS NULL, 0, (SELECT SUM(phong_kham_don_hang_w_thanh_toan.tien_thanh_toan) FROM phong_kham_don_hang_w_thanh_toan WHERE phong_kham_don_hang_w_thanh_toan.phong_kham_don_hang_id=phong_kham_don_hang.id AND phong_kham_don_hang_w_thanh_toan.tam_ung='1')) +
         *                IF((SELECT SUM(phong_kham_don_hang_w_thanh_toan.tien_thanh_toan) FROM phong_kham_don_hang_w_thanh_toan WHERE phong_kham_don_hang_w_thanh_toan.phong_kham_don_hang_id=phong_kham_don_hang.id AND phong_kham_don_hang_w_thanh_toan.tam_ung='0') IS NULL, 0, (SELECT SUM(phong_kham_don_hang_w_thanh_toan.tien_thanh_toan) FROM phong_kham_don_hang_w_thanh_toan WHERE phong_kham_don_hang_w_thanh_toan.phong_kham_don_hang_id=phong_kham_don_hang.id AND phong_kham_don_hang_w_thanh_toan.tam_ung='0'))
         *            )
         *        ) AS con_no,
         *    FROM
         *        `phong_kham_don_hang`
         *        LEFT JOIN `dep365_customer_online` ON `phong_kham_don_hang`.`customer_id` = `dep365_customer_online`.`id`
         *        LEFT JOIN `phong_kham_direct_sale` ON `phong_kham_don_hang`.`direct_sale_id` = `phong_kham_direct_sale`.`id`
         *        LEFT JOIN `dep365_co_so` ON `phong_kham_don_hang`.`co_so` = `dep365_co_so`.`id`
         *        LEFT JOIN `phong_kham_don_hang_w_order` ON phong_kham_don_hang_w_order.phong_kham_don_hang_id=phong_kham_don_hang.id
         *    WHERE
         *        (SELECT COUNT(*) FROM phong_kham_don_hang_w_thanh_toan WHERE phong_kham_don_hang_w_thanh_toan.tam_ung='2')
         *    GROUP BY `phong_kham_don_hang`.`id`
         * ) AS tbl
        */
        $query = PhongKhamDonHang::find()
            ->select(
                "
                phong_kham_don_hang.id,
                phong_kham_don_hang.chiet_khau,
                phong_kham_don_hang.direct_sale_id,
                phong_kham_don_hang.created_at,
                phong_kham_don_hang.created_by,
                phong_kham_don_hang.co_so,
                phong_kham_don_hang.customer_id,
                phong_kham_don_hang.order_code,
                phong_kham_don_hang.confirm
            "
            )
            ->joinWith(['clinicHasOne', 'coSoHasOne'])
            ->leftJoin(PhongKhamDonHangWThanhToan::tableName(), PhongKhamDonHangWThanhToan::tableName() . '.phong_kham_don_hang_id=' . PhongKhamDonHang::tableName() . '.id')
            //            ->leftJoin(PhongKhamDonHangWOrder::tableName(), PhongKhamDonHangWOrder::tableName() . '.phong_kham_don_hang_id=' . PhongKhamDonHang::tableName() . '.id')
            ->groupBy(PhongKhamDonHang::tableName() . '.id');

        if ($roleUser == User::USER_DIRECT_SALE) {
            $query->andFilterWhere(['dep365_customer_online.directsale' => \Yii::$app->user->id]);
        }
        /*if ($roleUser == User::USER_LE_TAN) {
            $co_so = $user->getCoso(\Yii::$app->user->id);
            $query->andFilterWhere([PhongKhamDonHang::tableName() . '.co_so' => $co_so->permission_coso]);
        }*/
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $query->joinWith(['clinicHasOne', 'coSoHasOne']);

        $this->load($params);

        $customer = null;
        if ($customer_id != null) {
            $customer = Clinic::find()->where(['id' => $customer_id])->one();
        }

        if ($this->button == '' && $customer == null) {
            $this->type_search_create = 'range';
            $this->creation_time_from = date('01-m-Y');
            $this->creation_time_to = date('d-m-Y');
        }
        if ($this->button == 2) {
            $this->creation_time_from = date('d-m-Y', strtotime(date('d-m-Y') . ' -1 days'));
            $this->type_search_create = 'date';
        }
        if ($this->button == 3) {
            $this->creation_time_from = date('d-m-Y', strtotime(date('d-m-Y')));
            $this->type_search_create = 'date';
        }

        if ($customer != null) {
            $query->andWhere([PhongKhamDonHang::tableName() . '.customer_id' => $customer_id]);
            $this->keyword = $customer->customer_code;
            $this->direct_sale_id = $customer->directsale;
            $this->tu_van_vien = $customer->permission_user;
        }

        //        if (!$this->validate()) {
        //            return $dataProvider;
        //        }

        /* Search ngày tạo */
        if (isset($this->type_search_create)) {
            if ($this->type_search_create == 'date') {
                if (isset($this->creation_time_from) && $this->creation_time_from != null) {
                    $from = strtotime($this->creation_time_from);
                    $to = strtotime($this->creation_time_from) + 86399;
                    $query->andFilterWhere(['>=', PhongKhamDonHang::tableName() . '.created_at', $from]);
                    $query->andFilterWhere(['<=', PhongKhamDonHang::tableName() . '.created_at', $to]);
                }
            } else {
                if (
                    isset($this->creation_time_from) && isset($this->creation_time_to) &&
                    $this->creation_time_from != null && $this->creation_time_to != null
                ) {
                    $from = strtotime($this->creation_time_from);
                    $to = strtotime($this->creation_time_to) + 86399;
                    $query->andFilterWhere(['>=', PhongKhamDonHang::tableName() . '.created_at', $from]);
                    $query->andFilterWhere(['<=', PhongKhamDonHang::tableName() . '.created_at', $to]);
                }
            }
        }

        /* Search ngày thanh toán */
        if (isset($this->type_search_payment)) {
            if ($this->type_search_payment == 'date') {
                if (isset($this->payment_time_from) && $this->payment_time_from != null) {
                    $from = strtotime($this->payment_time_from);
                    $to = strtotime($this->payment_time_from) + 86399;
                    $query->andFilterWhere(['>=', PhongKhamDonHangWThanhToan::tableName() . '.ngay_tao', $from]);
                    $query->andFilterWhere(['<=', PhongKhamDonHangWThanhToan::tableName() . '.ngay_tao', $to]);
                }
            } else {
                if (
                    isset($this->payment_time_from) && isset($this->payment_time_to) &&
                    $this->payment_time_from != null && $this->payment_time_to != null
                ) {
                    $from = strtotime($this->payment_time_from);
                    $to = strtotime($this->payment_time_to) + 86399;
                    $query->andFilterWhere(['>=', PhongKhamDonHangWThanhToan::tableName() . '.ngay_tao', $from]);
                    $query->andFilterWhere(['<=', PhongKhamDonHangWThanhToan::tableName() . '.ngay_tao', $to]);
                }
            }
        }

        /* Search name, phone, code */
        if (isset($this->keyword) && $this->keyword != null) {
            $this->keyword = trim($this->keyword);
            $this->keyword = preg_replace('/\s+/', ' ', $this->keyword);
            $query->andFilterWhere([
                'or',
                ['like', CustomerModel::tableName() . '.full_name', $this->keyword],
                ['like', CustomerModel::tableName() . '.forename', $this->keyword],
                ['like', CustomerModel::tableName() . '.name', $this->keyword],
                ['like', CustomerModel::tableName() . '.phone', $this->keyword],
                ['like', CustomerModel::tableName() . '.customer_code', $this->keyword],
                ['like', PhongKhamDonHang::tableName() . '.order_code', $this->keyword],
            ]);
        }

        // grid filtering conditions
        if (isset($this->direct_sale_id) && $this->direct_sale_id != null) {
            $query->andFilterWhere([PhongKhamDonHang::tableName() . '.direct_sale_id' => $this->direct_sale_id]);
        }

        if (isset($this->tu_van_vien) && $this->tu_van_vien != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.permission_user' => $this->tu_van_vien]);
        }

        if (isset($this->co_so) && $this->co_so != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.co_so' => $this->co_so]);
        }

        if (isset($this->id_dich_vu) && $this->id_dich_vu != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.id_dich_vu' => $this->id_dich_vu]);
        }

        return $dataProvider;
    }




    // Pham Thanh Nghia
    // clinic/clinic-check/index
    // Search  for Clinic Check
    public function searchClinicCheck($params)
    {
        $user = new User();
        $roleUser = $user->getRoleName(\Yii::$app->user->id);

        // $co_so = $user->getCoso(\Yii::$app->user->id);
        // $this->co_so = $co_so->permission_coso;



        $query = PhongKhamDonHang::find()
            ->select(
                "
                phong_kham_don_hang.id,
                phong_kham_don_hang.chiet_khau,
                phong_kham_don_hang.direct_sale_id,
                phong_kham_don_hang.created_at,
                phong_kham_don_hang.created_by,
                phong_kham_don_hang.co_so,
                phong_kham_don_hang.customer_id,
                phong_kham_don_hang.order_code,
                phong_kham_don_hang.thanh_tien,
                phong_kham_don_hang.chiet_khau,
                phong_kham_don_hang.trang_thai_hoan_thanh
            "
            )
            ->joinWith(['clinicHasOne', 'coSoHasOne', 'phongKhamLichDieuTriHasMany'])
            ->groupBy(PhongKhamDonHang::tableName() . '.id');

        if (in_array($roleUser, [User::USER_DIRECT_SALE, User::USER_MANAGER_DIRECT_SALE])) {
            $query->andFilterWhere(['dep365_customer_online.directsale' => \Yii::$app->user->id]);
        }

        // if ($roleUser == User::USER_BAC_SI) {
        //     $query->andFilterWhere([ PhongKhamLichDieuTri::tableName() . '.ekip' => \Yii::$app->user->id]);
        // }
        /*if ($roleUser == User::USER_LE_TAN) {
            $co_so = $user->getCoso(\Yii::$app->user->id);
            $query->andFilterWhere([PhongKhamDonHang::tableName() . '.co_so' => $co_so->permission_coso]);
        }*/
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $query->joinWith(['clinicHasOne', 'coSoHasOne']);


        $this->load($params);

        $customer = null;


        if ($this->button == '' && $customer == null) {
            $this->type_search_create = 'range';
            $this->creation_time_from = date('01-m-Y');
            $this->creation_time_to = date('d-m-Y');
        }
        if ($this->button == 2) {
            $this->creation_time_from = date('d-m-Y', strtotime(date('d-m-Y') . ' -1 days'));
            $this->type_search_create = 'date';
        }
        if ($this->button == 3) {
            $this->creation_time_from = date('d-m-Y', strtotime(date('d-m-Y')));
            $this->type_search_create = 'date';
        }


        //        if (!$this->validate()) {
        //            return $dataProvider;
        //        }

        /* Search ngày tạo */
        if (isset($this->type_search_create)) {
            if ($this->type_search_create == 'date') {
                if (isset($this->creation_time_from) && $this->creation_time_from != null) {
                    $from = strtotime($this->creation_time_from);
                    $to = strtotime($this->creation_time_from) + 86399;
                    $query->andFilterWhere(['>=', Clinic::tableName() . '.customer_come', $from]);
                    $query->andFilterWhere(['<=', Clinic::tableName() . '.customer_come', $to]);
                    // $query->andFilterWhere(['>=', PhongKhamDonHang::tableName() . '.ngay_tao', $from]);
                    // $query->andFilterWhere(['<=', PhongKhamDonHang::tableName() . '.ngay_tao', $to]);
                }
            } else {
                if (
                    isset($this->creation_time_from) && isset($this->creation_time_to) &&
                    $this->creation_time_from != null && $this->creation_time_to != null
                ) {
                    $from = strtotime($this->creation_time_from);
                    $to = strtotime($this->creation_time_to) + 86399;
                    $query->andFilterWhere(['>=', Clinic::tableName() . '.customer_come', $from]);
                    $query->andFilterWhere(['<=', Clinic::tableName() . '.customer_come', $to]);
                    // $query->andFilterWhere(['>=', PhongKhamDonHang::tableName() . '.ngay_tao', $from]);
                    // $query->andFilterWhere(['<=', PhongKhamDonHang::tableName() . '.ngay_tao', $to]);
                }
            }
        }

        /* Search ngày thanh toán */


        /* Search name, phone, code */
        if (isset($this->keyword) && $this->keyword != null) {
            $this->keyword = trim($this->keyword);
            $this->keyword = preg_replace('/\s+/', ' ', $this->keyword);
            $query->andFilterWhere([
                'or',
                ['like', CustomerModel::tableName() . '.full_name', $this->keyword],
                ['like', CustomerModel::tableName() . '.forename', $this->keyword],
                ['like', CustomerModel::tableName() . '.name', $this->keyword],
                ['like', CustomerModel::tableName() . '.phone', $this->keyword],
                ['like', CustomerModel::tableName() . '.customer_code', $this->keyword],
                ['like', PhongKhamDonHang::tableName() . '.order_code', $this->keyword],
            ]);
        }

        // grid filtering conditions
        if (isset($this->direct_sale_id) && $this->direct_sale_id != null) {
            $query->andFilterWhere([PhongKhamDonHang::tableName() . '.direct_sale_id' => $this->direct_sale_id]);
        }

        if (isset($this->tu_van_vien) && $this->tu_van_vien != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.permission_user' => $this->tu_van_vien]);
        }

        if (isset($this->co_so) && $this->co_so != null) {
            $query->andFilterWhere([CustomerModel::tableName() . '.co_so' => $this->co_so]);
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

        return $dataProvider;
    }
}
