<?php

namespace backend\modules\clinic\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\clinic\models\PhongKhamKpi;

/**
 * PhongKhamKpiSearch represents the model behind the search form of `backend\modules\clinic\models\PhongKhamKpi`.
 */
class PhongKhamKpiSearch extends PhongKhamKpi
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'kpi_tuong_tac', 'kpi_lich_hen', 'kpi_lich_moi', 'kpi_khach_den', 'kpi_khach_lam', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
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
        $query = PhongKhamKpi::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'kpi_tuong_tac' => $this->kpi_tuong_tac,
            'kpi_lich_hen' => $this->kpi_lich_hen,
            'kpi_lich_moi' => $this->kpi_lich_moi,
            'kpi_khach_den' => $this->kpi_khach_den,
            'kpi_khach_lam' => $this->kpi_khach_lam,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
