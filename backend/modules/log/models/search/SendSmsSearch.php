<?php

namespace backend\modules\log\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\log\models\Dep365SendSms;

/**
 * SendSmsSearch represents the model behind the search form of `backend\modules\log\models\Dep365SendSms`.
 */
class SendSmsSearch extends Dep365SendSms
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'updated_at', 'updated_by'], 'integer'],
            [['sms_uuid', 'sms_to'], 'safe'],
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
        $query = Dep365SendSms::find()->where('customer_id != 1');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

        $query->joinWith(['customerHasOne']);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'sms_time_send' => $this->sms_time_send,
            'sms_lanthu' => $this->sms_lanthu,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'sms_uuid', $this->sms_uuid])
            ->andFilterWhere(['like', 'sms_text', $this->sms_text])
            ->andFilterWhere(['like', 'sms_to', $this->sms_to]);

        return $dataProvider;
    }
}
