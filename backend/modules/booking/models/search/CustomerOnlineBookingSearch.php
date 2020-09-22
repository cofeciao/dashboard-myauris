<?php

namespace backend\modules\booking\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\booking\models\CustomerOnlineBooking;
use yii\db\Expression;

/**
 * CustomerOnlineBookingSearch represents the model behind the search form of `backend\modules\booking\models\CustomerOnlineBooking`.
 */
class CustomerOnlineBookingSearch extends CustomerOnlineBooking
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_type', 'time_id', 'coso_id'], 'integer'],
            [['booking_date', 'user_register_id'], 'safe'],
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
        $query = CustomerOnlineBooking::find()->joinWith(['timeWorkHasOne', 'customerOnlineHasOne', 'userRegisterHasOne'])->orderBy([
            new Expression('CASE customer_type WHEN ' . CustomerOnlineBooking::CUSTOMER_VITUAL . ' THEN 1 ELSE 0 END'),
            'status' => SORT_ASC,
            'id' => SORT_DESC,
        ]);
        $query->andWhere(['customer_type' => CustomerOnlineBooking::CUSTOMER_FROM_WEBSITE]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'status' => SORT_ASC,
                    'id' => SORT_DESC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'time_id' => $this->time_id,
            'customer_type' => $this->customer_type,
            'coso_id' => $this->coso_id,
        ]);

        if ($this->user_register_id != null && $this->user_register_id != '') {
            $query->orFilterWhere(['like', 'dep365_customer_online.name', $this->user_register_id])
                ->orFilterWhere(['like', 'dep365_customer_online.forename', $this->user_register_id])
                ->orFilterWhere(['like', 'dep365_customer_online.full_name', $this->user_register_id]);
            $query->orWhere("user_register_id IN (SELECT id FROM dep365_user_register.user_register WHERE (name='{$this->user_register_id}' OR name LIKE '%{$this->user_register_id}' OR name LIKE '{$this->user_register_id}%' OR name LIKE '%{$this->user_register_id}%'))");
        }

        if ($this->booking_date != null && $this->booking_date != '') {
            $query->andFilterWhere(['booking_date' => strtotime($this->booking_date)]);
        }

        return $dataProvider;
    }
}
