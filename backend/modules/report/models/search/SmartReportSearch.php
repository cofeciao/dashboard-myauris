<?php

namespace backend\modules\report\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\report\models\SmartReportModel;

/**
 * SmartReportSearch represents the model behind the search form of `backend\modules\report\models\SmartReportModel`.
 */
class SmartReportSearch extends SmartReportModel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_khoan_chi', 'report_timestamp', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['tien_da_chi', 'tien_cho_duyet'], 'safe'],
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
        $query = SmartReportModel::find();

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
            'id_khoan_chi' => $this->id_khoan_chi,
            'report_timestamp' => $this->report_timestamp,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'tien_da_chi', $this->tien_da_chi])
            ->andFilterWhere(['like', 'tien_cho_duyet', $this->tien_cho_duyet]);

        return $dataProvider;
    }
}
