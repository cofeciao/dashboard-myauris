<?php

namespace backend\modules\customer\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\customer\models\CustomerFeedback;

/**
 * CustomerFeedbackSearch represents the model behind the search form of `backend\modules\customer\models\CustomerFeedback`.
 */
class CustomerFeedbackSearch extends CustomerFeedback
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
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
        $query = CustomerFeedback::find()->joinWith(['customerHasOne']);

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
            'token_id' => $this->token_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'feedback', $this->feedback]);

        return $dataProvider;
    }
}
