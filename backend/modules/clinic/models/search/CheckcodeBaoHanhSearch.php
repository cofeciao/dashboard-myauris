<?php

namespace backend\modules\clinic\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\clinic\models\CheckcodeBaoHanh;

/**
 * CheckcodeBaoHanhSearch represents the model behind the search form of `backend\modules\clinic\models\CheckcodeBaoHanh`.
 */
class CheckcodeBaoHanhSearch extends CheckcodeBaoHanh
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'date_buy', 'warranty_time', 'co_so', 'status', 'created_at', 'created_by', 'updated_by', 'updated_at'], 'integer'],
            [['warranty_code', 'product_code', 'product_name', 'co_so_name'], 'safe'],
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
        $query = CheckcodeBaoHanh::find();

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
            'customer_id' => $this->customer_id,
            'date_buy' => $this->date_buy,
            'warranty_time' => $this->warranty_time,
            'co_so' => $this->co_so,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'warranty_code', $this->warranty_code])
            ->andFilterWhere(['like', 'product_code', $this->product_code])
            ->andFilterWhere(['like', 'product_name', $this->product_name])
            ->andFilterWhere(['like', 'co_so_name', $this->co_so_name]);

        return $dataProvider;
    }
}
