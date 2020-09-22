<?php

namespace backend\modules\affiliate\models\search;

use backend\modules\customer\models\Dep365CustomerOnlineCome;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\clinic\models\Clinic;

/**
 * AffiliateCustomerSearch represents the model behind the search form of `backend\modules\clinic\models\Clinic`.
 */
class AffiliateCustomerSearch extends Clinic
{
    public function rules()
    {
        return [
            [['id', 'status', 'agency_id', 'nguon_online', 'province', 'district', 'face_fanpage', 'face_post_id', 'directsale', 'permission_user', 'per_inactivity', 'permission_old', 'date_lichhen', 'time_lichhen', 'co_so', 'dat_hen', 'customer_come', 'customer_come_date', 'customer_come_time_to', 'customer_gen', 'status_fail', 'is_customer_who', 'customer_direct_sale_checkthammy', 'customer_bacsi_check_final', 'customer_old', 'ngay_tao', 'created_at', 'updated_at', 'created_by', 'updated_by', 'dat_hen_fail'], 'integer'],
            [['customer_code', 'full_name', 'forename', 'name', 'avatar', 'slug', 'phone', 'sex', 'birthday', 'address', 'face_customer', 'note', 'note_direct', 'tt_kh', 'ngaythang', 'customer_mongmuon', 'customer_thamkham', 'customer_huong_dieu_tri', 'customer_ghichu_bacsi', 'is_affiliate_created', 'reason_reject'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Clinic::find()->joinWith(['customerOnlineComeHasOne'])->where([Dep365CustomerOnlineCome::tableName() . '.accept' => Dep365CustomerOnlineCome::STATUS_ACCEPT]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['OR',
            [Clinic::tableName().'.name' => $this->name],
            [Clinic::tableName().'.full_name' => $this->name],
            [Clinic::tableName().'.forename' => $this->name],
        ]);
        $query->andFilterWhere([Clinic::tableName().'.customer_code' => $this->customer_code]);

        return $dataProvider;
    }
}
