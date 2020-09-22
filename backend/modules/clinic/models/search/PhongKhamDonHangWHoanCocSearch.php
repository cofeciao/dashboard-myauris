<?php

namespace backend\modules\clinic\models\search;

use backend\models\CustomerModel;
use backend\models\doanhthu\DonHangModel;
use backend\models\doanhthu\ThanhToanModel;
use backend\modules\clinic\models\PhongKhamDonHang;
use backend\modules\clinic\models\PhongKhamDonHangWThanhToan;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PhongKhamDonHangWHoanCocSearch represents the model behind the search form of `backend\modules\clinic\models\PhongKhamDonHangWThanhToan`.
 */
class PhongKhamDonHangWHoanCocSearch extends PhongKhamDonHangWThanhToan
{
    public $button = 2;
    public $keyword;
    public $type_search_create = 'date';
    public $creation_time_from;
    public $creation_time_to;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'phong_kham_don_hang_id', 'tien_thanh_toan', 'loai_thanh_toan', 'tam_ung', 'status', 'ngay_tao', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['accept_hoan_coc', 'button', 'keyword', 'creation_time_from', 'creation_time_to', 'type_search_create'], 'safe'],
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
    public function search($params, $order = null)
    {
        $query = PhongKhamDonHangWThanhToan::find()
                ->select([
//                    "phong_kham_don_hang.*",
                    "phong_kham_don_hang_w_thanh_toan.*",
                    "(SELECT SUM(" . PhongKhamDonHangWThanhToan::tableName() . ".tien_thanh_toan) FROM " . PhongKhamDonHangWThanhToan::tableName() . " WHERE " . PhongKhamDonHangWThanhToan::tableName() . ".phong_kham_don_hang_id=" . PhongKhamDonHang::tableName() . ".id AND " . PhongKhamDonHangWThanhToan::tableName() . ".tam_ung='" . ThanhToanModel::DAT_COC . "') AS dat_coc",
                ])
                ->where([PhongKhamDonHangWThanhToan::tableName() . '.tam_ung' => ThanhToanModel::HOAN_COC]);
//                ->groupBy(PhongKhamDonHang::tableName() . '.id');
//
        if ($order != null) {
            $query->andWhere(['phong_kham_don_hang_id' => $order->primaryKey]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);
        $query->joinWith(['loaiThanhToanHasOne', 'donHangHasOne', 'customerHasOne']);

        $this->load($params);

        if ($this->button == '') {
            $this->creation_time_from = date('d-m-Y');
            $this->creation_time_to = date('d-m-Y');
        }

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

        // grid filtering conditions
//        $query->andFilterWhere([
//            'id' => $this->id,
//            'customer_id' => $this->customer_id,
//            'phong_kham_don_hang_id' => $this->phong_kham_don_hang_id,
//            'tien_thanh_toan' => $this->tien_thanh_toan,
//            'loai_thanh_toan' => $this->loai_thanh_toan,
//            'tam_ung' => $this->tam_ung,
//            'status' => $this->status,
//            'ngay_tao' => $this->ngay_tao,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
//            'created_by' => $this->created_by,
//            'updated_by' => $this->updated_by,
//        ]);
//
//        $query->andFilterWhere(['like', 'accept_hoan_coc', $this->accept_hoan_coc]);

        /* Search ngày tạo */
        if (isset($this->type_search_create)) {
            if ($this->type_search_create == 'date') {
                if (isset($this->creation_time_from) && $this->creation_time_from != null) {
                    $from = strtotime($this->creation_time_from);
                    $to = strtotime($this->creation_time_from) + 86399;
                    $query->andFilterWhere(['>', CustomerModel::tableName() . '.created_at', $from]);
                    $query->andFilterWhere(['<', CustomerModel::tableName() . '.created_at', $to]);
                }
            } else {
                if (isset($this->creation_time_from) && isset($this->creation_time_to) &&
                    $this->creation_time_from != null && $this->creation_time_to != null) {
                    $from = strtotime($this->creation_time_from);
                    $to = strtotime($this->creation_time_to) + 86399;
                    $query->andFilterWhere(['>', CustomerModel::tableName() . '.created_at', $from]);
                    $query->andFilterWhere(['<', CustomerModel::tableName() . '.created_at', $to]);
                }
            }
        }

        if ($this->keyword != null) {
            $query->andFilterWhere(['OR',
                ['like', CustomerModel::tableName() . '.full_name', $this->keyword],
                ['like', CustomerModel::tableName() . '.forename', $this->keyword],
                ['like', CustomerModel::tableName() . '.name', $this->keyword],
                ['like', CustomerModel::tableName() . '.customer_code', $this->keyword],
                ['like', DonHangModel::tableName() . '.order_code', $this->keyword],
            ]);
        }

        if ($this->loai_thanh_toan != null) {
            $query->andFilterWhere([ThanhToanModel::tableName() . '.loai_thanh_toan' => $this->loai_thanh_toan]);
        }

//        echo $query->createCommand()->getRawSql(); die;

        return $dataProvider;
    }
}
