<?php

namespace backend\modules\clinic\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\clinic\models\PhongKhamSanPham;

/**
 * PhongKhamSanPhamSearch represents the model behind the search form of `backend\modules\clinic\models\PhongKhamSanPham`.
 */
class PhongKhamSanPhamSearch extends PhongKhamSanPham
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'services_id'], 'safe'],
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
        $query = PhongKhamSanPham::find();

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
            'OR',
            ['like', 'name', $this->name],
            ['like', 'mota', $this->name]
        ]);

        if ($this->services_id != null) {
            $query->andWhere([
                self::tableName() . '.services_id' => $this->services_id
            ]);
        }

        return $dataProvider;
    }
}
