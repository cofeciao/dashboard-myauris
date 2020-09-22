<?php

namespace backend\modules\customer\models\search;

use backend\models\CustomerModel;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\customer\models\Dep365CustomerOnline;

/**
 * CustomerFailSearch represents the model behind the search form of `backend\modules\customer\models\Dep365CustomerOnline`.
 */
class CustomerFailSearch extends Dep365CustomerOnline
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'agency_id', 'nguon_online', 'province', 'district', 'face_fanpage', 'face_post_id', 'directsale', 'permission_user', 'per_inactivity', 'permission_old', 'date_lichhen', 'time_lichhen', 'co_so', 'dat_hen', 'customer_come', 'customer_come_date', 'customer_come_time_to', 'customer_gen', 'status_fail', 'is_customer_who', 'customer_direct_sale_checkthammy', 'customer_bacsi_check_final', 'dat_hen_fail'], 'integer'],
            [['customer_code', 'full_name', 'forename', 'name', 'avatar', 'slug', 'phone', 'sex', 'birthday', 'address', 'face_customer', 'note', 'note_direct', 'tt_kh', 'ngaythang', 'customer_mongmuon', 'customer_thamkham', 'customer_huong_dieu_tri', 'customer_ghichu_bacsi'], 'safe'],
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
    public function search($params)
    {
        $seven_day_ago = strtotime('-7days');
        $query = Dep365CustomerOnline::find()->where([
            'status' => CustomerModel::STATUS_DH,
            'dat_hen' => Dep365CustomerOnline::DAT_HEN_KHONG_DEN
        ]);

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

        return $dataProvider;
    }
}
