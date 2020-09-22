<?php

namespace backend\modules\appmyauris\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\appmyauris\models\AppMyaurisCustomerLog;

/**
 * AppMyaurisCustomerLogSearch represents the model behind the search form of `backend\modules\appmyauris\models\AppMyaurisCustomerLog`.
 */
class AppMyaurisCustomerLogSearch extends AppMyaurisCustomerLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'status', 'created_at', 'created_by', 'updated_by', 'updated_at'], 'integer'],
            [['tu_van', 'don_hang'], 'safe'],
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
        $query = AppMyaurisCustomerLog::find();

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
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'tu_van', $this->tu_van])
            ->andFilterWhere(['like', 'don_hang', $this->don_hang]);

        return $dataProvider;
    }
}
