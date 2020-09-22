<?php

namespace backend\modules\testab\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\testab\models\AbCampaign;

/**
 * AbCampaignSearch represents the model behind the search form of `backend\modules\testab\models\AbCampaign`.
 */
class AbCampaignSearch extends AbCampaign
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ky_thuat', 'status', 'end_date'], 'integer'],
            [['name', 'created_at'], 'safe'],
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
    public function search($params, $id)
    {
        $query = AbCampaign::find()->andWhere(['campaign_id' => $id]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        $query->joinWith(['kyThuatHasOne']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'ky_thuat' => $this->ky_thuat,
            'status' => $this->status,
            'end_date' => $this->end_date,
            /*'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,*/
        ]);

        $query->andFilterWhere(['like', 'ab_campaign.name', $this->name]);
        /*->andFilterWhere(['like', 'slug', $this->slug])
        ->andFilterWhere(['like', 'link_test', $this->link_test])
        ->andFilterWhere(['like', 'content', $this->content])
        ->andFilterWhere(['like', 'chiphi_thucchay', $this->chiphi_thucchay])
        ->andFilterWhere(['like', 'comment', $this->comment])
        ->andFilterWhere(['like', 'tin_nhan', $this->tin_nhan])
        ->andFilterWhere(['like', 'tong_tuong_tac', $this->tong_tuong_tac])
        ->andFilterWhere(['like', 'hien_thi', $this->hien_thi])
        ->andFilterWhere(['like', 'tiep_can', $this->tiep_can])
        ->andFilterWhere(['like', 'nguoi_xem_1', $this->nguoi_xem_1])
        ->andFilterWhere(['like', 'nguoi_xem_50', $this->nguoi_xem_50])
        ->andFilterWhere(['like', 'tan_suat', $this->tan_suat])
        ->andFilterWhere(['like', 'gia_tuong_tac', $this->gia_tuong_tac])
        ->andFilterWhere(['like', 'gia_hien_thi', $this->gia_hien_thi])
        ->andFilterWhere(['like', 'gia_tiep_can', $this->gia_tiep_can])
        ->andFilterWhere(['like', 'gia_10s', $this->gia_10s])
        ->andFilterWhere(['like', 'gia_50phantram', $this->gia_50phantram]);*/

        if ($this->created_at != null) {
            $from = strtotime($this->created_at);
            $to = $from + 86400;
            $query->andWhere(['between', 'ab_campaign.created_at', $from, $to]);
        }

        return $dataProvider;
    }
}
