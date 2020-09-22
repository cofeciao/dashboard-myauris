<?php

namespace backend\modules\clinic\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\clinic\models\PhongKhamLichDieuTri;
use backend\modules\clinic\models\PhongKhamDonHang;

/**
 * PhongKhamLichDieuTriSearch represents the model behind the search form of `backend\modules\clinic\models\PhongKhamLichDieuTri`.
 */
class PhongKhamLichDieuTriSearch extends PhongKhamLichDieuTri
{
    public $button;
    public $type_search_date = 'date';
    public $type_search_code = 'customer_code';
    public $trang_thai;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'ekip', 'time_dieu_tri', 'created_at', 'updated_at', 'created_by', 'updated_by', 'direct_sale', 'danh_gia'], 'integer'],
            [['customer_code', 'order_code', 'huong_dieu_tri', 'note', 'name', 'phone', 'time_start', 'time_end', 'tro_thu', 'trang_thai'], 'safe'],
            [['button', 'type_search_date', 'type_search_code', 'keyword', 'co_so'], 'safe'],
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
    public function search($params, $order_code = null)
    {
        $query = PhongKhamLichDieuTri::find();
        if ($order_code != null) {
            $query->andWhere([PhongKhamLichDieuTri::tableName() . '.order_code' => $order_code]);
            $ekip = $query->one();
            if (isset($ekip)) {
                //                $this->ekip = $ekip->ekip;
                //                $this->direct_sale = $ekip->orderHasOne->direct_sale_id;
                $this->keyword = $ekip->orderHasOne->order_code;
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $query->joinWith(['clinicHasOne', 'orderHasOne']);
        $query->orderBy([new \yii\db\Expression('FIELD (time_dieu_tri, ' . strtotime(date('d-m-Y')) . ') DESC')]);
        $query->groupBy(PhongKhamLichDieuTri::tableName() . '.id');

        $this->load($params);
        //        if (!$this->validate()) {
        //            return $dataProvider;
        //        }

        if (isset($this->direct_sale) && $this->direct_sale != null) {
            $query->andFilterWhere(['dep365_customer_online.directsale' => $this->direct_sale]);
        }

        if (isset($this->co_so) && $this->co_so != null) {
            $query->andFilterWhere(['phong_kham_lich_dieu_tri.co_so' => $this->co_so]);
        }

        if (isset($this->ekip) && $this->ekip != null) {
            $query->andFilterWhere(['phong_kham_lich_dieu_tri.ekip' => $this->ekip]);
        }

        if (isset($this->tro_thu) && $this->tro_thu != null) {
            $query->andFilterWhere(['like', 'phong_kham_lich_dieu_tri.tro_thu', $this->tro_thu]);
        }

        if (isset($this->keyword) && $this->keyword != null) {
            $this->keyword = trim($this->keyword);
            $this->keyword = preg_replace('/\s+/', ' ', $this->keyword);
            $query->andFilterWhere([
                'or',
                ['like', 'dep365_customer_online.full_name', $this->keyword],
                ['like', 'dep365_customer_online.phone', $this->keyword],
                ['like', 'phong_kham_lich_dieu_tri.customer_code', $this->keyword],
                ['like', 'phong_kham_lich_dieu_tri.order_code', $this->keyword],
            ]);
        }

        if (isset($this->type_search_date)) {
            if ($this->type_search_date == 'date') {
                if (isset($this->time_start) && $this->time_start != null) {
                    $from = strtotime($this->time_start);
                    $to = strtotime($this->time_start) + 86399;
                    // $query->andFilterWhere(['>', 'phong_kham_lich_dieu_tri.time_start', $from]);
                    // $query->andFilterWhere(['<', 'phong_kham_lich_dieu_tri.time_end', $to]);
                    // Nghia update
                    $query->andFilterWhere(['>', 'phong_kham_lich_dieu_tri.time_dieu_tri', $from]);
                    $query->andFilterWhere(['<', 'phong_kham_lich_dieu_tri.time_dieu_tri', $to]);
                }
            } else {
                if (isset($this->time_start) && isset($this->time_end) && $this->time_start != null & $this->time_end != null) {
                    $from = strtotime($this->time_start);
                    $to = strtotime($this->time_end) + 86399;
                    // $query->andFilterWhere(['>', 'phong_kham_lich_dieu_tri.time_start', $from]);
                    // $query->andFilterWhere(['<', 'phong_kham_lich_dieu_tri.time_end', $to]);
                    $query->andFilterWhere(['>', 'phong_kham_lich_dieu_tri.time_dieu_tri', $from]);
                    $query->andFilterWhere(['<', 'phong_kham_lich_dieu_tri.time_dieu_tri', $to]);
                }
            }
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
                $query->andFilterWhere(['in', PhongKhamLichDieuTri::tableName() . '.order_code', $ArrayDonHangLichDieuTriID]);
            } else {
                // CHUA_HOAN_THANH_KHAM = [0,null]
                $list = $mLichDieuTri->all();
                foreach ($list as $item) {
                    $ArrayDonHangLichDieuTriID[] = $item->order_code;
                }
                $query->andFilterWhere(['not in', PhongKhamLichDieuTri::tableName() . '.order_code', $ArrayDonHangLichDieuTriID]);
            }
        } //

        return $dataProvider;
    }
}
