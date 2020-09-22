<?php

namespace backend\modules\customer\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\customer\models\Dep365CustomerOnlineFailDathen;

/**
 * Dep365CustomerOnlineFailDathenSearch represents the model behind the search form of `backend\modules\customer\models\Dep365CustomerOnlineFailDathen`.
 */
class Dep365CustomerOnlineFailDathenSearch extends Dep365CustomerOnlineFailDathen
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'safe'],
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
        $query = Dep365CustomerOnlineFailDathen::find();

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

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
