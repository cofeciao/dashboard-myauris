<?php

namespace backend\modules\support\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\support\models\Support;

/**
 * SupportSearch represents the model behind the search form of `backend\modules\support\models\Support`.
 */
class SupportSearch extends Support
{
    public $keyword;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'catagory_id', 'status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name', 'slug', 'question', 'anwser'], 'safe'],
            [['keyword'], 'string']
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
    public function search($params, $catagory_id = null)
    {
        $query = Support::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        if ($catagory_id !== null) {
            $this->catagory_id = $catagory_id;
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'catagory_id' => $this->catagory_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'slug', $this->slug])
            ->andFilterWhere(['like', 'question', $this->question])
            ->andFilterWhere(['like', 'anwser', $this->anwser]);

        if (isset($this->keyword)) {
            $query->andWhere([
                'or',
                ['like', 'name', $this->keyword],
                ['like', 'slug', $this->keyword],
                ['like', 'question', $this->keyword],
                ['like', 'anwser', $this->keyword],
            ]);
        }

        return $dataProvider;
    }
}
