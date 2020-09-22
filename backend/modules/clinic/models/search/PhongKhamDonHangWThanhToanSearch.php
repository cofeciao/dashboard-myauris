<?php

namespace backend\modules\clinic\models\search;

use backend\models\CustomerModel;
use backend\models\doanhthu\DonHangModel;
use backend\models\doanhthu\ThanhToanModel;
use backend\modules\clinic\models\PhongKhamDonHang;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\clinic\models\PhongKhamDonHangWThanhToan;

/**
 * PhongKhamDonHangWThanhToanSearch represents the model behind the search form of `backend\modules\clinic\models\PhongKhamDonHangWThanhToan`.
 */
class PhongKhamDonHangWThanhToanSearch extends PhongKhamDonHangWThanhToan
{
    public $from;
    public $to;

    public $co_so;

    public $button = '';

    public $type_search_date = 'date';

    public $keyword;
    public $creation_time_from;
    public $creation_time_to;
    public $payment_time_from;
    public $payment_time_to;
    public $appointment_time_from;
    public $appointment_time_to;
    public $type_search_create = 'date';
    public $type_search_payment = 'date';
    public $type_search_appointment = 'date';

    public function rules()
    {
        return [
            [['tien_thanh_toan', 'loai_thanh_toan', 'tam_ung', 'status', 'ngay_tao', 'created_at', 'updated_at', 'created_by', 'updated_by', 'co_so'], 'integer'],
            [['customer_id', 'phong_kham_don_hang_id'], 'safe'],
            [['from', 'to', 'type_search_date'], 'string'],
            [['button', 'keyword', 'appointment_time_from', 'appointment_time_to', 'payment_time_from', 'payment_time_to', 'type_search_payment', 'creation_time_from', 'creation_time_to', 'type_search_create', 'type_search_appointment', 'customer_come_time_to'], 'safe'],
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
    public function search($params, $order = null, $getSum = false, $field = null, $showSql = false, $hoan_coc = false)
    {
        $query = PhongKhamDonHangWThanhToan::find();
        $query->joinWith(['donHangHasOne', 'loaiThanhToanHasOne', 'customerHasOne']);

        if ($order != null) {
            $query->andWhere(['phong_kham_don_hang_id' => $order->primaryKey]);
            $this->keyword = $order->order_code;
            $this->button = '2';
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if ($order == null && $this->button == '') {
            $this->creation_time_from = date('d-m-Y');
            $this->creation_time_to = date('d-m-Y');
        }

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }
        // grid filtering conditions
        /*$query->andFilterWhere([
            'id' => $this->id,
            'tien_thanh_toan' => $this->tien_thanh_toan,
            'loai_thanh_toan' => $this->loai_thanh_toan,
            'tam_ung' => $this->tam_ung,
            'ngay_tao' => $this->ngay_tao,
        ]);
        $query->andFilterWhere(['or',
            ['like', 'dep365_customer_online.full_name', $this->customer_id],
            ['like', 'dep365_customer_online.forename', $this->customer_id],
            ['like', 'dep365_customer_online.name', $this->customer_id],
        ]);
        if ($this->phong_kham_don_hang_id != null) {
            $query->andFilterWhere(['phong_kham_don_hang.order_code' => $this->phong_kham_don_hang_id]);
        }*/

        if ($this->keyword != null) {
            $query->andFilterWhere(['OR',
                ['like', CustomerModel::tableName() . '.full_name', $this->keyword],
                ['like', CustomerModel::tableName() . '.forename', $this->keyword],
                ['like', CustomerModel::tableName() . '.name', $this->keyword],
                ['like', CustomerModel::tableName() . '.customer_code', $this->keyword],
                ['like', DonHangModel::tableName() . '.order_code', $this->keyword],
            ]);
        }

        if ($this->co_so != null) {
            $query->andFilterWhere([PhongKhamDonHang::tableName() . '.co_so' => $this->co_so]);
        }

        if ($this->loai_thanh_toan != null) {
            $query->andFilterWhere([ThanhToanModel::tableName() . '.loai_thanh_toan' => $this->loai_thanh_toan]);
        }

        if ($this->tam_ung != null) {
            $query->andFilterWhere([ThanhToanModel::tableName() . '.tam_ung' => $this->tam_ung]);
        }

        if ($this->creation_time_from != null) {
            $from = strtotime($this->creation_time_from);
            if ($this->creation_time_to == null || $this->type_search_create == 'date') {
                $this->creation_time_to = $this->creation_time_from;
            }
            $to = strtotime($this->creation_time_to) + 86399;
            $query->andFilterWhere(['BETWEEN', ThanhToanModel::tableName() . '.created_at', $from, $to]);
        }

        if ($this->payment_time_from != null) {
            $from = strtotime($this->payment_time_from);
            if ($this->payment_time_to == null || $this->type_search_payment == 'date') {
                $this->payment_time_to = $this->payment_time_from;
            }
            $to = strtotime($this->payment_time_to) + 86399;
            $query->andFilterWhere(['BETWEEN', ThanhToanModel::tableName() . '.ngay_tao', $from, $to]);
        }

        if ($getSum === true && $field != null) {
            if ($showSql === true) {
                echo $query->createCommand()->rawSql;
            }
            if ($hoan_coc == true) {
                $query->andWhere([PhongKhamDonHangWThanhToan::tableName() . '.tam_ung' => self::HOAN_COC]);
            }
            return $query->sum($field);
        }

        return $dataProvider;
    }
}
