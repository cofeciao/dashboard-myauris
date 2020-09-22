<?php

namespace backend\modules\log\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\log\models\CallLog;

/**
 * CallLogSearch represents the model behind the search form of `backend\modules\log\models\CallLog`.
 */
class CallLogSearch extends CallLog
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'call_den_di', 'call_status', 'user_id'], 'integer'],
            [['call_id', 'created_at'], 'safe'],
            [['phone'], 'integer', 'message' => 'Số điện thoại không hợp lệ'],
            [['call_time'], 'integer', 'message' => 'Thời gian gọi không hợp lệ'],
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
        $query = CallLog::find();

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
            'call_den_di' => $this->call_den_di,
            'call_status' => $this->call_status,
            'call_time' => $this->call_time,
            'call_time_start' => $this->call_time_start,
            'user_id' => $this->user_id,
            'phone' => $this->phone,
        ]);

        if ($this->created_at) {
            $query->andFilterWhere([
                'between', 'created_at', strtotime($this->created_at), strtotime($this->created_at) + 86399
            ]);
        }

        $query->andFilterWhere(['like', 'call_id', $this->call_id])
            ->andFilterWhere(['like', 'call_source', $this->call_source]);

        return $dataProvider;
    }
}
