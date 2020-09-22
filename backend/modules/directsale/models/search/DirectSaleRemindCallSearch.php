<?php

namespace backend\modules\directsale\models\search;

use backend\modules\directsale\models\DirectSaleRemindCall;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DirectSaleRemindCallSearch represents the model behind the search form of `backend\modules\directsale\models\DirectSaleRemindCall`.
 */
class DirectSaleRemindCallSearch extends DirectSaleRemindCall
{
    public $button;
    public $full_name;
    public $type_search_date = 'range';
    public $from;
    public $to;

    public function rules()
    {
        return [
            [['button'], 'integer'],
            [['full_name', 'type_search_date', 'from', 'to'], 'safe'],
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
        $query = DirectSaleRemindCall::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->joinWith(['customerHasOne', 'customerOnlineCome']);
        $query->orderBy([new \yii\db\Expression('FIELD (remind_call_time, ' . strtotime(date('d-m-Y')) . ') DESC'), 'remind_call_time' => SORT_DESC]);
        $query->published();
        $query->andWhere(['type' => parent::TYPE_DIRECT_SALE]);

        if ($this->button == '') {
            $this->from = date('d-m-Y', strtotime('-7days'));
            $this->to = date('d-m-Y', strtotime('+1days'));
        }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        if ($this->full_name != null) {
            $this->full_name = trim($this->full_name);
            $this->full_name = preg_replace('/\s+/', ' ', $this->full_name);
            $query->andFilterWhere(['or',
                ['like', 'dep365_customer_online.name', $this->full_name],
                ['like', 'dep365_customer_online.forename', $this->full_name],
                ['like', 'dep365_customer_online.full_name', $this->full_name],
                ['like', 'dep365_customer_online.phone', $this->full_name],
            ]);
        }

        if ($this->from != null && $this->from != '') {
            $from = strtotime($this->from);
            $to = ($this->to != null && $this->to != '' ? strtotime($this->to) : $from) + 86399;
            $query->andFilterWhere(['between', 'remind_call_time', $from, $to]);
        }

//        echo $query->createCommand()->rawSql;
//        die;

        return $dataProvider;
    }
}
