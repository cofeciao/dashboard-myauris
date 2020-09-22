<?php

namespace backend\modules\recommend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\recommend\models\RecommendOnline;

/**
 * RecommendOnlineSearch represents the model behind the search form of `backend\modules\recommend\models\RecommendOnline`.
 */
class RecommendOnlineSearch extends RecommendOnline
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'created_by', 'updated_by', 'updated_at'], 'integer'],
            [['gioi_tinh', 'nhom_tuoi', 'tinh_trang_rang', 'khach_quan_tam', 'san_pham', 'hinh_anh'], 'safe'],
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
        $query = RecommendOnline::find();

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
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'gioi_tinh', $this->gioi_tinh])
            ->andFilterWhere(['like', 'nhom_tuoi', $this->nhom_tuoi])
            ->andFilterWhere(['like', 'tinh_trang_rang', $this->tinh_trang_rang])
            ->andFilterWhere(['like', 'khach_quan_tam', $this->khach_quan_tam])
            ->andFilterWhere(['like', 'san_pham', $this->san_pham])
            ->andFilterWhere(['like', 'hinh_anh', $this->hinh_anh]);

        return $dataProvider;
    }
}
