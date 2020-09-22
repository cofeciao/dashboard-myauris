<?php

namespace backend\modules\location\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\location\models\Province;

/**
 * ProvinceSearch represents the model behind the search form of `backend\modules\location\models\Province`.
 */
class ProvinceSearch extends Province
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TelephoneCode', 'CountryId'], 'integer'],
            [['name', 'Type'], 'safe'],
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
        $query = Province::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['status' => SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'TelephoneCode' => $this->TelephoneCode,
            'CountryId' => $this->CountryId,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'Type', $this->Type]);

        return $dataProvider;
    }
}
