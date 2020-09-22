<?php

namespace backend\modules\customer\models\search;

use backend\modules\customer\models\Dep365CustomerOnline;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\customer\models\AffiliateCustomerContact;

/**
 * AffiliateCustomerContact represents the model behind the search form of `backend\modules\customer\models\AffiliateCustomerContact`.
 */
class AffiliateCustomerContactSearch extends AffiliateCustomerContact
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'updated_at'], 'integer'],
            [['name', 'phone', 'email'], 'safe'],
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
        $query = AffiliateCustomerContact::find();
//            ->select([
//                self::tableName() . '.name',
//                self::tableName() . '.phone',
//                self::tableName() . '.email',
//                self::tableName() . '.note',
//                self::tableName() . '.page',
//                self::tableName() . '.status',
//                self::tableName() . '.created_at',
//                self::tableName() . '.customer_code',
//            ])
//            ->innerJoin(Dep365CustomerOnline::tableName(), self::tableName() . '.phone=' . Dep365CustomerOnline::tableName() . '.phone');
//            ->joinWith(['customerByPhoneHasMany']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
        ]);

        $this->load($params);

//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

        $query->andFilterWhere(['like', self::tableName() . '.name', $this->name])
            ->andFilterWhere(['like', self::tableName() . '.phone', $this->phone])
            ->andFilterWhere(['like', self::tableName() . '.email', $this->email]);
//        echo $query->createCommand()->rawSql;
        return $dataProvider;
    }
}
